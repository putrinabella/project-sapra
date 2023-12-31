<?php

namespace App\Models;

use CodeIgniter\Model;

class DataPeminjamanModels extends Model
{
    protected $table            = 'tblManajemenPeminjaman';
    protected $primaryKey       = 'idManajemenPeminjaman';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idManajemenPeminjaman', 'namaPeminjam', 'asalPeminjam', 'jumlah', 'tanggal', 'loanStatus', 'tanggalPengembalian', 'kodePeminjaman', 'namaPenerima', 'idRincianLabAset'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll($startDate = null, $endDate = null)
    {
        $builder = $this->db->table('tblManajemenPeminjaman');
        $builder->select('tblManajemenPeminjaman.*, tblIdentitasLab.namaLab,  tblDataSiswa.*, tblIdentitasKelas.namaKelas,  COUNT(tblDetailManajemenPeminjaman.idManajemenPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenPeminjaman.deleted_at', null);
        $builder->orderBy("CASE 
                    WHEN tblManajemenPeminjaman.loanStatus = 'Peminjaman' THEN 1
                    WHEN tblManajemenPeminjaman.loanStatus = 'Pengembalian' THEN 2
                    WHEN tblManajemenPeminjaman.loanStatus = 'Dibatalkan' THEN 3
                    ELSE 4 END", 'asc'); 
        $builder->orderBy('tblManajemenPeminjaman.tanggal', "asc");
        $builder->orderBy('tblIdentitasKelas.namaKelas', "asc");
    
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblManajemenPeminjaman.tanggal >=', $startDate);
            $builder->where('tblManajemenPeminjaman.tanggal <=', $endDate);
        }
    
        $builder->groupBy('tblManajemenPeminjaman.idManajemenPeminjaman');
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataSiswa($startDate = null, $endDate = null, $idUser)
    {
        $builder = $this->db->table('tblManajemenPeminjaman');
        $builder->select('tblManajemenPeminjaman.*, tblIdentitasLab.namaLab,  tblDataSiswa.*, tblIdentitasKelas.namaKelas,  COUNT(tblDetailManajemenPeminjaman.idManajemenPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenPeminjaman.deleted_at', null);
        $builder->orderBy("CASE 
                    WHEN tblManajemenPeminjaman.loanStatus = 'Peminjaman' THEN 1
                    WHEN tblManajemenPeminjaman.loanStatus = 'Pengembalian' THEN 2
                    WHEN tblManajemenPeminjaman.loanStatus = 'Dibatalkan' THEN 3
                    ELSE 4 END", 'asc'); 
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblManajemenPeminjaman.tanggal >=', $startDate);
            $builder->where('tblManajemenPeminjaman.tanggal <=', $endDate);
        }
    
        $builder->where('tblManajemenPeminjaman.asalPeminjam', $idUser);
        $builder->groupBy('tblManajemenPeminjaman.idManajemenPeminjaman');
        $query = $builder->get();
    
        return $query->getResult();
    }
    
    function findHistory($id = null, $columns = '*')
    {
        $builder = $this->db->table('tblDetailManajemenPeminjaman');
        $builder->select($columns);
        $builder->select('tblManajemenPeminjaman.*, tblIdentitasLab.namaLab,  tblDataSiswa.namaSiswa, tblIdentitasKelas.namaKelas,  COUNT(tblDetailManajemenPeminjaman.idManajemenPeminjaman) as jumlahPeminjaman');
        $builder->join('tblManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        
        $builder->where('tblDetailManajemenPeminjaman.idManajemenPeminjaman', $id);
        $builder->groupBy('tblDetailManajemenPeminjaman.idManajemenPeminjaman');
        $query = $builder->get();
        return $query->getRow();
    }

    function findAllHistory($startDate = null, $endDate = null, $columns = '*') { 
        $builder = $this->db->table('tblDetailManajemenPeminjaman');
        $builder->select($columns);
        $builder->select('tblManajemenPeminjaman.*, tblIdentitasLab.namaLab,  tblDataSiswa.namaSiswa, tblIdentitasKelas.namaKelas,  COUNT(tblDetailManajemenPeminjaman.idManajemenPeminjaman) as jumlahPeminjaman');
        $builder->join('tblManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenPeminjaman.loanStatus =', 'Pengembalian');
        $builder->groupBy('tblDetailManajemenPeminjaman.idManajemenPeminjaman');
        if ($startDate && $endDate) {
            $builder->where('tblManajemenPeminjaman.tanggal >=', $startDate);
            $builder->where('tblManajemenPeminjaman.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
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
    
    public function getRincianLabAset($idManajemenPeminjaman)
    {
        $builder = $this->db->table('tblDetailManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblDetailManajemenPeminjaman.idManajemenPeminjaman', $idManajemenPeminjaman);
        $query = $builder->get();

        return $query->getResult();
    }

    public function getRincianItem($idManajemenPeminjaman)
    {
        $builder = $this->db->table('tblDetailManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->select('tblIdentitasSarana.*, COUNT(tblIdentitasSarana.idIdentitasSarana) as totalAset');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->where('tblDetailManajemenPeminjaman.idManajemenPeminjaman', $idManajemenPeminjaman);
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana');
        $query = $builder->get();

        return $query->getResult();
    }

    function getReturnItem($idManajemenPeminjaman)
    {
        $builder = $this->db->table('tblDetailManajemenPeminjaman');
        $builder->select('tblRincianLabAset.*');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->where('tblDetailManajemenPeminjaman.idManajemenPeminjaman', $idManajemenPeminjaman);
        $query = $builder->get();
        return $query->getResult();
    }

    function getBorrowItems($idManajemenPeminjaman)
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('*');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.idManajemenPeminjaman', $idManajemenPeminjaman);
        $query = $builder->get();
        return $query->getResult();
    }

    public function updateReturnStatus($idRincianLabAset, $status)
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->where('idRincianLabAset', $idRincianLabAset)
            ->where('sectionAset', "Dipinjam")
            ->set('status', $status)
            ->update();
    }

    public function updateDetailReturnStatus($idRincianLabAset, $getIdManajemenPeminjaman, $status)
    {
        $builder = $this->db->table('tblDetailManajemenPeminjaman');
        $builder->where('idRincianLabAset', $idRincianLabAset)
            ->where('idManajemenPeminjaman', $getIdManajemenPeminjaman)
            ->set('statusSetelahPengembalian', $status)
            ->update();
    }

    public function updateReturnSectionAset($idRincianLabAset)
    {
        $builder = $this->db->table('tblRincianLabAset');
        $data = [
            'sectionAset' => "None",
            'idManajemenPeminjaman' => null,
        ];
        $builder->where('idRincianLabAset', $idRincianLabAset)
            ->set($data)
            ->update();
    }

    public function updateRevokeLoan($idManajemenPeminjaman)
    {
        $builder = $this->db->table('tblManajemenPeminjaman');
        $builder->where('idManajemenPeminjaman', $idManajemenPeminjaman)
            ->set('loanStatus', "Dibatalkan")
            ->update();
    }

    function getDataExcel($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblDetailManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenPeminjaman.deleted_at', null);
        $builder->where('tblManajemenPeminjaman.loanStatus', "Pengembalian");

        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblManajemenPeminjaman.tanggal >=', $startDate);
            $builder->where('tblManajemenPeminjaman.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }
    
    function getDataExcelPeminjaman($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblDetailManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenPeminjaman.deleted_at', null);
        $builder->where('tblManajemenPeminjaman.loanStatus', "Peminjaman");

        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblManajemenPeminjaman.tanggal >=', $startDate);
            $builder->where('tblManajemenPeminjaman.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }
    
    function find($id = null, $columns = '*')
    {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        $builder->select('COUNT(tblDetailManajemenPeminjaman.idManajemenPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenPeminjaman.' . $this->primaryKey, $id);
        $query = $builder->get();
        return $query->getRow();
    }

    function getRecycle()
    {
        $builder = $this->db->table($this->table);
        $builder->select('tblManajemenPeminjaman.*, tblRincianLabAset.idIdentitasSarana, tblIdentitasSarana.*, tblIdentitasLab.*, tblDataSiswa.*, tblIdentitasKelas.*');
        $builder->select('COUNT(tblDetailManajemenPeminjaman.idManajemenPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenPeminjaman.deleted_at IS NOT NULL');
        $builder->groupBy('tblManajemenPeminjaman.idManajemenPeminjaman');
        $query = $builder->get();
        return $query->getResult();
    }

    function getIdDetailManajemenPeminjaman($idManajemenPeminjaman) {
        $builder = $this->db->table('tblDetailManajemenPeminjaman');
        $builder->where('tblDetailManajemenPeminjaman.idManajemenPeminjaman', $idManajemenPeminjaman);
        $query = $builder->get();
        return $query->getResult();
    }

    // Cancel loan request (user only) =============================================================================================== //
    
    public function getRequestItems($idRequestPeminjaman ) {
        $builder = $this->db->table('tblDetailRequestPeminjaman');
        $builder->select('*');
        $builder->where('tblDetailRequestPeminjaman.idRequestPeminjaman', $idRequestPeminjaman);
        $query = $builder->get();
        return $query->getResult();
    }

    public function updateRevokeRequest($idRequestPeminjaman ) {
        $builder = $this->db->table('tblRequestPeminjaman');
        $builder->where('idRequestPeminjaman ', $idRequestPeminjaman )
            ->set('loanStatus', "Cancel")
            ->update();
    }

    public function updateRequestSectionAset($idDetailRequestPeminjaman) {
        $builder = $this->db->table('tblDetailRequestPeminjaman');
        $data = [
            'requestItemStatus' => "Cancel",
        ];
        $builder->where('idDetailRequestPeminjaman', $idDetailRequestPeminjaman)
            ->set($data)
            ->update();
    }
    
    // End of cancel loan request (user only) ======================================================================================== //
}
