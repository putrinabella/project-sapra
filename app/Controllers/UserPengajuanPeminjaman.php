<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ManajemenAsetPeminjamanModels;
use App\Models\RequestAsetPeminjamanModels;
use App\Models\IdentitasSaranaModels;
use App\Models\IdentitasPrasaranaModels;
use App\Models\IdentitasKelasModels;
use App\Models\PrasaranaModels;
use App\Models\DataSiswaModels;

class UserPengajuanPeminjaman extends ResourceController
{

    function __construct()
    {
        $this->manajemenAsetPeminjamanModel = new ManajemenAsetPeminjamanModels();
        $this->requestAsetPeminjamanModel = new RequestAsetPeminjamanModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->prasaranaModel = new PrasaranaModels();
        $this->identitasKelasModel = new IdentitasKelasModels();
        $this->dataSiswaModel = new DataSiswaModels();
        $this->db = \Config\Database::connect();
        helper(['custom']);
    }

    public function user()
    {
        // Untuk menampilkan pengajuan peminjaman beradasarkan lokasi 
        // Aset ditampilkan berdasarkan lokasinya

        // $data = [
        //     'dataManajemenAsetPeminjaman' => $this->manajemenAsetPeminjamanModel->getAll(),
        //     'dataPrasarana' => $this->prasaranaModel->getRuangan(),
        // ];
        // return view('userView/pengajuanPeminjaman/user', $data);

        // Untuk menampilkan pengajuan peminjaman general
        // Semua aset yang tersedia ditampilkan

        $data = [
            'dataRincianAset' => $this->manajemenAsetPeminjamanModel->getDataLoanGeneral(),
            'dataSiswa' => $this->identitasKelasModel->findAll(),
            'dataPrasaranaPrasarana' => $this->manajemenAsetPeminjamanModel->getPrasaranaPrasarana(),
            'dataSaranaPrasarana' => $this->manajemenAsetPeminjamanModel->getSaranaPrasarana(),
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
            'namaKelas' => $this->dataSiswaModel->getNamaKelasByUsername(session('username')),
            'idUser' => $this->dataSiswaModel->getIdByUsername(session('username')),
        ];
        return view('userView/pengajuanPeminjaman/index', $data);
    }
    
    public function loanUser($id)
    {
        $data = [
            'dataRincianAset' => $this->manajemenAsetPeminjamanModel->getDataLoan($id),
            'dataSiswa' => $this->identitasKelasModel->findAll(),
            'dataPrasaranaPrasarana' => $this->manajemenAsetPeminjamanModel->getPrasaranaPrasarana(),
            'dataSaranaPrasarana' => $this->manajemenAsetPeminjamanModel->getSaranaPrasarana(),
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
            'namaPrasarana' => $this->manajemenAsetPeminjamanModel->getPrasaranaName($id),
            'namaKelas' => $this->dataSiswaModel->getNamaKelasByUsername(session('username')),
            'idUser' => $this->dataSiswaModel->getIdByUsername(session('username')),
        ];

        return view('userView/pengajuanPeminjaman/newUser', $data);
    }

    public function addLoanUser() {
        $data = $this->request->getPost();
        $idRincianAset = $_POST['selectedRows'];


        if (!empty($data['asalPeminjam'])) {
            $this->requestAsetPeminjamanModel->insert($data);
            $idRequestAsetPeminjaman = $this->db->insertID();
            foreach ($idRincianAset as $idRincianAset) {
                $detailData = [
                    'idRincianAset' => $idRincianAset,
                    'idRequestAsetPeminjaman' => $idRequestAsetPeminjaman,
                ];
                $this->db->table('tblDetailRequestAsetPeminjaman')->insert($detailData);
            }
            return redirect()->to(site_url('peminjamanAset'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('peminjamanAset'))->with('error', 'Semua field harus terisi');
        }
    }
}
