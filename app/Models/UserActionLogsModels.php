<?php 
    namespace App\Models;

    use CodeIgniter\Model;

    class UserActionLogsModels extends Model
    {
        protected $table            = 'tblUserActionsLogs';
        protected $primaryKey       = 'id';
        protected $returnType       = 'object';
        protected $allowedFields    = ['user_id', 'actionTime', 'actionType', 'actionDetails'];

        function getAll() {
            $builder = $this->db->table($this->table);
            $builder->join('tblUser', 'tblUser.idUser = tblUserActionsLogs.user_id');
            $builder->orderBy('actionTime', 'desc'); 
            $query = $builder->get();
            return $query->getResult();
        }
    }
?>