<?php

namespace App\Models;

use CodeIgniter\Model;

class RincianAsetModels extends Model
{
    protected $table            = 'tblRincianAset';
    protected $primaryKey       = 'idRincianAset';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idRincianAset', 'idIdentitasSarana', 'idSumberDana', 'idKategoriManajemen', 'kodePrasarana', 'tahunPengadaan', 'saranaLayak', 'saranaRusak', 'spesifikasi', 'totalSarana', 'bukti', 'kodeRincianAset', 'hargaBeli'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.kodePrasarana = tblRincianAset.kodePrasarana');
        $builder->where('tblRincianAset.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.kodePrasarana = tblRincianAset.kodePrasarana');
        $builder->where('tblRincianAset.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.kodePrasarana = tblRincianAset.kodePrasarana');
        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }

    function updateKodeAset($id) {
        $builder = $this->db->table($this->table);
        $builder->set('kodeRincianAset', 
                        'CONCAT("A", LPAD(idIdentitasSarana, 3, "0"), 
                        " ", tahunPengadaan, 
                        " ", "SD", LPAD(idSumberDana, 2, "0"), 
                        " ", kodePrasarana)',
                        false
                        );
        $builder->where('idRincianAset', $id);
        $builder->update();
    }

    function setKodeAset() {
        $builder = $this->db->table($this->table);
        $builder->set('kodeRincianAset', 
                        'CONCAT("A", LPAD(idIdentitasSarana, 3, "0"), 
                        " ", tahunPengadaan, 
                        " ", "SD", LPAD(idSumberDana, 2, "0"), 
                        " ", kodePrasarana)',
                        false
                        );
        $builder->update();
    }

    function calculateTotalSarana($saranaLayak, $saranaRusak) {
        $saranaLayak = intval($saranaLayak);
        $saranaRusak = intval($saranaRusak);
        $totalSarana = $saranaLayak + $saranaRusak;
        return $totalSarana;
    }
}