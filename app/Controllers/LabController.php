<?php

namespace App\Controllers;

class LabController extends BaseController
{
    public function getManajemenPeminjamanLab()
    {
        return view('lab/manajemenPeminjamanLabView');
    }

    public function getLayananAsetLab()
    {
        return view('lab/layananAsetLabView');
    }

    public function getLayananNonAsetLab()
    {
        return view('lab/layananNonAsetLabView');
    }

    public function getRincianAsetLab()
    {
        return view('lab/rincianAsetLabView');
    }

    public function getManajemenLab()
    {
        return view('lab/manajemenLabView');
    }
}
