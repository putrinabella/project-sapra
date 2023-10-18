<?php

namespace App\Models;

use CodeIgniter\Model;

class IdentitasKelasModels extends Model
{
    protected $table            = 'tblIdentitasKelas';
    protected $primaryKey       = 'idIdentitasKelas';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaKelas', 'jumlahSiswa'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
}
