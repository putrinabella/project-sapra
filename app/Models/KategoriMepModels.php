<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriMepModels extends Model
{
    protected $table            = 'tblKategoriMep';
    protected $primaryKey       = 'idKategoriMep';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaKategoriMep'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    public function isDuplicate($namaKategoriMep) {
        $builder = $this->db->table($this->table);
        return $builder->Where('namaKategoriMep', $namaKategoriMep)
            ->countAllResults() > 0;
    }
}
