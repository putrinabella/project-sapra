<?php

namespace App\Models;

use CodeIgniter\Model;

class SaranaLayananAsetModels extends Model
{
    protected $table            = 'tblSaranaLayananAset';
    protected $primaryKey       = 'idSaranaLayananAset';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idSaranaLayananAset', 'idIdentitasSarana', 'idSumberDana', 'idKategoriManajemen', 'idIdentitasPrasarana', 'idStatusLayanan', 'biaya', 'bukti','tanggal'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    public function getIdKategoriManajemenByPrasaranaId($idIdentitasPrasarana)
    {
        $builder = $this->db->table('tblRincianAset');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.idIdentitasPrasarana', $idIdentitasPrasarana);

        $query = $builder->get();
        $result = $query->getRow();

        return $result;
    }


    public function getKategoriManajemenOptions($idIdentitasPrasarana)
    {
        $builder = $this->db->table('tblRincianAset');
        return $this->select('idKategoriManajemen, name') // Replace 'idKategoriManajemen' and 'name' with your actual column names
            ->where('idIdentitasPrasarana', $idIdentitasPrasarana)
            ->distinct()
            ->get()
            ->getResultArray();
    }

    function getKategoriManajemen($idIdentitasPrasarana) {
        $builder = $this->db->table('tblRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblRincianAset.idStatusLayanan');
        $builder->where('tblRincianAset.deleted_at', null);
        
        $builder->where('tblRincianAset.idIdentitasPrasarana', $idIdentitasPrasarana);
        
        $query = $builder->get();
        return $query->getResult();
    }


    function getAll() {
        $builder = $this->db->table('tblSaranaLayananAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblSaranaLayananAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblSaranaLayananAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblSaranaLayananAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblSaranaLayananAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblSaranaLayananAset.idStatusLayanan');
        $builder->where('tblSaranaLayananAset.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function getPrasarana() {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('DISTINCT(tblRincianAset.idIdentitasPrasarana), tblIdentitasPrasarana.namaPrasarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->groupBy('tblIdentitasPrasarana.idIdentitasPrasarana'); 
        $query = $builder->get();
        return $query->getResult();
    }
    
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

    function getKodeRincianAset() {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('DISTINCT(tblRincianAset.kodeRincianAset), tblIdentitasSarana.namaSarana');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.sectionAset', "None");
        $builder->whereIn('tblRincianAset.status', ['Bagus', 'Rusak']);
        $builder->groupBy('tblRincianAset.kodeRincianAset'); 
        $query = $builder->get();
        return $query->getResult();
    }

    public function getKodeRincianAsetBySarana($idIdentitasSarana) {
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
    
    function getRecycle() {
        $builder = $this->db->table('tblSaranaLayananAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblSaranaLayananAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblSaranaLayananAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblSaranaLayananAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblSaranaLayananAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblSaranaLayananAset.idStatusLayanan');
        $builder->where('tblSaranaLayananAset.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    public function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblSaranaLayananAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblSaranaLayananAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblSaranaLayananAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblSaranaLayananAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblSaranaLayananAset.idStatusLayanan');
        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }
}
