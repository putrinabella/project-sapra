<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ManajemenPeminjamanModels;
use App\Models\RequestPeminjamanModels;
use App\Models\IdentitasSaranaModels;
use App\Models\IdentitasLabModels;
use App\Models\KategoriPegawaiModels;
use App\Models\IdentitasKelasModels;
use App\Models\RincianLabAsetModels;
use App\Models\LaboratoriumModels;
use App\Models\DataSiswaModels;
use App\Models\DataPegawaiModels;
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
        $this->kategoriPegawaiModel = new KategoriPegawaiModels();
        $this->dataSiswaModel = new DataSiswaModels();
        $this->dataPegawaiModel = new DataPegawaiModels();
        $this->manajemenUserModel = new ManajemenUserModels();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'dataManajemenPeminjaman' => $this->manajemenPeminjamanModel->getAll(),
            'dataLaboratorium' => $this->laboratoriumModel->getRuangan(),
        ];

        return view('labView/manajemenPeminjaman/index', $data);
    }

    public function user()
    {
        $data = [
            'dataManajemenPeminjaman' => $this->manajemenPeminjamanModel->getAll(),
            'dataLaboratorium' => $this->laboratoriumModel->getRuangan(),
        ];

        return view('labView/manajemenPeminjaman/user', $data);
    }

    public function new()
    {
        $data = [
            'dataSiswa' => $this->identitasKelasModel->findAll(),
            'dataPrasaranaLab' => $this->manajemenPeminjamanModel->getPrasaranaLab(),
            'dataSaranaLab' => $this->manajemenPeminjamanModel->getSaranaLab(),
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataIdentitasLab' => $this->identitasLabModel->findAll(),
            'dataRincianLabAset' => $this->manajemenPeminjamanModel->getData()
        ];

        return view('labView/manajemenPeminjaman/new', $data);
    }

    public function loan($id)
    {
        $data = [
            'dataRincianLabAset' => $this->manajemenPeminjamanModel->getDataLoan($id),
            'dataSiswa' => $this->identitasKelasModel->findAll(),
            'dataPrasaranaLab' => $this->manajemenPeminjamanModel->getPrasaranaLab(),
            'dataSaranaLab' => $this->manajemenPeminjamanModel->getSaranaLab(),
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataIdentitasLab' => $this->identitasLabModel->findAll(),
            'namaLaboratorium' => $this->manajemenPeminjamanModel->getLabName($id),
        ];

        return view('labView/manajemenPeminjaman/new', $data);
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

        return view('labView/manajemenPeminjaman/newUser', $data);
    }

    public function getKodeLab($idIdentitasSarana)
    {
        $data = $this->manajemenPeminjamanModel->getKodeLabData($idIdentitasSarana);

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
            $dataIdentitasLab = $this->manajemenPeminjamanModel->getAllIdIdentitasLab();
            return $this->response->setJSON($dataIdentitasLab);
        } elseif ($filterJenis === 'sarana') {
            $dataIdentitasSarana = $this->manajemenPeminjamanModel->getAllIdIdentitasSarana();
            return $this->response->setJSON($dataIdentitasSarana);
        } else {
            return $this->response->setJSON([]);
        }
    }

    public function getSaranaByLab()
    {
        $selectedIdIdentitasLab = $this->request->getPost('idIdentitasLab');
        $idIdentitasSaranaOptions = $this->manajemenPeminjamanModel->getSaranaByLab($selectedIdIdentitasLab);
        return $this->response->setJSON($idIdentitasSaranaOptions);
    }

    public function getKodeBySarana()
    {
        $selectedIdIdentitasSarana = $this->request->getPost('idIdentitasSarana');
        $selectedIdIdentitasLab = $this->request->getPost('idIdentitasLab');
        $asalPeminjamOptions = $this->manajemenPeminjamanModel->getKodeBySarana($selectedIdIdentitasSarana, $selectedIdIdentitasLab);
        return $this->response->setJSON($asalPeminjamOptions);
    }



    public function getRincianLabAsetByLab()
    {
        if (!$this->request->isAJAX()) {
            exit('Direct access is not allowed');
        }

        $idIdentitasLab = $this->request->getPost('idIdentitasLab');

        $data = $this->manajemenPeminjamanModel->getSaranaByLabId($idIdentitasLab);

        return $this->response->setJSON($data);
    }

    public function show($id = null)
    {
        if ($id != null) {
            $dataLaboratorium = $this->laboratoriumModel->find($id);

            if (is_object($dataLaboratorium)) {
                $dataInfoLab = $this->laboratoriumModel->getIdentitasGedung($dataLaboratorium->idIdentitasLab);
                $dataInfoLab->namaLantai = $this->laboratoriumModel->getIdentitasLantai($dataLaboratorium->idIdentitasLab)->namaLantai;
                $dataSemuaSarana = $this->laboratoriumModel->getSaranaByLabId($dataLaboratorium->idIdentitasLab);
                $dataSarana = $this->laboratoriumModel->getSaranaByLab($dataLaboratorium->idIdentitasLab);
                $asetBagus = $this->laboratoriumModel->getSaranaLayakCount($dataLaboratorium->idIdentitasLab);
                $data = [
                    'dataSiswa' => $this->identitasKelasModel->findAll(),
                    'dataLaboratorium'  => $dataLaboratorium,
                    'dataInfoLab'       => $dataInfoLab,
                    'dataSemuaSarana'   => $dataSemuaSarana,
                    'dataSarana'        => $dataSarana,
                    'asetBagus'         => $asetBagus,
                ];
                return view('labView/manajemenPeminjaman/show', $data);
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
            $dataLaboratorium = $this->laboratoriumModel->find($id);

            if (is_object($dataLaboratorium)) {
                $dataInfoLab = $this->laboratoriumModel->getIdentitasGedung($dataLaboratorium->idIdentitasLab);
                $dataInfoLab->namaLantai = $this->laboratoriumModel->getIdentitasLantai($dataLaboratorium->idIdentitasLab)->namaLantai;
                $dataSemuaSarana = $this->laboratoriumModel->getSaranaByLabId($dataLaboratorium->idIdentitasLab);
                $dataSarana = $this->laboratoriumModel->getSaranaByLab($dataLaboratorium->idIdentitasLab);
                $asetBagus = $this->laboratoriumModel->getSaranaLayakCount($dataLaboratorium->idIdentitasLab);
                $data = [
                    'dataSiswa' => $this->identitasKelasModel->findAll(),
                    'dataLaboratorium'  => $dataLaboratorium,
                    'dataInfoLab'       => $dataInfoLab,
                    'dataSemuaSarana'   => $dataSemuaSarana,
                    'dataSarana'        => $dataSarana,
                    'asetBagus'         => $asetBagus,
                ];
                return view('labView/manajemenPeminjaman/showUser', $data);
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
            $dataLaboratorium = $this->laboratoriumModel->find($id);

            if (is_object($dataLaboratorium)) {

                $dataInfoLab = $this->laboratoriumModel->getIdentitasGedung($dataLaboratorium->idIdentitasLab);
                $dataInfoLab->namaLantai = $this->laboratoriumModel->getIdentitasLantai($dataLaboratorium->idIdentitasLab)->namaLantai;
                $dataSarana = $this->laboratoriumModel->getSaranaByLab($dataLaboratorium->idIdentitasLab);

                $data = [
                    'dataLaboratorium'  => $dataLaboratorium,
                    'dataInfoLab'       => $dataInfoLab,
                    'dataSarana'        => $dataSarana,
                ];

                $html = view('labView/manajemenPeminjaman/print', $data);

                $options = new Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isPhpEnabled', true);

                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $namaLab = $data['dataLaboratorium']->namaLab;
                $filename = "Laboratorium - $namaLab.pdf";
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
            return redirect()->to(site_url('peminjamanDataUser'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('peminjamanDataUser'))->with('error', 'Semua field harus terisi');
        }
    }

    // public function addLoanUser() {
    //     $data = $this->request->getPost();
    //     $idRincianLabAset = $_POST['selectedRows'];
    //     $sectionAsetValue = 'Dipinjam';


    //     if (!empty($data['asalPeminjam'])) {
    //         $this->manajemenPeminjamanModel->insert($data);
    //         $idManajemenPeminjaman = $this->db->insertID();
    //         foreach ($idRincianLabAset as $idRincianAset) {
    //             $detailData = [
    //                 'idRincianLabAset' => $idRincianAset,
    //                 'idManajemenPeminjaman' => $idManajemenPeminjaman,
    //             ];
    //             $this->manajemenPeminjamanModel->updateSectionAset($detailData, $sectionAsetValue);
    //             $this->db->table('tblDetailManajemenPeminjaman')->insert($detailData);
    //         }
    //         return redirect()->to(site_url('peminjamanDataUser'))->with('success', 'Data berhasil disimpan');
    //     } else {
    //         return redirect()->to(site_url('peminjamanDataUser'))->with('error', 'Semua field harus terisi');
    //     }
    // }
}
