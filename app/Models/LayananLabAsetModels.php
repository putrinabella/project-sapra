<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananLabAsetModels extends Model
{
    protected $table            = 'tblLayananLabAset';
    protected $primaryKey       = 'idLayananLabAset';
    protected $returnType       = 'object';
    protected $allowedFields    = ['ididLayananLabAset', 'idRincianLabAset', 'idSumberDana','idStatusLayanan', 'biaya', 'bukti','tanggal' , 'keterangan'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table('tblLayananLabAset');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblLayananLabAset.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblLayananLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblLayananLabAset.idStatusLayanan');
        $builder->where('tblLayananLabAset.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

        
    function getRecycle() {
        $builder = $this->db->table('tblLayananLabAset');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblLayananLabAset.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblLayananLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblLayananLabAset.idStatusLayanan');
        $builder->where('tblLayananLabAset.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    public function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idRincianLabAset = tblLayananLabAset.idRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblLayananLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblLayananLabAset.idStatusLayanan');
        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }

    function getSarana() {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('DISTINCT(tblRincianLabAset.idIdentitasSarana), tblIdentitasSarana.namaSarana');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset', "None"); 
        $builder->where('tblRincianLabAset.status', "Bagus");   
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana'); 
        $query = $builder->get();
        return $query->getResult();
    }

    function getKodeRincianLabAsetBySarana($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('kodeRincianLabAset');
        $builder->distinct();
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('idIdentitasSarana', $idIdentitasSarana);
        $builder->where('tblRincianLabAset.sectionAset', 'None');
        $builder->whereIn('tblRincianLabAset.status', ['Bagus', 'Rusak']);
        $query = $builder->get();
        return $query->getResult();
    }

    function getIdentitasLabByKodeRincianLabAset($kodeRincianLabAset) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('idIdentitasLab');
        $builder->where('kodeRincianLabAset', $kodeRincianLabAset);
        $builder->where('sectionAset', 'None');
        $builder->whereIn('status', ['Bagus', 'Rusak']);
        $query = $builder->get();
        $result = $query->getRow();
        return $result ? $result->idIdentitasLab : null;
    }

    function getNamaLabById($idIdentitasLab) {
        $builder = $this->db->table('tblIdentitasLab');
        $builder->select('namaLab');
        $builder->where('idIdentitasLab', $idIdentitasLab);
        $query = $builder->get();
        $result = $query->getRow();
        return $result ? $result->namaLab : null;
    }

    function getKategoriManajemenByKodeRincianLabAset($kodeRincianLabAset) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('idKategoriManajemen');
        $builder->where('kodeRincianLabAset', $kodeRincianLabAset);
        $builder->where('sectionAset', 'None');
        $builder->whereIn('status', ['Bagus', 'Rusak']);
        $query = $builder->get();
        $result = $query->getRow();
        return $result ? $result->idKategoriManajemen : null;
    }

    public function getNamaKategoriManajemenById($idKategoriManajemen) {
        $builder = $this->db->table('tblKategoriManajemen');
        $builder->select('namaKategoriManajemen');
        $builder->where('idKategoriManajemen', $idKategoriManajemen);
        $query = $builder->get();
        $result = $query->getRow();

        return $result ? $result->namaKategoriManajemen : null;
    }

    function getIdRincianLabAsetByKodeRincianLabAset($kodeRincianLabAset) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('idRincianLabAset');
        $builder->where('kodeRincianLabAset', $kodeRincianLabAset);
        $query = $builder->get();
        $result = $query->getRow();
        return $result ? $result->idRincianLabAset : null;
    }
    // function getAll() {
    //     $builder = $this->db->table('tblLayananLabAset');
    //     $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblLayananLabAset.idIdentitasSarana');
    //     $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblLayananLabAset.idSumberDana');
    //     $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblLayananLabAset.idKategoriManajemen');
    //     $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblLayananLabAset.idIdentitasLab');
    //     $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblLayananLabAset.idStatusLayanan');
    //     $builder->where('tblLayananLabAset.deleted_at', null);
    //     $query = $builder->get();
    //     return $query->getResult();
    // }
    // function getRecycle() {
    //     $builder = $this->db->table('tblLayananLabAset');
    //     $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblLayananLabAset.idIdentitasSarana');
    //     $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblLayananLabAset.idSumberDana');
    //     $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblLayananLabAset.idKategoriManajemen');
    //     $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblLayananLabAset.idIdentitasLab');
    //     $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblLayananLabAset.idStatusLayanan');
    //     $builder->where('tblLayananLabAset.deleted_at IS NOT NULL');
    //     $query = $builder->get();
    //     return $query->getResult();
    // }

    // public function find($id = null, $columns = '*') {
    //     $builder = $this->db->table($this->table);
    //     $builder->select($columns);
        
    //     $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblLayananLabAset.idIdentitasSarana');
    //     $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblLayananLabAset.idSumberDana');
    //     $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblLayananLabAset.idKategoriManajemen');
    //     $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblLayananLabAset.idIdentitasLab');
    //     $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblLayananLabAset.idStatusLayanan');
        
    //     $builder->where($this->primaryKey, $id);

    //     $query = $builder->get();
    //     return $query->getRow();
    // }
}
