<?php

namespace App\Models;

use CodeIgniter\Model;

class DataAsetPeminjamanModels extends Model
{
    protected $table            = 'tblManajemenAsetPeminjaman';
    protected $primaryKey       = 'idManajemenAsetPeminjaman';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idManajemenAsetPeminjaman', 'namaPeminjam', 'asalPeminjam', 'jumlah', 'tanggal', 'loanStatus', 'tanggalPengembalian', 'kodePeminjaman', 'namaPenerima', 'idRincianAset'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll($startDate = null, $endDate = null)
    {
        $builder = $this->db->table('tblManajemenAsetPeminjaman');
        $builder->select('tblManajemenAsetPeminjaman.*, tblIdentitasPrasarana.namaPrasarana,  tblDataSiswa.*, tblIdentitasKelas.namaKelas,  COUNT(tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailManajemenAsetPeminjaman', 'tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman = tblManajemenAsetPeminjaman.idManajemenAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailManajemenAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenAsetPeminjaman.deleted_at', null);
        $builder->orderBy("CASE 
                    WHEN tblManajemenAsetPeminjaman.loanStatus = 'Peminjaman' THEN 1
                    WHEN tblManajemenAsetPeminjaman.loanStatus = 'Pengembalian' THEN 2
                    WHEN tblManajemenAsetPeminjaman.loanStatus = 'Dibatalkan' THEN 3
                    ELSE 4 END", 'asc'); 
        $builder->orderBy('tblManajemenAsetPeminjaman.tanggal', 'asc'); 
    
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblManajemenAsetPeminjaman.tanggal >=', $startDate);
            $builder->where('tblManajemenAsetPeminjaman.tanggal <=', $endDate);
        }
    
        $builder->groupBy('tblManajemenAsetPeminjaman.idManajemenAsetPeminjaman');
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataSiswa($startDate = null, $endDate = null, $idUser)
    {
        $builder = $this->db->table('tblManajemenAsetPeminjaman');
        $builder->select('tblManajemenAsetPeminjaman.*, tblIdentitasPrasarana.namaPrasarana,  tblDataSiswa.*, tblIdentitasKelas.namaKelas,  COUNT(tblRincianAset.idManajemenAsetPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailManajemenAsetPeminjaman', 'tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman = tblManajemenAsetPeminjaman.idManajemenAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailManajemenAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenAsetPeminjaman.deleted_at', null);
    
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblManajemenAsetPeminjaman.tanggal >=', $startDate);
            $builder->where('tblManajemenAsetPeminjaman.tanggal <=', $endDate);
        }
    
        $builder->where('tblManajemenAsetPeminjaman.asalPeminjam', $idUser);
        $builder->groupBy('tblManajemenAsetPeminjaman.idManajemenAsetPeminjaman');
        $query = $builder->get();
    
        return $query->getResult();
    }
    
    

