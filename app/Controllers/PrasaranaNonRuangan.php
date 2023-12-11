<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PrasaranaNonRuanganModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasPrasaranaModels; 
use App\Models\IdentitasGedungModels; 
use App\Models\IdentitasLantaiModels; 
use App\Models\RincianAsetModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class PrasaranaNonRuangan extends ResourceController
{
    function __construct() {
        $this->prasaranaNonRuanganModel = new PrasaranaNonRuanganModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->identitasGedungModel = new IdentitasGedungModels();
        $this->identitasLantaiModel = new IdentitasLantaiModels();
        $this->rincianAsetModel = new RincianAsetModels();
        $this->db = \Config\Database::connect();
        helper(['pdf']);
    }

    public function index() {
        $data['dataPrasaranaNonRuangan'] = $this->prasaranaNonRuanganModel->getRuangan();
        return view('prasaranaView/nonRuangan/index', $data);
    }

    public function search() {
        if ($this->request->isAJAX()) {
            $namaPrasarana = $this->request->getVar('namaPrasarana');
            $result = $this->prasaranaNonRuanganModel->searchPrasarana($namaPrasarana);
            return $this->response->setJSON($result);
        }
    }
    
    public function show($id = null) {
        if ($id != null) {
            $dataPrasaranaNonRuangan = $this->prasaranaNonRuanganModel->find($id);
            
            if (is_object($dataPrasaranaNonRuangan)) {
                
                $dataInfoPrasarana = $this->prasaranaNonRuanganModel->getIdentitasGedung($dataPrasaranaNonRuangan->idIdentitasPrasarana);
                $dataInfoPrasarana->namaLantai = $this->prasaranaNonRuanganModel->getIdentitasLantai($dataPrasaranaNonRuangan->idIdentitasPrasarana)->namaLantai;
                $dataSarana = $this->prasaranaNonRuanganModel->getSaranaByPrasaranaId($dataPrasaranaNonRuangan->idIdentitasPrasarana);

                $data = [
                    'dataPrasaranaNonRuangan'  => $dataPrasaranaNonRuangan,
                    'dataInfoPrasarana'     => $dataInfoPrasarana,
                    'dataSarana'            => $dataSarana,
                    
                ];
                return view('prasaranaView/nonRuangan/show', $data);
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
            $dataRincianAset = $this->rincianAsetModel->find($id);
        
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
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];
                return view('prasaranaView/nonRuangan/showInfo', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }
    

    function print($id = null) {
        if ($id != null) {
            $dataPrasaranaRuangan = $this->prasaranaNonRuanganModel->find($id);
            $dataInfoPrasarana = $this->prasaranaNonRuanganModel->getIdentitasGedung($dataPrasaranaRuangan->idIdentitasPrasarana);
            $dataInfoPrasarana->namaLantai = $this->prasaranaNonRuanganModel->getIdentitasLantai($dataPrasaranaRuangan->idIdentitasPrasarana)->namaLantai;
            $dataSarana = $this->prasaranaNonRuanganModel->getSaranaByPrasaranaId($dataPrasaranaRuangan->idIdentitasPrasarana);
            $dataGeneral = $this->prasaranaNonRuanganModel->getDataBySarana($id);

            $pdfData = pdfAsetRuangan($dataPrasaranaRuangan, $dataInfoPrasarana, $dataGeneral);
        
            
            $filename = 'IT - Rincian Aset' . ".pdf";
            
            $response = $this->response;
            $response->setHeader('Content-Type', 'application/pdf');
            $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
            $response->setBody($pdfData);
            $response->send();
        } else {
            return view('error/404');
        }
    }
}