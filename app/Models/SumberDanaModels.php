<?php

namespace App\Models;

use CodeIgniter\Model;

class SumberDanaModels extends Model
{
    protected $table            = 'tblSumberDana';
    protected $primaryKey       = 'idSumberDana';
    protected $returnType       = 'object';
    protected $allowedFields    = ['kodeSumberDana', 'namaSumberDana'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    public function isDuplicate($kodeSumberDana, $namaSumberDana) {
        $builder = $this->db->table($this->table);
        return $builder->where('kodeSumberDana', $kodeSumberDana)
            ->orWhere('namaSumberDana', $namaSumberDana)
            ->countAllResults() > 0;
    }

    public function getKodeSumberDanaById($idSumberDana) {
        $builder = $this->db->table($this->table);
        $builder->select('kodeSumberDana');
        $builder->where('idSumberDana', $idSumberDana);
        $query = $builder->get();
        
        if ($query->getNumRows() > 0) {
            $result = $query->getRow();
            return $result->kodeSumberDana;
        } else {
            return null; 
        }
    }
}
