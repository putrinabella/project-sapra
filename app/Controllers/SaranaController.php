<?php

namespace App\Controllers;

class SaranaController extends BaseController
{
    public function getLayananAsetSarana()
    {
        return view('sarana/layananAsetView');
    }

    public function getLayananNonAsetSarana()
    {
        return view('sarana/layananNonAsetView');
    }

    public function getRincianAsetSarana()
    {
        return view('sarana/RincianAsetView');
    }
}
