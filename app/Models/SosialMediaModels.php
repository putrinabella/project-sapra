<?php

namespace App\Models;

use CodeIgniter\Model;

class SosialMediaModels extends Model
{
    protected $table            = 'tblSosialMedia';
    protected $primaryKey       = 'idSosialMedia';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idSosialMedia', 'namaSosialMedia', 'usernameSosialMedia', 'linkSosialMedia', 'picSosialMedia'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->where('tblSosialMedia.deleted_at IS NOT NULL');
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
}
