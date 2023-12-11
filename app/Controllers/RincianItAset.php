<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\RincianAsetModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasPrasaranaModels; 
use App\Models\UserActionLogsModels; 
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

class RincianItAset extends ResourceController
{
    
    function __construct() {
        $this->rincianAsetModel = new RincianAsetModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['pdf', 'custom']);
    }

    
    public function generateAndSetKodeRincianItAset() {

        $dataRincianItAset = $this->rincianAsetModel->getItAll();

        foreach ($dataRincianItAset as $data) {
            $newKodeRincianItAset = $this->generateKodeRincianItAset(
                $data->idKategoriManajemen,
                $data->idIdentitasPrasarana,
                $data->idSumberDana,
                $data->idIdentitasSarana,
                $data->tahunPengadaan,
                $data->nomorBarang
            );

            $this->rincianAsetModel->updateKodeRincianAset($data->idRincianAset, $newKodeRincianItAset);
        }
        return redirect()->to(site_url('rincianItAset'))->with('success', 'Berhasil generate kode aset');
    }

    public function generateKodeRincianItAset($idKategoriManajemen, $idIdentitasPrasarana, $idSumberDana, $idIdentitasSarana, $tahunPengadaan, $nomorBarang) {
        $kodeKategoriManajemen = $this->kategoriManajemenModel->getKodeKategoriManajemenById($idKategoriManajemen);
        $kodePrasarana = $this->identitasPrasaranaModel->getKodePrasaranaById($idIdentitasPrasarana);
        $kodeSumberDana = $this->sumberDanaModel->getKodeSumberDanaById($idSumberDana);
        $kodeSarana = $this->identitasSaranaModel->getKodeSaranaById($idIdentitasSarana);

        if ($tahunPengadaan === '0000' || $tahunPengadaan === '0') {
            $tahunPengadaan = 'xx';
        } else {
            $tahunPengadaan = substr($tahunPengadaan, -2);
        }

        $nomorBarang = str_pad($nomorBarang, 3, '0', STR_PAD_LEFT);
        $kodePrasarana = substr($kodePrasarana, -2);

        $kodeRincianAset = 'TS-BJB ' . $kodeKategoriManajemen . ' ' . $kodePrasarana . ' ' . $kodeSumberDana . ' ' . $tahunPengadaan . ' ' . $kodeSarana . ' ' . $nomorBarang;

        return $kodeRincianAset;
    }

    public function generateKode() {
        $idKategoriManajemen = $this->request->getPost('idKategoriManajemen');
        $idIdentitasPrasarana = $this->request->getPost('idIdentitasPrasarana');
        $idSumberDana = $this->request->getPost('idSumberDana');
        $tahunPengadaan = $this->request->getPost('tahunPengadaan');
        $idIdentitasSarana = $this->request->getPost('idIdentitasSarana');
        $nomorBarang = $this->request->getPost('nomorBarang');

        $kodeKategoriManajemen = $this->kategoriManajemenModel->getKodeKategoriManajemenById($idKategoriManajemen);
        $kodePrasarana = $this->identitasPrasaranaModel->getKodePrasaranaById($idIdentitasPrasarana);
        $kodeSumberDana = $this->sumberDanaModel->getKodeSumberDanaById($idSumberDana);
        $kodeSarana = $this->identitasSaranaModel->getKodeSaranaById($idIdentitasSarana);

        if ($tahunPengadaan === '0000') {
            $tahunPengadaan = 'xx';
        } else {
            $tahunPengadaan = substr($tahunPengadaan, -2);
        }
        $kodePrasarana = substr($kodePrasarana, -2);
        $nomorBarang = str_pad($nomorBarang, 3, '0', STR_PAD_LEFT);

        
        $kodeRincianAset = 'TS-BJB ' . $kodeKategoriManajemen . ' ' . $kodePrasarana . ' ' . $kodeSumberDana . ' ' . $tahunPengadaan . ' ' . $kodeSarana . ' ' . $nomorBarang;
        echo json_encode($kodeRincianAset);
    }

    public function checkDuplicate() {
        $kodeRincianAset = $this->request->getPost('kodeRincianAset');
    
        $isDuplicate = $this->rincianAsetModel->isDuplicate($kodeRincianAset);
    
        echo json_encode(['isDuplicate' => $isDuplicate]);
    }
    

    public function index() {
        $data['dataRincianItAset'] = $this->rincianAsetModel->getItData();
        return view('itView/rincianItAset/index', $data);
    }
    
    // For generate QR Code 
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

    public function show($id = null) {
        if ($id != null) {
            $dataRincianItAset = $this->rincianAsetModel->find($id);
        
            if (is_object($dataRincianItAset)) {
                $spesifikasiMarkup = $dataRincianItAset->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataRincianItAset->bukti);
                $qrCodeData = $this->generateQRCode($dataRincianItAset->kodeRincianAset);

                $data = [
                    'dataRincianItAset'           => $dataRincianItAset,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->getDataIt(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                    'qrCodeData'                => $qrCodeData
                ];
                return view('itView/rincianItAset/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }


    public function new() {
        $data = [
            'dataIdentitasSarana' => $this->identitasSaranaModel->getDataIt(),
            'dataSumberDana' => $this->sumberDanaModel->findAll(),
            'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
            'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
        ];
        
        return view('itView/rincianItAset/new', $data);        
    }
    
    public function create() {
        $data = $this->request->getPost();
        if (!empty($data['idIdentitasSarana']) && !empty($data['tahunPengadaan']) && !empty($data['idSumberDana']) && !empty($data['idIdentitasPrasarana'])) {
            $this->rincianAsetModel->insert($data);
            
            return redirect()->to(site_url('rincianItAset'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('rincianItAset'))->with('error', 'Semua field harus terisi');
        }
    }
    
    private function uploadFile($fieldName) {
        $file = $this->request->getFile($fieldName);
        if ($file !== null) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(ROOTPATH . 'public/uploads', $newName);
                return 'uploads/' . $newName;
            } else {
                return null;
            }
        } else {
            return null;
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
    
    public function update($id = null) {
        if ($id != null) {
            $data = $this->request->getPost();
                $this->rincianAsetModel->update($id, $data);
                return redirect()->to(site_url('rincianItAset'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }
    
    public function edit($id = null) {
        if ($id != null) {
            $dataRincianItAset = $this->rincianAsetModel->find($id);
    
            if (is_object($dataRincianItAset)) {
                $data = [
                    'dataRincianItAset' => $dataRincianItAset,
                    'dataIdentitasSarana' => $this->identitasSaranaModel->getDataIt(),
                    'dataSumberDana' => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
                ];
                return view('itView/rincianItAset/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

 
    public function delete($id = null) {
        $this->rincianAsetModel->delete($id);
        return redirect()->to(site_url('rincianItAset'));
    }

    public function trash() {
        $data['dataRincianItAset'] = $this->rincianAsetModel->onlyDeleted()->getItRecycle();
        return view('itView/rincianItAset/trash', $data);
    } 

    public function restore($id = null) {
        $affectedRows = restoreData('tblRincianAset', 'idRincianAset', $id, $this->userActionLogsModel, 'IT - Rincian Aset');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('rincianItAset'))->with('success', 'Data berhasil direstore');
        }
    
        return redirect()->to(site_url('rincianItAset/trash'))->with('error', 'Tidak ada data untuk direstore');
    }

    public function deletePermanent($id = null) {
        $affectedRows = deleteData('tblRincianAset', 'idRincianAset', $id, $this->userActionLogsModel, 'IT - Rincian Aset');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('rincianItAset'))->with('success', 'Data berhasil dihapus');
        } 
    
        return redirect()->to(site_url('rincianItAset/trash'))->with('error', 'Tidak ada data untuk dihapus');
    }

    private function htmlConverter($html) {
        $plainText = strip_tags(str_replace('<br />', "\n", $html));
        $plainText = preg_replace('/\n+/', "\n", $plainText);
        return $plainText;  
    }
    
    public function export() {
        $data = $this->rincianAsetModel->getItAll();
        $dataAsetItBagus = $this->rincianAsetModel->getDataItBagus();
        $dataAsetItRusak = $this->rincianAsetModel->getDataItRusak();
        $dataAsetItHilang = $this->rincianAsetModel->getDataItHilang();

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Data Aset');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Kode Aset', 'Lokasi', 'Kategori Barang','Spesifikasi Barang', 'Status', 'Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Spesifikasi', 'Bukti'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:N1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $tahunPengadaan = ($value->tahunPengadaan == 0 || $value->tahunPengadaan == "0000") ? "Tidak diketahui" : $value->tahunPengadaan;
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->kodeRincianAset);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaPrasarana);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->namaKategoriManajemen);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->status);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('H'.($index + 2), $tahunPengadaan);
            $activeWorksheet->setCellValue('I'.($index + 2), $value->hargaBeli);
            $activeWorksheet->setCellValue('J'.($index + 2), $value->merk);
            $activeWorksheet->setCellValue('K'.($index + 2), $value->noSeri);
            $activeWorksheet->setCellValue('L'.($index + 2), $value->warna);
            $activeWorksheet->setCellValue('M' . ($index + 2), $value->spesifikasi);
            $activeWorksheet->getStyle('M' . ($index + 2))->getAlignment()->setWrapText(true);
            $linkValue = $value->bukti; 
            $linkTitle = 'Click here'; 
            $hyperlinkFormula = '=HYPERLINK("' . $linkValue . '", "' . $linkTitle . '")';
            $activeWorksheet->setCellValue('N'.($index + 2), $hyperlinkFormula);
            
            $activeWorksheet->getStyle('I' . ($index + 2))->getNumberFormat()->setFormatCode("Rp#,##0");
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I' ,'J', 'K', 'L', 'N'];
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
        
        $activeWorksheet->getStyle('A1:N1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:N1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:N'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:N')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'N') as $column) {
            if ($column === 'M') {
                $activeWorksheet->getColumnDimension($column)->setWidth(40); 
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
        }
    
        $asetBagusSheet = $spreadsheet->createSheet();
        $asetBagusSheet->setTitle('Aset Bagus');
        $asetBagusSheet->getTabColor()->setRGB('DF2E38');
        $headerData = ['No.', 'Kode Aset', 'Lokasi', 'Kategori Barang','Spesifikasi Barang', 'Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Spesifikasi', 'Bukti'];
        $asetBagusSheet->fromArray([$headerData], NULL, 'A1');
        $asetBagusSheet->getStyle('A1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($dataAsetItBagus as $index => $value) {
            $tahunPengadaan = ($value->tahunPengadaan == 0 || $value->tahunPengadaan == "0000") ? "Tidak diketahui" : $value->tahunPengadaan;
            $asetBagusSheet->setCellValue('A'.($index + 2), $index + 1);
            $asetBagusSheet->setCellValue('B'.($index + 2), $value->kodeRincianAset);
            $asetBagusSheet->setCellValue('C'.($index + 2), $value->namaPrasarana);
            $asetBagusSheet->setCellValue('D'.($index + 2), $value->namaKategoriManajemen);
            $asetBagusSheet->setCellValue('E'.($index + 2), $value->namaSarana);
            $asetBagusSheet->setCellValue('F'.($index + 2), $value->namaSumberDana);
            $asetBagusSheet->setCellValue('G'.($index + 2), $tahunPengadaan);
            $asetBagusSheet->setCellValue('H'.($index + 2), $value->hargaBeli);
            $asetBagusSheet->setCellValue('I'.($index + 2), $value->merk);
            $asetBagusSheet->setCellValue('J'.($index + 2), $value->noSeri);
            $asetBagusSheet->setCellValue('K'.($index + 2), $value->warna);
            $asetBagusSheet->setCellValue('L' . ($index + 2), $value->spesifikasi);
            $asetBagusSheet->getStyle('L' . ($index + 2))->getAlignment()->setWrapText(true);
            $linkValue = $value->bukti; 
            $linkTitle = 'Click here'; 
            $hyperlinkFormula = '=HYPERLINK("' . $linkValue . '", "' . $linkTitle . '")';
            $asetBagusSheet->setCellValue('M'.($index + 2), $hyperlinkFormula);

            $asetBagusSheet->getStyle('H' . ($index + 2))->getNumberFormat()->setFormatCode("Rp#,##0");
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I' ,'J', 'K', 'L', 'M'];
            
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $asetBagusSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }              
        }
        
        $asetBagusSheet->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $asetBagusSheet->getStyle('A1:M1')->getFont()->setBold(true);
        $asetBagusSheet->getStyle('A1:M'.$asetBagusSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $asetBagusSheet->getStyle('A:M')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'M') as $column) {
            if ($column === 'L') {
                $asetBagusSheet->getColumnDimension($column)->setWidth(40); 
            } else {
                $asetBagusSheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        
        $asetRusakSheet = $spreadsheet->createSheet();
        $asetRusakSheet->setTitle('Aset Rusak');
        $asetRusakSheet->getTabColor()->setRGB('DF2E38');
        $asetRusakSheet->fromArray([$headerData], NULL, 'A1');
        $asetRusakSheet->getStyle('A1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($dataAsetItRusak as $index => $value) {
            $tahunPengadaan = ($value->tahunPengadaan == 0 || $value->tahunPengadaan == "0000") ? "Tidak diketahui" : $value->tahunPengadaan;
            $asetRusakSheet->setCellValue('A'.($index + 2), $index + 1);
            $asetRusakSheet->setCellValue('B'.($index + 2), $value->kodeRincianAset);
            $asetRusakSheet->setCellValue('C'.($index + 2), $value->namaPrasarana);
            $asetRusakSheet->setCellValue('D'.($index + 2), $value->namaKategoriManajemen);
            $asetRusakSheet->setCellValue('E'.($index + 2), $value->namaSarana);
            $asetRusakSheet->setCellValue('F'.($index + 2), $value->namaSumberDana);
            $asetRusakSheet->setCellValue('G'.($index + 2), $tahunPengadaan);
            $asetRusakSheet->setCellValue('H'.($index + 2), $value->hargaBeli);
            $asetRusakSheet->setCellValue('I'.($index + 2), $value->merk);
            $asetRusakSheet->setCellValue('J'.($index + 2), $value->noSeri);
            $asetRusakSheet->setCellValue('K'.($index + 2), $value->warna);
            $asetRusakSheet->setCellValue('L' . ($index + 2), $value->spesifikasi);
            $asetRusakSheet->getStyle('L' . ($index + 2))->getAlignment()->setWrapText(true);
            $linkValue = $value->bukti; 
            $linkTitle = 'Click here'; 
            $hyperlinkFormula = '=HYPERLINK("' . $linkValue . '", "' . $linkTitle . '")';
            $asetRusakSheet->setCellValue('M'.($index + 2), $hyperlinkFormula);

            $asetRusakSheet->getStyle('H' . ($index + 2))->getNumberFormat()->setFormatCode("Rp#,##0");
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I' ,'J', 'K', 'L', 'M'];
            
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $asetRusakSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }              
        }
        
        $asetRusakSheet->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $asetRusakSheet->getStyle('A1:M1')->getFont()->setBold(true);
        $asetRusakSheet->getStyle('A1:M'.$asetRusakSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $asetRusakSheet->getStyle('A:M')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'M') as $column) {
            if ($column === 'L') {
                $asetRusakSheet->getColumnDimension($column)->setWidth(40); 
            } else {
                $asetRusakSheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        $asetHilangSheet = $spreadsheet->createSheet();
        $asetHilangSheet->setTitle('Aset Hilang');
        $asetHilangSheet->getTabColor()->setRGB('DF2E38');
        $asetHilangSheet->fromArray([$headerData], NULL, 'A1');
        $asetHilangSheet->getStyle('A1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($dataAsetItHilang as $index => $value) {
            $tahunPengadaan = ($value->tahunPengadaan == 0 || $value->tahunPengadaan == "0000") ? "Tidak diketahui" : $value->tahunPengadaan;
            $asetHilangSheet->setCellValue('A'.($index + 2), $index + 1);
            $asetHilangSheet->setCellValue('B'.($index + 2), $value->kodeRincianAset);
            $asetHilangSheet->setCellValue('C'.($index + 2), $value->namaPrasarana);
            $asetHilangSheet->setCellValue('D'.($index + 2), $value->namaKategoriManajemen);
            $asetHilangSheet->setCellValue('E'.($index + 2), $value->namaSarana);
            $asetHilangSheet->setCellValue('F'.($index + 2), $value->namaSumberDana);
            $asetHilangSheet->setCellValue('G'.($index + 2), $tahunPengadaan);
            $asetHilangSheet->setCellValue('H'.($index + 2), $value->hargaBeli);
            $asetHilangSheet->setCellValue('I'.($index + 2), $value->merk);
            $asetHilangSheet->setCellValue('J'.($index + 2), $value->noSeri);
            $asetHilangSheet->setCellValue('K'.($index + 2), $value->warna);
            $asetHilangSheet->setCellValue('L' . ($index + 2), $value->spesifikasi);
            $asetHilangSheet->getStyle('L' . ($index + 2))->getAlignment()->setWrapText(true);
            $linkValue = $value->bukti; 
            $linkTitle = 'Click here'; 
            $hyperlinkFormula = '=HYPERLINK("' . $linkValue . '", "' . $linkTitle . '")';
            $asetHilangSheet->setCellValue('M'.($index + 2), $hyperlinkFormula);

            $asetHilangSheet->getStyle('H' . ($index + 2))->getNumberFormat()->setFormatCode("Rp#,##0");
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I' ,'J', 'K', 'L', 'M'];
            
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $asetHilangSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }              
        }
        
        $asetHilangSheet->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $asetHilangSheet->getStyle('A1:M1')->getFont()->setBold(true);
        $asetHilangSheet->getStyle('A1:M'.$asetHilangSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $asetHilangSheet->getStyle('A:M')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'M') as $column) {
            if ($column === 'L') {
                $asetHilangSheet->getColumnDimension($column)->setWidth(40); 
            } else {
                $asetHilangSheet->getColumnDimension($column)->setAutoSize(true);
            }
        }
        

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=IT - Rincian Aset.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->rincianAsetModel->getItAll();
        $keyPrasarana = $this->identitasPrasaranaModel->findAll();
        $keySumberDana = $this->sumberDanaModel->findAll();
        $keyKategoriManajemen = $this->kategoriManajemenModel->findAll();
        $keySarana = $this->identitasSaranaModel->getDataIt();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
        
        $headerInputTable = ['No.', 'Status', 'ID Identitas Prasarana', 'ID Kategori Manajemen','ID Identitas Sarana', 'Nomor Barang', 'Status (Bagus, Rusak, Hilang)', 'ID Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Spesifikasi', 'Bukti (GDRIVE LINK)'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:O1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerPrasaranaID = ['ID Identitas Prasarana', 'Nama Prasarana', 'Kode'];
        $activeWorksheet->fromArray([$headerPrasaranaID], NULL, 'Q1');
        $activeWorksheet->getStyle('Q1:S1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerKategoriManajemenID = ['ID Kategori Barang', 'Kategori Barang', 'Kode'];
        $activeWorksheet->fromArray([$headerKategoriManajemenID], NULL, 'U1');
        $activeWorksheet->getStyle('U1:W1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerSaranaID = ['ID Identitas Sarana', 'Nama Aset', 'Kode'];
        $activeWorksheet->fromArray([$headerSaranaID], NULL, 'Y1');
        $activeWorksheet->getStyle('Y1:AA1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerSumberDanaID = ['ID Sumber Dana', 'Sumber Dana', 'Kode'];
        $activeWorksheet->fromArray([$headerSumberDanaID], NULL, 'AC1');
        $activeWorksheet->getStyle('AC1:AE1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }

            $activeWorksheet->setCellValue('G' . ($index + 2), 'Bagus'); 

            $dataValidation = $activeWorksheet->getCell('G' . ($index + 2))->getDataValidation();
            $dataValidation->setType(DataValidation::TYPE_LIST);
            $dataValidation->setErrorStyle(DataValidation::STYLE_STOP);
            $dataValidation->setShowDropDown(true);
            $dataValidation->setErrorTitle('Input error');
            $dataValidation->setError('Value is not in list.');
            $dataValidation->setFormula1('"Bagus,Rusak,Hilang"'); 

            $generateStatus = '=IF(OR(ISBLANK(C' . ($index + 2) . '), ISBLANK(D' . ($index + 2) . '), ISBLANK(E' . ($index + 2) . '), ISBLANK(F' . ($index + 2) . '), ISBLANK(H' . ($index + 2) . '), ISBLANK(I' . ($index + 2) . '), ISBLANK(J' . ($index + 2) . '), ISBLANK(K' . ($index + 2) . '), ISBLANK(L' . ($index + 2) . '), ISBLANK(M' . ($index + 2) . '), ISBLANK(N' . ($index + 2) . '), ISBLANK(O' . ($index + 2) . ')), "ERROR: empty data", "CORRECT: fill up")';
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $generateStatus);
            $activeWorksheet->setCellValue('C'.($index + 2), '');
            $activeWorksheet->setCellValue('D'.($index + 2), '');
            $activeWorksheet->setCellValue('E'.($index + 2), '');
            $activeWorksheet->setCellValue('F'.($index + 2), '');
            $activeWorksheet->setCellValue('H'.($index + 2), '');
            $activeWorksheet->setCellValue('I'.($index + 2), '');
            $activeWorksheet->setCellValue('J'.($index + 2), '');
            $activeWorksheet->setCellValue('K'.($index + 2), '');
            $activeWorksheet->setCellValue('L'.($index + 2), '');
            $activeWorksheet->setCellValue('M'.($index + 2), '');
            $activeWorksheet->setCellValue('N'.($index + 2), '');
            $activeWorksheet->setCellValue('O'.($index + 2), '');
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('A1:O1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:O'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:O')->getAlignment()->setWrapText(true);
        

        foreach (range('A', 'O') as $column) {
            if ($column === 'B') {
                $activeWorksheet->getColumnDimension($column)->setWidth(35);
            } else if ($column === 'N') {
                $activeWorksheet->getColumnDimension($column)->setWidth(50);
            }
            else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        $activeWorksheet->getColumnDimension('P')->setWidth(15); 
                
        foreach ($keyPrasarana as $index => $value) {
            $activeWorksheet->setCellValue('Q'.($index + 2), $value->idIdentitasPrasarana);
            $activeWorksheet->setCellValue('R'.($index + 2), $value->namaPrasarana);
            $activeWorksheet->setCellValue('S'.($index + 2), $value->kodePrasarana);
        
            $columns = ['Q', 'R', 'S'];
            foreach ($columns as $column) {
                $alignment = $activeWorksheet->getStyle($column . ($index + 2))->getAlignment();
                
                if ($column === 'R') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
            }
        }
        
        $activeWorksheet->getStyle('Q1:S1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('Q1:S1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('Q1:S'.(count($keyPrasarana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('Q:S')->getAlignment()->setWrapText(true);
        
        foreach (range('Q', 'S') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keyKategoriManajemen as $index => $value) {
            $activeWorksheet->setCellValue('U'.($index + 2), $value->idKategoriManajemen);
            $activeWorksheet->setCellValue('V'.($index + 2), $value->namaKategoriManajemen);
            $activeWorksheet->setCellValue('W'.($index + 2), $value->kodeKategoriManajemen);
        
            $columns = ['U', 'V', 'W'];
            foreach ($columns as $column) {
                $alignment = $activeWorksheet->getStyle($column . ($index + 2))->getAlignment();
                
                if ($column === 'V') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
            }
        }
        
        $activeWorksheet->getStyle('U1:W1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('U1:W1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('U1:W'.(count($keyKategoriManajemen) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('U:W')->getAlignment()->setWrapText(true);
        
        foreach (range('U', 'V') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keySarana as $index => $value) {
            $activeWorksheet->setCellValue('Y'.($index + 2), $value->idIdentitasSarana);
            $activeWorksheet->setCellValue('Z'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('AA'.($index + 2), $value->kodeSarana);
        
            $columns = ['Y', 'Z', 'AA'];
            foreach ($columns as $column) {
                $alignment = $activeWorksheet->getStyle($column . ($index + 2))->getAlignment();
                
                if ($column === 'Z') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
            }
        }
        
        $activeWorksheet->getStyle('Y1:AA1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('Y1:AA1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('Y1:AA'.(count($keySarana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('Y:AA')->getAlignment()->setWrapText(true);
        
        foreach (range('Y', 'Z') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keySumberDana as $index => $value) {
            $activeWorksheet->setCellValue('AC'.($index + 2), $value->idSumberDana);
            $activeWorksheet->setCellValue('AD'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('AE'.($index + 2), $value->kodeSumberDana);
        
            $columns = ['AC', 'AD', 'AE'];
            foreach ($columns as $column) {
                $alignment = $activeWorksheet->getStyle($column . ($index + 2))->getAlignment();
                
                if ($column === 'AD') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
            }
        }
        
        $activeWorksheet->getStyle('AC1:AE1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('AC1:AE1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('AC1:AE'.(count($keySumberDana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('AC:AE')->getAlignment()->setWrapText(true);
        
        $activeWorksheet->getColumnDimension('AC')->setWidth(20);
        $activeWorksheet->getColumnDimension('AD')->setWidth(30); 
        $activeWorksheet->getColumnDimension('AE')->setWidth(20); 

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');
        $headerExampleTable =  ['No.', 'Status', 'ID Identitas Prasarana', 'ID Kategori Manajemen','ID Identitas Sarana', 'Nomor Barang', 'Status', 'ID Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Spesifikasi', 'Bukti (GDRIVE LINK)'];
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:O1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $generateStatus = '=IF(OR(ISBLANK(C' . ($index + 2) . '), ISBLANK(D' . ($index + 2) . '), ISBLANK(E' . ($index + 2) . '), ISBLANK(F' . ($index + 2) . '), ISBLANK(H' . ($index + 2) . '), ISBLANK(I' . ($index + 2) . '), ISBLANK(J' . ($index + 2) . '), ISBLANK(K' . ($index + 2) . '), ISBLANK(L' . ($index + 2) . '), ISBLANK(M' . ($index + 2) . '), ISBLANK(N' . ($index + 2) . '), ISBLANK(O' . ($index + 2) . ')), "ERROR: empty data", "CORRECT: fill up")';
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $generateStatus);
            $exampleSheet->setCellValue('C'.($index + 2), $value->idIdentitasPrasarana);
            $exampleSheet->setCellValue('D'.($index + 2), $value->idKategoriManajemen);
            $exampleSheet->setCellValue('E'.($index + 2), $value->idIdentitasSarana);
            $exampleSheet->setCellValue('F'.($index + 2), $value->nomorBarang);
            $exampleSheet->setCellValue('G'.($index + 2), $value->status);
            $exampleSheet->setCellValue('H'.($index + 2), $value->idSumberDana);
            $exampleSheet->setCellValue('I'.($index + 2), $value->tahunPengadaan);
            $exampleSheet->setCellValue('J'.($index + 2), $value->hargaBeli);
            $exampleSheet->setCellValue('K'.($index + 2), $value->merk);
            $exampleSheet->setCellValue('L'.($index + 2), $value->noSeri);
            $exampleSheet->setCellValue('M'.($index + 2), $value->warna);
            $exampleSheet->setCellValue('N'.($index + 2), $value->spesifikasi);
            $exampleSheet->setCellValue('O'.($index + 2), $value->bukti);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I' ,'J', 'K', 'L', 'M', 'N', 'O'];
            
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $exampleSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }              
        }
        
        $exampleSheet->getStyle('A1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $exampleSheet->getStyle('A1:O1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:O'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:O')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'O') as $column) {
            if ($column === 'N' || $column === 'O') {
                $exampleSheet->getColumnDimension($column)->setWidth(40); 
            } else {
                $exampleSheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=IT - Rincian Aset Example.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function import() {
        $file = $this->request->getFile('formExcel');
        $extension = $file->getClientExtension();
        if($extension == 'xlsx' || $extension == 'xls') {
            if($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            
            $spreadsheet = $reader->load($file);
            $theFile = $spreadsheet->getActiveSheet()->toArray();

            foreach ($theFile as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                
                $kodeRincianAset        = "";
                $idIdentitasPrasarana   = $value[2] ?? null;
                $idKategoriManajemen    = $value[3] ?? null;
                $idIdentitasSarana      = $value[4] ?? null;
                $nomorBarang            = $value[5] ?? null;
                $status                 = $value[6] ?? null;
                $idSumberDana           = $value[7] ?? null;
                $tahunPengadaan         = $value[8] ?? null;
                $hargaBeli              = $value[9] ?? null;
                $merk                   = $value[10] ?? null;
                $noSeri                 = $value[11] ?? null;
                $warna                  = $value[12] ?? null;
                $spesifikasi            = $value[13] ?? null;
                $bukti                  = $value[14] ?? null;
                $statusData             = $value[1] ?? null;

                $data = [
                    'kodeRincianAset' => $kodeRincianAset,
                    'idIdentitasPrasarana' => $idIdentitasPrasarana,
                    'idKategoriManajemen' => $idKategoriManajemen,
                    'idIdentitasSarana' => $idIdentitasSarana,
                    'nomorBarang' => $nomorBarang,
                    'status' => $status,
                    'idSumberDana' => $idSumberDana,
                    'tahunPengadaan' => $tahunPengadaan,
                    'hargaBeli' => $hargaBeli,
                    'merk' => $merk,
                    'noSeri' => $noSeri,
                    'warna' => $warna,
                    'spesifikasi' => $spesifikasi,
                    'bukti' => $bukti,
                    'statusData' => $statusData,
                ];
                
                if ($statusData == "ERROR: empty data") {
                    return redirect()->to(site_url('rincianItAset'))->with('error', 'Pastika semua data sudah terisi');
                } else if ($statusData == "CORRECT: fill up") {
                    $this->rincianAsetModel->insert($data);
                    $this->generateAndSetKodeRincianItAset();
                }
            }
            return redirect()->to(site_url('rincianItAset'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('rincianItAset'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }

    public function generateQRDoc() {
        $dataRincianItAset = $this->rincianAsetModel->getItAll();
    
        $data = [
            'dataRincianItAset' => $dataRincianItAset,
        ];

        foreach ($data['dataRincianItAset'] as $key => $value) {
            $qrCode = $this->generateQRCode($value->kodeRincianAset);
            $data['dataRincianItAset'][$key]->qrCodeData = $qrCode;
        }

        $filePath = APPPATH . 'Views/itView/rincianItAset/printQrCode.php';

        if (!file_exists($filePath)) {
            return view('error/404');
        }
        
        ob_start();

        $includeFile = function ($filePath, $data) {
            include $filePath;
        };
    
        $includeFile($filePath, $data);
    
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $filename = 'IT - QR Code Rincian Aset.pdf';
        $dompdf->stream($filename, array("Attachment" => false));
    }


    public function generatePDF() {
        $dataAsetItBagus = $this->rincianAsetModel->getDataItBagus();
        $dataAsetItRusak = $this->rincianAsetModel->getDataItRusak();
        $dataAsetItHilang = $this->rincianAsetModel->getDataItHilang();
        
        $title = "REPORT RINCIAN ASET IT";
        
        if (!$dataAsetItBagus && !$dataAsetItRusak && !$dataAsetItHilang) {
            return view('error/404');
        }
    
        $data = [
            'dataAsetItBagus' => $dataAsetItBagus,
            'dataAsetItRusak' => $dataAsetItRusak,
            'dataAsetItHilang' => $dataAsetItHilang,
        ];
    
        $pdfData = pdfRincianItAset($dataAsetItBagus, $dataAsetItRusak, $dataAsetItHilang, $title);
    
        
        $filename = 'IT - Rincian Aset' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }

    public function print($id = null) {
        $dataRincianItAset = $this->rincianAsetModel->find($id);
        
        if (!is_object($dataRincianItAset)) {
            return view('error/404');
        }

        $spesifikasiMarkup = $dataRincianItAset->spesifikasi; 
        $parsedown = new Parsedown();
        $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
        $buktiUrl = $this->generateFileId($dataRincianItAset->bukti);
        $qrCodeData = $this->generateQRCode($dataRincianItAset->kodeRincianAset);
        // print_r($qrCodeData);
        // die;
        $data = [
            'dataRincianItAset' => $dataRincianItAset,
            'dataIdentitasSarana' => $this->identitasSaranaModel->getDataIt(),
            'dataSumberDana' => $this->sumberDanaModel->findAll(),
            'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
            'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
            'buktiUrl' => $buktiUrl,
            'spesifikasiHtml' => $spesifikasiHtml,
            'qrCodeData' => $qrCodeData, 
        ];

        $filePath = APPPATH . 'Views/itView/rincianItAset/printInfo.php';

        if (!file_exists($filePath)) {
            return view('error/404');
        }

        ob_start();

        $includeFile = function ($filePath, $data) {
            include $filePath;
        };

        $includeFile($filePath, $data);

        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $filename = 'IT - Detail Rincian Aset.pdf';
        $namaSarana = $data['dataRincianItAset']->namaSarana;
        $filename = "IT - Detail Rincian Aset $namaSarana.pdf";
        $dompdf->stream($filename, array("Attachment" => false));;
    }

}
