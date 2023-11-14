<?php

namespace App\Models;

use CodeIgniter\Model;

class DataInventarisModels extends Model
{
    protected $table            = 'tblDataInventaris';
    protected $primaryKey       = 'idDataInventaris';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idDataInventaris', 'idInventaris', 'tanggalDataInventaris', 'tipeDataInventaris', 'jumlahDataInventaris'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblInventaris', 'tblInventaris.idInventaris = tblDataInventaris.idInventaris');
        $builder->where('tblDataInventaris.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function getInventarisMasuk() {
        $builder = $this->db->table($this->table);
        $builder->join('tblInventaris', 'tblInventaris.idInventaris = tblDataInventaris.idInventaris');
        $builder->select('SUM(CASE WHEN tblDataInventaris.tipeDataInventaris = "Pemasukan" THEN tblDataInventaris.jumlahDataInventaris ELSE 0 END) as inventarismasuk');
        $builder->where('tblDataInventaris.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function getData($startYear = null, $endYear = null) {
        $builder = $this->db->table($this->table);
        $builder->where('tblDataInventaris.deleted_at', null);
    
        if ($startYear !== null && $endYear !== null) {
            $builder->where('tblDataInventaris.tahunPemakaianAir >=', $startYear);
            $builder->where('tblDataInventaris.tahunPemakaianAir <=', $endYear);
        }
    
        $builder->orderBy('tblDataInventaris.tahunPemakaianAir', 'asc'); 
        $builder->orderBy('tblDataInventaris.bulanPemakaianAir', 'asc');
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
        $builder->join('tblInventaris', 'tblInventaris.idInventaris = tblDataInventaris.idInventaris');
        $builder->where('tblDataInventaris.deleted_at IS NOT NULL');
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
