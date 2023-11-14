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
use App\Models\ProfilSekolahModels; 
use App\Models\DokumenSekolahModels; 

class Home extends BaseController
{
        
    function __construct() {
        $this->rincianAsetModel = new RincianAsetModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->homeModel = new HomeModels();
        $this->profilSekolahModel = new ProfilSekolahModels();
        $this->dokumenSekolahModel = new DokumenSekolahModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $dataRincianAset = $this->homeModel->getData();
        $dataRincianItAset = $this->homeModel->getDataIt();
        $dataRincianAsetLab = $this->homeModel->getDataLab();
        $dataProfilSekolah = $this->profilSekolahModel->findAll();
        $dataDokumenSekolah = $this->dokumenSekolahModel->findAll();

        $firstRecord = $this->profilSekolahModel->first();
        $firstRecordId = $firstRecord ? $firstRecord->idProfilSekolah : null;
        $rowCount =  $this->profilSekolahModel->getCount();
        $data = [
            'dataRincianAset' => $dataRincianAset,
            'dataRincianItAset' => $dataRincianItAset,
            'dataRincianAsetLab' => $dataRincianAsetLab,
            'rowCount'              => $rowCount,
            'firstRecordId'         => $firstRecordId,
            'dataProfilSekolah'     => $dataProfilSekolah,
            'dataDokumenSekolah'    => $dataDokumenSekolah,
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