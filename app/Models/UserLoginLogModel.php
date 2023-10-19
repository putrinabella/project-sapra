<?php 
namespace App\Models;

use CodeIgniter\Model;

class UserLoginLogModel extends Model
{
    protected $table            = 'user_login_logs';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['user_id', 'login_time', 'ip_address'];


    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblUser', 'tblUser.idUser = user_login_logs.user_id');
        $query = $builder->get();
        return $query->getResult();
    }
}
?>