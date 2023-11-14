<?php

namespace App\Models;

use CodeIgniter\Model;

class IdentitasSaranaModels extends Model
{
    protected $table            = 'tblIdentitasSarana';
    protected $primaryKey       = 'idIdentitasSarana';
    protected $returnType       = 'object';
    protected $allowedFields    = ['kodeSarana', 'namaSarana', 'perangkatIT'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    
    public function isDuplicate($kodeSarana, $namaSarana) {
        $builder = $this->db->table($this->table);
        return $builder->where('kodeSarana', $kodeSarana)
            ->orWhere('namaSarana', $namaSarana)
            ->countAllResults() > 0;
    }

    public function getKodeSaranaById($idIdentitasSarana) {
        $builder = $this->db->table($this->table);
        $builder->select('kodeSarana');
        $builder->where('idIdentitasSarana', $idIdentitasSarana);
        $query = $builder->get();
        
        if ($query->getNumRows() > 0) {
            $result = $query->getRow();
            return $result->kodeSarana;
        } else {
            return null; 
        }
    }

    public function findAsetIT() {
        $builder = $this->db->table($this->table);
        $builder->where('tblIdentitasSarana.perangkatIT', 1); 
        $query = $builder->get();
        return $query->getResult();
    }
}
