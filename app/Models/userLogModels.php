<?php 
    namespace App\Models;

    use CodeIgniter\Model;

    class UserLogModels extends Model
    {
        protected $table            = 'tblUserLogs';
        protected $primaryKey       = 'id';
        protected $returnType       = 'object';
        protected $allowedFields    = ['user_id', 'loginTime', 'ipAddress', 'actionType'];


        function getAll() {
            $builder = $this->db->table($this->table);
            $builder->join('tblUser', 'tblUser.idUser = tblUserLogs.user_id');
            $query = $builder->get();
            return $query->getResult();
        }
    }
?>