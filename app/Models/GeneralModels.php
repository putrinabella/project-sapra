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


    public function convertDateSqlFormat($inputDate)
    {
        $dateTime = date_create_from_format('d F Y', $inputDate);

        if ($dateTime === false) {
            throw new \Exception("Invalid date format: $inputDate");
        }

        $sqlDateFormat = $dateTime->format('Y-m-d');

        return $sqlDateFormat;
    }

    public function converDateDisplayFormat($inputDate) {
    $dateTime = date_create_from_format('Y-m-d', $inputDate);

    if ($dateTime === false) {
        throw new \Exception("Invalid date format: $inputDate");
    }

    $displayDateFormat = $dateTime->format('d F Y');

    return $displayDateFormat;
}

}
