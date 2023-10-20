<?php

namespace App\Models;

use CodeIgniter\Model;

class AplikasiModels extends Model
{
    protected $table            = 'tblAplikasi';
    protected $primaryKey       = 'idAplikasi';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idAplikasi', 'namaAplikasi', 'picAplikasi'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->where('tblAplikasi.deleted_at IS NOT NULL');
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
