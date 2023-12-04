<?php

namespace App\Models;

use CodeIgniter\Model;

class NonInventarisModels extends Model
{
    protected $table            = 'tblNonInventaris';
    protected $primaryKey       = 'idNonInventaris';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idNonInventaris', 'nama', 'satuan'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->where('tblNonInventaris.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function getData($startYear = null, $endYear = null) {
        $builder = $this->db->table($this->table);
        $builder->where('tblNonInventaris.deleted_at', null);
    
        if ($startYear !== null && $endYear !== null) {
            $builder->where('tblNonInventaris.tahunPemakaianAir >=', $startYear);
            $builder->where('tblNonInventaris.tahunPemakaianAir <=', $endYear);
        }
    
        $builder->orderBy('tblNonInventaris.tahunPemakaianAir', 'asc'); 
        $builder->orderBy('tblNonInventaris.bulanPemakaianAir', 'asc');
        $query = $builder->get();
        return $query->getResult();
    }

    public function convertMonth($monthValue) {
        if (!is_numeric($monthValue) || $monthValue < 1 || $monthValue > 12) {
            return 'Invalid Month';
        }
        return date('F', mktime(0, 0, 0, $monthValue, 1));
    }


    
    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->where('tblNonInventaris.deleted_at IS NOT NULL');
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

    public function getChartData() {
        $builder = $this->db->table($this->table);
        $builder->select('bulanPemakaianAir, biaya');
        $query = $builder->get();
        return $query->getResult();
    }
    
}
