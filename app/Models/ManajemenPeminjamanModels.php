<?php

namespace App\Models;

use CodeIgniter\Model;

class ManajemenPeminjamanModels extends Model
{
    protected $table            = 'tblManajemenPeminjaman';
    protected $primaryKey       = 'idManajemenPeminjaman';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idManajemenPeminjaman', 'asalPeminjam', 'idIdentitasSarana', 'idIdentitasLab', 'jumlah', 'tanggal', 'loanStatus', 'tanggalPengembalian', 'keperluanAlat', 'lamaPinjam'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    protected $tableRincianLabAset  = 'tblRincianLabAset';


    public function getBorrowItems($idIdentitasSarana, $jumlah, $idIdentitasLab)
    {
        $builder = $this->db->table($this->tableRincianLabAset);
        return $builder->where('idIdentitasSarana', $idIdentitasSarana)
            ->where('idIdentitasLab', $idIdentitasLab)
            ->limit($jumlah)
            ->get()
            ->getResultArray();
    }

    public function updateSectionAsetLAMA($idIdentitasSarana, $sectionAsetValue, $idIdentitasLab, $jumlah, $idManajemenPeminjaman)
    {
        $builder = $this->db->table($this->tableRincianLabAset);
        $data = [
            'sectionAset' => $sectionAsetValue,
            'idManajemenPeminjaman' => $idManajemenPeminjaman,
        ];

        $builder->where('idIdentitasSarana', $idIdentitasSarana)
            ->where('idIdentitasLab', $idIdentitasLab)
            ->limit($jumlah)
            ->set($data)
            ->update();
    }

    public function updateSectionAset($detailData, $sectionAsetValue)
    {
        $builder = $this->db->table($this->tableRincianLabAset);
        $data = [
            'sectionAset' => $sectionAsetValue,
            'idManajemenPeminjaman' => $detailData['idManajemenPeminjaman'],
        ];

        $builder->where('idRincianLabAset', $detailData['idRincianLabAset'])
            ->update($data);
    }


    // Data Peminjaman for return
    public function getBorrowedItems($idIdentitasSarana, $jumlah, $idIdentitasLab)
    {
        $builder = $this->db->table($this->tableRincianLabAset);
        return $builder->where('idIdentitasSarana', $idIdentitasSarana)
            ->where('idIdentitasLab', $idIdentitasLab)
            ->where('sectionAset', "Dipinjam")
            ->limit($jumlah)
            ->get()
            ->getResultArray();
    }

    public function updateReturnStatus($idIdentitasSarana, $newStatus, $count = 1, $idIdentitasLab, $idManajemenPeminjaman)
    {
        $builder = $this->db->table($this->tableRincianLabAset);
        $builder->where('idIdentitasSarana', $idIdentitasSarana)
            ->where('idManajemenPeminjaman', $idManajemenPeminjaman)
            ->where('idIdentitasLab', $idIdentitasLab)
            ->where('sectionAset', "Dipinjam")
            ->set('status', $newStatus)
            ->limit($count)
            ->update();
    }

    public function updateReturnSectionAset($idIdentitasSarana, $sectionAsetValue, $idIdentitasLab, $jumlah, $idManajemenPeminjaman)
    {
        $builder = $this->db->table($this->tableRincianLabAset);
        $data = [
            'sectionAset' => $sectionAsetValue,
            'idManajemenPeminjaman' => null,
        ];

        $builder->where('idIdentitasSarana', $idIdentitasSarana)
            ->where('idManajemenPeminjaman', $idManajemenPeminjaman)
            ->where('idIdentitasLab', $idIdentitasLab)
            ->limit($jumlah)
            ->set($data)
            ->update();
    }

    public function updateReturnSectionAsetRusak($idIdentitasSarana, $sectionAsetValue, $idIdentitasLab, $jumlah, $idManajemenPeminjaman, $status)
    {
        $builder = $this->db->table($this->tableRincianLabAset);
        $data = [
            'sectionAset' => $sectionAsetValue,
            'idManajemenPeminjaman' => null,
        ];

        $builder->where('idIdentitasSarana', $idIdentitasSarana)
            ->where('idManajemenPeminjaman', $idManajemenPeminjaman)
            ->where('idIdentitasLab', $idIdentitasLab)
            ->where('status', $status)
            ->limit($jumlah)
            ->set($data)
            ->update();
    }

    public function updateReturnSectionAsetHilang($idIdentitasSarana, $sectionAsetValue, $idIdentitasLab, $jumlah, $idManajemenPeminjaman, $status)
    {
        $builder = $this->db->table($this->tableRincianLabAset);
        $data = [
            'sectionAset' => $sectionAsetValue,
            'idManajemenPeminjaman' => null,
        ];

        $builder->where('idIdentitasSarana', $idIdentitasSarana)
            ->where('idManajemenPeminjaman', $idManajemenPeminjaman)
            ->where('idIdentitasLab', $idIdentitasLab)
            ->where('status', $status)
            ->limit($jumlah)
            ->set($data)
            ->update();
    }

    function getKodeLabData($idIdentitasSarana)
    {
        $builder = $this->db->table($this->tableRincianLabAset);
        $builder->select('tblRincianLabAset.idIdentitasLab, tblIdentitasLab.namaLab');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.idIdentitasSarana', $idIdentitasSarana);
        $query = $builder->get();
        return $query->getResult();
    }

    function getSaranaLab()
    {
        $builder = $this->db->table('tblIdentitasSarana');
        $builder->distinct();
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idIdentitasSarana = tblIdentitasSarana.idIdentitasSarana');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function getLabBySaranaId($idIdentitasSarana)
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->join('tblIdentitasLab', 'tblRincianLabAset.idIdentitasLab = tblIdentitasLab.idIdentitasLab');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->where('tblRincianLabAset.idIdentitasSarana', $idIdentitasSarana);
        $builder->where('tblRincianLabAset.deleted_at', null);
        $query = $builder->get();

        return $query->getResult();
    }

