<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ManajemenAsetPeminjamanModels;
use App\Models\RequestPeminjamanModels;
use App\Models\IdentitasSaranaModels;
use App\Models\IdentitasPrasaranaModels;
use App\Models\IdentitasKelasModels;
use App\Models\RincianAsetModels;
use App\Models\PrasaranaModels;
use App\Models\DataSiswaModels;
use App\Models\ManajemenUserModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class ManajemenAsetPeminjaman extends ResourceController
{

    function __construct()
    {
        $this->manajemenAsetPeminjamanModel = new ManajemenAsetPeminjamanModels();
        $this->requestPeminjamanModel = new RequestPeminjamanModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->rincianAsetModel = new RincianAsetModels();
        $this->prasaranaModel = new PrasaranaModels();
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
        //     'dataManajemenAsetPeminjaman' => $this->manajemenAsetPeminjamanModel->getAll(),
        //     'dataPrasarana' => $this->prasaranaModel->getRuangan(),
        // ];
        // return view('saranaView/manajemenAsetPeminjaman/manajemenAsetPeminjamanSpesifik/index', $data);

        // Untuk menampilkan pengajuan peminjaman general
        // Semua aset yang tersedia ditampilkan

        $data = [
            'dataSiswa' => $this->identitasKelasModel->findAll(),
            'dataPrasaranaPrasarana' => $this->manajemenAsetPeminjamanModel->getPrasaranaPrasarana(),
            'dataSaranaPrasarana' => $this->manajemenAsetPeminjamanModel->getSaranaPrasarana(),
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
            'dataRincianAset' => $this->manajemenAsetPeminjamanModel->getData()
        ];
        return view('saranaView/manajemenAsetPeminjaman/index', $data);
    }

    public function loan($id)
    {
        $data = [
            'dataRincianAset' => $this->manajemenAsetPeminjamanModel->getDataLoan($id),
            'dataSiswa' => $this->identitasKelasModel->findAll(),
            'dataPrasaranaPrasarana' => $this->manajemenAsetPeminjamanModel->getPrasaranaPrasarana(),
            'dataSaranaPrasarana' => $this->manajemenAsetPeminjamanModel->getSaranaPrasarana(),
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
            'namaPrasarana' => $this->manajemenAsetPeminjamanModel->getPrasaranaName($id),
        ];

        return view('saranaView/manajemenAsetPeminjaman/manajemenAsetPeminjamanSpesifik/new', $data);
    }

    public function getRole()
    {
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
        $idRincianAset = $_POST['selectedRows'];
        $sectionAsetValue = 'Dipinjam';
        if (!empty($data['asalPeminjam'])) {
            $tmp = $this->manajemenAsetPeminjamanModel->insert($data);
            $idManajemenAsetPeminjaman = $this->db->insertID();
            foreach ($idRincianAset as $idRincianAset) {
                $detailData = [
                    'idRincianAset' => $idRincianAset,
                    'idManajemenAsetPeminjaman' => $idManajemenAsetPeminjaman,
                ];
                $this->manajemenAsetPeminjamanModel->updateSectionAset($detailData, $sectionAsetValue);
                $this->db->table('tblDetailManajemenAsetPeminjaman')->insert($detailData);
            }
            return redirect()->to(site_url('dataAsetPeminjaman'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('manajemenAsetPeminjaman'))->with('error', 'Semua field harus terisi');
        }
    }

    public function getNama() {
        $asalPeminjam = $this->request->getPost('asalPeminjam');
        $namaSiswa = $this->manajemenAsetPeminjamanModel->getNamaSiswa($asalPeminjam);
        $namaKelas = $this->manajemenAsetPeminjamanModel->getNamaKelas($asalPeminjam);
        return $this->response->setJSON(['namaPeminjam' => $namaSiswa, 'kategori' => $namaKelas]);
    }    
    
}
