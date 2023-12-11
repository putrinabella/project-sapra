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
        $data = [
            'dataManajemenAsetPeminjaman' => $this->manajemenAsetPeminjamanModel->getAll(),
            'dataPrasarana' => $this->prasaranaModel->getRuangan(),
        ];

        return view('saranaView/manajemenAsetPeminjaman/index', $data);
    }

    public function user()
    {
        $data = [
            'dataManajemenAsetPeminjaman' => $this->manajemenAsetPeminjamanModel->getAll(),
            'dataPrasarana' => $this->prasaranaModel->getRuangan(),
        ];

        return view('saranaView/manajemenAsetPeminjaman/user', $data);
    }

    public function new()
    {
        $data = [
            'dataSiswa' => $this->identitasKelasModel->findAll(),
            'dataPrasaranaPrasarana' => $this->manajemenAsetPeminjamanModel->getPrasaranaPrasarana(),
            'dataSaranaPrasarana' => $this->manajemenAsetPeminjamanModel->getSaranaPrasarana(),
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
            'dataRincianAset' => $this->manajemenAsetPeminjamanModel->getData()
        ];

        return view('saranaView/manajemenAsetPeminjaman/new', $data);
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

        return view('saranaView/manajemenAsetPeminjaman/new', $data);
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

        return view('saranaView/manajemenAsetPeminjaman/newUser', $data);
    }

    public function getKodePrasarana($idIdentitasSarana)
    {
        $data = $this->manajemenAsetPeminjamanModel->getKodePrasaranaData($idIdentitasSarana);

        echo json_encode($data);
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

    public function getFilterOptions()
    {
        $filterJenis = $this->request->getPost('filterJenis');
        if ($filterJenis === 'lokasi') {
            $dataIdentitasPrasarana = $this->manajemenAsetPeminjamanModel->getAllIdIdentitasPrasarana();
            return $this->response->setJSON($dataIdentitasPrasarana);
        } elseif ($filterJenis === 'sarana') {
            $dataIdentitasSarana = $this->manajemenAsetPeminjamanModel->getAllIdIdentitasSarana();
            return $this->response->setJSON($dataIdentitasSarana);
        } else {
            return $this->response->setJSON([]);
        }
    }

    public function getSaranaByPrasarana()
    {
        $selectedIdIdentitasPrasarana = $this->request->getPost('idIdentitasPrasarana');
        $idIdentitasSaranaOptions = $this->manajemenAsetPeminjamanModel->getSaranaByPrasarana($selectedIdIdentitasPrasarana);
        return $this->response->setJSON($idIdentitasSaranaOptions);
    }

    public function getKodeBySarana()
    {
        $selectedIdIdentitasSarana = $this->request->getPost('idIdentitasSarana');
        $selectedIdIdentitasPrasarana = $this->request->getPost('idIdentitasPrasarana');
        $asalPeminjamOptions = $this->manajemenAsetPeminjamanModel->getKodeBySarana($selectedIdIdentitasSarana, $selectedIdIdentitasPrasarana);
        return $this->response->setJSON($asalPeminjamOptions);
    }



    public function getRincianAsetByPrasarana()
    {
        if (!$this->request->isAJAX()) {
            exit('Direct access is not allowed');
        }

        $idIdentitasPrasarana = $this->request->getPost('idIdentitasPrasarana');

        $data = $this->manajemenAsetPeminjamanModel->getSaranaByPrasaranaId($idIdentitasPrasarana);

        return $this->response->setJSON($data);
    }

    public function show($id = null)
    {
        if ($id != null) {
            $dataPrasarana = $this->prasaranaModel->find($id);

            if (is_object($dataPrasarana)) {
                $dataInfoPrasarana = $this->prasaranaModel->getIdentitasGedung($dataPrasarana->idIdentitasPrasarana);
                $dataInfoPrasarana->namaLantai = $this->prasaranaModel->getIdentitasLantai($dataPrasarana->idIdentitasPrasarana)->namaLantai;
                $dataSemuaSarana = $this->prasaranaModel->getSaranaByPrasaranaId($dataPrasarana->idIdentitasPrasarana);
                $dataSarana = $this->prasaranaModel->getSaranaByPrasarana($dataPrasarana->idIdentitasPrasarana);
                $asetBagus = $this->prasaranaModel->getSaranaLayakCount($dataPrasarana->idIdentitasPrasarana);
                $data = [
                    'dataSiswa' => $this->identitasKelasModel->findAll(),
                    'dataPrasarana'  => $dataPrasarana,
                    'dataInfoPrasarana'       => $dataInfoPrasarana,
                    'dataSemuaSarana'   => $dataSemuaSarana,
                    'dataSarana'        => $dataSarana,
                    'asetBagus'         => $asetBagus,
                ];
                return view('saranaView/manajemenAsetPeminjaman/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function showUser($id = null)
    {
        if ($id != null) {
            $dataPrasarana = $this->prasaranaModel->find($id);

            if (is_object($dataPrasarana)) {
                $dataInfoPrasarana = $this->prasaranaModel->getIdentitasGedung($dataPrasarana->idIdentitasPrasarana);
                $dataInfoPrasarana->namaLantai = $this->prasaranaModel->getIdentitasLantai($dataPrasarana->idIdentitasPrasarana)->namaLantai;
                $dataSemuaSarana = $this->prasaranaModel->getSaranaByPrasaranaId($dataPrasarana->idIdentitasPrasarana);
                $dataSarana = $this->prasaranaModel->getSaranaByPrasarana($dataPrasarana->idIdentitasPrasarana);
                $asetBagus = $this->prasaranaModel->getSaranaLayakCount($dataPrasarana->idIdentitasPrasarana);
                $data = [
                    'dataSiswa' => $this->identitasKelasModel->findAll(),
                    'dataPrasarana'  => $dataPrasarana,
                    'dataInfoPrasarana'       => $dataInfoPrasarana,
                    'dataSemuaSarana'   => $dataSemuaSarana,
                    'dataSarana'        => $dataSarana,
                    'asetBagus'         => $asetBagus,
                ];
                return view('saranaView/manajemenAsetPeminjaman/showUser', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    function print($id = null)
    {
        if ($id != null) {
            $dataPrasarana = $this->prasaranaModel->find($id);

            if (is_object($dataPrasarana)) {

                $dataInfoPrasarana = $this->prasaranaModel->getIdentitasGedung($dataPrasarana->idIdentitasPrasarana);
                $dataInfoPrasarana->namaLantai = $this->prasaranaModel->getIdentitasLantai($dataPrasarana->idIdentitasPrasarana)->namaLantai;
                $dataSarana = $this->prasaranaModel->getSaranaByPrasarana($dataPrasarana->idIdentitasPrasarana);

                $data = [
                    'dataPrasarana'  => $dataPrasarana,
                    'dataInfoPrasarana'       => $dataInfoPrasarana,
                    'dataSarana'        => $dataSarana,
                ];

                $html = view('saranaView/manajemenAsetPeminjaman/print', $data);

                $options = new Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isPhpEnabled', true);

                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $namaPrasarana = $data['dataPrasarana']->namaPrasarana;
                $filename = "Prasarana - $namaPrasarana.pdf";
                $dompdf->stream($filename);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function addLoan() {
        $data = $this->request->getPost();
        $idRincianAset = $_POST['selectedRows'];
        $sectionAsetValue = 'Dipinjam';
        // var_dump($data);
        // die;

        if (!empty($data['asalPeminjam'])) {
            $tmp = $this->manajemenAsetPeminjamanModel->insert($data);
            $idManajemenAsetPeminjaman = $this->db->insertID();
            // var_dump($idManajemenAsetPeminjaman);
            // die;
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
    
    public function addLoanUser() {
        $data = $this->request->getPost();
        $idRincianAset = $_POST['selectedRows'];


        if (!empty($data['asalPeminjam'])) {
            $this->requestPeminjamanModel->insert($data);
            $idRequestPeminjaman = $this->db->insertID();
            foreach ($idRincianAset as $idRincianAset) {
                $detailData = [
                    'idRincianAset' => $idRincianAset,
                    'idRequestPeminjaman' => $idRequestPeminjaman,
                ];
                $this->db->table('tblDetailRequestPeminjaman')->insert($detailData);
            }
            return redirect()->to(site_url('dataPrasaranaPeminjaman'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('dataPrasaranaPeminjaman'))->with('error', 'Semua field harus terisi');
        }
    }

    // public function addLoanUser() {
    //     $data = $this->request->getPost();
    //     $idRincianAset = $_POST['selectedRows'];
    //     $sectionAsetValue = 'Dipinjam';


    //     if (!empty($data['asalPeminjam'])) {
    //         $this->manajemenAsetPeminjamanModel->insert($data);
    //         $idManajemenAsetPeminjaman = $this->db->insertID();
    //         foreach ($idRincianAset as $idRincianAset) {
    //             $detailData = [
    //                 'idRincianAset' => $idRincianAset,
    //                 'idManajemenAsetPeminjaman' => $idManajemenAsetPeminjaman,
    //             ];
    //             $this->manajemenAsetPeminjamanModel->updateSectionAset($detailData, $sectionAsetValue);
    //             $this->db->table('tblDetailManajemenAsetPeminjaman')->insert($detailData);
    //         }
    //         return redirect()->to(site_url('dataPrasaranaPeminjaman'))->with('success', 'Data berhasil disimpan');
    //     } else {
    //         return redirect()->to(site_url('dataPrasaranaPeminjaman'))->with('error', 'Semua field harus terisi');
    //     }
    // }
}
