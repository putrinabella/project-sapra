<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriManajemenModels extends Model
{
    protected $table            = 'tblKategoriManajemen';
    protected $primaryKey       = 'idKategoriManajemen';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaKategoriManajemen'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
}