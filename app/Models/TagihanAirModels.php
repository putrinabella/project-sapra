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

    // function getData($startYear = null, $endYear = null) {
    //     $builder = $this->db->table($this->table);
    //     $builder->where('tblTagihanAir.deleted_at', null);
        
    //     if ($startYear !== null && $endYear !== null) {
    //         $builder->where('tblTagihanAir.tahunPemakaianAir >=', $startYear);
    //         $builder->where('tblTagihanAir.tahunPemakaianAir <=', $endYear);
    //         $query = $builder->get();
    //     } else if ($startYear === null && $endYear === null) { 
    //         $query = $builder->get();
    //     }

    //     return $query->getResult();
    // }


    function getData($startYear = null, $endYear = null) {
        $builder = $this->db->table($this->table);
        $builder->where('tblTagihanAir.deleted_at', null);
    
        if ($startYear !== null && $endYear !== null) {
            $builder->where('tblTagihanAir.tahunPemakaianAir >=', $startYear);
            $builder->where('tblTagihanAir.tahunPemakaianAir <=', $endYear);
        }
    
        $builder->orderBy('tblTagihanAir.tahunPemakaianAir', 'asc'); // Sort by year in ascending order
        $builder->orderBy('tblTagihanAir.bulanPemakaianAir', 'asc');
        $query = $builder->get();
        return $query->getResult();
    }

    public function convertMonth($monthValue)
    {
        // Ensure $monthValue is a valid integer from 1 to 12
        if (!is_numeric($monthValue) || $monthValue < 1 || $monthValue > 12) {
            return 'Invalid Month';
        }

        // Use the date() function to get the month name
        return date('F', mktime(0, 0, 0, $monthValue, 1));
    }


    
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

    public function getChartData() {
        $builder = $this->db->table($this->table);
        $builder->select('bulanPemakaianAir, biaya');
        $query = $builder->get();
        return $query->getResult();
    }
    
}
