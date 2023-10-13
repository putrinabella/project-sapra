<?php

namespace App\Models;

use CodeIgniter\Model;

class ManajemenPeminjamanModels extends Model
{
    protected $table            = 'tblManajemenPeminjaman';
    protected $primaryKey       = 'idManajemenPeminjaman';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idManajemenPeminjaman', 'namaPeminjam', 'asalPeminjam', 'idIdentitasSarana', 'kodeLab', 'jumlah'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;


    protected $tableRincianLabAset            = 'tblRincianLabAset';

    function getKodeLabData($idIdentitasSarana){
        $builder = $this->db->table($this->tableRincianLabAset);
        $builder->select('tblRincianLabAset.kodeLab, tblIdentitasLab.namaLab');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.kodeLab = tblRincianLabAset.kodeLab');
        $builder->where('tblRincianLabAset.idIdentitasSarana', $idIdentitasSarana);
        $query = $builder->get();
        return $query->getResult();
    }
    

    function getSaranaLab(){
        $builder = $this->db->table('tblIdentitasSarana');
        $builder->distinct();
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.idIdentitasSarana = tblIdentitasSarana.idIdentitasSarana');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }


    function getLabBySaranaId($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->join('tblIdentitasLab', 'tblRincianLabAset.kodeLab = tblIdentitasLab.kodeLab');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->where('tblRincianLabAset.idIdentitasSarana', $idIdentitasSarana);
        $builder->where('tblRincianLabAset.deleted_at', null); 
        $query = $builder->get();
    
        return $query->getResult();
    }

    function getPrasaranaLab() {
        $builder = $this->db->table('tblIdentitasLab');
        $builder->distinct();
        $builder->select('tblIdentitasLab.kodeLab, tblIdentitasLab.namaLab');
        $builder->join('tblRincianLabAset', 'tblRincianLabAset.kodeLab = tblIdentitasLab.kodeLab');
        $builder->join('tblIdentitasSarana', 'tblRincianLabAset.idIdentitasSarana = tblIdentitasSarana.idIdentitasSarana');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->groupBy('tblIdentitasLab.kodeLab'); 
        $query = $builder->get();
        return $query->getResult();
    }

 

    // function getPrasaranaLab(){
    //     $builder = $this->db->table('tblIdentitasLab');
    //     $builder->distinct();
    //     $builder->select('tblIdentitasLab.kodeLab, tblIdentitasLab.namaLab');
    //     $builder->join('tblRincianLabAset', 'tblRincianLabAset.kodeLab = tblIdentitasLab.kodeLab');
    //     $builder->where('tblRincianLabAset.deleted_at', null);
    //     $query = $builder->get();
    //     return $query->getResult();
    // }

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblManajemenPeminjaman.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.kodeLab = tblManajemenPeminjaman.kodeLab');
        $builder->where('tblManajemenPeminjaman.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblManajemenPeminjaman.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblManajemenPeminjaman.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblManajemenPeminjaman.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.kodeLab = tblManajemenPeminjaman.kodeLab');
        $builder->where('tblManajemenPeminjaman.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblManajemenPeminjaman.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblManajemenPeminjaman.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblManajemenPeminjaman.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.kodeLab = tblManajemenPeminjaman.kodeLab');
        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }

    function updateKodeLabAset($id) {
        $builder = $this->db->table($this->table);
        $builder->set('kodeRincianLabAset', 
                        'CONCAT("A", LPAD(idIdentitasSarana, 3, "0"), 
                        "/", tahunPengadaan, 
                        "/", "SD", LPAD(idSumberDana, 2, "0"), 
                        "/", kodeLab)',
                        false
                        );
        $builder->where('idManajemenPeminjaman', $id);
        $builder->update();
    }

    function setKodeLabAset() {
        $builder = $this->db->table($this->table);
        $builder->set('kodeRincianLabAset', 
                        'CONCAT("A", LPAD(idIdentitasSarana, 3, "0"), 
                        "/", tahunPengadaan, 
                        "/", "SD", LPAD(idSumberDana, 2, "0"), 
                        "/", kodeLab)',
                        false
                        );
        $builder->update();
    }

    function calculateTotalSarana($saranaLayak, $saranaRusak) {
        $saranaLayak = intval($saranaLayak);
        $saranaRusak = intval($saranaRusak);
        $totalSarana = $saranaLayak + $saranaRusak;
        return $totalSarana;
    }
}
