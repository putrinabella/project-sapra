<?php

namespace App\Models;

use CodeIgniter\Model;

class RequestPeminjamanModels extends Model
{
    protected $table            = 'tblRequestPeminjaman';
    protected $primaryKey       = 'idRequestPeminjaman';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idRequestPeminjaman', 'tanggal', 'asalPeminjam', 'keperluanAlat', 'lamaPinjam', 'loanStatus'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    protected $tableRincianLabAset  = 'tblRincianLabAset';


    function getAll($startDate = null, $endDate = null)
    {
        $builder = $this->db->table('tblRequestPeminjaman');
        $builder->select('tblRequestPeminjaman.*, tblIdentitasLab.namaLab,  tblDataSiswa.*, tblIdentitasKelas.namaKelas,  COUNT(tblDetailRequestPeminjaman.idRequestPeminjaman) as jumlahPeminjaman');
        $builder->select('(SUM(CASE WHEN tblDetailRequestPeminjaman.requestItemStatus = "Approve" THEN 1 ELSE 0 END)) AS jumlahApprove', false);
        $builder->join('tblDetailRequestPeminjaman', 'tblDetailRequestPeminjaman.idRequestPeminjaman = tblRequestPeminjaman.idRequestPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailRequestPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestPeminjaman.deleted_at', null);
        $builder->orderBy('tblRequestPeminjaman.loanStatus', 'desc'); 
        $builder->orderBy('tblRequestPeminjaman.tanggal', 'asc'); 

        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblRequestPeminjaman.tanggal >=', $startDate);
            $builder->where('tblRequestPeminjaman.tanggal <=', $endDate);
        }
    
        $builder->groupBy('tblRequestPeminjaman.idRequestPeminjaman');
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataRequestUser($startDate = null, $endDate = null, $idUser)
    {
        $builder = $this->db->table('tblRequestPeminjaman');
        $builder->select('tblRequestPeminjaman.*, tblIdentitasLab.namaLab,  tblDataSiswa.*, tblIdentitasKelas.namaKelas,  COUNT(tblDetailRequestPeminjaman.idRequestPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailRequestPeminjaman', 'tblDetailRequestPeminjaman.idRequestPeminjaman = tblRequestPeminjaman.idRequestPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailRequestPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestPeminjaman.loanStatus !=', 'Approve');
        $builder->where('tblRequestPeminjaman.deleted_at', null);
    
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblRequestPeminjaman.tanggal >=', $startDate);
            $builder->where('tblRequestPeminjaman.tanggal <=', $endDate);
        }
    
