<?php

namespace App\Models;

use CodeIgniter\Model;

class DataPrasaranaModels extends Model
{
    protected $table            = 'tblDataPrasarana';
    protected $primaryKey       = 'idDataPrasarana';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idDataPrasarana', 'idIdentitasPrasarana', 'idDataSarana'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table('tblDataPrasarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblDataPrasarana.idIdentitasPrasarana');
        $builder->where('tblDataPrasarana.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }
}
