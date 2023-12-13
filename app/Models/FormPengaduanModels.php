<?php

namespace App\Models;

use CodeIgniter\Model;

class FormPengaduanModels extends Model
{
    protected $table            = 'tblFormPengaduan';
    protected $primaryKey       = 'idFormPengaduan';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idFormPengaduan', 'idDataSiswa', 'idPertanyaanPengaduan', 'tanggal', 'sp', 'p', 'n', 'tp', 'st'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
}
