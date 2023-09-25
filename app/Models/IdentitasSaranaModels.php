<?php

namespace App\Models;

use CodeIgniter\Model;

class IdentitasSaranaModels extends Model
{
    protected $table            = 'tblIdentitasSarana';
    protected $primaryKey       = 'idIdentitasSarana';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaSarana'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
}
