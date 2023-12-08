<?php 
    namespace App\Models;

    use CodeIgniter\Model;

    class UserActionLogsModels extends Model
    {
        protected $table            = 'tblUserActionsLogs';
        protected $primaryKey       = 'id';
        protected $returnType       = 'object';
        protected $allowedFields    = ['user_id', 'actionTime', 'actionType', 'actionDetails'];

        function getAll($startYear = null, $endYear = null) {
            $builder = $this->db->table($this->table);
            $builder->join('tblUser', 'tblUser.idUser = tblUserActionsLogs.user_id');
            
            if ($startYear !== null && $endYear !== null) {
                $builder->where("YEAR(tblUserActionsLogs.actionTime) BETWEEN $startYear AND $endYear");
            }
            $builder->orderBy('actionTime', 'desc');
        
            $query = $builder->get();
            return $query->getResult();
        }
        
        function getData($startYear = null, $endYear = null) {
            $builder = $this->db->table($this->table);
            $builder->join('tblUser', 'tblUser.idUser = tblUserActionsLogs.user_id');
            $builder->orderBy('actionTime', 'asc'); 
                  
            if ($startYear !== null && $endYear !== null) {
                $builder->where("YEAR(tblUserActionsLogs.actionTime) BETWEEN $startYear AND $endYear");
            }
        
            $query = $builder->get();
            return $query->getResult();
        }

        function getDataRestore($startYear = null, $endYear = null) {
            $builder = $this->db->table($this->table);
            $builder->join('tblUser', 'tblUser.idUser = tblUserActionsLogs.user_id');
            $builder->where('(tblUserActionsLogs.actionType = \'Restore\' OR tblUserActionsLogs.actionType = \'Restore All\')');
            
            if ($startYear !== null && $endYear !== null) {
                $builder->where("YEAR(tblUserActionsLogs.actionTime) BETWEEN $startYear AND $endYear");
            }
            
            $builder->orderBy('actionTime', 'asc');
            $builder->orderBy('actionType', 'asc');
            
            $query = $builder->get();
            return $query->getResult();
        }
        
        function getDataDelete($startYear = null, $endYear = null) {
            $builder = $this->db->table($this->table);
            $builder->join('tblUser', 'tblUser.idUser = tblUserActionsLogs.user_id');
            $builder->where('(tblUserActionsLogs.actionType = \'Delete\' OR tblUserActionsLogs.actionType = \'Delete All\')');
            
            if ($startYear !== null && $endYear !== null) {
                $builder->where("YEAR(tblUserActionsLogs.actionTime) BETWEEN $startYear AND $endYear");
            }
            
            $builder->orderBy('actionTime', 'asc');
            $builder->orderBy('actionType', 'asc');
            
            $query = $builder->get();
            return $query->getResult();
        }
        
    }
?>