        $builder->where('tblRequestPeminjaman.asalPeminjam', $idUser);
        $builder->groupBy('tblRequestPeminjaman.idRequestPeminjaman');
        $query = $builder->get();
        return $query->getResult();
    }

    
    function getBorrowItems($idRequestPeminjaman)
    {
        $builder = $this->db->table('tblDetailRequestPeminjaman');
        $builder->select('*');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailRequestPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblRequestPeminjaman', 'tblRequestPeminjaman.idRequestPeminjaman = tblDetailRequestPeminjaman.idRequestPeminjaman');
        $builder->where('tblDetailRequestPeminjaman.idRequestPeminjaman', $idRequestPeminjaman);
        $query = $builder->get();
        return $query->getResult();
    }

    public function updateRequestPeminjaman($idRequestPeminjaman, $requestStatus) {
        $builder = $this->db->table('tblRequestPeminjaman');
        $data = [
            'loanStatus' => $requestStatus,
        ];

        $builder->where('idRequestPeminjaman', $idRequestPeminjaman)
                ->update($data);
    }


    public function approveDetailRequestPeminjaman($idRequestPeminjaman, $requestStatus, $idRincianLabAset) {
        $builder = $this->db->table('tblDetailRequestPeminjaman');
        $data = [
            'requestItemStatus' => $requestStatus,
        ];
    
        $builder->where('idRequestPeminjaman', $idRequestPeminjaman)
                ->whereIn('idRincianLabAset', $idRincianLabAset)
                ->update($data);
    }
    
    public function rejectDetailRequestPeminjaman($idRequestPeminjaman) {
        $builder = $this->db->table('tblDetailRequestPeminjaman');
        $builder->where('idRequestPeminjaman', $idRequestPeminjaman)
                ->where('requestItemStatus !=', 'Approve');
    
        $rowsToUpdate = $builder->get()->getResult();
    
        foreach ($rowsToUpdate as $data) {
            $updateBuilder = $this->db->table('tblDetailRequestPeminjaman');
            $data = [
                'requestItemStatus' => 'Reject',
            ];
            $builder->where('idRequestPeminjaman', $idRequestPeminjaman)
                    ->update($data);
        }
    }
    
    function find($id = null, $columns = '*')
    {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        $builder->select('COUNT(tblDetailRequestPeminjaman.idRequestPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailRequestPeminjaman', 'tblDetailRequestPeminjaman.idRequestPeminjaman = tblRequestPeminjaman.idRequestPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailRequestPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestPeminjaman.' . $this->primaryKey, $id);
        $query = $builder->get();
        return $query->getRow();
    }

    function getDataSiswa($startDate = null, $endDate = null, $idUser)
    {
        $builder = $this->db->table('tblRequestPeminjaman');
        $builder->select('tblRequestPeminjaman.*, tblIdentitasLab.namaLab,  tblDataSiswa.*, tblIdentitasKelas.namaKelas,  COUNT(tblDetailRequestPeminjaman.idRequestPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailRequestPeminjaman', 'tblDetailRequestPeminjaman.idRequestPeminjaman = tblRequestPeminjaman.idRequestPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailRequestPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestPeminjaman.deleted_at', null);
    
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblRequestPeminjaman.tanggal >=', $startDate);
            $builder->where('tblRequestPeminjaman.tanggal <=', $endDate);
        }
    
        $builder->where('tblRequestPeminjaman.loanStatus', 'Peminjaman');
        $builder->where('tblRequestPeminjaman.asalPeminjam', $idUser);
        $builder->groupBy('tblRequestPeminjaman.idRequestPeminjaman');
        $query = $builder->get();
    
        return $query->getResult();
    }
    
    

    function findHistory($id = null, $columns = '*')
    {
        $builder = $this->db->table('tblDetailRequestPeminjaman');
        $builder->select($columns);
        $builder->select('tblRequestPeminjaman.*, tblIdentitasLab.namaLab,  tblDataSiswa.namaSiswa, tblIdentitasKelas.namaKelas,  COUNT(tblDetailRequestPeminjaman.idRequestPeminjaman) as jumlahPeminjaman');
        $builder->join('tblRequestPeminjaman', 'tblDetailRequestPeminjaman.idRequestPeminjaman = tblRequestPeminjaman.idRequestPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailRequestPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        
        $builder->where('tblDetailRequestPeminjaman.idRequestPeminjaman', $id);
        $builder->groupBy('tblDetailRequestPeminjaman.idRequestPeminjaman');
        $query = $builder->get();
        return $query->getRow();
    }

    function findAllHistory($startDate = null, $endDate = null, $columns = '*') { 
        $builder = $this->db->table('tblDetailRequestPeminjaman');
        $builder->select($columns);
        $builder->select('tblRequestPeminjaman.*, tblIdentitasLab.namaLab,  tblDataSiswa.namaSiswa, tblIdentitasKelas.namaKelas,  COUNT(tblDetailRequestPeminjaman.idRequestPeminjaman) as jumlahPeminjaman');
        $builder->join('tblRequestPeminjaman', 'tblDetailRequestPeminjaman.idRequestPeminjaman = tblRequestPeminjaman.idRequestPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailRequestPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestPeminjaman.loanStatus =', 'Pengembalian');
        $builder->groupBy('tblDetailRequestPeminjaman.idRequestPeminjaman');
        if ($startDate && $endDate) {
            $builder->where('tblRequestPeminjaman.tanggal >=', $startDate);
            $builder->where('tblRequestPeminjaman.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }

    

    public function getRincianLabAset($idRequestPeminjaman)
    {
        $builder = $this->db->table('tblDetailRequestPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailRequestPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->where('tblDetailRequestPeminjaman.idRequestPeminjaman', $idRequestPeminjaman);
        $query = $builder->get();

        return $query->getResult();
    }

    public function getRincianItem($idRequestPeminjaman)
    {
        $builder = $this->db->table('tblDetailRequestPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailRequestPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->select('tblIdentitasSarana.*, COUNT(tblIdentitasSarana.idIdentitasSarana) as totalAset');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->where('tblDetailRequestPeminjaman.idRequestPeminjaman', $idRequestPeminjaman);
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana');
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
        $builder = $this->db->table('tblDetailRequestPeminjaman');
        $builder->where('idRincianLabAset', $idRincianLabAset)
            ->where('idRequestPeminjaman', $getIdManajemenPeminjaman)
            ->set('statusSetelahPengembalian', $status)
            ->update();
    }

    public function updateReturnSectionAset($idRincianLabAset)
    {
        $builder = $this->db->table('tblRincianLabAset');
        $data = [
            'sectionAset' => "None",
            'idRequestPeminjaman' => null,
        ];
        $builder->where('idRincianLabAset', $idRincianLabAset)
            ->set($data)
            ->update();
    }

    function getDataRequest($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblDetailRequestPeminjaman', 'tblDetailRequestPeminjaman.idRequestPeminjaman = tblRequestPeminjaman.idRequestPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailRequestPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestPeminjaman.deleted_at', null);
        $builder->where('tblRequestPeminjaman.loanStatus', "Request");
        $builder->orderBy('tblRequestPeminjaman.tanggal', 'asc'); 
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblRequestPeminjaman.tanggal >=', $startDate);
            $builder->where('tblRequestPeminjaman.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataApprove($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblDetailRequestPeminjaman', 'tblDetailRequestPeminjaman.idRequestPeminjaman = tblRequestPeminjaman.idRequestPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailRequestPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestPeminjaman.deleted_at', null);
        $builder->where('tblRequestPeminjaman.loanStatus', "Approve");
        $builder->orderBy('tblRequestPeminjaman.tanggal', 'asc'); 
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblRequestPeminjaman.tanggal >=', $startDate);
            $builder->where('tblRequestPeminjaman.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataReject($startDate = null, $endDate = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('tblDetailRequestPeminjaman', 'tblDetailRequestPeminjaman.idRequestPeminjaman = tblRequestPeminjaman.idRequestPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailRequestPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblRequestPeminjaman.asalPeminjam');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblRequestPeminjaman.deleted_at', null);
        $builder->where('tblRequestPeminjaman.loanStatus', "Reject");
        $builder->orderBy('tblRequestPeminjaman.tanggal', 'asc'); 
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblRequestPeminjaman.tanggal >=', $startDate);
            $builder->where('tblRequestPeminjaman.tanggal <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }
}
