<?php

namespace App\Models;

use CodeIgniter\Model;

class DataSiswaModels extends Model
{
    protected $table            = 'tblDataSiswa';
    protected $primaryKey       = 'idDataSiswa';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idDataSiswa', 'namaSiswa', 'nis', 'idIdentitasKelas'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
    protected $manajemenUserModel;

    public function __construct()
    {
        parent::__construct();
        $this->manajemenUserModel = new \App\Models\ManajemenUserModels();
    }

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');
        $builder->where('tblDataSiswa.deleted_at', NULL);
        $builder->where('tblDataSiswa.idIdentitasKelas !=', 1);
        $query = $builder->get();
        return $query->getResult();
    }
    
    function getAllPegawai() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');
        $builder->where('tblDataSiswa.deleted_at', NULL);
        $builder->where('tblDataSiswa.idIdentitasKelas =', 1);
        $query = $builder->get();
        return $query->getResult();
    }

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');  
        $builder->where('tblDataSiswa.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }

    public function isDuplicate($nis) {
        $builder = $this->db->table($this->table);
        return $builder->where('nis', $nis)
            ->countAllResults() > 0;
    }

    public function purgeDeletedWithUser()
    {
        $deletedUsernames = $this->onlyDeleted()->findAll();
    
        foreach ($deletedUsernames as $deletedData) {
            $username = $deletedData->nis;
            var_dump($username);
            $idUser = $this->manajemenUserModel->getIdByUsername($username);
            var_dump($idUser);
            if ($idUser !== null) {
                $this->manajemenUserModel->deleteByUsername($idUser);
            }
        }
    
        $this->onlyDeleted()->purgeDeleted();
    }
    
    public function getNamaKelasByUsername($username)
    {
        $builder = $this->db->table($this->table);
        $builder->select('tblIdentitasKelas.namaKelas');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');
        $builder->where('tblDataSiswa.nis', $username);
    
        $result = $builder->get()->getRow();
    
        return $result ? $result->namaKelas : null;
    }

    public function getIdByUsername($username)
    {
        $builder = $this->db->table($this->table);
        $builder->select('idDataSiswa'); 
        $builder->where('nis', $username);
        $userData = $builder->get()->getRowArray();
    
        return $userData ? $userData['idDataSiswa'] : null;
    }
}
