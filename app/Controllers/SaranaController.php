<?php

namespace App\Controllers;

class SaranaController extends BaseController
{
    public function getLayananAset()
    {
        return view('sarana/layananAsetView');
    }

    public function getLayananNonAset()
    {
        return view('sarana/layananNonAsetView');
    }

    public function getRincianAset()
    {
        return view('sarana/RincianAsetView');
    }
}
