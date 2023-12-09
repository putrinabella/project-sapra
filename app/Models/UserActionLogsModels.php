<?php

namespace App\Models;

use CodeIgniter\Model;

class UserActionLogsModels extends Model
{
    protected $table            = 'tblUserActionsLogs';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['user_id', 'actionTime', 'actionType', 'actionDetails'];

    function getAll($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblUser', 'tblUser.idUser = tblUserActionsLogs.user_id');
        $builder->orderBy('actionTime', 'desc');
    
        if ($startDate !== null && $endDate !== null) {
            $startDateTime = $startDate . ' 00:00:00';
            $endDateTime = $endDate . ' 23:59:59';
    
            $builder->where('tblUserActionsLogs.actionTime >=', $startDateTime);
            $builder->where('tblUserActionsLogs.actionTime <=', $endDateTime);
        }
    
        $query = $builder->get();
        return $query->getResult();
    }
    

    function getData($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblUser', 'tblUser.idUser = tblUserActionsLogs.user_id');
        $builder->orderBy('actionTime', 'asc');

        if ($startDate !== null && $endDate !== null) {
            $startDateTime = $startDate . ' 00:00:00';
            $endDateTime = $endDate . ' 23:59:59';
    
            $builder->where('tblUserActionsLogs.actionTime >=', $startDateTime);
            $builder->where('tblUserActionsLogs.actionTime <=', $endDateTime);
        }

        $query = $builder->get();
        return $query->getResult();
    }

    function getDataRestore($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblUser', 'tblUser.idUser = tblUserActionsLogs.user_id');
        $builder->where('(tblUserActionsLogs.actionType = \'Restore\' OR tblUserActionsLogs.actionType = \'Restore All\')');

        if ($startDate !== null && $endDate !== null) {
            $startDateTime = $startDate . ' 00:00:00';
            $endDateTime = $endDate . ' 23:59:59';
    
            $builder->where('tblUserActionsLogs.actionTime >=', $startDateTime);
            $builder->where('tblUserActionsLogs.actionTime <=', $endDateTime);
        }

        $builder->orderBy('actionTime', 'asc');
        $builder->orderBy('actionType', 'asc');

        $query = $builder->get();
        return $query->getResult();
    }

    function getDataDelete($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblUser', 'tblUser.idUser = tblUserActionsLogs.user_id');
        $builder->where('(tblUserActionsLogs.actionType = \'Delete\' OR tblUserActionsLogs.actionType = \'Delete All\')');

        if ($startDate !== null && $endDate !== null) {
            $startDateTime = $startDate . ' 00:00:00';
            $endDateTime = $endDate . ' 23:59:59';
    
            $builder->where('tblUserActionsLogs.actionTime >=', $startDateTime);
            $builder->where('tblUserActionsLogs.actionTime <=', $endDateTime);
        }

        $builder->orderBy('actionTime', 'asc');
        $builder->orderBy('actionType', 'asc');

        $query = $builder->get();
        return $query->getResult();
    }

    function getDataSoftDelete($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblUser', 'tblUser.idUser = tblUserActionsLogs.user_id');
        $builder->where('tblUserActionsLogs.actionType', 'Soft Delete');
        if ($startDate !== null && $endDate !== null) {
            $startDateTime = $startDate . ' 00:00:00';
            $endDateTime = $endDate . ' 23:59:59';
    
            $builder->where('tblUserActionsLogs.actionTime >=', $startDateTime);
            $builder->where('tblUserActionsLogs.actionTime <=', $endDateTime);
        }

        $builder->orderBy('actionTime', 'asc');
        $builder->orderBy('actionType', 'asc');

        $query = $builder->get();
        return $query->getResult();
    }
}
?>
