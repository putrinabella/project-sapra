<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ManajemenPeminjamanModels;
use App\Models\RequestPeminjamanModels;
use App\Models\IdentitasSaranaModels;
use App\Models\IdentitasLabModels;
use App\Models\IdentitasKelasModels;
use App\Models\LaboratoriumModels;
use App\Models\DataSiswaModels;

class UserPengajuanLabPeminjaman extends ResourceController
{

    function __construct()
    {
        $this->manajemenPeminjamanModel = new ManajemenPeminjamanModels();
        $this->requestPeminjamanModel = new RequestPeminjamanModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->laboratoriumModel = new LaboratoriumModels();
        $this->identitasKelasModel = new IdentitasKelasModels();
        $this->dataSiswaModel = new DataSiswaModels();
        $this->db = \Config\Database::connect();
        helper(['custom']);
    }

    public function user()
    {
        $data = [
            'dataManajemenPeminjaman' => $this->manajemenPeminjamanModel->getAll(),
            'dataLaboratorium' => $this->laboratoriumModel->getRuangan(),
        ];

        return view('userView/pengajuanPeminjamanLab/user', $data);
    }
    
    public function loanUser($id)
    {
        $data = [
            'dataRincianLabAset' => $this->manajemenPeminjamanModel->getDataLoan($id),
            'dataSiswa' => $this->identitasKelasModel->findAll(),
            'dataPrasaranaLab' => $this->manajemenPeminjamanModel->getPrasaranaLab(),
            'dataSaranaLab' => $this->manajemenPeminjamanModel->getSaranaLab(),
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataIdentitasLab' => $this->identitasLabModel->findAll(),
            'namaLaboratorium' => $this->manajemenPeminjamanModel->getLabName($id),
            'namaKelas' => $this->dataSiswaModel->getNamaKelasByUsername(session('username')),
            'idUser' => $this->dataSiswaModel->getIdByUsername(session('username')),
        ];

        return view('userView/pengajuanPeminjamanLab/newUser', $data);
    }

    public function addLoanUser() {
        $data = $this->request->getPost();
        $idRincianLabAset = $_POST['selectedRows'];


        if (!empty($data['asalPeminjam'])) {
            $this->requestPeminjamanModel->insert($data);
            $idRequestPeminjaman = $this->db->insertID();
            foreach ($idRincianLabAset as $idRincianAset) {
                $detailData = [
                    'idRincianLabAset' => $idRincianAset,
                    'idRequestPeminjaman' => $idRequestPeminjaman,
                ];
                $this->db->table('tblDetailRequestPeminjaman')->insert($detailData);
            }
            return redirect()->to(site_url('dataLabPeminjaman'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('dataLabPeminjaman'))->with('error', 'Semua field harus terisi');
        }
    }
}
