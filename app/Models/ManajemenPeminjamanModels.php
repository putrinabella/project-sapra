<?php

namespace App\Models;

use CodeIgniter\Model;

class ManajemenPeminjamanModels extends Model
{
    protected $table            = 'tblManajemenPeminjaman';
    protected $primaryKey       = 'idManajemenPeminjaman';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idManajemenPeminjaman', 'namaPeminjam', 'asalPeminjam', 'idIdentitasSarana', 'idIdentitasLab', 'jumlah', 'tanggal', 'status', 'tanggalPengembalian'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;


    protected $tableRincianLabAset            = 'tblRincianLabAset';

    public function getAssetsByIdIdentitasSarana($idIdentitasSarana, $jumlah, $idIdentitasLab) {
        $builder = $this->db->table($this->tableRincianLabAset);
        return $builder->where('idIdentitasSarana', $idIdentitasSarana)
            ->where('idIdentitasLab', $idIdentitasLab) 
            ->limit($jumlah)
            ->get()
            ->getResultArray();
    }
    
    public function updateSectionAset($idIdentitasSarana, $sectionAsetValue, $idIdentitasLab, $jumlah) {
        $builder = $this->db->table($this->tableRincianLabAset);
        $data = [
            'sectionAset' => $sectionAsetValue,
        ];
    
        $builder->where('idIdentitasSarana', $idIdentitasSarana)
                ->where('idIdentitasLab', $idIdentitasLab) 
                ->limit($jumlah)
                ->set($data)
                ->update();
    }
    
    // ===========================================================
  // public function getAssetsByIdRincianLabAset($idRincianLabAset)
    // {
    //     $builder = $this->db->table($this->tableRincianLabAset);
    //     return $builder->where('idRincianLabAset', $idRincianLabAset)
    //         ->get()
    //         ->getResultArray();
    // }

    // public function getAssetsByIdRincianLabAset($idRincianLabAset, $jumlah) {
    //     $builder = $this->db->table($this->tableRincianLabAset);
    //     return $builder->where('idRincianLabAset', $idRincianLabAset)
    //         ->limit($jumlah) // Limit the number of rows to retrieve
    //         ->get()
    //         ->getResultArray();
    // }

    // public function getAssetsByIdRincianLabAset($idRincianLabAset, $jumlah) {
    //     $idArray = explode(',', $idRincianLabAset);
    //     $assets = [];
    
    //     foreach ($idArray as $id) {
    //         $builder = $this->db->table($this->tableRincianLabAset);
    //         $result = $builder->where('idRincianLabAset', $id)
    //             ->limit($jumlah)
    //             ->get()
    //             ->getResultArray();
    
    //         $assets = array_merge($assets, $result);
    //     }
    
    //     return $assets;
    // }
    
    // public function getAssetsByIdIdentitasSarana($idIdentitasSarana, $jumlah) {
    //     $builder = $this->db->table($this->tableRincianLabAset);
    //     return $builder->where('idIdentitasSarana', $idIdentitasSarana)
    //         ->limit($jumlah)
    //         ->get()
    //         ->getResultArray();
    // }
    
    // public function updateSectionAset($idIdentitasSarana, $sectionAsetValue) {
    //     $builder = $this->db->table($this->tableRincianLabAset);
    //     $data = [
    //         'sectionAset' => $sectionAsetValue,
    //     ];
    
    //     $builder->where('idIdentitasSarana', $idIdentitasSarana)
    //             ->set($data)
    //             ->update();
    // }

    public function updateStatus($idRincianLabAset, $newStatus, $count = 1) {
        $builder = $this->db->table($this->tableRincianLabAset);
        $builder->where('idRincianLabAset', $idRincianLabAset)
                ->set('status', $newStatus)
                ->where('sectionAset', 'Dipinjam')
                ->limit($count)
                ->update();
    }

    function getKodeLabData($idIdentitasSarana){
        $builder = $this->db->table($this->tableRincianLabAset);
        $builder->select('tblRincianLabAset.idIdentitasLab, tblIdentitasLab.namaLab');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
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
        $builder->join('tblIdentitasLab', 'tblRincianLabAset.idIdentitasLab = tblIdentitasLab.idIdentitasLab');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->where('tblRincianLabAset.idIdentitasSarana', $idIdentitasSarana);
        $builder->where('tblRincianLabAset.deleted_at', null); 
        $query = $builder->get();
    
        return $query->getResult();
    }

    function getTotalJumlahBySaranaId($idIdentitasSarana) {
        $builder = $this->db->table($this->table);
        $builder->selectSum('jumlah');
        $builder->where('idIdentitasSarana', $idIdentitasSarana);
        $query = $builder->get();
        return $query->getRow()->jumlah;
    }
    
    function getPrasaranaLab() {
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

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblManajemenPeminjaman.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblManajemenPeminjaman.idIdentitasLab');
        $builder->where('tblManajemenPeminjaman.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblManajemenPeminjaman.idIdentitasSarana');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblManajemenPeminjaman.idIdentitasLab');
        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }

    public function updateAsetTersedia($idIdentitasSarana, $saranaLayak) {
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
    

}