    function getTotalJumlahBySaranaId($idIdentitasSarana)
    {
        $builder = $this->db->table($this->table);
        $builder->selectSum('jumlah');
        $builder->where('idIdentitasSarana', $idIdentitasSarana);
        $query = $builder->get();
        return $query->getRow()->jumlah;
    }

    function getPrasaranaLab()
    {
        $builder = $this->db->table('tblIdentitasLab');
        $builder->distinct();
        $builder->select('tblIdentitasLab.idIdentitasLab, tblIdentitasLab.namaLab');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idIdentitasLab = tblIdentitasLab.idIdentitasLab');
        $builder->join('tblIdentitasSarana', 'tblRincianLabAset.idIdentitasSarana = tblIdentitasSarana.idIdentitasSarana');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->groupBy('tblIdentitasLab.idIdentitasLab');
        $query = $builder->get();
        return $query->getResult();
    }

    function getAll()
    {
        $builder = $this->db->table($this->table);
        // $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblManajemenPeminjaman.idIdentitasSarana');
        // $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblManajemenPeminjaman.idIdentitasLab');
        $builder->where('tblManajemenPeminjaman.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function find($id = null, $columns = '*')
    {
        $builder = $this->db->table($this->table);
        $builder->select($columns);

        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblManajemenPeminjaman.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblManajemenPeminjaman.idIdentitasLab');

        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }

    public function updateAsetTersedia($idIdentitasSarana, $saranaLayak)
    {
        $builder = $this->db->table('tblRincianLabAset');

        $existingAsetTersedia = $builder->select('saranaLayak')
            ->where('idIdentitasSarana', $idIdentitasSarana)
            ->get()
            ->getRow();

        if ($existingAsetTersedia && $existingAsetTersedia->saranaLayak !== $saranaLayak) {
            $builder->set('saranaLayak', $saranaLayak)
                ->where('idIdentitasSarana', $idIdentitasSarana)
                ->update();
        }
    }

    function getSaranaByLab($idIdentitasLab)
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->distinct();
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana', 'left');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('idIdentitasLab', $idIdentitasLab);
        $builder->where('tblRincianLabAset.sectionAset', 'None');
        $builder->whereIn('tblRincianLabAset.status', ['Bagus']);
        $query = $builder->get();
        return $query->getResult();
    }

    function getKodeBySarana($idIdentitasSarana, $idIdentitasLab)
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('kodeRincianLabAset, idRincianLabAset');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.idIdentitasSarana', $idIdentitasSarana);
        $builder->where('tblRincianLabAset.idIdentitasLab', $idIdentitasLab);
        $builder->where('tblRincianLabAset.sectionAset', 'None');
        $builder->where('tblRincianLabAset.status', ['Bagus']);
        $query = $builder->get();
        return $query->getResult();
    }

    function getSaranaByLabId($idIdentitasLab)
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->join('tblIdentitasLab', 'tblRincianLabAset.idIdentitasLab = tblIdentitasLab.idIdentitasLab');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->where('tblIdentitasLab.idIdentitasLab', $idIdentitasLab);
        $builder->where('tblRincianLabAset.deleted_at', null);
        $query = $builder->get();

        return $query->getResult();
    }

    public function getAllIdIdentitasLab()
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->distinct();
        $builder->select('tblRincianLabAset.idIdentitasLab, tblIdentitasLab.namaLab');
        $builder->join('tblIdentitasLab', 'tblRincianLabAset.idIdentitasLab = tblIdentitasLab.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);

        $query = $builder->get();
        return $query->getResult();
    }

    public function getAllIdIdentitasSarana()
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->distinct();
        $builder->select('tblRincianLabAset.idIdentitasSarana, tblIdentitasSarana.namaSarana');
        $builder->join('tblIdentitasSarana', 'tblRincianLabAset.idIdentitasSarana = tblIdentitasSarana.idIdentitasSarana');
        $builder->where('tblRincianLabAset.deleted_at', null);

        $query = $builder->get();
        return $query->getResult();
    }

    function getData()
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset =', 'None');
        $builder->where('tblRincianLabAset.status =', 'Bagus');
        $builder->orderBy('tblIdentitasSarana.namaSarana', 'asc'); 
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataLoan($idIdentitasLab)
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset =', 'None');
        $builder->where('tblRincianLabAset.status =', 'Bagus');
        $builder->where('tblRincianLabAset.idIdentitasLab =', $idIdentitasLab);
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataLoanGeneral()
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset =', 'None');
        $builder->where('tblRincianLabAset.status =', 'Bagus');
        $query = $builder->get();
        return $query->getResult();
    }

    function getLabName($idIdentitasLab) {
        $builder = $this->db->table('tblIdentitasLab');
        $builder->where('idIdentitasLab', $idIdentitasLab);
        $query = $builder->get();
        return $query->getRow();
    }

    function getNamaSiswa($asalPeminjam) {
        $builder = $this->db->table('tblDataSiswa');
        $builder->where('idDataSiswa', $asalPeminjam);
        $query = $builder->get();
        $result = $query->getRow();
        return $result ? $result->namaSiswa : null;
    }

    function getNamaKelas($asalPeminjam) {
        $builder = $this->db->table('tblDataSiswa');        
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('idDataSiswa', $asalPeminjam);
        $query = $builder->get();
        $result = $query->getRow();
        return $result ? $result->namaKelas : null;
    }
}
