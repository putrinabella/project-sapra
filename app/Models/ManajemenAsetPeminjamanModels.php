<?php

namespace App\Models;

use CodeIgniter\Model;

class ManajemenAsetPeminjamanModels extends Model
{
    protected $table            = 'tblManajemenAsetPeminjaman';
    protected $primaryKey       = 'idManajemenAsetPeminjaman';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idManajemenAsetPeminjaman', 'asalPeminjam', 'idIdentitasSarana', 'idIdentitasPrasarana', 'jumlah', 'tanggal', 'loanStatus', 'tanggalPengembalian', 'keperluanAlat', 'lamaPinjam'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    protected $tableRincianAset  = 'tblRincianAset';


    public function getBorrowItems($idIdentitasSarana, $jumlah, $idIdentitasPrasarana)
    {
        $builder = $this->db->table($this->tableRincianAset);
        return $builder->where('idIdentitasSarana', $idIdentitasSarana)
            ->where('idIdentitasPrasarana', $idIdentitasPrasarana)
            ->limit($jumlah)
            ->get()
            ->getResultArray();
    }

    public function updateSectionAset($detailData, $sectionAsetValue)
    {
        $builder = $this->db->table($this->tableRincianAset);
        $data = [
            'sectionAset' => $sectionAsetValue,
            'idManajemenAsetPeminjaman' => $detailData['idManajemenAsetPeminjaman'],
        ];

        $builder->where('idRincianAset', $detailData['idRincianAset'])
            ->update($data);
    }


    // Data Peminjaman for return
    public function getBorrowedItems($idIdentitasSarana, $jumlah, $idIdentitasPrasarana)
    {
        $builder = $this->db->table($this->tableRincianAset);
        return $builder->where('idIdentitasSarana', $idIdentitasSarana)
            ->where('idIdentitasPrasarana', $idIdentitasPrasarana)
            ->where('sectionAset', "Dipinjam")
            ->limit($jumlah)
            ->get()
            ->getResultArray();
    }

    public function updateReturnStatus($idIdentitasSarana, $newStatus, $count = 1, $idIdentitasPrasarana, $idManajemenAsetPeminjaman)
    {
        $builder = $this->db->table($this->tableRincianAset);
        $builder->where('idIdentitasSarana', $idIdentitasSarana)
            ->where('idManajemenAsetPeminjaman', $idManajemenAsetPeminjaman)
            ->where('idIdentitasPrasarana', $idIdentitasPrasarana)
            ->where('sectionAset', "Dipinjam")
            ->set('status', $newStatus)
            ->limit($count)
            ->update();
    }

    public function updateReturnSectionAset($idIdentitasSarana, $sectionAsetValue, $idIdentitasPrasarana, $jumlah, $idManajemenAsetPeminjaman)
    {
        $builder = $this->db->table($this->tableRincianAset);
        $data = [
            'sectionAset' => $sectionAsetValue,
            'idManajemenAsetPeminjaman' => null,
        ];

        $builder->where('idIdentitasSarana', $idIdentitasSarana)
            ->where('idManajemenAsetPeminjaman', $idManajemenAsetPeminjaman)
            ->where('idIdentitasPrasarana', $idIdentitasPrasarana)
            ->limit($jumlah)
            ->set($data)
            ->update();
    }

    public function updateReturnSectionAsetRusak($idIdentitasSarana, $sectionAsetValue, $idIdentitasPrasarana, $jumlah, $idManajemenAsetPeminjaman, $status)
    {
        $builder = $this->db->table($this->tableRincianAset);
        $data = [
            'sectionAset' => $sectionAsetValue,
            'idManajemenAsetPeminjaman' => null,
        ];

        $builder->where('idIdentitasSarana', $idIdentitasSarana)
            ->where('idManajemenAsetPeminjaman', $idManajemenAsetPeminjaman)
            ->where('idIdentitasPrasarana', $idIdentitasPrasarana)
            ->where('status', $status)
            ->limit($jumlah)
            ->set($data)
            ->update();
    }

    public function updateReturnSectionAsetHilang($idIdentitasSarana, $sectionAsetValue, $idIdentitasPrasarana, $jumlah, $idManajemenAsetPeminjaman, $status)
    {
        $builder = $this->db->table($this->tableRincianAset);
        $data = [
            'sectionAset' => $sectionAsetValue,
            'idManajemenAsetPeminjaman' => null,
        ];

        $builder->where('idIdentitasSarana', $idIdentitasSarana)
            ->where('idManajemenAsetPeminjaman', $idManajemenAsetPeminjaman)
            ->where('idIdentitasPrasarana', $idIdentitasPrasarana)
            ->where('status', $status)
            ->limit($jumlah)
            ->set($data)
            ->update();
    }

