<?php

namespace App\Controllers;

class ItController extends BaseController
{
    public function getLayananAset()
    {
        return view('it/layananAsetView');
    }

    public function getPerangkatIt()
    {
        return view('it/perangkatItView');
    }

    public function getRincianAset()
    {
        return view('it/rincianAsetView');
    }

    public function getWebsiteSosmed()
    {
        return view('it/WebsiteSosmedView');
    }
}
