<?php

namespace App\Models;

use CodeIgniter\Model;

class IdentitasPrasaranaModels extends Model
{
    protected $table            = 'tblIdentitasPrasarana';
    protected $primaryKey       = 'idIdentitasPrasarana';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaPrasarana', 'luas', 'idIdentitasGedung', 'idIdentitasLantai'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table('tblIdentitasPrasarana');
        $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasPrasarana.idIdentitasGedung');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasPrasarana.idIdentitasLantai');
        $query = $builder->get();
        return $query->getResult();
    }
}
