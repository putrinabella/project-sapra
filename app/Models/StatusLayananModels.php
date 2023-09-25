<?php

namespace App\Models;

use CodeIgniter\Model;

class StatusLayananModels extends Model
{
    protected $table            = 'tblStatusLayanan';
    protected $primaryKey       = 'idStatusLayanan';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaStatusLayanan'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
}
