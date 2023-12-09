<?php
    function userLogin(){
        $db = \Config\Database::connect();
        return $db->table('tblUser')->where('idUser', session('id_user'))->get()->getRow();
    }

    if (!function_exists('activityLogs')) {
        function activityLogs($model, $actionType, $actionDetails) {
            $data = [
                'user_id'      => session('id_user'),
                'actionTime'   => date('Y-m-d H:i:s'),
                'actionType'   => $actionType,
                'actionDetails' => $actionDetails,
            ];
            $model->insert($data);
        }
    }

    if (!function_exists('restoreData')) {
        function restoreData($table, $idColumn, $id = null, $model, $namaData) {
            $db = \Config\Database::connect();
            $builder = $db->table($table);
            $deletedAtColumn = 'deleted_at';
    
            if ($id !== null) {
                $builder->set($deletedAtColumn, null, true)
                        ->where([$idColumn => $id])
                        ->update();
    
                if ($model !== null) {
                    activityLogs($model, "Restore", "Melakukan restore data $namaData dengan id $id");
                }
            } else {
                $countInTrash = $builder->where("$deletedAtColumn IS NOT NULL", null, false)->countAllResults();
    
                if ($countInTrash > 0) {
                    $builder->set($deletedAtColumn, null, true)
                            ->where("$deletedAtColumn IS NOT NULL", null, false)
                            ->update();
    
                    // Log activity
                    if ($model !== null) {
                        activityLogs($model, "Restore All", "Melakukan restore semua data $namaData");
                    }
    
                    return $countInTrash; // or return a success message
                } else {
                    return 0; // or return a message indicating that the trash is empty
                }
            }
    
            return $db->affectedRows();
        }
    }

    if (!function_exists('deleteData')) {
        function deleteData($table, $idColumn, $id = null, $model, $namaData) {
            $db = \Config\Database::connect();
            $builder = $db->table($table);
            $deletedAtColumn = 'deleted_at';
    
            if ($id !== null) {
                $builder->where([$idColumn => $id])->delete();
    
                if ($model !== null) {
                    activityLogs($model, "Delete", "Melakukan delete data $namaData dengan id $id");
                }
            } else {
                $countInTrash = $builder->where("$deletedAtColumn IS NOT NULL", null, false)->countAllResults();
    
                if ($countInTrash > 0) {
                    // Directly purge deleted records
                    $db->table($table)->where("$deletedAtColumn IS NOT NULL", null, false)->delete();
                    // Log activity
                    if ($model !== null) {
                        activityLogs($model, "Delete All", "Mengosongkan tempat sampah  $namaData");
                    }
                    return $countInTrash; // or return a success message
                } else {
                    return 0; // or return a message indicating that the trash is empty
                }
            }
    
            return $db->affectedRows();
        }
    }
    
    if (!function_exists('uploadFile')) {
        function uploadFile($fieldName)
        {
            $request = \Config\Services::request();
            $file = $request->getFile($fieldName);
    
            if ($file !== null) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH . 'public/uploads', $newName);
                    return 'uploads/' . $newName;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        }
    }
    
?>