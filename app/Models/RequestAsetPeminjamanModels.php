<?php

namespace App\Models;

use CodeIgniter\Model;

class RequestAsetPeminjamanModels extends Model
{
    protected $table            = 'tblRequestAsetPeminjaman';
    protected $primaryKey       = 'idRequestAsetPeminjaman';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idRequestAsetPeminjaman', 'tanggal', 'asalPeminjam', 'keperluanAlat', 'lamaPinjam', 'loanStatus'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    protected $tblRincianAset  = 'tblRincianAset';


    function getAll($startDate = null, $endDate = null)
    {
        $builder = $this->db->table('tblRequestAsetPeminjaman');
        $builder->select('tblRequestAsetPeminjaman.*, tblIdentitasPrasarana.namaPrasarana,  tblDataSiswa.*, tblIdentitasKelas.namaKelas,  COUNT(tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman) as jumlahPeminjaman');
        $builder->select('(SUM(CASE WHEN tblDetailRequestAsetPeminjaman.requestItemStatus = "Approve" THEN 1 ELSE 0 END)) AS jumlahApprove', false);
        $builder->join('tblDetailRequestAsetPeminjaman', 'tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman = tblRequestAsetPeminjaman.idRequestAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailRequestAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestAsetPeminjaman.deleted_at', null);
        $builder->orderBy("CASE 
                            WHEN tblRequestAsetPeminjaman.loanStatus = 'Request' THEN 1
                            WHEN tblRequestAsetPeminjaman.loanStatus = 'Approve' THEN 2
                            WHEN tblRequestAsetPeminjaman.loanStatus = 'Reject' THEN 3
                            WHEN tblRequestAsetPeminjaman.loanStatus = 'Cancel' THEN 4
                            ELSE 5 END", 'asc'); 
        $builder->orderBy('tblRequestAsetPeminjaman.tanggal', 'asc'); 
                            
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblRequestAsetPeminjaman.tanggal >=', $startDate);
            $builder->where('tblRequestAsetPeminjaman.tanggal <=', $endDate);
        }
    
        $builder->groupBy('tblRequestAsetPeminjaman.idRequestAsetPeminjaman');
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataRequestUser($startDate = null, $endDate = null, $idUser)
    {
        $builder = $this->db->table('tblRequestAsetPeminjaman');
        $builder->select('tblRequestAsetPeminjaman.*, tblIdentitasPrasarana.namaPrasarana,  tblDataSiswa.*, tblIdentitasKelas.namaKelas,  COUNT(tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailRequestAsetPeminjaman', 'tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman = tblRequestAsetPeminjaman.idRequestAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailRequestAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestAsetPeminjaman.loanStatus !=', 'Approve');
        $builder->where('tblRequestAsetPeminjaman.deleted_at', null);
        $builder->orderBy("CASE 
                    WHEN tblRequestAsetPeminjaman.loanStatus = 'Request' THEN 1
                    WHEN tblRequestAsetPeminjaman.loanStatus = 'Approve' THEN 2
                    WHEN tblRequestAsetPeminjaman.loanStatus = 'Reject' THEN 3
                    WHEN tblRequestAsetPeminjaman.loanStatus = 'Cancel' THEN 4
                    ELSE 5 END", 'asc'); 

        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblRequestAsetPeminjaman.tanggal >=', $startDate);
            $builder->where('tblRequestAsetPeminjaman.tanggal <=', $endDate);
        }
    
        $builder->where('tblRequestAsetPeminjaman.asalPeminjam', $idUser);
        $builder->groupBy('tblRequestAsetPeminjaman.idRequestAsetPeminjaman');
        $query = $builder->get();
        return $query->getResult();
    }

    
    function getBorrowItems($idRequestAsetPeminjaman)
    {
        $builder = $this->db->table('tblDetailRequestAsetPeminjaman');
        $builder->select('*');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailRequestAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblRequestAsetPeminjaman', 'tblRequestAsetPeminjaman.idRequestAsetPeminjaman = tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman');
        $builder->where('tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman', $idRequestAsetPeminjaman);
        $query = $builder->get();
        return $query->getResult();
    }

    public function updateRequestAsetPeminjaman($idRequestAsetPeminjaman, $requestStatus) {
        $builder = $this->db->table('tblRequestAsetPeminjaman');
        $data = [
            'loanStatus' => $requestStatus,
        ];

        $builder->where('idRequestAsetPeminjaman', $idRequestAsetPeminjaman)
                ->update($data);
    }


    public function approveDetailRequestAsetPeminjaman($idRequestAsetPeminjaman, $requestStatus, $idRincianAset) {
        $builder = $this->db->table('tblDetailRequestAsetPeminjaman');
        $data = [
            'requestItemStatus' => $requestStatus,
        ];
    
        $builder->where('idRequestAsetPeminjaman', $idRequestAsetPeminjaman)
                ->whereIn('idRincianAset', $idRincianAset)
                ->update($data);
    }
    
    function find($id = null, $columns = '*')
    {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        $builder->select('COUNT(tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailRequestAsetPeminjaman', 'tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman = tblRequestAsetPeminjaman.idRequestAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailRequestAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestAsetPeminjaman.' . $this->primaryKey, $id);
        $query = $builder->get();
        return $query->getRow();
    }

    function findHistory($id = null, $columns = '*')
    {
        $builder = $this->db->table('tblDetailRequestAsetPeminjaman');
        $builder->select($columns);
        $builder->select('tblRequestAsetPeminjaman.*, tblIdentitasPrasarana.namaPrasarana,  tblDataSiswa.namaSiswa, tblIdentitasKelas.namaKelas,  COUNT(tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman) as jumlahPeminjaman');
        $builder->join('tblRequestAsetPeminjaman', 'tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman = tblRequestAsetPeminjaman.idRequestAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailRequestAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        
        $builder->where('tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman', $id);
        $builder->groupBy('tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman');
        $query = $builder->get();
        return $query->getRow();
    }

    function findAllHistory($startDate = null, $endDate = null, $columns = '*') { 
        $builder = $this->db->table('tblDetailRequestAsetPeminjaman');
        $builder->select($columns);
        $builder->select('tblRequestAsetPeminjaman.*, tblIdentitasPrasarana.namaPrasarana,  tblDataSiswa.namaSiswa, tblIdentitasKelas.namaKelas,  COUNT(tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman) as jumlahPeminjaman');
        $builder->join('tblRequestAsetPeminjaman', 'tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman = tblRequestAsetPeminjaman.idRequestAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailRequestAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestAsetPeminjaman.loanStatus =', 'Pengembalian');
        $builder->groupBy('tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman');
        if ($startDate && $endDate) {
            $builder->where('tblRequestAsetPeminjaman.tanggal >=', $startDate);
            $builder->where('tblRequestAsetPeminjaman.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }

    public function getRincianItem($idRequestAsetPeminjaman)
    {
        $builder = $this->db->table('tblDetailRequestAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailRequestAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->select('tblIdentitasSarana.*, COUNT(tblIdentitasSarana.idIdentitasSarana) as totalAset');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->where('tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman', $idRequestAsetPeminjaman);
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana');
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
        $builder = $this->db->table('tblDetailRequestAsetPeminjaman');
        $builder->where('idRincianAset', $idRincianAset)
            ->where('idRequestAsetPeminjaman', $getIdManajemenAsetPeminjaman)
            ->set('statusSetelahPengembalian', $status)
            ->update();
    }

    public function updateReturnSectionAset($idRincianAset)
    {
        $builder = $this->db->table('tblRincianAset');
        $data = [
            'sectionAset' => "None",
            'idRequestAsetPeminjaman' => null,
        ];
        $builder->where('idRincianAset', $idRincianAset)
            ->set($data)
            ->update();
    }

    function getDataRequest($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblDetailRequestAsetPeminjaman', 'tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman = tblRequestAsetPeminjaman.idRequestAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailRequestAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestAsetPeminjaman.deleted_at', null);
        $builder->where('tblRequestAsetPeminjaman.loanStatus', "Request");
        $builder->orderBy('tblRequestAsetPeminjaman.tanggal', 'asc'); 
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblRequestAsetPeminjaman.tanggal >=', $startDate);
            $builder->where('tblRequestAsetPeminjaman.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataApprove($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblDetailRequestAsetPeminjaman', 'tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman = tblRequestAsetPeminjaman.idRequestAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailRequestAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestAsetPeminjaman.deleted_at', null);
        $builder->where('tblRequestAsetPeminjaman.loanStatus', "Approve");
        $builder->orderBy('tblRequestAsetPeminjaman.tanggal', 'asc'); 
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblRequestAsetPeminjaman.tanggal >=', $startDate);
            $builder->where('tblRequestAsetPeminjaman.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataReject($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblDetailRequestAsetPeminjaman', 'tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman = tblRequestAsetPeminjaman.idRequestAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailRequestAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestAsetPeminjaman.deleted_at', null);
        $builder->where('tblRequestAsetPeminjaman.loanStatus', "Reject");
        $builder->orderBy('tblRequestAsetPeminjaman.tanggal', 'asc'); 
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblRequestAsetPeminjaman.tanggal >=', $startDate);
            $builder->where('tblRequestAsetPeminjaman.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataCancel($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblDetailRequestAsetPeminjaman', 'tblDetailRequestAsetPeminjaman.idRequestAsetPeminjaman = tblRequestAsetPeminjaman.idRequestAsetPeminjaman');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblDetailRequestAsetPeminjaman.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestAsetPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestAsetPeminjaman.deleted_at', null);
        $builder->where('tblRequestAsetPeminjaman.loanStatus', "Cancel");
        $builder->orderBy('tblRequestAsetPeminjaman.tanggal', 'asc'); 
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblRequestAsetPeminjaman.tanggal >=', $startDate);
            $builder->where('tblRequestAsetPeminjaman.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }
}
