<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriManajemenModels extends Model
{
    protected $table            = 'tblKategoriManajemen';
    protected $primaryKey       = 'idKategoriManajemen';
    protected $returnType       = 'object';
    protected $allowedFields    = ['kodeKategoriManajemen', 'namaKategoriManajemen'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    public function isDuplicate($kodeKategoriManajemen, $namaKategoriManajemen) {
        $builder = $this->db->table($this->table);
        return $builder->where('kodeKategoriManajemen', $kodeKategoriManajemen)
            ->orWhere('namaKategoriManajemen', $namaKategoriManajemen)
            ->countAllResults() > 0;
    }
    public function getKodeKategoriManajemenById($idKategoriManajemen) {
        $builder = $this->db->table($this->table);
        $builder->select('kodeKategoriManajemen');
        $builder->where('idKategoriManajemen', $idKategoriManajemen);
        $query = $builder->get();
        
        if ($query->getNumRows() > 0) {
            $result = $query->getRow();
            return $result->kodeKategoriManajemen;
        } else {
            return null; 
        }
    }
    
}
