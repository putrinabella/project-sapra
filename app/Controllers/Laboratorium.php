<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\LaboratoriumModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasLabModels; 
use App\Models\IdentitasGedungModels; 
use App\Models\IdentitasLantaiModels; 
use App\Models\RincianLabAsetModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class Laboratorium extends ResourceController
{
    
     function __construct() {
        $this->laboratoriumModel = new LaboratoriumModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->identitasGedungModel = new IdentitasGedungModels();
        $this->identitasLantaiModel = new IdentitasLantaiModels();
        $this->rincianLabAsetModel = new RincianLabAsetModels();
        $this->db = \Config\Database::connect();
        helper(['pdf']);
    }

    public function index() {
        $data['dataLaboratorium'] = $this->laboratoriumModel->getRuangan();
        return view('labView/laboratorium/index', $data);
    }

    public function search() {
        if ($this->request->isAJAX()) {
            $namaLab = $this->request->getVar('namaLab');
            $result = $this->laboratoriumModel->searchLab($namaLab);
            return $this->response->setJSON($result);
        }
    }
    
    public function show($id = null) {
        if ($id != null) {
            $dataLaboratorium = $this->laboratoriumModel->find($id);
            if (is_object($dataLaboratorium)) {
                $idIdentitasLab = $dataLaboratorium->idIdentitasLab;
                $dataInfoLab = $this->laboratoriumModel->getIdentitasGedung($dataLaboratorium->idIdentitasLab);
                $dataInfoLab->namaLantai = $this->laboratoriumModel->getIdentitasLantai($dataLaboratorium->idIdentitasLab)->namaLantai;
                $dataSarana = $this->laboratoriumModel->getSaranaByLabId($dataLaboratorium->idIdentitasLab);
                $data = [
                    'dataLaboratorium'  => $dataLaboratorium,
                    'dataInfoLab'       => $dataInfoLab,
                    'dataSarana'        => $dataSarana,
                    'dataRincianLabAsetCount' => $this->laboratoriumModel->countDataByIdentitasLab($idIdentitasLab),  
                ];
                $data['dataRincianLabAset'] = $this->rincianLabAsetModel->getAll();
                $data['jumlahAset'] = $this->laboratoriumModel->countDataByIdentitasLab($idIdentitasLab);
                return view('labView/laboratorium/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }
    
    function print($id = null) {
        if ($id != null) {
            $dataLabRuangan = $this->laboratoriumModel->find($id);
            $dataInfoLab = $this->laboratoriumModel->getIdentitasGedung($dataLabRuangan->idIdentitasLab);
            $dataInfoLab->namaLantai = $this->laboratoriumModel->getIdentitasLantai($dataLabRuangan->idIdentitasLab)->namaLantai;
            $dataSarana = $this->laboratoriumModel->getSaranaByLabId($dataLabRuangan->idIdentitasLab);
            $dataGeneral = $this->laboratoriumModel->getDataBySarana($id);

            $pdfData = pdfAsetLaboratorium($dataLabRuangan, $dataInfoLab, $dataGeneral);
        
            $namaLaboratorium = $dataLabRuangan->namaLab;
            $filename = $namaLaboratorium. ' - Daftar Aset' . ".pdf";
            
            $response = $this->response;
            $response->setHeader('Content-Type', 'application/pdf');
            $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
            $response->setBody($pdfData);
            $response->send();
        } else {
            return view('error/404');
        }
    }

    function print2($id = null) {
        if ($id != null) {
            $dataLaboratorium = $this->laboratoriumModel->find($id);
    
            if (is_object($dataLaboratorium)) {
    
                $dataInfoLab = $this->laboratoriumModel->getIdentitasGedung($dataLaboratorium->idIdentitasLab);
                $dataInfoLab->namaLantai = $this->laboratoriumModel->getIdentitasLantai($dataLaboratorium->idIdentitasLab)->namaLantai;
                $dataSarana = $this->laboratoriumModel->getSaranaByLabId($dataLaboratorium->idIdentitasLab);
    
                $data = [
                    'dataLaboratorium'  => $dataLaboratorium,
                    'dataInfoLab'       => $dataInfoLab,
                    'dataSarana'        => $dataSarana,
                ];
    
                $html = view('labView/laboratorium/print', $data); 
    
                $options = new Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isPhpEnabled', true);
    
                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
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

    private function htmlConverter($html) {
        $plainText = strip_tags(str_replace('<br />', "\n", $html));
        $plainText = preg_replace('/\n+/', "\n", $plainText);
        return $plainText;  
    }
    
    private function generateFileId($url) {
        preg_match('/\/file\/d\/(.*?)\//', $url, $matches);
        
        if (isset($matches[1])) {
            $fileId = $matches[1];
            return "https://drive.google.com/uc?export=view&id=" . $fileId;
        } else {
            return "Invalid Google Drive URL";
        }
    }

    public function showInfo($id = null) {
        if ($id != null) {
            $dataRincianAset = $this->rincianLabAsetModel->find($id);
        
            if (is_object($dataRincianAset)) {
                $spesifikasiMarkup = $dataRincianAset->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataRincianAset->bukti);
                $data = [
                    'dataRincianAset'           => $dataRincianAset,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasLab'          => $this->identitasLabModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];
                return view('labView/laboratorium/showInfo', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }
    
}