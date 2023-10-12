<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\LaboratoriumModels; 
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

class Laboratorium extends ResourceController
{
    
     function __construct() {
        $this->prasaranaRuanganModel = new LaboratoriumModels();
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
        $data['dataLaboratorium'] = $this->prasaranaRuanganModel->getRuangan();
        return view('prasaranaView/ruangan/index', $data);
    }
    
    public function show($id = null) {
        if ($id != null) {
            $dataLaboratorium = $this->prasaranaRuanganModel->find($id);
            
            if (is_object($dataLaboratorium)) {
                
                $dataInfoPrasarana = $this->prasaranaRuanganModel->getIdentitasGedung($dataLaboratorium->idIdentitasPrasarana);
                $dataInfoPrasarana->namaLantai = $this->prasaranaRuanganModel->getIdentitasLantai($dataLaboratorium->idIdentitasPrasarana)->namaLantai;
                $dataSarana = $this->prasaranaRuanganModel->getSaranaByPrasaranaId($dataLaboratorium->idIdentitasPrasarana);

                $data = [
                    'dataLaboratorium'  => $dataLaboratorium,
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
    
    
    private function htmlConverter($html) {
        $plainText = strip_tags(str_replace('<br />', "\n", $html));
        $plainText = preg_replace('/\n+/', "\n", $plainText);
        return $plainText;  
    }
    
    public function export() {
        $data = $this->prasaranaRuanganModel->getAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Rincian Aset');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Kode Aset', 'Nama Aset', 'Lokasi','Tahun Pengadaan', 'Kategori Manajemen', 'Sumber Dana', 'Aset Layak', 'Aset Rusak', 'Total Aset' , 'Link Dokumentasi', 'Spesifikasi'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:Q1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $spesifikasiMarkup = $value->spesifikasi; 
            $parsedown = new Parsedown();
            $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
            $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
            
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->kodeLaboratorium);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->namaPrasarana);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->tahunPengadaan);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaKategoriManajemen);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('H'.($index + 2), $value->saranaLayak);
            $activeWorksheet->setCellValue('I'.($index + 2), $value->saranaRusak);
            $activeWorksheet->setCellValue('J'.($index + 2), $value->totalSarana);
            $activeWorksheet->setCellValue('K'.($index + 2), $value->bukti);
            $activeWorksheet->setCellValue('L'.($index + 2), $spesifikasiText);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I' ,'J', 'K'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }
        $activeWorksheet->getStyle('L')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $activeWorksheet->getStyle('L')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        
        $activeWorksheet->getStyle('A1:Q1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:Q1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:L'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:L')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'L') as $column) {
            if ($column === 'K') {
                $activeWorksheet->getColumnDimension($column)->setWidth(20);
            } else if ($column === 'L') {
                $activeWorksheet->getColumnDimension($column)->setWidth(40); 
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }

        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Rincian Aset.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    function print($id = null) {
        if ($id != null) {
            $dataLaboratorium = $this->prasaranaRuanganModel->find($id);
    
            if (is_object($dataLaboratorium)) {
    
                $dataInfoPrasarana = $this->prasaranaRuanganModel->getIdentitasGedung($dataLaboratorium->idIdentitasPrasarana);
                $dataInfoPrasarana->namaLantai = $this->prasaranaRuanganModel->getIdentitasLantai($dataLaboratorium->idIdentitasPrasarana)->namaLantai;
                $dataSarana = $this->prasaranaRuanganModel->getSaranaByPrasaranaId($dataLaboratorium->idIdentitasPrasarana);
    
                $data = [
                    'dataLaboratorium'  => $dataLaboratorium,
                    'dataInfoPrasarana'     => $dataInfoPrasarana,
                    'dataSarana'            => $dataSarana,
                ];
    
                $html = view('prasaranaView/Ruangan/print', $data); 
    
                $options = new Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isPhpEnabled', true);
    
                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $namaPrasarana = $data['dataLaboratorium']->namaPrasarana;
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