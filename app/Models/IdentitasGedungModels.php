<?php

namespace App\Models;

use CodeIgniter\Model;

class IdentitasGedungModels extends Model
{
    protected $table            = 'tblIdentitasGedung';
    protected $primaryKey       = 'idIdentitasGedung';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaGedung'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
}
