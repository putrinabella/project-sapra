<?php 
namespace App\Models;

use CodeIgniter\Model;

class UserLoginLogModel extends Model
{
    protected $table            = 'tblUserLogs';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields = ['user_id', 'login_time', 'ip_address', 'action_type'];


    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblUser', 'tblUser.idUser = tblUserLogs.user_id');
        $query = $builder->get();
        return $query->getResult();
    }
}
?>