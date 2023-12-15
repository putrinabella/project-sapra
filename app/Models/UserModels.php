<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModels extends Model
{
    protected $table            = 'tblUser';
    protected $primaryKey       = 'idUser';
    protected $returnType       = 'object';
    protected $allowedFields    = ['username', 'password', 'nama', 'role'];
    // protected $useTimestamps    = true;
    // protected $useSoftDeletes   = true;

    public function getIdByUsername($username) {
        $builder = $this->db->table($this->table);
        $builder->select('idUser'); 
        $builder->where('username', $username);
        $userData = $builder->get()->getRowArray();
    
        return $userData ? $userData['idUser'] : null;
    }

    public function getProfileUser($username) {
        $builder = $this->db->table('tblUser');
        $builder->join('tblDataSiswa', 'tblDataSiswa.nis = tblUser.username ');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');
        $builder->where('tblDataSiswa.nis', $username);
        $query = $builder->get();
        return $query->getRow();
    }

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblManajemenPeminjaman.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.kodeLab = tblManajemenPeminjaman.kodeLab');
        $builder->where('tblManajemenPeminjaman.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function findUserByUsername($username, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);        
        $builder->where('username', $username);
    
        $query = $builder->get();
        return $query->getRow();
    }

    
    function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);        
        $builder->where('idUser', $id);

        $query = $builder->get();
        return $query->getRow();
    }

    function getPerangkatIT() {
        $builder = $this->db->table('tblIdentitasSarana');
        $builder->where('tblIdentitasSarana.perangkatIT', 1); 
        $query = $builder->get();
        return $query->getResult();
    }
    
    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblManajemenPeminjaman.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.kodeLab = tblManajemenPeminjaman.kodeLab');
        $builder->where('tblManajemenPeminjaman.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
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
        $builder->where('idRincianLabAset', $id);
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
