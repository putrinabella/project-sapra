<?php

namespace App\Models;

use CodeIgniter\Model;

class SumberDanaModels extends Model
{
    protected $table            = 'tblSumberDana';
    protected $primaryKey       = 'idSumberDana';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaSumberDana'];
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
}
