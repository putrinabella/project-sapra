<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ManajemenPeminjamanModels;
use App\Models\RequestPeminjamanModels;
use App\Models\IdentitasSaranaModels;
use App\Models\IdentitasLabModels;
use App\Models\IdentitasKelasModels;
use App\Models\RincianLabAsetModels;
use App\Models\LaboratoriumModels;
use App\Models\DataSiswaModels;
use App\Models\ManajemenUserModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class ManajemenPeminjaman extends ResourceController
{

    function __construct()
    {
        $this->manajemenPeminjamanModel = new ManajemenPeminjamanModels();
        $this->requestPeminjamanModel = new RequestPeminjamanModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->rincianLabAsetModel = new RincianLabAsetModels();
        $this->laboratoriumModel = new LaboratoriumModels();
        $this->identitasKelasModel = new IdentitasKelasModels();
        $this->dataSiswaModel = new DataSiswaModels();
        $this->manajemenUserModel = new ManajemenUserModels();
        $this->db = \Config\Database::connect();
        helper(['custom']);
    }

    public function index()
    {
        // Untuk menampilkan pengajuan peminjaman beradasarkan lokasi 
        // Aset ditampilkan berdasarkan lokasinya

        // $data = [
        //     'dataManajemenPeminjaman' => $this->manajemenPeminjamanModel->getAll(),
        //     'dataLaboratorium' => $this->laboratoriumModel->getRuangan(),
        // ];
        // return view('labView/manajemenPeminjaman/manajemenPeminjamanSpesifik/index', $data);

        // Untuk menampilkan pengajuan peminjaman general
        // Semua aset yang tersedia ditampilkan

        $data = [
            'dataSiswa' => $this->identitasKelasModel->findAll(),
            'dataPrasaranaLab' => $this->manajemenPeminjamanModel->getPrasaranaLab(),
            'dataSaranaLab' => $this->manajemenPeminjamanModel->getSaranaLab(),
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataIdentitasLab' => $this->identitasLabModel->findAll(),
            'dataRincianLabAset' => $this->manajemenPeminjamanModel->getData()
        ];
        return view('labView/manajemenPeminjaman/index', $data);
    }

    public function loan($id) {
        $data = [
            'dataRincianLabAset' => $this->manajemenPeminjamanModel->getDataLoan($id),
            'dataSiswa' => $this->identitasKelasModel->findAll(),
            'dataPrasaranaLab' => $this->manajemenPeminjamanModel->getPrasaranaLab(),
            'dataSaranaLab' => $this->manajemenPeminjamanModel->getSaranaLab(),
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataIdentitasLab' => $this->identitasLabModel->findAll(),
            'namaLaboratorium' => $this->manajemenPeminjamanModel->getLabName($id),
        ];

        return view('labView/manajemenPeminjaman/manajemenPeminjamanSpesifik/new', $data);
    }

    public function getRole() {
        $kategoriPeminjam = $this->request->getPost('kategoriPeminjam');
        if ($kategoriPeminjam === 'karyawan') {
            $dataPegawai = $this->dataSiswaModel->getAllPegawai();
            return $this->response->setJSON($dataPegawai);
        } elseif ($kategoriPeminjam === 'siswa') {
            $dataSiswa = $this->dataSiswaModel->getAll();
            return $this->response->setJSON($dataSiswa);
        } else {
            return $this->response->setJSON([]);
        }
    }

    public function addLoan() {
        $data = $this->request->getPost();
        $idRincianLabAset = $_POST['selectedRows'];
        $sectionAsetValue = 'Dipinjam';

        if (!empty($data['asalPeminjam'])) {
            $this->manajemenPeminjamanModel->insert($data);
            $idManajemenPeminjaman = $this->db->insertID();
            foreach ($idRincianLabAset as $idRincianAset) {
                $detailData = [
                    'idRincianLabAset' => $idRincianAset,
                    'idManajemenPeminjaman' => $idManajemenPeminjaman,
                ];
                $this->manajemenPeminjamanModel->updateSectionAset($detailData, $sectionAsetValue);
                $this->db->table('tblDetailManajemenPeminjaman')->insert($detailData);
            }
            return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('manajemenPeminjaman'))->with('error', 'Semua field harus terisi');
        }
    }

    public function getNama() {
        $asalPeminjam = $this->request->getPost('asalPeminjam');
        $namaSiswa = $this->manajemenPeminjamanModel->getNamaSiswa($asalPeminjam);
        $namaKelas = $this->manajemenPeminjamanModel->getNamaKelas($asalPeminjam);
        return $this->response->setJSON(['namaPeminjam' => $namaSiswa, 'kategori' => $namaKelas]);
    }    
}
