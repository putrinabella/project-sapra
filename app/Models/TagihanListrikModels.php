<?php

namespace App\Models;

use CodeIgniter\Model;

class TagihanListrikModels extends Model
{
    protected $table            = 'tblTagihanListrik';
    protected $primaryKey       = 'idTagihanListrik';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idTagihanListrik', 'pemakaianListrik', 'bulanPemakaianListrik', 'tahunPemakaianListrik', 'biaya', 'bukti'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getData($startYear = null, $endYear = null) {
        $builder = $this->db->table($this->table);
        $builder->where('tblTagihanListrik.deleted_at', null);
    
        if ($startYear !== null && $endYear !== null) {
            $builder->where('tblTagihanListrik.tahunPemakaianListrik >=', $startYear);
            $builder->where('tblTagihanListrik.tahunPemakaianListrik <=', $endYear);
        }
    
        $builder->orderBy('tblTagihanListrik.tahunPemakaianListrik', 'asc'); 
        $builder->orderBy('tblTagihanListrik.bulanPemakaianListrik', 'asc');
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
        $builder->where('tblTagihanListrik.deleted_at IS NOT NULL');
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
        $builder->select('bulanPemakaianListrik, biaya');
        $query = $builder->get();
        return $query->getResult();
    }
    
}
