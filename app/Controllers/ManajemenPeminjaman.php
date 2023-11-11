<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ManajemenPeminjamanModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\IdentitasLabModels; 
use App\Models\KategoriPegawaiModels; 
use App\Models\IdentitasKelasModels; 
use App\Models\RincianLabAsetModels; 
use App\Models\LaboratoriumModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class ManajemenPeminjaman extends ResourceController
{
    
     function __construct() {
        $this->manajemenPeminjamanModel = new ManajemenPeminjamanModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->rincianLabAsetModel = new RincianLabAsetModels();
        $this->laboratoriumModel = new LaboratoriumModels();
        $this->identitasKelasModel = new IdentitasKelasModels();
        $this->kategoriPegawaiModel = new KategoriPegawaiModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data = [
            'dataManajemenPeminjaman' => $this->manajemenPeminjamanModel->getAll(),
            'dataLaboratorium' => $this->laboratoriumModel->getRuangan(),
        ];

        return view('labView/manajemenPeminjaman/index', $data);
    }
    
    public function newOLD() {
        $data = [
            'dataIdentitasKelas' => $this->identitasKelasModel->findAll(),
            'dataPrasaranaLab' => $this->manajemenPeminjamanModel->getPrasaranaLab(),
            'dataSaranaLab' => $this->manajemenPeminjamanModel->getSaranaLab(),
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataIdentitasLab' => $this->identitasLabModel->findAll(),
            
        ];
        
        return view('labView/manajemenPeminjaman/new', $data);        
    }
    
    public function new() {
        $data = [
            'dataIdentitasKelas' => $this->identitasKelasModel->findAll(),
            'dataPrasaranaLab' => $this->manajemenPeminjamanModel->getPrasaranaLab(),
            'dataSaranaLab' => $this->manajemenPeminjamanModel->getSaranaLab(),
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataIdentitasLab' => $this->identitasLabModel->findAll(),
            'dataRincianLabAset' => $this->manajemenPeminjamanModel->getData()
        ];
        
        return view('labView/manajemenPeminjaman/new', $data);        
    }

    public function getPeminjamanTabel() {
        $params['draw'] = $_REQUEST['draw'];
        $start = $_REQUEST['start'];
        $length = $_REQUEST['length'];
        $search_value = $_REQUEST['search']['value'];
        $orders = $_REQUEST['order'];
        $columns = $_REQUEST['columns'];
    
        $dataKategori = new ManajemenPeminjamanModels();
    
        // Check if sortingData is set
        $sortingData = isset($_REQUEST['sortingData']) ? $_REQUEST['sortingData'] : '';
    
        $data = $dataKategori->kategoriDisplay($search_value, $start, $length, $columns, $orders, $sortingData);
        $total_count = $dataKategori->kategoriDisplay($search_value);
    
        $result = array(
            'draw' => intval($params['draw']),
            'recordsTotal' => count($total_count),
            'recordsFiltered' => count($total_count),
            'data' => $data
        );
    
        header('Content-Type: application/json');
        echo json_encode($result);
        exit();
    }
    
    
    // public function getPeminjamanTabel() {
    //     $params['draw'] = $_REQUEST['draw'];
    //     $start = $_REQUEST['start'];
    //     $length = $_REQUEST['length'];
    //     $search_value = $_REQUEST['search']['value'];
    //     $orders = $_REQUEST['order'];
    //     $columns = $_REQUEST['columns'];
    
    //     $dataKategori = new ManajemenPeminjamanModels();
    //     $data = $dataKategori->kategoriDisplay($search_value, $start, $length, $columns, $orders);
    //     $total_count = $dataKategori->kategoriDisplay($search_value);

    //     $json_data = array(
    //         'draw' => intval($params['draw']),
    //         'recordsTotal' => count($total_count),
    //         'recordsFiltered' => count($total_count),
    //         'data' => $data
    //     );
    //     echo json_encode($json_data);
    // }
    

    public function getKodeLab($idIdentitasSarana) {
        $data = $this->manajemenPeminjamanModel->getKodeLabData($idIdentitasSarana);
    
        echo json_encode($data);
    }

    public function getRole() {
        $kategoriPeminjam = $this->request->getPost('kategoriPeminjam'); 
        if ($kategoriPeminjam === 'karyawan') {
            $dataIdentitasPegawai = $this->kategoriPegawaiModel->findAll();
            return $this->response->setJSON($dataIdentitasPegawai);
        } elseif ($kategoriPeminjam === 'siswa') {
            $dataIdentitasKelas = $this->identitasKelasModel->findAll();
            return $this->response->setJSON($dataIdentitasKelas);
        } else {
            return $this->response->setJSON([]);
        }
    }

    public function getFilterOptions() {
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
    
    public function getSaranaByLab() {
        $selectedIdIdentitasLab = $this->request->getPost('idIdentitasLab');
        $idIdentitasSaranaOptions = $this->manajemenPeminjamanModel->getSaranaByLab($selectedIdIdentitasLab);
        return $this->response->setJSON($idIdentitasSaranaOptions);
    }

    public function getKodeBySarana() {
        $selectedIdIdentitasSarana = $this->request->getPost('idIdentitasSarana');
        $selectedIdIdentitasLab = $this->request->getPost('idIdentitasLab'); 
        $kodeRincianLabAsetOptions = $this->manajemenPeminjamanModel->getKodeBySarana($selectedIdIdentitasSarana, $selectedIdIdentitasLab);
        return $this->response->setJSON($kodeRincianLabAsetOptions);
    }
    
    

    public function getRincianLabAsetByLab() {
        if (!$this->request->isAJAX()) {
            exit('Direct access is not allowed');
        }

        $idIdentitasLab = $this->request->getPost('idIdentitasLab');

        $data = $this->manajemenPeminjamanModel->getSaranaByLabId($idIdentitasLab);

        return $this->response->setJSON($data);
    }

    public function show($id = null) {
        if ($id != null) {
            $dataLaboratorium = $this->laboratoriumModel->find($id);
    
            if (is_object($dataLaboratorium)) {
                $dataInfoLab = $this->laboratoriumModel->getIdentitasGedung($dataLaboratorium->idIdentitasLab);
                $dataInfoLab->namaLantai = $this->laboratoriumModel->getIdentitasLantai($dataLaboratorium->idIdentitasLab)->namaLantai;
                $dataSemuaSarana = $this->laboratoriumModel->getSaranaByLabId($dataLaboratorium->idIdentitasLab);
                $dataSarana = $this->laboratoriumModel->getSaranaByLab($dataLaboratorium->idIdentitasLab);
                $asetBagus = $this->laboratoriumModel->getSaranaLayakCount($dataLaboratorium->idIdentitasLab);
                $data = [
                    'dataIdentitasKelas' => $this->identitasKelasModel->findAll(),
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
    
    function print($id = null) {
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

    // public function addLoan() {
    //     $data = $this->request->getPost(); 
    //     if (!empty($data['namaPeminjam']) && !empty($data['asalPeminjam']) && !empty($data['jumlah'])) {
    //         $this->manajemenPeminjamanModel->insert($data);
    //         return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Data berhasil disimpan');
    //     } else {
    //         return redirect()->to(site_url('manajemenPeminjaman'))->with('error', 'Semua field harus terisi');
    //     }
    // }

    // public function addLoan() {
    //     $data = $this->request->getPost(); 
    //     if (!empty($data['namaPeminjam']) && !empty($data['asalPeminjam']) && !empty($data['jumlah'])) {
    //         // Insert the loan data
    //         $this->manajemenPeminjamanModel->insert($data);
            
    //         // Update the sectionAset for the assets with the corresponding idRincianLabAset
    //         $idRincianLabAset = $data['idRincianLabAset'];
    //         $jumlah = $data['jumlah'];
            
    //         // Update the sectionAset for the asset(s) using the model
    //         $this->manajemenPeminjamanModel->updateSectionAset($idRincianLabAset, 'Dipinjam');
    
    //         return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Data berhasil disimpan');
    //     } else {
    //         return redirect()->to(site_url('manajemenPeminjaman'))->with('error', 'Semua field harus terisi');
    //     }
    // }


    // public function addLoan() {
    //     $data = $this->request->getPost();
    //     $idRincianLabAset = $data['idRincianLabAset'];
    //     $jumlah = $data['jumlah'];
  
    //     if (!empty($data['namaPeminjam']) && !empty($data['asalPeminjam']) && !empty($jumlah)) {
    //         // DIE THE INSERT FIRST
    //         // $this->manajemenPeminjamanModel->insert($data);
    
    //         $assetsToBorrow = $this->manajemenPeminjamanModel->getAssetsByIdRincianLabAset($idRincianLabAset, $jumlah);
    //         echo "Assets to Borrow:\n";
    //         print_r($assetsToBorrow);
    //         // Debugging: Output the number of assets retrieved
    //         echo "Assets to Borrow: " . count($assetsToBorrow) . " rows\n";
    //         die;
    //         // Update the sectionAset for all matching assets
    //         foreach ($assetsToBorrow as $asset) {
    //             // Debugging: Output the asset ID being updated
    //             echo "Updating asset ID: " . $asset['idRincianLabAset'] . "\n";
    
    //             $this->manajemenPeminjamanModel->updateSectionAset($asset['idRincianLabAset'], 'Dipinjam');
    //         }
    
    //         return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Data berhasil disimpan');
    //     } else {
    //         return redirect()->to(site_url('manajemenPeminjaman'))->with('error', 'Semua field harus terisi');
    //     }
    // }


    public function addLoan() {
        $data = $this->request->getPost();
        $idRincianLabAset = $_POST['selectedRows'];
        $sectionAsetValue = 'Dipinjam'; 
        
    
        if (!empty($data['namaPeminjam']) && !empty($data['asalPeminjam'])) {
            // DIE THE INSERT FIRST
            $this->manajemenPeminjamanModel->insert($data);
            $idManajemenPeminjaman = $this->db->insertID();
        
            // print_r($idManajemenPeminjaman);
            // die;
            foreach ($idRincianLabAset as $idRincianAset) {
                $detailData = [
                    'idRincianLabAset' => $idRincianAset,
                    'idManajemenPeminjaman' => $idManajemenPeminjaman,
                ];
                $this->manajemenPeminjamanModel->updateSectionAset($detailData, $sectionAsetValue);
                $this->db->table('tblDetailManajemenPeminjaman')->insert($detailData);
            }
            // foreach ($idRincianLabAset as $idRincianAset) {
            //     $detailData = [
            //         'idRincianLabAset' => $idRincianAset,
            //         'idManajemenPeminjaman' => $idManajemenPeminjaman,
            //     ];
            //     $this->manajemenPeminjamanModel->updateSectionAset($detailData, $sectionAsetValue);
            //     // $this->db->table('tblDetailManajemenPeminjaman')->insert($detailData);
            // }
            
            // $this->manajemenPeminjamanModel->updateSectionAset($idRincianLabAset, $sectionAsetValue);

            // $assetsToBorrow = $this->manajemenPeminjamanModel->getBorrowItems($idIdentitasSarana, $jumlah, $idIdentitasLab);
            // foreach ($assetsToBorrow as $asset) {
            //     $this->manajemenPeminjamanModel->updateSectionAset($idIdentitasSarana, $sectionAsetValue, $idIdentitasLab, $jumlah, $idManajemenPeminjaman);
            // }    
            return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('manajemenPeminjaman'))->with('error', 'Semua field harus terisi');
        }
    }
    
    /// BISA TAPI SALAH
    public function addLoanworkworkworkjangadihapuas() {
        $data = $this->request->getPost();
        $idRincianLabAset = $data['idRincianLabAset'];
        $idIdentitasSarana = $data['idIdentitasSarana'];
        $idIdentitasLab = $data['idIdentitasLab'];
        $jumlah = $data['jumlah'];
        $sectionAsetValue = 'Dipinjam'; 
        
    
        if (!empty($data['namaPeminjam']) && !empty($data['asalPeminjam']) && !empty($jumlah)) {
            // DIE THE INSERT FIRST
            $this->manajemenPeminjamanModel->insert($data);
            $idManajemenPeminjaman = $this->db->insertID();
        
            // print_r($idManajemenPeminjaman);
            // die;
            $assetsToBorrow = $this->manajemenPeminjamanModel->getBorrowItems($idIdentitasSarana, $jumlah, $idIdentitasLab);
            foreach ($assetsToBorrow as $asset) {
                $this->manajemenPeminjamanModel->updateSectionAset($idIdentitasSarana, $sectionAsetValue, $idIdentitasLab, $jumlah, $idManajemenPeminjaman);
            }    
            return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('manajemenPeminjaman'))->with('error', 'Semua field harus terisi');
        }
    }
    
// WORK BUT BUT THAT ONLY ONE DATA SET TO DIPINJAM
    // public function addLoan() {
    //     $data = $this->request->getPost();
    //     $idRincianLabAset = $data['idRincianLabAset'];
    //     $jumlah = $data['jumlah'];
    
    //     if (!empty($data['namaPeminjam']) && !empty($data['asalPeminjam']) && !empty($jumlah)) {
    //         $this->manajemenPeminjamanModel->insert($data);
    
    //         $assetsToBorrow = $this->manajemenPeminjamanModel->getAssetsByIdRincianLabAset($idRincianLabAset);
    
    //         $count = 0;
    //         foreach ($assetsToBorrow as $asset) {
    //             if ($count < $jumlah) {
    //                 $this->manajemenPeminjamanModel->updateSectionAset($asset['idRincianLabAset'], 'Dipinjam');
    //                 $count++;
    //             }
    //         }
    
    //         return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Data berhasil disimpan');
    //     } else {
    //         return redirect()->to(site_url('manajemenPeminjaman'))->with('error', 'Semua field harus terisi');
    //     }
    // }
    
    
}