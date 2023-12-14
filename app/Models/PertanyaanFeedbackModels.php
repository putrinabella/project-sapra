<?php

namespace App\Models;

use CodeIgniter\Model;

class PertanyaanFeedbackModels extends Model
{
    protected $table            = 'tblPertanyaanFeedback';
    protected $primaryKey       = 'idPertanyaanFeedback';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idPertanyaanFeedback', 'pertanyaanFeedback'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
}
