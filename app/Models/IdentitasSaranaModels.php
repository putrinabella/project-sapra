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
}
