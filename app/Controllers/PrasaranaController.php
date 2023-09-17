<?php

namespace App\Controllers;

class PrasaranaController extends BaseController
{
    public function getKantin()
    {
        return view('prasarana/kantinView');
    }

    public function getLapangan()
    {
        return view('prasarana/lapanganView');
    }

    public function getParkiran()
    {
        return view('prasarana/parkiranView');
    }

    public function getRuangan()
    {
        return view('prasarana/ruanganView');
    }

    public function getToilet()
    {
        return view('prasarana/toiletView');
    }
}
