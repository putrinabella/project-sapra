<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailManajemenPeminjamanModels extends Model
{
    protected $table            = 'tblDetailManajemenPeminjaman';
    protected $primaryKey       = 'idDetailManajemenPeminjaman';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idManajemenPeminjaman', 'idRincianLabAset', 'statusSetelahPengembalian'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
}
