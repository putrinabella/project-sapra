<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\RincianLabAsetModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasLabModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class PemusnahanLabAset extends ResourceController
{
    function __construct() {
        $this->rincianLabAsetModel = new RincianLabAsetModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->db = \Config\Database::connect();
        helper(['pdf']);
    }


    public function index() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $formattedStartDate = !empty($startDate) ? date('d F Y', strtotime($startDate)) : '';
        $formattedEndDate = !empty($endDate) ? date('d F Y', strtotime($endDate)) : '';

        $tableHeading = "";
        if (!empty($formattedStartDate) && !empty($formattedEndDate)) {
            $tableHeading = " $formattedStartDate - $formattedEndDate";
        }
        

        $data['tableHeading'] = $tableHeading;
        $data['dataRincianLabAset'] = $this->rincianLabAsetModel->getDestroy($startDate, $endDate);

        return view('labView/pemusnahanLabAset/index', $data);
    }

    
    public function show($id = null) {
        if ($id != null) {
            $dataRincianLabAset = $this->rincianLabAsetModel->find($id);
        
            if (is_object($dataRincianLabAset)) {
                $spesifikasiMarkup = $dataRincianLabAset->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataRincianLabAset->bukti);
                $qrCodeData = $this->generateQRCode($dataRincianLabAset->kodeRincianLabAset);

                $data = [
                    'dataRincianLabAset'           => $dataRincianLabAset,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasLab'    => $this->identitasLabModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                    'qrCodeData'                => $qrCodeData
                ];
                return view('labView/pemusnahanLabAset/show', $data);
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

    public function generateQRCode($kodeRincianLabAset)    {
        $writer = new PngWriter();
        $qrCode = QrCode::create($kodeRincianLabAset)
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
    
    public function edit($id = null) {
        if ($id != null) {
            $dataRincianLabAset = $this->rincianLabAsetModel->find($id);
    
            if (is_object($dataRincianLabAset)) {
                $data = [
                    'dataRincianLabAset' => $dataRincianLabAset,
                    'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana' => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                ];
                return view('labView/pemusnahanLabAset/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    
    public function update($id = null) {
        if ($id != null) {
            $data = $this->request->getPost();
            $this->rincianLabAsetModel->update($id, $data);
            return redirect()->to(site_url('pemusnahanLabAset'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }

    
    public function destruction($idRincianLabAset = null) {
        if ($this->request->getMethod(true) === 'POST') {
            $newSectionAset = $this->request->getPost('sectionAset');
            $namaAkun = $this->request->getPost('namaAkun'); 
            $kodeAkun = $this->request->getPost('kodeAkun'); 
    
            if ($this->rincianLabAsetModel->updateSectionAset($idRincianLabAset, $newSectionAset, $namaAkun, $kodeAkun)) {
                if ($newSectionAset === 'Dimusnahkan') {
                    return redirect()->to(site_url('pemusnahanLabAset'))->with('success', 'Aset berhasil dimusnahkan');
                } elseif ($newSectionAset === 'None') {
                    return redirect()->to(site_url('rincianLabAset'))->with('success', 'Aset berhasil dikembalikan');
                }
            } else {
                return redirect()->to(site_url('rincianLabAset'))->with('error', 'Aset batal dimusnahkan');
            }
        }
    }

    public function export() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $formattedStartDate = !empty($startDate) ? date('d F Y', strtotime($startDate)) : '';
        $formattedEndDate = !empty($endDate) ? date('d F Y', strtotime($endDate)) : '';
        
        $data = $this->rincianLabAsetModel->getDestroy($startDate, $endDate);
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Pemusnahan Aset');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Tanggal Pemusnahan',  'Nama Akun',  'Kode Akun', 'Kode Aset', 'Lokasi', 'Kategori','Nama Aset', 'Status', 'Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Bukti', 'Keterangan'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:Q1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggalPemusnahan));
            
            $pengadaan = $value->tahunPengadaan;
            if ($pengadaan == 0 || 0000) {
                $pengadaan = "Tidak Diketahui";
            } else {
                $pengadaan = $value->tahunPengadaan;
            }
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $date);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaAkun);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->kodeAkun);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->kodeRincianLabAset);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaLab);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->namaKategoriManajemen);
            $activeWorksheet->setCellValue('H'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('I'.($index + 2), $value->status);
            $activeWorksheet->setCellValue('J'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('K'.($index + 2), $pengadaan);
            $activeWorksheet->setCellValue('L'.($index + 2), $value->biaya);
            $activeWorksheet->setCellValue('M'.($index + 2), $value->merk);
            $activeWorksheet->setCellValue('N'.($index + 2), $value->noSeri);
            $activeWorksheet->setCellValue('O'.($index + 2), $value->warna);
            $linkValue = $value->bukti; 
            $linkTitle = 'Click here'; 
            $hyperlinkFormula = '=HYPERLINK("' . $linkValue . '", "' . $linkTitle . '")';
            $activeWorksheet->setCellValue('P'.($index + 2), $hyperlinkFormula);
            $activeWorksheet->setCellValue('Q' . ($index + 2), $value->spesifikasi);
            $activeWorksheet->getStyle('Q' . ($index + 2))->getAlignment()->setWrapText(true);
        
            $activeWorksheet->getStyle('L' . ($index + 2))->getNumberFormat()->setFormatCode("Rp#,##0");
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I' ,'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q' ];
            
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $activeWorksheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }              
        }

        $activeWorksheet->getStyle('A1:Q1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:Q1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:Q'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:Q')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'Q') as $column) {
            if ($column === 'P') {
                $activeWorksheet->getColumnDimension($column)->setWidth(20);
            } else if ($column === 'Q') {
                $activeWorksheet->getColumnDimension($column)->setWidth(40); 
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Sarana - Pemusnahan Aset.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function GeneratePDF() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
        $dataPemusnahanLabAset = $this->rincianLabAsetModel->getDestroy($startDate, $endDate);
        
        $title = "REPORT PEMUSNAHAN ASET";
        if (!$dataPemusnahanLabAset) {
            return view('error/404');
        }
    
        $data = [
            'dataPemusnahanLabAset' => $dataPemusnahanLabAset,
        ];
    
        $pdfData = pdfPemusnahanLabAset($dataPemusnahanLabAset, $title, $startDate, $endDate);
    
        
        $filename = 'Sarana - Pemusnahan Aset' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }

    
}
