<?php

namespace App\Models;

use CodeIgniter\Model;

class SaranaLayananAsetModels extends Model
{
    protected $table            = 'tblSaranaLayananAset';
    protected $primaryKey       = 'idSaranaLayananAset';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idSaranaLayananAset', 'idIdentitasSarana', 'idSumberDana', 'idKategoriManajemen', 'idIdentitasPrasarana', 'idStatusLayanan', 'biaya', 'bukti'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table('tblSaranaLayananAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblSaranaLayananAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblSaranaLayananAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblSaranaLayananAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblSaranaLayananAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblSaranaLayananAset.idStatusLayanan');
        $builder->where('tblSaranaLayananAset.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }
    function getRecycle() {
        $builder = $this->db->table('tblSaranaLayananAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblSaranaLayananAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblSaranaLayananAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblSaranaLayananAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblSaranaLayananAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblSaranaLayananAset.idStatusLayanan');
        $builder->where('tblSaranaLayananAset.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }
}
