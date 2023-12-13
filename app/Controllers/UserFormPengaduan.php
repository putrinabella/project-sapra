<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FormPengaduanModels;
use App\Models\PertanyaanPengaduanModels;


class UserFormPengaduan extends ResourceController
{

    function __construct()
    {
        $this->formPengaduanModel = new FormPengaduanModels();
        $this->pertanyaanPengaduanModel = new PertanyaanPengaduanModels();
        $this->db = \Config\Database::connect();
        helper(['custom']);
    }

    public function pengaduan()
    {
        $data = [
            'dataPertanyaanPengaduan' => $this->pertanyaanPengaduanModel->findAll()
        ];
        // var_dump($data);
        // die;

        return view('userView/formPengaduan/user', $data);
    }
    
    public function loanUser($id)
    {
        $data = [
            'dataRincianPrasaranaAset' => $this->formPengaduanModel->getDataLoan($id),
            'dataSiswa' => $this->identitasKelasModel->findAll(),
            'dataPrasaranaPrasarana' => $this->formPengaduanModel->getPrasaranaPrasarana(),
            'dataSaranaPrasarana' => $this->formPengaduanModel->getSaranaPrasarana(),
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
            'namaPrasarana' => $this->formPengaduanModel->getPrasaranaName($id),
            'namaKelas' => $this->dataSiswaModel->getNamaKelasByUsername(session('username')),
            'idUser' => $this->dataSiswaModel->getIdByUsername(session('username')),
        ];

        return view('userView/formPengaduan/newUser', $data);
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
