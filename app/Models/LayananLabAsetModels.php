<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananLabAsetModels extends Model
{
    protected $table            = 'tblLayananLabAset';
    protected $primaryKey       = 'idLayananLabAset';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idLayananLabAset', 'idIdentitasSarana', 'idSumberDana', 'idKategoriManajemen', 'idIdentitasLab', 'idStatusLayanan', 'biaya', 'bukti','tanggal'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table('tblLayananLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblLayananLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblLayananLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblLayananLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblLayananLabAset.idIdentitasLab');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblLayananLabAset.idStatusLayanan');
        $builder->where('tblLayananLabAset.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }
    function getRecycle() {
        $builder = $this->db->table('tblLayananLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblLayananLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblLayananLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblLayananLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblLayananLabAset.idIdentitasLab');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblLayananLabAset.idStatusLayanan');
        $builder->where('tblLayananLabAset.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    public function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblLayananLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblLayananLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblLayananLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblLayananLabAset.idIdentitasLab');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblLayananLabAset.idStatusLayanan');
        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }
}
