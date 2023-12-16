<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FormPengaduanModels;
use App\Models\DataSiswaModels;
use App\Models\PertanyaanPengaduanModels;


class UserFormPengaduan extends ResourceController
{

    function __construct()
    {
        $this->formPengaduanModel = new FormPengaduanModels();
        $this->dataSiswaModel = new DataSiswaModels();
        $this->pertanyaanPengaduanModel = new PertanyaanPengaduanModels();
        $this->db = \Config\Database::connect();
        helper(['custom']);
    }

    public function pengaduan()
    {
        $data = [
            'dataPertanyaanPengaduan' => $this->pertanyaanPengaduanModel->findAll()
        ];

        return view('userView/formPengaduan/user', $data);
    }
    
    public function tambahPengaduan() {
        $idPertanyaanPengaduanArray = $this->request->getPost('idPertanyaanPengaduan');
        $isiPengaduanArray = $this->request->getPost('isiPengaduan');
        $tanggal = date('Y-m-d');
        $idDataSiswa = $this->dataSiswaModel->getIdByUsername(session('username'));
        $statusPengaduan = 'request';
        $username = session('username');
        $numericPart = substr(mt_rand() . uniqid(), 0, 8); 
        $kodeFormPengaduan = 'TS-BJB/C-' . $username . '/' . $numericPart;

        $dataPengaduan = [
            'idDataSiswa' => $idDataSiswa,
            'tanggal' => $tanggal,
            'statusPengaduan' => $statusPengaduan,
            'kodeFormPengaduan' => $kodeFormPengaduan,
        ];
        $this->formPengaduanModel->insert($dataPengaduan);
        $idFormPengaduan = $this->db->insertID();
        foreach ($idPertanyaanPengaduanArray as $idPertanyaanPengaduan) {
            $detailData = [
                'idFormPengaduan' => $idFormPengaduan,
                'idPertanyaanPengaduan' => $idPertanyaanPengaduan,
                'isiPengaduan' => $isiPengaduanArray[$idPertanyaanPengaduan],
            ];
            $this->db->table('tblDetailFormPengaduan')->insert($detailData);
        }
        return redirect()->to(site_url('dataPengaduanUser'))->with('success', 'Data berhasil disimpan');
    }
}
