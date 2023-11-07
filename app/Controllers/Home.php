<?php

namespace App\Controllers;
use App\Models\LaboratoriumModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasLabModels; 
use App\Models\IdentitasGedungModels; 
use App\Models\IdentitasLantaiModels; 
use App\Models\RincianLabAsetModels; 
use App\Models\RincianAsetModels; 
use App\Models\IdentitasPrasaranaModels; 
use App\Models\HomeModels; 

class Home extends BaseController
{
        
    function __construct() {
        $this->rincianAsetModel = new RincianAsetModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->homeModel = new HomeModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $dataRincianAset = $this->homeModel->getData();
        $dataRincianAsetLab = $this->homeModel->getDataLab();
        $data = [
            'dataRincianAset' => $dataRincianAset,
            'dataRincianAsetLab' => $dataRincianAsetLab,
        ];
        
        return view('home', $data);
    }

        function userLogin(){
        $db = \Config\Database::connect();
        return $db->table('tblUser')->where('idUser', session('id_user'))->get()->getRow();
    }
}


            // 'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            // 'dataSumberDana' => $this->sumberDanaModel->findAll(),
            // 'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
            // 'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),