    function getKodePrasaranaData($idIdentitasSarana)
    {
        $builder = $this->db->table($this->tableRincianAset);
        $builder->select('tblRincianAset.idIdentitasPrasarana, tblIdentitasPrasarana.namaPrasarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->where('tblRincianAset.idIdentitasSarana', $idIdentitasSarana);
        $query = $builder->get();
        return $query->getResult();
    }

    function getSaranaPrasarana()
    {
        $builder = $this->db->table('tblIdentitasSarana');
        $builder->distinct();
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana');
        $builder->join('tblRincianAset', 'tblRincianAset.idIdentitasSarana = tblIdentitasSarana.idIdentitasSarana');
        $builder->where('tblRincianAset.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function getPrasaranaBySaranaId($idIdentitasSarana)
    {
        $builder = $this->db->table('tblRincianAset');
        $builder->join('tblIdentitasPrasarana', 'tblRincianAset.idIdentitasPrasarana = tblIdentitasPrasarana.idIdentitasPrasarana');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->where('tblRincianAset.idIdentitasSarana', $idIdentitasSarana);
        $builder->where('tblRincianAset.deleted_at', null);
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

    function getPrasaranaPrasarana()
    {
        $builder = $this->db->table('tblIdentitasPrasarana');
        $builder->distinct();
        $builder->select('tblIdentitasPrasarana.idIdentitasPrasarana, tblIdentitasPrasarana.namaPrasarana');
        $builder->join('tblRincianAset', 'tblRincianAset.idIdentitasPrasarana = tblIdentitasPrasarana.idIdentitasPrasarana');
        $builder->join('tblIdentitasSarana', 'tblRincianAset.idIdentitasSarana = tblIdentitasSarana.idIdentitasSarana');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->groupBy('tblIdentitasPrasarana.idIdentitasPrasarana');
        $query = $builder->get();
        return $query->getResult();
    }

    function getAll()
    {
        $builder = $this->db->table($this->table);
        // $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblManajemenAsetPeminjaman.idIdentitasSarana');
        // $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblManajemenAsetPeminjaman.idIdentitasPrasarana');
        $builder->where('tblManajemenAsetPeminjaman.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function find($id = null, $columns = '*')
    {
        $builder = $this->db->table($this->table);
        $builder->select($columns);

        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblManajemenAsetPeminjaman.idIdentitasSarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblManajemenAsetPeminjaman.idIdentitasPrasarana');

        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }

    public function updateAsetTersedia($idIdentitasSarana, $saranaLayak)
    {
        $builder = $this->db->table('tblRincianAset');

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

    function getSaranaByPrasarana($idIdentitasPrasarana)
    {
        $builder = $this->db->table('tblRincianAset');
        $builder->distinct();
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana', 'left');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianAset.sectionAset', 'None');
        $builder->whereIn('tblRincianAset.status', ['Bagus']);
        $query = $builder->get();
        return $query->getResult();
    }

    function getKodeBySarana($idIdentitasSarana, $idIdentitasPrasarana)
    {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('kodeRincianAset, idRincianAset');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.idIdentitasSarana', $idIdentitasSarana);
        $builder->where('tblRincianAset.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianAset.sectionAset', 'None');
        $builder->where('tblRincianAset.status', ['Bagus']);
        $query = $builder->get();
        return $query->getResult();
    }

    function getSaranaByPrasaranaId($idIdentitasPrasarana)
    {
        $builder = $this->db->table('tblRincianAset');
        $builder->join('tblIdentitasPrasarana', 'tblRincianAset.idIdentitasPrasarana = tblIdentitasPrasarana.idIdentitasPrasarana');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianAset.deleted_at', null);
        $query = $builder->get();

        return $query->getResult();
    }

    public function getAllIdIdentitasPrasarana()
    {
        $builder = $this->db->table('tblRincianAset');
        $builder->distinct();
        $builder->select('tblRincianAset.idIdentitasPrasarana, tblIdentitasPrasarana.namaPrasarana');
        $builder->join('tblIdentitasPrasarana', 'tblRincianAset.idIdentitasPrasarana = tblIdentitasPrasarana.idIdentitasPrasarana');
        $builder->where('tblRincianAset.deleted_at', null);

        $query = $builder->get();
        return $query->getResult();
    }

    public function getAllIdIdentitasSarana()
    {
        $builder = $this->db->table('tblRincianAset');
        $builder->distinct();
        $builder->select('tblRincianAset.idIdentitasSarana, tblIdentitasSarana.namaSarana');
        $builder->join('tblIdentitasSarana', 'tblRincianAset.idIdentitasSarana = tblIdentitasSarana.idIdentitasSarana');
        $builder->where('tblRincianAset.deleted_at', null);

        $query = $builder->get();
        return $query->getResult();
    }

    function getData()
    {
        $builder = $this->db->table('tblRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.sectionAset =', 'None');
        $builder->where('tblRincianAset.status =', 'Bagus');
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataLoan($idIdentitasPrasarana)
    {
        $builder = $this->db->table('tblRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.sectionAset =', 'None');
        $builder->where('tblRincianAset.status =', 'Bagus');
        $builder->where('tblRincianAset.idIdentitasPrasarana =', $idIdentitasPrasarana);
        $query = $builder->get();
        return $query->getResult();
    }

    function getPrasaranaName($idIdentitasPrasarana) {
        $builder = $this->db->table('tblIdentitasPrasarana');
        $builder->where('idIdentitasPrasarana', $idIdentitasPrasarana);
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
