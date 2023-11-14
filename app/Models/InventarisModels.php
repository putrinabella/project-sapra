<?php

namespace App\Models;

use CodeIgniter\Model;

class InventarisModels extends Model
{
    protected $table            = 'tblInventaris';
    protected $primaryKey       = 'idInventaris';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idInventaris', 'namaInventaris', 'satuan', 'tanggalInventaris', 'tipeInventaris'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->where('tblInventaris.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function getData($startYear = null, $endYear = null) {
        $builder = $this->db->table($this->table);
        $builder->where('tblInventaris.deleted_at', null);
    
        if ($startYear !== null && $endYear !== null) {
            $builder->where('tblInventaris.tahunPemakaianAir >=', $startYear);
            $builder->where('tblInventaris.tahunPemakaianAir <=', $endYear);
        }
    
        $builder->orderBy('tblInventaris.tahunPemakaianAir', 'asc'); 
        $builder->orderBy('tblInventaris.bulanPemakaianAir', 'asc');
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
        $builder->where('tblInventaris.deleted_at IS NOT NULL');
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
