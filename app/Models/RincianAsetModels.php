<?php

namespace App\Models;

use CodeIgniter\Model;

class RincianAsetModels extends Model
{
    protected $table            = 'tblRincianAset';
    protected $primaryKey       = 'idRincianAset';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idRincianAset', 'idIdentitasSarana', 'idSumberDana', 'idKategoriManajemen', 'idIdentitasPrasarana', 'tahunPengadaan', 'saranaLayak', 'saranaRusak', 'spesifikasi', 'bukti', 'kodeRincianAset', 'hargaBeli', 'merk', 'type', 'warna', 'noSeri', 'nomorBarang', 'status', 'sectionAset','tanggalPemusnahan' , 'namaAkun', 'kodeAkun'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $query = $builder->get();
        return $query->getResult();
    }

    function getDestroy() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.sectionAset =', 'Dimusnahkan');
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

    function getDataBySarana() {
        $builder = $this->db->table($this->table);
        $builder->select('tblIdentitasSarana.*');
        $builder->select('COUNT(*) AS jumlahTotal', false);
        $builder->select('SUM(1) AS jumlahAset', false); 
        $builder->select('SUM(CASE WHEN tblRincianAset.status = "Bagus" THEN 1 ELSE 0 END) AS jumlahBagus', false);
        $builder->select('SUM(CASE WHEN tblRincianAset.status = "Rusak" THEN 1 ELSE 0 END) AS jumlahRusak', false); 
        $builder->select('SUM(CASE WHEN tblRincianAset.status = "Hilang" THEN 1 ELSE 0 END) AS jumlahHilang', false); 
        $builder->select('SUM(CASE WHEN tblRincianAset.sectionAset = "Dipinjam" THEN 1 ELSE 0 END) AS jumlahDipinjam', false); 
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana');
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataBySaranaDetail($id = null) {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->where('tblIdentitasSarana.idIdentitasSarana', $id);
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianAset.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }
    
    function calculateTotalSarana($saranaLayak, $saranaRusak) {
        $saranaLayak = intval($saranaLayak);
        $saranaRusak = intval($saranaRusak);
        $totalSarana = $saranaLayak + $saranaRusak;
        return $totalSarana;
    }

    public function updateSectionAset($idRincianAset, $newSectionAset, $namaAkun, $kodeAkun) {
        if (in_array($newSectionAset, ["Dipinjam", "Dimusnahkan", "None"])) {
            $data = ['sectionAset' => $newSectionAset];

            if ($newSectionAset === 'Dimusnahkan') {
                $data['tanggalPemusnahan'] = date('Y-m-d H:i:s');
                $data['namaAkun'] = $namaAkun;
                $data['kodeAkun'] = $kodeAkun; 
            } else if ($newSectionAset === 'None') {
                $data['tanggalPemusnahan'] = NULL;
                $data['namaAkun'] = NULL;
                $data['kodeAkun'] = NULL;
            }
            $this->update($idRincianAset, $data);
            return true;
        }
        return false;
    }

    // public function updateSectionAset($idRincianAset, $newSectionAset)
    // {
    //     if (in_array($newSectionAset, ["Dipinjam", "Dimusnahkan", "None"])) {
    //         $data = ['sectionAset' => $newSectionAset];
    
    //         if ($newSectionAset === 'Dimusnahkan') {
    //             $data['tanggalPemusnahan'] = date('Y-m-d H:i:s');
    //         }
    
    //         $this->update($idRincianAset, $data);
    //         return true;
    //     }
    //     return false;
    // }
    
}
