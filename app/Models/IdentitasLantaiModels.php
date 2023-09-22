<?php

namespace App\Models;

use CodeIgniter\Model;

class IdentitasLantaiModels extends Model
{
    protected $table            = 'tblIdentitasLantai';
    protected $primaryKey       = 'idIdentitasLantai';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaLantai'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
}
