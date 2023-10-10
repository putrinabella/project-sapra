<?php

namespace App\Models;

use CodeIgniter\Model;

class WebsiteModels extends Model
{
    protected $table            = 'tblWebsite';
    protected $primaryKey       = 'idWebsite';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idWebsite', 'namaWebsite', 'fungsiWebsite', 'linkWebsite', 'picWebsite'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->where('tblWebsite.deleted_at IS NOT NULL');
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
