<?php

namespace App\Controllers;

class InformasiController extends BaseController
{
    public function getIdentitasSarana() {
        $builder = $this->db->table('tblIdentitasSarana');
        $query = $builder->get()->getResult();
        $data['dataIdentitasSarana'] = $query;
        return view('informasi/identitasSaranaView', $data);
    }

    public function addIdentitasSarana() {
        return view('informasi/add/addIdentitasSaranaView');
    }

    public function getIdentitasPrasarana() {
        return view('informasi/identitasPrasaranaView');
    }

    public function getIdentitasGedung() {
        return view('informasi/identitasGedungView');
    }

    public function getIdentitasLantai() {
        return view('informasi/identitasLantaiView');
    }

    public function getSumberDana() {
        return view('informasi/sumberDanaView');
    }

    public function getStatusManajemen() {
        return view('informasi/statusManajemenView');
    }

    public function getKategoriManajemen() {
        return view('informasi/kategoriManajemenView');
    }

    public function getProfilSekolah() {
        return view('informasi/profilSekolahView');
    }
}
