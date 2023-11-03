<?php

namespace App\Models;

use CodeIgniter\Model;

class TagihanInternetModels extends Model
{
    protected $table            = 'tblTagihanInternet';
    protected $primaryKey       = 'idTagihanInternet';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idTagihanInternet', 'pemakaianInternet', 'bulanPemakaianInternet', 'tahunPemakaianInternet', 'biaya', 'bukti'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getData($startYear = null, $endYear = null) {
        $builder = $this->db->table($this->table);
        $builder->where('tblTagihanInternet.deleted_at', null);
    
        if ($startYear !== null && $endYear !== null) {
            $builder->where('tblTagihanInternet.tahunPemakaianInternet >=', $startYear);
            $builder->where('tblTagihanInternet.tahunPemakaianInternet <=', $endYear);
        }
    
        $builder->orderBy('tblTagihanInternet.tahunPemakaianInternet', 'asc'); 
        $builder->orderBy('tblTagihanInternet.bulanPemakaianInternet', 'asc');
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
        $builder->where('tblTagihanInternet.deleted_at IS NOT NULL');
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
        $builder->select('bulanPemakaianInternet, biaya');
        $query = $builder->get();
        return $query->getResult();
    }
    
}
