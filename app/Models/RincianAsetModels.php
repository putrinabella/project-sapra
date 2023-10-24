<?php

namespace App\Models;

use CodeIgniter\Model;

class RincianAsetModels extends Model
{
    protected $table            = 'tblRincianAset';
    protected $primaryKey       = 'idRincianAset';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idRincianAset', 'idIdentitasSarana', 'idSumberDana', 'idKategoriManajemen', 'idIdentitasPrasarana', 'tahunPengadaan', 'saranaLayak', 'saranaRusak', 'spesifikasi', 'bukti', 'kodeRincianAset', 'hargaBeli', 'merk', 'type', 'warna', 'noSeri', 'nomorBarang'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->where('tblRincianAset.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
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
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }

    public function setKodeAset($idRincianAset) {
        $relatedData = $this->find($idRincianAset);
    
        if ($relatedData) {
            $kodeKategoriManajemen = $relatedData->kodeKategoriManajemen;
            $kodeSarana = $relatedData->kodeSarana;
            $kodePrasarana = $relatedData->kodePrasarana;
            $kodeSumberDana = $relatedData->kodeSumberDana;
            $tahunPengadaan = $relatedData->tahunPengadaan;
            $nomorBarang = $relatedData->nomorBarang;
        
            if ($tahunPengadaan === '0000') {
                $tahunPengadaan = 'xx';
            } else {
                $tahunPengadaan = substr($tahunPengadaan, -2);
            }
            $nomorBarang = str_pad($nomorBarang, 3, '0', STR_PAD_LEFT);

            $kodeRincianAset = 'TS-BJB ' . $kodeKategoriManajemen . ' ' . $kodePrasarana . ' ' . $kodeSumberDana . ' ' . $tahunPengadaan . ' ' . $kodeSarana . ' ' . $nomorBarang;
    
            $this->update($idRincianAset, ['kodeRincianAset' => $kodeRincianAset]);
        }
    }
    

    public function updateKodeAset($idRincianAset) {
        $relatedData = $this->find($idRincianAset);
    
        if ($relatedData) {
            $kodeKategoriManajemen = $relatedData->kodeKategoriManajemen;
            $kodeSarana = $relatedData->kodeSarana;
            $kodePrasarana = $relatedData->kodePrasarana;
            $kodeSumberDana = $relatedData->kodeSumberDana;
            $tahunPengadaan = $relatedData->tahunPengadaan;
            $nomorBarang = $relatedData->nomorBarang;
        
            if ($tahunPengadaan === '0000') {
                $tahunPengadaan = 'xx';
            } else {
                $tahunPengadaan = substr($tahunPengadaan, -2);
            }

            $nomorBarang = str_pad($nomorBarang, 3, '0', STR_PAD_LEFT);

            $kodeRincianAset = 'TS-BJB ' . $kodeKategoriManajemen . ' ' . $kodePrasarana . ' ' . $kodeSumberDana . ' ' . $tahunPengadaan . ' ' . $kodeSarana . ' ' . $nomorBarang;
    
            $this->update($idRincianAset, ['kodeRincianAset' => $kodeRincianAset]);
        }
    }
    
    function calculateTotalSarana($saranaLayak, $saranaRusak) {
        $saranaLayak = intval($saranaLayak);
        $saranaRusak = intval($saranaRusak);
        $totalSarana = $saranaLayak + $saranaRusak;
        return $totalSarana;
    }
}
