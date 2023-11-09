<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriPegawaiModels extends Model
{
    protected $table            = 'tblKategoriPegawai';
    protected $primaryKey       = 'idKategoriPegawai';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaKategoriPegawai'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
}
