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

    function getAll()
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblDetailManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblManajemenPeminjaman.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function getData($startDate = null, $endDate = null)
    {
        $builder = $this->db->table('tblManajemenPeminjaman');
        $builder->select('tblManajemenPeminjaman.*, tblIdentitasLab.namaLab,  tblDataSiswa.*, tblIdentitasKelas.namaKelas,  COUNT(tblRincianLabAset.idManajemenPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenPeminjaman.deleted_at', null);
    
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
        $builder->select('tblManajemenPeminjaman.*, tblIdentitasLab.namaLab,  tblDataSiswa.*, tblIdentitasKelas.namaKelas,  COUNT(tblRincianLabAset.idManajemenPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenPeminjaman.deleted_at', null);
    
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblManajemenPeminjaman.tanggal >=', $startDate);
            $builder->where('tblManajemenPeminjaman.tanggal <=', $endDate);
        }
    
        $builder->where('tblManajemenPeminjaman.loanStatus', 'Peminjaman');
        $builder->where('tblManajemenPeminjaman.asalPeminjam', $idUser);
        $builder->groupBy('tblManajemenPeminjaman.idManajemenPeminjaman');
        $query = $builder->get();
    
        return $query->getResult();
    }
    
    

    function findHistory($id = null, $columns = '*')
    {
        $builder = $this->db->table('tblDetailManajemenPeminjaman');
        $builder->select($columns);
        $builder->select('tblManajemenPeminjaman.*, tblIdentitasLab.namaLab,  tblDataSiswa.namaSiswa, tblIdentitasKelas.namaKelas,  COUNT(tblRincianLabAset.idManajemenPeminjaman) as jumlahPeminjaman');
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
        $builder->select('tblManajemenPeminjaman.*, tblIdentitasLab.namaLab,  tblDataSiswa.namaSiswa, tblIdentitasKelas.namaKelas,  COUNT(tblRincianLabAset.idManajemenPeminjaman) as jumlahPeminjaman');
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

    // function findAllHistory($startDate = null, $endDate = null, $columns = '*') {
    //     $builder = $this->db->table('tblDetailManajemenPeminjaman');
    //     $builder->select($columns);;
    //     $builder->select('tblManajemenPeminjaman.*, tblIdentitasLab.namaLab, tblDataPegawai.namaPegawai,  tblDataSiswa.namaSiswa, tblIdentitasKelas.namaKelas, tblKategoriPegawai.namaKategoriPegawai, COUNT(tblRincianLabAset.idManajemenPeminjaman) as jumlahPeminjaman');
    //     $builder->join('tblManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
    //     $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
    //     $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
    //     $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
    //     $builder->join('tblDataPegawai', 'tblDataPegawai.idDataPegawai = tblManajemenPeminjaman.asalPeminjam');
    //     $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenPeminjaman.asalPeminjam');
    //     $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
    //     $builder->join('tblKategoriPegawai', 'tblKategoriPegawai.idKategoriPegawai = tblDataPegawai.idKategoriPegawai');
    //     $builder->where('tblManajemenPeminjaman.loanStatus =', 'Pengembalian');
    //     $builder->groupBy('tblDetailManajemenPeminjaman.idManajemenPeminjaman');

        // if ($startDate && $endDate) {
        //     $builder->where('tblManajemenPeminjaman.tanggal >=', $startDate);
        //     $builder->where('tblManajemenPeminjaman.tanggal <=', $endDate);
        // }
    //     $query = $builder->get();
    //     return $query->getResult();
    // }



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

    function getReturnItemLAMAAA($idManajemenPeminjaman)
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('*');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->where('tblRincianLabAset.idManajemenPeminjaman', $idManajemenPeminjaman);
        $query = $builder->get();
        return $query->getResult();
    }

    function getBorrowItems($idManajemenPeminjaman)
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('*');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
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

    function getDataExport($startDate = null, $endDate = null)
    {
        $builder = $this->db->table('tblManajemenPeminjaman');
        $builder->select('tblManajemenPeminjaman.*, tblIdentitasLab.namaLab,  tblDataSiswa.*, tblIdentitasKelas.namaKelas,  COUNT(tblRincianLabAset.idManajemenPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenPeminjaman.deleted_at', null);

        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblManajemenPeminjaman.tanggal >=', $startDate);
            $builder->where('tblManajemenPeminjaman.tanggal <=', $endDate);
        }
        $builder->groupBy('tblManajemenPeminjaman.idManajemenPeminjaman');
        $query = $builder->get();
        return $query->getResult();
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
        $builder->select('COUNT(tblRincianLabAset.idManajemenPeminjaman) as jumlahPeminjaman');
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

    function getPerangkatIT()
    {
        $builder = $this->db->table('tblIdentitasSarana');
        $builder->where('tblIdentitasSarana.perangkatIT', 1);
        $query = $builder->get();
        return $query->getResult();
    }

    function getRecycle()
    {
        $builder = $this->db->table($this->table);
        $builder->select('tblManajemenPeminjaman.*, tblRincianLabAset.idIdentitasSarana, tblIdentitasSarana.*, tblIdentitasLab.*, tblDataSiswa.*, tblIdentitasKelas.*, tblKategoriPegawai.*');
        $builder->select('COUNT(tblRincianLabAset.idManajemenPeminjaman) as jumlahPeminjaman');
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


    // function updateKodeLabAset($id) {
    //     $builder = $this->db->table($this->table);
    //     $builder->set('kodeRincianLabAset', 
    //                     'CONCAT("A", LPAD(idIdentitasSarana, 3, "0"), 
    //                     "/", tahunPengadaan, 
    //                     "/", "SD", LPAD(idSumberDana, 2, "0"), 
    //                     "/", idIdentitasLab)',
    //                     false
    //                     );
    //     $builder->where('idRincianLabAset', $id);
    //     $builder->update();
    // }

    // function setKodeLabAset() {
    //     $builder = $this->db->table($this->table);
    //     $builder->set('kodeRincianLabAset', 
    //                     'CONCAT("A", LPAD(idIdentitasSarana, 3, "0"), 
    //                     "/", tahunPengadaan, 
    //                     "/", "SD", LPAD(idSumberDana, 2, "0"), 
    //                     "/", idIdentitasLab)',
    //                     false
    //                     );
    //     $builder->update();
    // }

    // function calculateTotalSarana($saranaLayak, $saranaRusak) {
    //     $saranaLayak = intval($saranaLayak);
    //     $saranaRusak = intval($saranaRusak);
    //     $totalSarana = $saranaLayak + $saranaRusak;
    //     return $totalSarana;
    // }
}
