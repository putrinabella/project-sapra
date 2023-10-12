<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenSekolahModels extends Model
{
    protected $table            = 'tblDokumenSekolah';
    protected $primaryKey       = 'idDokumenSekolah';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idDokumenSekolah', 'namaDokumenSekolah', 'linkDokumenSekolah'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->where('tblDokumenSekolah.deleted_at IS NOT NULL');
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
