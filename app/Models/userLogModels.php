<?php 
    namespace App\Models;

    use CodeIgniter\Model;

    class UserLogModels extends Model
    {
        protected $table            = 'tblUserLogs';
        protected $primaryKey       = 'id';
        protected $returnType       = 'object';
        protected $allowedFields    = ['user_id', 'loginTime', 'ipAddress', 'actionType'];


        function getAll($startDate = null, $endDate = null) {
            $builder = $this->db->table($this->table);
            $builder->join('tblUser', 'tblUser.idUser = tblUserLogs.user_id');
            $builder->orderBy('loginTime', 'desc'); 
    
            if ($startDate !== null && $endDate !== null) {
                $startDateTime = $startDate . ' 00:00:00';
                $endDateTime = $endDate . ' 23:59:59';
        
                $builder->where('tblUserLogs.loginTime >=', $startDateTime);
                $builder->where('tblUserLogs.loginTime <=', $endDateTime);
            }
            $query = $builder->get();
            return $query->getResult();
        }
    }
?>