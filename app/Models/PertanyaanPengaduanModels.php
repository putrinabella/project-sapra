<?php

namespace App\Models;

use CodeIgniter\Model;

class PertanyaanPengaduanModels extends Model
{
    protected $table            = 'tblPertanyaanPengaduan';
    protected $primaryKey       = 'idPertanyaanPengaduan';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idPertanyaanPengaduan', 'pertanyaanPengaduan'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
}
