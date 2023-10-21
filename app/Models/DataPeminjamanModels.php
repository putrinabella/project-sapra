<?php

namespace App\Models;

use CodeIgniter\Model;

class DataPeminjamanModels extends Model
{
    protected $table            = 'tblManajemenPeminjaman';
    protected $primaryKey       = 'idManajemenPeminjaman';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idManajemenPeminjaman', 'namaPeminjam', 'asalPeminjam', 'idIdentitasSarana', 'idIdentitasLab', 'jumlah', 'tanggal', 'status', 'tanggalPengembalian', 'kodePeminjaman', 'namaPenerima', 'jumlahBarangDikembalikan', 'jumlahBarangRusak', 'jumlahBarangHilang'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblManajemenPeminjaman.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblManajemenPeminjaman.idIdentitasLab');
        $builder->where('tblManajemenPeminjaman.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }
    
    function getData($startDate = null, $endDate = null) {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblManajemenPeminjaman.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblManajemenPeminjaman.idIdentitasLab');
        $builder->where('tblManajemenPeminjaman.deleted_at', null);
    
        if ($startDate !== null && $endDate !== null) {
            // Add a where clause to filter by the date range
            $builder->where('tblManajemenPeminjaman.tanggal >=', $startDate);
            $builder->where('tblManajemenPeminjaman.tanggal <=', $endDate);
        }
    
        $query = $builder->get();
        return $query->getResult();
    }
    

    function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblManajemenPeminjaman.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblManajemenPeminjaman.idIdentitasLab');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idIdentitasLab = tblManajemenPeminjaman.idIdentitasLab');
        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }

    function getPerangkatIT() {
        $builder = $this->db->table('tblIdentitasSarana');
        $builder->where('tblIdentitasSarana.perangkatIT', 1); 
        $query = $builder->get();
        return $query->getResult();
    }
    
    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblManajemenPeminjaman.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblManajemenPeminjaman.idIdentitasLab');
        $builder->where('tblManajemenPeminjaman.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }


    function updateKodeLabAset($id) {
        $builder = $this->db->table($this->table);
        $builder->set('kodeRincianLabAset', 
                        'CONCAT("A", LPAD(idIdentitasSarana, 3, "0"), 
                        "/", tahunPengadaan, 
                        "/", "SD", LPAD(idSumberDana, 2, "0"), 
                        "/", idIdentitasLab)',
                        false
                        );
        $builder->where('idRincianLabAset', $id);
        $builder->update();
    }

    function setKodeLabAset() {
        $builder = $this->db->table($this->table);
        $builder->set('kodeRincianLabAset', 
                        'CONCAT("A", LPAD(idIdentitasSarana, 3, "0"), 
                        "/", tahunPengadaan, 
                        "/", "SD", LPAD(idSumberDana, 2, "0"), 
                        "/", idIdentitasLab)',
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

    public function updateSaranaLayak($idRincianLabAset, $jumlahBarangRusak, $jumlahBarangHilang) {
        $builder = $this->db->table('tblRincianLabAset');
        $existingAsetTersedia = $builder->select('saranaLayak, saranaRusak, saranaHilang')
            ->where('idRincianLabAset', $idRincianLabAset)
            ->get()
            ->getRow();
    
        if ($existingAsetTersedia) {
            $currentSaranaLayak = $existingAsetTersedia->saranaLayak;
            $currentSaranaRusak = $existingAsetTersedia->saranaRusak;
            $currentSaranaHilang = $existingAsetTersedia->saranaHilang;
    
            if ($jumlahBarangRusak != 0) {
                $newSaranaRusak = $currentSaranaRusak + $jumlahBarangRusak;
                $builder->set('saranaRusak', $newSaranaRusak);
            }
    
            if ($jumlahBarangHilang != 0) {
                $newSaranaHilang = $currentSaranaHilang + $jumlahBarangHilang;
                $builder->set('saranaHilang', $newSaranaHilang);
            }
    
            if ($jumlahBarangRusak != 0 || $jumlahBarangHilang != 0) {
                $newSaranaLayak = $currentSaranaLayak - ($jumlahBarangRusak + $jumlahBarangHilang);
    
                // Make sure the new value is not negative
                if ($newSaranaLayak < 0) {
                    $newSaranaLayak = 0;
                }
    
                // Check if the value is different before attempting the update
                if ($currentSaranaLayak !== $newSaranaLayak) {
                    $builder->set('saranaLayak', $newSaranaLayak);
                }
            }
    
            $builder->where('idRincianLabAset', $idRincianLabAset)->update();
        }
    }
    
    
    

    // public function updateSaranaLayak($idIdentitasSarana, $jumlahBarangRusak, $jumlahBarangHilang) {
    //     $builder = $this->db->table('tblRincianLabAset');
    //     $builder->join('tblRincianLabAset', 'tblRincianLabAset.idIdentitasLab = tblIdentitasLab.idIdentitasLab');
    //     $existingAsetTersedia = $builder->select('saranaLayak')
    //         ->where('idIdentitasSarana', $idIdentitasSarana)
    //         ->where('idIdentitasLab', $idIdentitasLab) 
    //         ->get()
    //         ->getRow();
    
    //     if ($existingAsetTersedia) {
    //         $currentSaranaLayak = $existingAsetTersedia->saranaLayak;
    //         if ($jumlahBarangRusak != 0 || $jumlahBarangHilang != 0) {
    //             $newSaranaLayak = $currentSaranaLayak - ($jumlahBarangRusak + $jumlahBarangHilang);
    
    //             // Make sure the new value is not negative
    //             if ($newSaranaLayak < 0) {
    //                 $newSaranaLayak = 0;
    //             }
    
    //             // Check if the value is different before attempting the update
    //             if ($currentSaranaLayak !== $newSaranaLayak) {
    //                 $builder->set('saranaLayak', $newSaranaLayak)
    //                     ->where('idIdentitasSarana', $idIdentitasSarana)
    //                     ->where('idIdentitasLab', $idIdentitasLab)
    //                     ->update();
    //             }
    //         }
    //     }
    // }
    
}