    function findHistory($id = null, $columns = '*')
    {
        $builder = $this->db->table('tblDetailManajemenAsetPeminjaman');
        $builder->select($columns);
        $builder->select('tblManajemenAsetPeminjaman.*, tblIdentitasPrasarana.namaPrasarana,  tblDataSiswa.namaSiswa, tblIdentitasKelas.namaKelas,  COUNT(tblRincianAset.idManajemenAsetPeminjaman) as jumlahPeminjaman');
        $builder->join('tblManajemenAsetPeminjaman', 'tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman = tblManajemenAsetPeminjaman.idManajemenAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailManajemenAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        
        $builder->where('tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman', $id);
        $builder->groupBy('tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman');
        $query = $builder->get();
        return $query->getRow();
    }

    function findAllHistory($startDate = null, $endDate = null, $columns = '*') { 
        $builder = $this->db->table('tblDetailManajemenAsetPeminjaman');
        $builder->select($columns);
        $builder->select('tblManajemenAsetPeminjaman.*, tblIdentitasPrasarana.namaPrasarana,  tblDataSiswa.namaSiswa, tblIdentitasKelas.namaKelas,  COUNT(tblRincianAset.idManajemenAsetPeminjaman) as jumlahPeminjaman');
        $builder->join('tblManajemenAsetPeminjaman', 'tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman = tblManajemenAsetPeminjaman.idManajemenAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailManajemenAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenAsetPeminjaman.loanStatus =', 'Pengembalian');
        $builder->groupBy('tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman');
        if ($startDate && $endDate) {
            $builder->where('tblManajemenAsetPeminjaman.tanggal >=', $startDate);
            $builder->where('tblManajemenAsetPeminjaman.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
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
    

    public function getRincianAset($idManajemenAsetPeminjaman)
    {
        $builder = $this->db->table('tblDetailManajemenAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailManajemenAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->where('tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman', $idManajemenAsetPeminjaman);
        $query = $builder->get();

        return $query->getResult();
    }

    public function getRincianItem($idManajemenAsetPeminjaman)
    {
        $builder = $this->db->table('tblDetailManajemenAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailManajemenAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->select('tblIdentitasSarana.*, COUNT(tblIdentitasSarana.idIdentitasSarana) as totalAset');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->where('tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman', $idManajemenAsetPeminjaman);
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana');
        $query = $builder->get();

        return $query->getResult();
    }

    function getReturnItem($idManajemenAsetPeminjaman)
    {
        $builder = $this->db->table('tblDetailManajemenAsetPeminjaman');
        $builder->select('tblRincianAset.*');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailManajemenAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->where('tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman', $idManajemenAsetPeminjaman);
        $query = $builder->get();
        return $query->getResult();
    }


    function getBorrowItems($idManajemenAsetPeminjaman)
    {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('*');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->where('tblRincianAset.idManajemenAsetPeminjaman', $idManajemenAsetPeminjaman);
        $query = $builder->get();
        return $query->getResult();
    }

    public function updateReturnStatus($idRincianAset, $status)
    {
        $builder = $this->db->table('tblRincianAset');
        $builder->where('idRincianAset', $idRincianAset)
            ->where('sectionAset', "Dipinjam")
            ->set('status', $status)
            ->update();
    }

    public function updateDetailReturnStatus($idRincianAset, $getIdManajemenAsetPeminjaman, $status)
    {
        $builder = $this->db->table('tblDetailManajemenAsetPeminjaman');
        $builder->where('idRincianAset', $idRincianAset)
            ->where('idManajemenAsetPeminjaman', $getIdManajemenAsetPeminjaman)
            ->set('statusSetelahPengembalian', $status)
            ->update();
    }

    public function updateReturnSectionAset($idRincianAset)
    {
        $builder = $this->db->table('tblRincianAset');
        $data = [
            'sectionAset' => "None",
            'idManajemenAsetPeminjaman' => null,
        ];
        $builder->where('idRincianAset', $idRincianAset)
            ->set($data)
            ->update();
    }

    public function updateRevokeLoan($idManajemenAsetPeminjaman)
    {
        $builder = $this->db->table('tblManajemenAsetPeminjaman');
        $builder->where('idManajemenAsetPeminjaman', $idManajemenAsetPeminjaman)
            ->set('loanStatus', "Dibatalkan")
            ->update();
    }

    function getDataExcel($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblDetailManajemenAsetPeminjaman', 'tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman = tblManajemenAsetPeminjaman.idManajemenAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailManajemenAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenAsetPeminjaman.deleted_at', null);
        $builder->where('tblManajemenAsetPeminjaman.loanStatus', "Pengembalian");

        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblManajemenAsetPeminjaman.tanggal >=', $startDate);
            $builder->where('tblManajemenAsetPeminjaman.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }
    
    function getDataExcelPeminjaman($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblDetailManajemenAsetPeminjaman', 'tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman = tblManajemenAsetPeminjaman.idManajemenAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailManajemenAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenAsetPeminjaman.deleted_at', null);
        $builder->where('tblManajemenAsetPeminjaman.loanStatus', "Peminjaman");

        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblManajemenAsetPeminjaman.tanggal >=', $startDate);
            $builder->where('tblManajemenAsetPeminjaman.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }
    

    function find($id = null, $columns = '*')
    {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        $builder->select('COUNT(tblRincianAset.idManajemenAsetPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailManajemenAsetPeminjaman', 'tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman = tblManajemenAsetPeminjaman.idManajemenAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailManajemenAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenAsetPeminjaman.' . $this->primaryKey, $id);
        $query = $builder->get();
        return $query->getRow();
    }

    function getRecycle()
    {
        $builder = $this->db->table($this->table);
        $builder->select('tblManajemenAsetPeminjaman.*, tblRincianAset.idIdentitasSarana, tblIdentitasSarana.*, tblIdentitasPrasarana.*, tblDataSiswa.*, tblIdentitasKelas.*');
        $builder->select('COUNT(tblRincianAset.idManajemenAsetPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailManajemenAsetPeminjaman', 'tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman = tblManajemenAsetPeminjaman.idManajemenAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailManajemenAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblManajemenAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblManajemenAsetPeminjaman.deleted_at IS NOT NULL');
        $builder->groupBy('tblManajemenAsetPeminjaman.idManajemenAsetPeminjaman');
        $query = $builder->get();
        return $query->getResult();
    }

    function getIdDetailManajemenAsetPeminjaman($idManajemenAsetPeminjaman) {
        $builder = $this->db->table('tblDetailManajemenAsetPeminjaman');
        $builder->where('tblDetailManajemenAsetPeminjaman.idManajemenAsetPeminjaman', $idManajemenAsetPeminjaman);
        $query = $builder->get();
        return $query->getResult();
    }
}
