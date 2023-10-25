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
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class RincianAset extends ResourceController
{
    
     function __construct() {
        $this->rincianAsetModel = new RincianAsetModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataRincianAset'] = $this->rincianAsetModel->getAll();
        return view('saranaView/rincianAset/index', $data);
    }

    // public function index() {
    //     $data['dataRincianAset'] = $this->rincianAsetModel->getAll();
    
    //     $groupedData = [];
    //     foreach ($data['dataRincianAset'] as $row) {
    //         $kodeAset = $row->kodeRincianAset;
    //         $status = $row->statusAset;
    
    //         if (!isset($groupedData[$kodeAset])) {
    //             $groupedData[$kodeAset] = [
    //                 'saranaLayak' => 0,
    //                 'saranaRusak' => 0,
    //             ];
    //         }
    
    //         if ($status === 'layak') {
    //             $groupedData[$kodeAset]['saranaLayak']++;
    //         } elseif ($status === 'rusak') {
    //             $groupedData[$kodeAset]['saranaRusak']++;
    //         }
    //     }
    
    //     $data['groupedData'] = $groupedData;
    //     return view('saranaView/rincianAset/index', $data);
    // }
    

    public function show($id = null) {
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
                return view('saranaView/rincianAset/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data = [
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataSumberDana' => $this->sumberDanaModel->findAll(),
            'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
            'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
        ];
        
        return view('saranaView/rincianAset/new', $data);        
    }
    
    // public function create() {
    //     $data = $this->request->getPost();
    //     if (!empty($data['idIdentitasSarana']) && !empty($data['tahunPengadaan']) && !empty($data['idSumberDana']) && !empty($data['idIdentitasPrasarana'])) {
    //         $totalSarana =  $this->rincianAsetModel->calculateTotalSarana($data['saranaLayak'], $data['saranaRusak']);
    //         $data['totalSarana'] = $totalSarana;
    //         $this->rincianAsetModel->insert($data);
    //         $this->rincianAsetModel->setKodeAset();
    //         return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil disimpan');
    //     } else {
    //         return redirect()->to(site_url('rincianAset'))->with('error', 'Semua field harus terisi');
    //     }
    // }

    public function create() {
        $data = $this->request->getPost();
        if (!empty($data['idIdentitasSarana']) && !empty($data['tahunPengadaan']) && !empty($data['idSumberDana']) && !empty($data['idIdentitasPrasarana'])) {
            
            // Insert the data and get the ID of the inserted record
            $insertedID = $this->rincianAsetModel->insert($data);
    
            // Pass the inserted ID to setKodeAset
            $this->rincianAsetModel->setKodeAset($insertedID);
            
            return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('rincianAset'))->with('error', 'Semua field harus terisi');
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
                
                $this->rincianAsetModel->updateKodeAset($id);
                
                return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }
    

    public function edit($id = null) {
        if ($id != null) {
            $dataRincianAset = $this->rincianAsetModel->find($id);
    
            if (is_object($dataRincianAset)) {
                $data = [
                    'dataRincianAset' => $dataRincianAset,
                    'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana' => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
                ];
                return view('saranaView/rincianAset/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->rincianAsetModel->delete($id);
        return redirect()->to(site_url('rincianAset'));
    }

    public function trash() {
        $data['dataRincianAset'] = $this->rincianAsetModel->onlyDeleted()->getRecycle();
        return view('saranaView/rincianAset/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblRincianAset')
                ->set('deleted_at', null, true)
                ->where(['idRincianAset' => $id])
                ->update();
        } else {
            $this->db->table('tblRincianAset')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('rincianAset/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->rincianAsetModel->delete($id, true);
        return redirect()->to(site_url('rincianAset/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->rincianAsetModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->rincianAsetModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('rincianAset/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('rincianAset/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 

    private function htmlConverter($html) {
        $plainText = strip_tags(str_replace('<br />', "\n", $html));
        $plainText = preg_replace('/\n+/', "\n", $plainText);
        return $plainText;  
    }
    
    public function export() {
        $data = $this->rincianAsetModel->getAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Rincian Aset');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Kode Aset', 'Lokasi', 'Kategori Barang','Spesifikasi Barang', 'Status', 'Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Spesifikasi', 'Bukti'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:N1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $spesifikasiMarkup = $value->spesifikasi; 
            $parsedown = new Parsedown();
            $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
            $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
            
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->kodeRincianAset);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaPrasarana);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->namaKategoriManajemen);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->status);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('H'.($index + 2), $value->tahunPengadaan);
            $activeWorksheet->setCellValue('I'.($index + 2), $value->hargaBeli);
            $activeWorksheet->setCellValue('J'.($index + 2), $value->merk);
            $activeWorksheet->setCellValue('K'.($index + 2), $value->noSeri);
            $activeWorksheet->setCellValue('L'.($index + 2), $value->warna);
            $activeWorksheet->setCellValue('M'.($index + 2), $spesifikasiText);
            $linkCell = 'N' . ($index + 2);
            $linkValue = $value->bukti; 
            $linkTitle = 'Click here'; 

            $hyperlinkFormula = '=HYPERLINK("' . $linkValue . '", "' . $linkTitle . '")';
            $activeWorksheet->setCellValue($linkCell, $hyperlinkFormula);
        
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I' ,'J', 'K', 'L', 'N'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }
        $activeWorksheet->getStyle('M')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $activeWorksheet->getStyle('M')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        
        $activeWorksheet->getStyle('A1:N1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:N1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:N'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:N')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'N') as $column) {
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
        header('Content-Disposition: attachment;filename=Sarana - Rincian Aset.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->rincianAsetModel->getAll();
        $keyPrasarana = $this->identitasPrasaranaModel->findAll();
        $keySumberDana = $this->sumberDanaModel->findAll();
        $keyKategoriManajemen = $this->kategoriManajemenModel->findAll();
        $keySarana = $this->identitasSaranaModel->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
        
        $headerInputTable = ['No.', 'Kode Rincian Aset', 'ID Identitas Prasarana', 'ID Kategori Manajemen','ID Identitas Sarana', 'Nomor Barang', 'Status', 'ID Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Spesifikasi', 'Bukti (GDRIVE LINK)'];
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
            if ($index >= 1) {
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
            // work
            // $generateID = '=CONCAT("TS-BJB ", IF(D' . ($index + 2) . ' = U' . ($index + 2) . ', W' . ($index + 2) . ', D' . ($index + 2) . '), " ", IF(C' . ($index + 2) . ' = Q' . ($index + 2) . ', S' . ($index + 2) . ', C' . ($index + 2) . '), " ", IF(H' . ($index + 2) . ' = AC' . ($index + 2) . ', AE' . ($index + 2) . ', H' . ($index + 2) . '), " ",  IF(I' . ($index + 2) . ' = 0, "xx", RIGHT(TEXT(I' . ($index + 2) . ', "0000"), 2)), " ", IF(E' . ($index + 2) . ' = Y' . ($index + 2) . ', AA' . ($index + 2) . ', E' . ($index + 2) . '), " ", TEXT(F' . ($index + 2) . ', "000"))';
            
            $kategoriBarangKode = '=IFERROR(INDEX($W$2:$W$' . (count($keyKategoriManajemen) + 1) . ', MATCH(D' . ($index + 2) . ', $U$2:$U$' . (count($keyKategoriManajemen) + 1) . ', 0)), D' . ($index + 2) . ')';
            $ruanganKode        = '=IFERROR(INDEX($S$2:$S$' . (count($keyPrasarana) + 1) . ', MATCH(C' . ($index + 2) . ', $Q$2:$Q$' . (count($keyPrasarana) + 1) . ', 0)), C' . ($index + 2) . ')';
            $sumberDanaKode     = '=IFERROR(INDEX($AE$2:$AE$' . (count($keySumberDana) + 1) . ', MATCH(H' . ($index + 2) . ', $AC$2:$AC$' . (count($keySumberDana) + 1) . ', 0)), H' . ($index + 2) . ')';
            $tahunKode          = '=IF(I' . ($index + 2) . ' = 0, "xx", RIGHT(TEXT(I' . ($index + 2) . ', "0000"), 2))';
            $spesifikasiKode    = '=IFERROR(INDEX($AA$2:$AA$' . (count($keySarana) + 1) . ', MATCH(E' . ($index + 2) . ', $Y$2:$Y$' . (count($keySarana) + 1) . ', 0)), E' . ($index + 2) . ')';
            $nomorBarangKode    = '=TEXT(F' . ($index + 2) . ', "000")';

            $generateID = '=CONCAT("TS-BJB ", IFERROR(INDEX($W$2:$W$' . (count($keyKategoriManajemen) + 1) . ', MATCH(D' . ($index + 2) . ', $U$2:$U$' . (count($keyKategoriManajemen) + 1) . ', 0)), D' . ($index + 2) . '), " ", IFERROR(INDEX($S$2:$S$' . (count($keyPrasarana) + 1) . ', MATCH(C' . ($index + 2) . ', $Q$2:$Q$' . (count($keyPrasarana) + 1) . ', 0)), C' . ($index + 2) . '), " ", IFERROR(INDEX($AE$2:$AE$' . (count($keySumberDana) + 1) . ', MATCH(H' . ($index + 2) . ', $AC$2:$AC$' . (count($keySumberDana) + 1) . ', 0)), H' . ($index + 2) . '), " ", IF(I' . ($index + 2) . ' = 0, "xx", RIGHT(TEXT(I' . ($index + 2) . ', "0000"), 2)), " ", IFERROR(INDEX($AA$2:$AA$' . (count($keySarana) + 1) . ', MATCH(E' . ($index + 2) . ', $Y$2:$Y$' . (count($keySarana) + 1) . ', 0)), E' . ($index + 2) . '), " ", TEXT(F' . ($index + 2) . ', "000"))';
            $generateStatus = '=IF(OR(ISBLANK(C' . ($index + 2) . '), ISBLANK(D' . ($index + 2) . '), ISBLANK(E' . ($index + 2) . '), ISBLANK(F' . ($index + 2) . '), ISBLANK(H' . ($index + 2) . '), ISBLANK(I' . ($index + 2) . '), ISBLANK(J' . ($index + 2) . '), ISBLANK(K' . ($index + 2) . '), ISBLANK(L' . ($index + 2) . '), ISBLANK(M' . ($index + 2) . '), ISBLANK(N' . ($index + 2) . '), ISBLANK(O' . ($index + 2) . ')), "ERROR: empty data", "CORRECT: fill up")';
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $generateID);
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
            $activeWorksheet->setCellValue('P'.($index + 2), $generateStatus);
            
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
        $headerExampleTable =  ['No.', 'Kode Rincian Aset', 'ID Identitas Prasarana', 'ID Kategori Manajemen','ID Identitas Sarana', 'Nomor Barang', 'Status', 'ID Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Spesifikasi', 'Bukti (GDRIVE LINK)'];
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:O1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $spesifikasiMarkup = $value->spesifikasi; 
            $parsedown = new Parsedown();
            $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
            $spesifikasiText = $this->htmlConverter($spesifikasiHtml);

            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->kodeRincianAset);
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
            $exampleSheet->setCellValue('N'.($index + 2), $spesifikasiText);
            $exampleSheet->setCellValue('O'.($index + 2), $value->bukti);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $exampleSheet->getStyle('A1:O1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $exampleSheet->getStyle('A1:O'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:O')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'O') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Rincian Aset Example.xlsx');
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
                
                $kodeRincianAset        = $value[1] ?? null;
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
                $status                 = $value[15] ?? null;

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
                    'status' => $status,
                ];
                
                if ($status == "ERROR: empty data") {
                    return redirect()->to(site_url('rincianAset'))->with('error', 'Pastika semua data sudah terisi');
                } else if ($status == "CORRECT: fill up") {
                    $this->rincianAsetModel->insert($data);
                }

            }
            return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('rincianAset'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $filePath = APPPATH . 'Views/saranaView/rincianAset/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataRincianAset'] = $this->rincianAsetModel->getAll();

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
        $filename = 'Sarana - Rincian Aset Report.pdf';
        $dompdf->stream($filename);
    }


    public function print($id = null) {
        $dataRincianAset = $this->rincianAsetModel->find($id);
        
        if (!is_object($dataRincianAset)) {
            return view('error/404');
        }

        $spesifikasiMarkup = $dataRincianAset->spesifikasi; 
        $parsedown = new Parsedown();
        $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
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

        $filePath = APPPATH . 'Views/saranaView/rincianAset/printInfo.php';

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
        $filename = 'Sarana - Detail Rincian Aset.pdf';
        $namaSarana = $data['dataRincianAset']->namaSarana;
        $filename = "Sarana - Detail Rincian Aset $namaSarana.pdf";
        $dompdf->stream($filename);
    }
}
            // $generateID = '=CONCAT("TS-BJB ", TEXT(D'.($index + 2).', "000"), " ", TEXT(C'.($index + 2).', "00"), " ", H'. ($index + 2).', " ", I' .($index + 2) .', " ", TEXT(E'.($index + 2) . ', "000"), " ", TEXT(F' . ($index + 2) . ', "000"))';
            // $generateID = '=CONCAT("TS-BJB ", TEXT(D' . ($index + 2) . ', "000"), " ", TEXT(C' . ($index + 2) . ', "00"), " ", H' . ($index + 2) . ', " ", IF(I' . ($index + 2) . ' = 0, "xx", TEXT(I' . ($index + 2) . ', "00")), " ", TEXT(E' . ($index + 2) . ', "000"), " ", TEXT(F' . ($index + 2) . ', "000"))';
            // $generateID = '=CONCAT("TS-BJB ", TEXT(D' . ($index + 2) . ', "000"), " ", TEXT(C' . ($index + 2) . ', "00"), " ", H' . ($index + 2) . ', " ", IF(I' . ($index + 2) . ' = 0, "xx", RIGHT(TEXT(I' . ($index + 2) . ', "0000"), 2)), " ", TEXT(E' . ($index + 2) . ', "000"), " ", TEXT(F' . ($index + 2) . ', "000"))';
            