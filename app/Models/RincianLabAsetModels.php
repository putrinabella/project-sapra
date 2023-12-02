<?php

namespace App\Models;

use CodeIgniter\Model;

class RincianLabAsetModels extends Model
{
    protected $table            = 'tblRincianLabAset';
    protected $primaryKey       = 'idRincianLabAset';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idRincianLabAset', 'idIdentitasSarana', 'idSumberDana', 'idKategoriManajemen', 'idIdentitasLab', 'tahunPengadaan', 'saranaLayak', 'saranaRusak', 'spesifikasi', 'bukti', 'kodeRincianLabAset', 'hargaBeli', 'merk', 'type', 'warna', 'noSeri', 'nomorBarang', 'status', 'sectionAset','tanggalPemusnahan' , 'namaAkun', 'kodeAkun'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    
    public function isDuplicate($kodeRincianLabAset) {
        $builder = $this->db->table($this->table);
        return $builder->where('kodeRincianLabAset', $kodeRincianLabAset)
            ->countAllResults() > 0;
    }
    public function getSelectedRows($selectedRows) {
        $builder = $this->db->table($this->table);
        $builder->select('tblRincianLabAset.*, tblIdentitasSarana.*, tblSumberDana.*, tblKategoriManajemen.*, tblIdentitasLab.*');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->whereIn('tblRincianLabAset.idRincianLabAset', $selectedRows);
    
        return $builder->get()->getResult();
    }

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->orderBy('idRincianLabAset', 'asc'); 
        $query = $builder->get();
        return $query->getResult();
    }

    function getDestroy() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset =', 'Dimusnahkan');
        $query = $builder->get();
        return $query->getResult();
    }

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }

    public function setKodeAset($idRincianLabAset) {
        $relatedData = $this->find($idRincianLabAset);
    
        if ($relatedData) {
            $kodeKategoriManajemen = $relatedData->kodeKategoriManajemen;
            $kodeSarana = $relatedData->kodeSarana;
            $kodeLab = $relatedData->kodeLab;
            $kodeSumberDana = $relatedData->kodeSumberDana;
            $tahunPengadaan = $relatedData->tahunPengadaan;
            $nomorBarang = $relatedData->nomorBarang;
        
            if ($tahunPengadaan === '0000') {
                $tahunPengadaan = 'xx';
            } else {
                $tahunPengadaan = substr($tahunPengadaan, -2);
            }
            $nomorBarang = str_pad($nomorBarang, 3, '0', STR_PAD_LEFT);

            $kodeRincianLabAset = 'TS-BJB ' . $kodeKategoriManajemen . ' ' . $kodeLab . ' ' . $kodeSumberDana . ' ' . $tahunPengadaan . ' ' . $kodeSarana . ' ' . $nomorBarang;
    
            $this->update($idRincianLabAset, ['kodeRincianLabAset' => $kodeRincianLabAset]);
        }
    }
    

    public function updateKodeAset($idRincianLabAset) {
        $relatedData = $this->find($idRincianLabAset);
    
        if ($relatedData) {
            $kodeKategoriManajemen = $relatedData->kodeKategoriManajemen;
            $kodeSarana = $relatedData->kodeSarana;
            $kodeLab = $relatedData->kodeLab;
            $kodeSumberDana = $relatedData->kodeSumberDana;
            $tahunPengadaan = $relatedData->tahunPengadaan;
            $nomorBarang = $relatedData->nomorBarang;
        
            if ($tahunPengadaan === '0000') {
                $tahunPengadaan = 'xx';
            } else {
                $tahunPengadaan = substr($tahunPengadaan, -2);
            }
            $nomorBarang = str_pad($nomorBarang, 3, '0', STR_PAD_LEFT);
            $kodeRincianLabAset = 'TS-BJB ' . $kodeKategoriManajemen . ' ' . $kodeLab . ' ' . $kodeSumberDana . ' ' . $tahunPengadaan . ' ' . $kodeSarana . ' ' . $nomorBarang;
            $this->update($idRincianLabAset, ['kodeRincianLabAset' => $kodeRincianLabAset]);
        }
    }

    function getDataBySarana() {
        $builder = $this->db->table($this->table);
        $builder->select('tblIdentitasSarana.*');
        $builder->select('COUNT(*) AS jumlahTotal', false);
        $builder->select('SUM(1) AS jumlahAset', false); 
        $builder->select('SUM(CASE WHEN tblRincianLabAset.status = "Bagus" THEN 1 ELSE 0 END) AS jumlahBagus', false);
        $builder->select('SUM(CASE WHEN tblRincianLabAset.status = "Rusak" THEN 1 ELSE 0 END) AS jumlahRusak', false); 
        $builder->select('SUM(CASE WHEN tblRincianLabAset.status = "Hilang" THEN 1 ELSE 0 END) AS jumlahHilang', false); 
        $builder->select('SUM(CASE WHEN tblRincianLabAset.sectionAset = "Dipinjam" THEN 1 ELSE 0 END) AS jumlahDipinjam', false); 
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana');
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataBySaranaDetail($id = null) {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblIdentitasSarana.idIdentitasSarana', $id);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }
    
    function calculateTotalSarana($saranaLayak, $saranaRusak) {
        $saranaLayak = intval($saranaLayak);
        $saranaRusak = intval($saranaRusak);
        $totalSarana = $saranaLayak + $saranaRusak;
        return $totalSarana;
    }

    public function updateSectionAset($idRincianLabAset, $newSectionAset, $namaAkun, $kodeAkun) {
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
            $this->update($idRincianLabAset, $data);
            return true;
        }
        return false;
    }

    public function updateKodeRincianLabAset($idRincianLabAset, $newKodeRincianLabAset)
    {
        $data = [
            'kodeRincianLabAset' => $newKodeRincianLabAset,
        ];

        $builder = $this->db->table($this->table);
        $builder->where('idRincianLabAset', $idRincianLabAset);
        $builder->update($data);
    }
    

    // public function updateSectionAset($idRincianLabAset, $newSectionAset)
    // {
    //     if (in_array($newSectionAset, ["Dipinjam", "Dimusnahkan", "None"])) {
    //         $data = ['sectionAset' => $newSectionAset];
    
    //         if ($newSectionAset === 'Dimusnahkan') {
    //             $data['tanggalPemusnahan'] = date('Y-m-d H:i:s');
    //         }
    
    //         $this->update($idRincianLabAset, $data);
    //         return true;
    //     }
    //     return false;
    // }

    
    
}
