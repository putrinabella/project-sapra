<?php

namespace App\Models;

use CodeIgniter\Model;

class GeneralModels extends Model
{
    protected $table            = '';
    protected $primaryKey       = '';
    protected $returnType       = '';
    protected $allowedFields    = [];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;


    public function convertDateFormat($inputDate)
    {
        $dateTime = date_create_from_format('d M Y', $inputDate);

        if ($dateTime === false) {
            throw new \Exception("Invalid date format: $inputDate");
        }

        $sqlDateFormat = $dateTime->format('Y-m-d');

        return $sqlDateFormat;
    }
}
