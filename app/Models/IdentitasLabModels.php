<?php

namespace App\Models;

use CodeIgniter\Model;

class IdentitasLabModels extends Model
{
    protected $table            = 'tblIdentitasLab';
    protected $primaryKey       = 'idIdentitasLab';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaLab', 'luas', 'idIdentitasGedung', 'idIdentitasLantai', 'kodeLab' ,'tipe', 'picturePath'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasLab.idIdentitasGedung');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasLab.idIdentitasLantai');
        $builder->where('tblIdentitasLab.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }
    
    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasLab.idIdentitasGedung');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasLab.idIdentitasLantai');
        $builder->where('tblIdentitasLab.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    public function isDuplicate($kodeLab, $namaLab) {
        $builder = $this->db->table($this->table);
        return $builder->where('kodeLab', $kodeLab)
            ->orWhere('namaLab', $namaLab)
            ->countAllResults() > 0;
    }
    
    public function kodeLabDuplicate($kodeLab) {
        $builder = $this->db->table($this->table);
        return $builder->where('kodeLab', $kodeLab)
            ->countAllResults() > 0;
    }

    public function namaLabDuplicate($namaLab) {
        $builder = $this->db->table($this->table);
        return $builder->where('namaLab', $namaLab)
            ->countAllResults() > 0;
    }
    
    public function getKodeLabById($idIdentitasLab) {
        $builder = $this->db->table($this->table);
        $builder->select('kodeLab');
        $builder->where('idIdentitasLab', $idIdentitasLab);
        $query = $builder->get();
        
        if ($query->getNumRows() > 0) {
            $result = $query->getRow();
            return $result->kodeLab;
        } else {
            return null; 
        }
    }
}
