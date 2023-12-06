<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\RincianAsetModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasPrasaranaModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class AsetGeneral extends ResourceController
{
    
    function __construct() {
        $this->rincianAsetModel = new RincianAsetModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->db = \Config\Database::connect();
        helper(['pdf']);
    }

    public function asetGeneral() {
        $dataGeneral = $this->rincianAsetModel->getDataBySarana();

        $jumlahTotal = 0;
        foreach ($dataGeneral as $value) {
            $jumlahTotal += $value->jumlahAset;
        }
    
        $data['dataGeneral'] = $dataGeneral;
        $data['jumlahTotal'] = $jumlahTotal;
        
        return view('saranaView/asetGeneral/view', $data);
    }

    public function asetGeneralDetail($id = null) {
        if ($id != null) {
            $detailAset = $this->identitasSaranaModel->find($id);
            if (is_object($detailAset)) {
                $data = [
                    'detailAset'        => $detailAset,
                    'asetGeneral'       => $this->rincianAsetModel->getDataBySaranaDetail($id),
                    'totalSarana'       => $this->rincianAsetModel->getTotalSarana($detailAset->idIdentitasSarana),
                    'saranaLayak'       => $this->rincianAsetModel->getSaranaLayak($detailAset->idIdentitasSarana),
                    'saranaRusak'       => $this->rincianAsetModel->getSaranaRusak($detailAset->idIdentitasSarana),
                    'saranaHilang'      => $this->rincianAsetModel->getSaranaHilang($detailAset->idIdentitasSarana),
                ];

                return view('saranaView/asetGeneral/detail', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function Export() {
        $data = $this->rincianAsetModel->getDataBySarana();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Data General');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Nama Aset', 'Total', 'Aset Bagus','Aset Rusak', 'Aset Hilang'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
        
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->jumlahAset);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->jumlahBagus);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->jumlahRusak);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->jumlahHilang);

            $columns = ['A', 'B', 'C', 'D', 'E', 'F'];
            
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $activeWorksheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'B') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    
                }
            }            
        }
        
        $activeWorksheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:F1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:F'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:F')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'F') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Sarana - Data General Aset.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function generatePDF() {
        $asetGeneral = $this->rincianAsetModel->getDataBySarana();
        // var_dump($asetGeneral);
        // die;
        $title = "REPORT DATA GENERAL ASET";
        if (!$asetGeneral) {
            return view('error/404');
        }
    
        $data = [
            'asetGeneral' => $asetGeneral,
        ];
    
        $pdfData = pdfAsetGeneral($asetGeneral, $title);
    
        
        $filename = 'Sarana - Data General Aset' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }
    
    public function generatePDF2() {
        $filePath = APPPATH . 'Views/saranaView/asetGeneral/printGeneral.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['asetGeneral'] = $this->rincianAsetModel->getDataBySarana();

        ob_start();

        $includeFile = function ($filePath, $data) {
            include $filePath;
        };
    
        $includeFile($filePath, $data);
    
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $filename = 'Sarana - Rincian Aset General Report.pdf';
        $dompdf->stream($filename);
    }
}
