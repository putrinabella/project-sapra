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


    public function updateSectionAset($detailData, $sectionAsetValue)
    {
        $builder = $this->db->table('tblRincianAset');
        $data = [
            'sectionAset' => $sectionAsetValue,
            'idManajemenAsetPeminjaman' => $detailData['idManajemenAsetPeminjaman'],
        ];

        $builder->where('idRincianAset', $detailData['idRincianAset'])
            ->update($data);
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
        $builder->where('tblManajemenAsetPeminjaman.deleted_at', null);
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
        $builder->orderBy('tblIdentitasSarana.namaSarana', 'asc'); 
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
