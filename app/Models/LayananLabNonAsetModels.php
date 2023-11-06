<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananLabNonAsetModels extends Model
{   
    protected $table            = 'tblLayananLabNonAset';
    protected $primaryKey       = 'idLayananLabNonAset';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idLayananLabNonAset', 'idSumberDana', 'idKategoriMep', 'idIdentitasLab', 'idStatusLayanan', 'biaya', 'bukti', 'tanggal', 'spesifikasi'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblLayananLabNonAset.idSumberDana');
        $builder->join('tblKategoriMep', 'tblKategoriMep.idKategoriMep = tblLayananLabNonAset.idKategoriMep');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblLayananLabNonAset.idIdentitasLab');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblLayananLabNonAset.idStatusLayanan');
        $builder->where('tblLayananLabNonAset.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblLayananLabNonAset.idSumberDana');
        $builder->join('tblKategoriMep', 'tblKategoriMep.idKategoriMep = tblLayananLabNonAset.idKategoriMep');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblLayananLabNonAset.idIdentitasLab');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblLayananLabNonAset.idStatusLayanan');
        $builder->where('tblLayananLabNonAset.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblLayananLabNonAset.idSumberDana');
        $builder->join('tblKategoriMep', 'tblKategoriMep.idKategoriMep = tblLayananLabNonAset.idKategoriMep');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblLayananLabNonAset.idIdentitasLab');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblLayananLabNonAset.idStatusLayanan');
        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }
}
