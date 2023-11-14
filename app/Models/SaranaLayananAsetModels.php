<?php

namespace App\Models;

use CodeIgniter\Model;

class SaranaLayananAsetModels extends Model
{
    protected $table            = 'tblSaranaLayananAset';
    protected $primaryKey       = 'idSaranaLayananAset';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idSaranaLayananAset', 'idRincianAset', 'idSumberDana','idStatusLayanan', 'biaya', 'bukti','tanggal' , 'keterangan'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table('tblSaranaLayananAset');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblSaranaLayananAset.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblSaranaLayananAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblSaranaLayananAset.idStatusLayanan');
        $builder->where('tblSaranaLayananAset.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function getItAll() {
        $builder = $this->db->table('tblSaranaLayananAset');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblSaranaLayananAset.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblSaranaLayananAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblSaranaLayananAset.idStatusLayanan');
        $builder->where('tblSaranaLayananAset.deleted_at', null);
        $builder->where('tblIdentitasSarana.perangkatIT', 1); 
        $query = $builder->get();
        return $query->getResult();
    }

        
    function getRecycle() {
        $builder = $this->db->table('tblSaranaLayananAset');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblSaranaLayananAset.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblSaranaLayananAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblSaranaLayananAset.idStatusLayanan');
        $builder->where('tblSaranaLayananAset.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    public function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblSaranaLayananAset.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblSaranaLayananAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblSaranaLayananAset.idStatusLayanan');
        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }

    // function getPrasarana() {
    //     $builder = $this->db->table('tblRincianAset');
    //     $builder->select('DISTINCT(tblRincianAset.idIdentitasPrasarana), tblIdentitasPrasarana.namaPrasarana');
    //     $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
    //     $builder->where('tblRincianAset.deleted_at', null);
    //     $builder->groupBy('tblIdentitasPrasarana.idIdentitasPrasarana'); 
    //     $query = $builder->get();
    //     return $query->getResult();
    // }
    
    function getSarana() {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('DISTINCT(tblRincianAset.idIdentitasSarana), tblIdentitasSarana.namaSarana');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.sectionAset', "None");  
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana'); 
        $query = $builder->get();
        return $query->getResult();
    }

    function getKodeRincianAsetBySarana($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('kodeRincianAset');
        $builder->distinct();
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('idIdentitasSarana', $idIdentitasSarana);
        $builder->where('tblRincianAset.sectionAset', 'None');
        $builder->whereIn('tblRincianAset.status', ['Bagus', 'Rusak']);
        $query = $builder->get();
        return $query->getResult();
    }

    function getIdentitasPrasaranaByKodeRincianAset($kodeRincianAset) {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('idIdentitasPrasarana');
        $builder->where('kodeRincianAset', $kodeRincianAset);
        $builder->where('sectionAset', 'None');
        $builder->whereIn('status', ['Bagus', 'Rusak']);
        $query = $builder->get();
        $result = $query->getRow();
        return $result ? $result->idIdentitasPrasarana : null;
    }

    function getNamaPrasaranaById($idIdentitasPrasarana) {
        $builder = $this->db->table('tblIdentitasPrasarana');
        $builder->select('namaPrasarana');
        $builder->where('idIdentitasPrasarana', $idIdentitasPrasarana);
        $query = $builder->get();
        $result = $query->getRow();
        return $result ? $result->namaPrasarana : null;
    }

    function getKategoriManajemenByKodeRincianAset($kodeRincianAset) {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('idKategoriManajemen');
        $builder->where('kodeRincianAset', $kodeRincianAset);
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

    function getIdRincianAsetByKodeRincianAset($kodeRincianAset) {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('idRincianAset');
        $builder->where('kodeRincianAset', $kodeRincianAset);
        $query = $builder->get();
        $result = $query->getRow();
        return $result ? $result->idRincianAset : null;
    }
}
