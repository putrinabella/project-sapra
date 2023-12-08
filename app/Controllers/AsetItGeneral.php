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

class AsetItGeneral extends ResourceController
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

    public function view() {
        $dataGeneral = $this->rincianAsetModel->getDataItBySarana();

        $jumlahTotal = 0;
        foreach ($dataGeneral as $value) {
            $jumlahTotal += $value->jumlahAset;
        }
    
        $data['dataGeneral'] = $dataGeneral;
        $data['jumlahTotal'] = $jumlahTotal;
        
        return view('itView/asetItGeneral/view', $data);
    }

    public function show($id = null) {
        if ($id != null) {
            $detailAset = $this->identitasSaranaModel->find($id);
            if (is_object($detailAset)) {
                $data = [
                    'detailAset'        => $detailAset,
                    'asetItGeneral'       => $this->rincianAsetModel->getDataItBySaranaDetail($id),
                    'totalSarana'       => $this->rincianAsetModel->getTotalItSarana($detailAset->idIdentitasSarana),
                    'saranaLayak'       => $this->rincianAsetModel->getSaranaItLayak($detailAset->idIdentitasSarana),
                    'saranaRusak'       => $this->rincianAsetModel->getSaranaItRusak($detailAset->idIdentitasSarana),
                    'saranaHilang'      => $this->rincianAsetModel->getSaranaItHilang($detailAset->idIdentitasSarana),
                ];

                return view('itView/asetItGeneral/detail', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function info($id = null) {
        if ($id != null) {
            $dataRincianAset = $this->rincianAsetModel->find($id);
        
            if (is_object($dataRincianAset)) {
                $buktiUrl = $this->generateFileId($dataRincianAset->bukti);
                $qrCodeData = $this->generateQRCode($dataRincianAset->kodeRincianAset);

                $data = [
                    'dataRincianAset'           => $dataRincianAset,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'qrCodeData'                => $qrCodeData
                ];
                return view('itView/asetItGeneral/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function Export() {
        $data = $this->rincianAsetModel->getDataItBySarana();
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
        header('Content-Disposition: attachment;filename=IT - Data General Aset.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function generatePDF() {
        $asetItGeneral = $this->rincianAsetModel->getDataItBySarana();
        // var_dump($asetItGeneral);
        // die;
        $title = "REPORT DATA GENERAL ASET IT";
        if (!$asetItGeneral) {
            return view('error/404');
        }
    
        $data = [
            'asetItGeneral' => $asetItGeneral,
        ];
    
        $pdfData = pdfAsetItGeneral($asetItGeneral, $title);
    
        
        $filename = 'IT - Data General Aset' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
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
    
    public function generateQRCode($kodeRincianAset)    {
        $writer = new PngWriter();
        $qrCode = QrCode::create($kodeRincianAset)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $result = $writer->write($qrCode);

        $dataUrl = $result->getDataUri();

        return $dataUrl;
    }
}
