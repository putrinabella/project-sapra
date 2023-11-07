<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ManajemenPeminjamanModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\IdentitasLabModels; 
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
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data = [
            'dataManajemenPeminjaman' => $this->manajemenPeminjamanModel->getAll(),
            'dataLaboratorium' => $this->laboratoriumModel->getRuangan(),
        ];

        return view('labView/manajemenPeminjaman/index', $data);
    }
    
    public function new() {
        $data = [
            'dataIdentitasKelas' => $this->identitasKelasModel->findAll(),
            'dataPrasaranaLab' => $this->manajemenPeminjamanModel->getPrasaranaLab(),
            'dataSaranaLab' => $this->manajemenPeminjamanModel->getSaranaLab(),
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataIdentitasLab' => $this->identitasLabModel->findAll(),
        ];
        
        return view('labView/manajemenPeminjaman/new', $data);        
    }

    public function getKodeLab($idIdentitasSarana) {
        $data = $this->manajemenPeminjamanModel->getKodeLabData($idIdentitasSarana);
    
        echo json_encode($data);
    }
    
    public function show($id = null) {
        if ($id != null) {
            $dataLaboratorium = $this->laboratoriumModel->find($id);
    
            if (is_object($dataLaboratorium)) {
                $dataInfoLab = $this->laboratoriumModel->getIdentitasGedung($dataLaboratorium->idIdentitasLab);
                $dataInfoLab->namaLantai = $this->laboratoriumModel->getIdentitasLantai($dataLaboratorium->idIdentitasLab)->namaLantai;
                $dataSarana = $this->laboratoriumModel->getSaranaByLab($dataLaboratorium->idIdentitasLab);
                $asetBagus = $this->laboratoriumModel->getSaranaLayakCount($dataLaboratorium->idIdentitasLab);
                $data = [
                    'dataIdentitasKelas' => $this->identitasKelasModel->findAll(),
                    'dataLaboratorium'  => $dataLaboratorium,
                    'dataInfoLab'       => $dataInfoLab,
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