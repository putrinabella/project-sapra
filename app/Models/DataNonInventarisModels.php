<?php

namespace App\Models;

use CodeIgniter\Model;

class DataNonInventarisModels extends Model
{
    protected $table            = 'tblDataNonInventaris';
    protected $primaryKey       = 'idDataNonInventaris';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idDataNonInventaris', 'idNonInventaris', 'tanggal', 'tipe', 'jumlah'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll($startDate = null, $endDate = null) {
        $builder = $this->db->table($this->table);
        $builder->join('tblNonInventaris', 'tblNonInventaris.idNonInventaris = tblDataNonInventaris.idNonInventaris');
        $builder->where('tblDataNonInventaris.deleted_at', null);
        $builder->orderBy('tanggal', 'desc'); 
        $builder->orderBy('tblNonInventaris.nama', 'asc');
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblDataNonInventaris.tanggal >=', $startDate);
            $builder->where('tblDataNonInventaris.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }

    function getPemasukan($startDate = null, $endDate = null) {
        $builder = $this->db->table($this->table);
        $builder->join('tblNonInventaris', 'tblNonInventaris.idNonInventaris = tblDataNonInventaris.idNonInventaris');
        $builder->where('tblDataNonInventaris.deleted_at', null);
        $builder->where('tblDataNonInventaris.tipe', "Pemasukan");
        $builder->orderBy('tanggal', 'desc'); 
        $builder->orderBy('tblNonInventaris.nama', 'asc');
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblDataNonInventaris.tanggal >=', $startDate);
            $builder->where('tblDataNonInventaris.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }

    function getPengeluaran($startDate = null, $endDate = null) {
        $builder = $this->db->table($this->table);
        $builder->join('tblNonInventaris', 'tblNonInventaris.idNonInventaris = tblDataNonInventaris.idNonInventaris');
        $builder->where('tblDataNonInventaris.deleted_at', null);
        $builder->where('tblDataNonInventaris.tipe', "Pengeluaran");
        $builder->orderBy('tanggal', 'desc'); 
        $builder->orderBy('tblNonInventaris.nama', 'asc');
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblDataNonInventaris.tanggal >=', $startDate);
            $builder->where('tblDataNonInventaris.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataTemplate() {
        $builder = $this->db->table($this->table);
        $builder->join('tblNonInventaris', 'tblNonInventaris.idNonInventaris = tblDataNonInventaris.idNonInventaris');
        $builder->where('tblDataNonInventaris.deleted_at', null);
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
        $builder->join('tblNonInventaris', 'tblNonInventaris.idNonInventaris = tblDataNonInventaris.idNonInventaris');
        $builder->where('tblDataNonInventaris.deleted_at IS NOT NULL');
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
