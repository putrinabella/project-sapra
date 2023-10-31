<?php

namespace App\Models;

use CodeIgniter\Model;

class ManajemenUserModels extends Model
{
    protected $table            = 'tbluser';
    protected $primaryKey       = 'idUser';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idUser', 'username', 'nama', 'password', 'role'];

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $query = $builder->get();
        return $query->getResult();
    }

    function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }

    public function isDuplicate($username) {
        $builder = $this->db->table($this->table);
        return $builder->where('username', $username)
            ->countAllResults() > 0;
    }
}
