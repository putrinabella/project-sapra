<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailManajemenAsetPeminjamanModels extends Model
{
    protected $table            = 'tblDetailManajemenAsetPeminjaman';
    protected $primaryKey       = 'idDetailManajemenAsetPeminjaman';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idManajemenAsetPeminjaman', 'idRincianAset', 'statusSetelahPengembalian'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
}
