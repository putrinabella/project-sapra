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
        
        // Check if the value is different before attempting the update
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
