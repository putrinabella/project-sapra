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
        $builder->select('tblManajemenPeminjaman.*, tblIdentitasLab.namaLab, COUNT(tblRincianLabAset.idManajemenPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblManajemenPeminjaman.deleted_at', null);
    
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblManajemenPeminjaman.tanggal >=', $startDate);
            $builder->where('tblManajemenPeminjaman.tanggal <=', $endDate);
        }
    
        $builder->groupBy('tblManajemenPeminjaman.idManajemenPeminjaman');
        $query = $builder->get();
        return $query->getResult();
    }
    

    function findHistory($id = null, $columns = '*')
    {
        $builder = $this->db->table('tblDetailManajemenPeminjaman');
        $builder->select($columns);
        $builder->select('COUNT(tblDetailManajemenPeminjaman.idManajemenPeminjaman) as jumlahPeminjaman');
        $builder->join('tblManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblDetailManajemenPeminjaman.idManajemenPeminjaman', $id);
        $builder->groupBy('tblDetailManajemenPeminjaman.idManajemenPeminjaman');
        $query = $builder->get();
        return $query->getRow();
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
        $builder = $this->db->table($this->table);
        $builder->select('tblManajemenPeminjaman.*, tblIdentitasLab.namaLab, COUNT(tblRincianLabAset.idManajemenPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblManajemenPeminjaman.deleted_at', null);
        $builder->where('tblManajemenPeminjaman.loanStatus', "Pengembalian");

        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblManajemenPeminjaman.tanggal >=', $startDate);
            $builder->where('tblManajemenPeminjaman.tanggal <=', $endDate);
        }
        $builder->groupBy('tblManajemenPeminjaman.idManajemenPeminjaman');
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
        $builder->select('tblManajemenPeminjaman.*, tblDetailManajemenPeminjaman.*, tblRincianLabAset.*, tblIdentitasSarana.*, tblIdentitasLab.*');
        $builder->select('COUNT(tblRincianLabAset.idManajemenPeminjaman) as jumlahPeminjaman');
        $builder->join('tblDetailManajemenPeminjaman', 'tblDetailManajemenPeminjaman.idManajemenPeminjaman = tblManajemenPeminjaman.idManajemenPeminjaman');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblDetailManajemenPeminjaman.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
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
