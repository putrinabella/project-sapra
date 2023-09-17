<?php

namespace App\Controllers;

class InformasiController extends BaseController
{
    public function getIdentitasSarana()
    {
        return view('informasi/identitasSaranaView');
    }

    public function getIdentitasPrasarana()
    {
        return view('informasi/identitasPrasaranaView');
    }

    public function getIdentitasGedung()
    {
        return view('informasi/identitasGedungView');
    }

    public function getIdentitasLantai()
    {
        return view('informasi/identitasLantaiView');
    }

    public function getSumberDana()
    {
        return view('informasi/sumberDanaView');
    }

    public function getStatusManajemen()
    {
        return view('informasi/statusManajemenView');
    }

    public function getKategoriManajemen()
    {
        return view('informasi/kategoriManajemenView');
    }

    public function getProfilSekolah()
    {
        return view('informasi/profilSekolahView');
    }
}
