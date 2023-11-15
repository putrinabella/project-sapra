<?php

function generate_pdf($html, $filename = '', $stream = true)
{
    require ROOTPATH . 'vendor/autoload.php';

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
}
