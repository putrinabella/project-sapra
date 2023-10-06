<?php

namespace App\Models;

use CodeIgniter\Model;

class SaranaLayananNonAsetModels extends Model
{
    protected $table            = 'tblSaranaLayananNonAset';
    protected $primaryKey       = 'idSaranaLayananNonAset';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idSaranaLayananNonAset', 'idSumberDana', 'idKategoriManajemen', 'idIdentitasPrasarana', 'idStatusLayanan', 'biaya', 'bukti', 'tanggal', 'spesifikasi'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblSaranaLayananNonAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblSaranaLayananNonAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblSaranaLayananNonAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblSaranaLayananNonAset.idStatusLayanan');
        $builder->where('tblSaranaLayananNonAset.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblSaranaLayananNonAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblSaranaLayananNonAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblSaranaLayananNonAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblSaranaLayananNonAset.idStatusLayanan');
        $builder->where('tblSaranaLayananNonAset.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblSaranaLayananNonAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblSaranaLayananNonAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblSaranaLayananNonAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblSaranaLayananNonAset.idStatusLayanan');
        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }
}
