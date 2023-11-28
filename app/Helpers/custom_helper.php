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
        function restoreData($table, $idColumn, $id = null, $model) {
            $db = \Config\Database::connect();
    
            $builder = $db->table($table);
            $deletedAtColumn = 'deleted_at';
            if ($id !== null) {
                $builder->set($deletedAtColumn, null, true)
                        ->where([$idColumn => $id])
                        ->update();
                if ($model !== null) {
                    activityLogs($model, "Restore", "Melakukan restore data $table dengan id $id");
                }
                
            } else {
                $builder->set($deletedAtColumn, null, true)
                        ->where("$deletedAtColumn IS NOT NULL", null, false)
                        ->update();

                // Log activity
                if ($model !== null) {
                    activityLogs($model, "Restore All", "Melakukan restore semua data $table");
                }
            }
    
            return $db->affectedRows();
        }
    }
?>