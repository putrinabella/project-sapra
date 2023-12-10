<?php

namespace App\Models;

use CodeIgniter\Model;

class IdentitasPrasaranaModels extends Model
{
    protected $table            = 'tblIdentitasPrasarana';
    protected $primaryKey       = 'idIdentitasPrasarana';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaPrasarana', 'luas', 'idIdentitasGedung', 'idIdentitasLantai', 'kodePrasarana' ,'tipe', 'picturePath'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasPrasarana.idIdentitasGedung');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasPrasarana.idIdentitasLantai');
        $builder->where('tblIdentitasPrasarana.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }
    
    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasPrasarana.idIdentitasGedung');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasPrasarana.idIdentitasLantai');
        $builder->where('tblIdentitasPrasarana.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    public function isDuplicate($kodePrasarana, $namaPrasarana) {
        $builder = $this->db->table($this->table);
        return $builder->where('kodePrasarana', $kodePrasarana)
            ->orWhere('namaPrasarana', $namaPrasarana)
            ->countAllResults() > 0;
    }
    
    public function kodePrasaranaDuplicate($kodePrasarana) {
        $builder = $this->db->table($this->table);
        return $builder->where('kodePrasarana', $kodePrasarana)
            ->countAllResults() > 0;
    }

    public function namaPrasaranaDuplicate($namaPrasarana) {
        $builder = $this->db->table($this->table);
        return $builder->where('namaPrasarana', $namaPrasarana)
            ->countAllResults() > 0;
    }
    
    public function getKodePrasaranaById($idIdentitasPrasarana) {
        $builder = $this->db->table($this->table);
        $builder->select('kodePrasarana');
        $builder->where('idIdentitasPrasarana', $idIdentitasPrasarana);
        $query = $builder->get();
        
        if ($query->getNumRows() > 0) {
            $result = $query->getRow();
            return $result->kodePrasarana;
        } else {
            return null; 
        }
    }
}
