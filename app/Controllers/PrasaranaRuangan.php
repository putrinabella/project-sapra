<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PrasaranaRuanganModels; 
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

class PrasaranaRuangan extends ResourceController
{
    
     function __construct() {
        $this->prasaranaRuanganModel = new PrasaranaRuanganModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->identitasGedungModel = new IdentitasGedungModels();
        $this->identitasLantaiModel = new IdentitasLantaiModels();
        $this->rincianAsetModel = new RincianAsetModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataPrasaranaRuangan'] = $this->prasaranaRuanganModel->getRuangan();
        return view('prasaranaView/ruangan/index', $data);
    }
    
    public function show($id = null) {
        if ($id != null) {
            $dataPrasaranaRuangan = $this->prasaranaRuanganModel->find($id);
            
            if (is_object($dataPrasaranaRuangan)) {
                
                $dataInfoPrasarana = $this->prasaranaRuanganModel->getIdentitasGedung($dataPrasaranaRuangan->idIdentitasPrasarana);
                $dataInfoPrasarana->namaLantai = $this->prasaranaRuanganModel->getIdentitasLantai($dataPrasaranaRuangan->idIdentitasPrasarana)->namaLantai;
                $dataSarana = $this->prasaranaRuanganModel->getSaranaByPrasaranaId($dataPrasaranaRuangan->idIdentitasPrasarana);

                $data = [
                    'dataPrasaranaRuangan'  => $dataPrasaranaRuangan,
                    'dataInfoPrasarana'     => $dataInfoPrasarana,
                    'dataSarana'            => $dataSarana,
                    
                ];
                return view('prasaranaView/ruangan/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
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
                return view('prasaranaView/ruangan/showInfo', $data);
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
    
    function print($id = null) {
        if ($id != null) {
            $dataPrasaranaRuangan = $this->prasaranaRuanganModel->find($id);
    
            if (is_object($dataPrasaranaRuangan)) {
    
                $dataInfoPrasarana = $this->prasaranaRuanganModel->getIdentitasGedung($dataPrasaranaRuangan->idIdentitasPrasarana);
                $dataInfoPrasarana->namaLantai = $this->prasaranaRuanganModel->getIdentitasLantai($dataPrasaranaRuangan->idIdentitasPrasarana)->namaLantai;
                $dataSarana = $this->prasaranaRuanganModel->getSaranaByPrasaranaId($dataPrasaranaRuangan->idIdentitasPrasarana);
    
                $data = [
                    'dataPrasaranaRuangan'  => $dataPrasaranaRuangan,
                    'dataInfoPrasarana'     => $dataInfoPrasarana,
                    'dataSarana'            => $dataSarana,
                ];
    
                $html = view('prasaranaView/Ruangan/print', $data); 
    
                $options = new Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isPhpEnabled', true);
    
                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();
                $namaPrasarana = $data['dataPrasaranaRuangan']->namaPrasarana;
                $filename = "Prasarana - $namaPrasarana.pdf";
                $dompdf->stream($filename);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

}