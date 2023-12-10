<?php

namespace App\Models;

use CodeIgniter\Model;

class PrasaranaRuanganModels extends Model
{
    protected $table            = 'tblIdentitasPrasarana';
    protected $primaryKey       = 'idIdentitasPrasarana';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaPrasarana', 'luas', 'idIdentitasGedung', 'idIdentitasLantai', 'kodePrasarana' ,'tipe'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getRuangan() {
        $builder = $this->db->table($this->table);
        $builder->where('tblIdentitasPrasarana.tipe', 'Ruangan'); 
        $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasPrasarana.idIdentitasGedung');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasPrasarana.idIdentitasLantai');
        $query = $builder->get();
        return $query->getResult();
    }

    public function searchPrasarana($namaPrasarana) {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasPrasarana.idIdentitasGedung');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasPrasarana.idIdentitasLantai');
        $builder->like('tblIdentitasPrasarana.namaPrasarana', $namaPrasarana);
        $builder->where('tblIdentitasPrasarana.deleted_at', null);
        $builder->where('tblIdentitasPrasarana.tipe', 'Ruangan'); 
        $query = $builder->get();
        return $query->getResult();
    }
    

    
    function getIdentitasGedung($idIdentitasPrasarana) {
        $builder = $this->db->table($this->table);
        $builder->select('tblIdentitasGedung.namaGedung');
        $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasPrasarana.idIdentitasGedung');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasPrasarana.idIdentitasLantai');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $query = $builder->get();
    
        return $query->getRow();
    }
    
    function getIdentitasLantai($idIdentitasPrasarana) {
        $builder = $this->db->table($this->table);
        $builder->select('tblIdentitasLantai.namaLantai');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasPrasarana.idIdentitasLantai');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $query = $builder->get();
    
        return $query->getRow();
    }

    function getSaranaByPrasaranaId($idIdentitasPrasarana) {
        $builder = $this->db->table('tblRincianAset');
        $builder->join('tblIdentitasPrasarana', 'tblRincianAset.idIdentitasPrasarana = tblIdentitasPrasarana.idIdentitasPrasarana');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianAset.deleted_at', null); 
        $query = $builder->get();

        return $query->getResult();
    }

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->where('tblIdentitasPrasarana.tipe', 'Ruangan'); 
        $query = $builder->get();
        return $query->getResult();
    }
}
