<?php

namespace App\Models;

use CodeIgniter\Model;

class TagihanAirModels extends Model
{
    protected $table            = 'tblTagihanAir';
    protected $primaryKey       = 'idTagihanAir';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idTagihanAir', 'pemakaianAir', 'bulanPemakaianAir', 'tahunPemakaianAir', 'biaya', 'bukti'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->where('tblTagihanAir.deleted_at IS NOT NULL');
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
