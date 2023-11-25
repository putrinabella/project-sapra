<?php

namespace App\Models;

use CodeIgniter\Model;

class DataPegawaiModels extends Model
{
    protected $table            = 'tblDataPegawai';
    protected $primaryKey       = 'idDataPegawai';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idDataPegawai', 'namaPegawai', 'nip', 'idKategoriPegawai'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblKategoriPegawai', 'tblKategoriPegawai.idKategoriPegawai = tblDataPegawai.idKategoriPegawai');
        $builder->where('tblDataPegawai.deleted_at', NULL);
        $query = $builder->get();
        return $query->getResult();
    }

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblKategoriPegawai', 'tblKategoriPegawai.idKategoriPegawai = tblDataPegawai.idKategoriPegawai');  
        $builder->where('tblDataPegawai.deleted_at IS NOT NULL');
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

    public function isDuplicate($nip) {
        $builder = $this->db->table($this->table);
        return $builder->where('nip', $nip)
            ->countAllResults() > 0;
    }
}
