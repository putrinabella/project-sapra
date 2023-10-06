<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\RincianAsetModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasPrasaranaModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
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

    public function show($id = null) {
        if ($id != null) {
            $dataRincianAset = $this->rincianAsetModel->find($id);
        
            if (is_object($dataRincianAset)) {
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

    
    public function create() {
        $data = $this->request->getPost();
        // $buktiPath = $this->uploadFile('bukti'); 
        if (!empty($data['idIdentitasSarana']) && !empty($data['tahunPengadaan']) && !empty($data['idSumberDana']) && !empty($data['kodePrasarana'])) {
            // $data['bukti'] = $buktiPath;
            $totalSarana =  $this->rincianAsetModel->calculateTotalSarana($data['saranaLayak'], $data['saranaRusak']);
            $data['totalSarana'] = $totalSarana;
            $this->rincianAsetModel->insert($data);
            $this->rincianAsetModel->setKodeAset();
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
            // $uploadedFilePath = $this->uploadFile('bukti');
            if (!empty($data['idIdentitasSarana']) && !empty($data['tahunPengadaan']) && !empty($data['idSumberDana']) && !empty($data['kodePrasarana'])) {
                // if ($uploadedFilePath !== null) {
                //     $data['bukti'] = $uploadedFilePath;
                // }

                $totalSarana =  $this->rincianAsetModel->calculateTotalSarana($data['saranaLayak'], $data['saranaRusak']);
                $data['totalSarana'] = $totalSarana;

                $this->rincianAsetModel->update($id, $data);
                $this->rincianAsetModel->updateKodeAset($id);
                return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to
                (site_url('rincianAset/edit/'.$id))->with('error', 'Id Sarana dan Lantai harus diisi.');
            }
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
    
        $headers = ['No.', 'Kode Aset', 'Nama Aset', 'Lokasi','Tahun Pengadaan', 'Kategori Manajemen', 'Sumber Dana', 'Aset Layak', 'Aset Rusak', 'Total Aset' , 'Link Dokumentasi', 'Spesifikasi'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:Q1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $spesifikasiMarkup = $value->spesifikasi; 
            $parsedown = new Parsedown();
            $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
            $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
            
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->kodeRincianAset);
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
        
        $activeWorksheet->getStyle('A1:Q1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:Q1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:L'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:L')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'L') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Rincian Aset.xlsx');
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
        
        $headerInputTable = ['No.', 'Kode Aset', 'ID Aset', 'ID Prasarana', 'ID Sumber Dana', 'ID Kategori Manajemen', 'Tahun Pengadaan', 'Sarana Layak', 'Sarana Rusak', 'Total Sarana', 'Link Dokumentasi', 'Kode Prasarana'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerSumberDanaID = ['ID Sumber Dana', 'Sumber Dana'];
        $activeWorksheet->fromArray([$headerSumberDanaID], NULL, 'N1');
        $activeWorksheet->getStyle('N1:O1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerKategoriManajemenID = ['ID Kategori Manajemen', 'Kategori Manajemen'];
        $activeWorksheet->fromArray([$headerKategoriManajemenID], NULL, 'Q1');
        $activeWorksheet->getStyle('Q1:R1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerPrasaranaID = ['ID Prasarana', 'Nama Prasarana', 'Kode Prasarana'];
        $activeWorksheet->fromArray([$headerPrasaranaID], NULL, 'T1');
        $activeWorksheet->getStyle('T1:V1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerSaranaID = ['ID Aset', 'Nama Aset'];
        $activeWorksheet->fromArray([$headerSaranaID], NULL, 'X1');
        $activeWorksheet->getStyle('X1:Y1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }

            $generateID = '=CONCAT("A", TEXT(C'.($index + 2).', "000"), "/", G'.($index + 2).', "/SD", TEXT(E'.($index + 2).', "00"), "/", L'.($index + 2).')';
            $getKodePrasarana = '=INDEX($V$2:$V$'.(count($keyPrasarana) + 1).', MATCH(D'.($index + 2).', $T$2:$T$'.(count($keyPrasarana) + 1).', 0))';
            $getTotalSarana = '=SUM(H'.($index + 2).', I'.($index + 2).')';

            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $generateID);
            $activeWorksheet->setCellValue('C'.($index + 2), '');
            $activeWorksheet->setCellValue('D'.($index + 2), '');
            $activeWorksheet->setCellValue('E'.($index + 2), '');
            $activeWorksheet->setCellValue('F'.($index + 2), '');
            $activeWorksheet->setCellValue('G'.($index + 2), '');
            $activeWorksheet->setCellValue('H'.($index + 2), '');
            $activeWorksheet->setCellValue('I'.($index + 2), '');
            $activeWorksheet->setCellValue('J'.($index + 2), $getTotalSarana);
            $activeWorksheet->setCellValue('K'.($index + 2), '');
            $activeWorksheet->setCellValue('L'.($index + 2), $getKodePrasarana);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }
    
        $activeWorksheet->getStyle('A1:L1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:L'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:L')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'L') as $column) {
            if ($column === 'B') {
                $activeWorksheet->getColumnDimension($column)->setWidth(35);
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
        }
        
        foreach ($keySumberDana as $index => $value) {
            $activeWorksheet->setCellValue('N'.($index + 2), $value->idSumberDana);
            $activeWorksheet->setCellValue('O'.($index + 2), $value->namaSumberDana);
    
            $columns = ['N', 'O'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('N1:O1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('N1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('N1:O'.(count($keySumberDana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('N:O')->getAlignment()->setWrapText(true);
    
        foreach (range('N', 'O') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keyKategoriManajemen as $index => $value) {
            $activeWorksheet->setCellValue('Q'.($index + 2), $value->idKategoriManajemen);
            $activeWorksheet->setCellValue('R'.($index + 2), $value->namaKategoriManajemen);
    
            $columns = ['Q', 'R'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('Q1:R1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('Q1:R1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('Q1:R'.(count($keyKategoriManajemen) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('Q:R')->getAlignment()->setWrapText(true);
    
        foreach (range('Q', 'R') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keyPrasarana as $index => $value) {
            $activeWorksheet->setCellValue('T'.($index + 2), $value->idIdentitasPrasarana);
            $activeWorksheet->setCellValue('U'.($index + 2), $value->namaPrasarana);
            $activeWorksheet->setCellValue('V'.($index + 2), $value->kodePrasarana);
    
            $columns = ['T', 'U', 'V'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('T1:V1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('T1:V1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('T1:V'.(count($keyPrasarana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('T:V')->getAlignment()->setWrapText(true);
    
        foreach (range('T', 'V') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keySarana as $index => $value) {
            $activeWorksheet->setCellValue('X'.($index + 2), $value->idIdentitasSarana);
            $activeWorksheet->setCellValue('Y'.($index + 2), $value->namaSarana);
    
            $columns = ['X', 'Y'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('X1:Y1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('X1:Y1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('X1:Y'.(count($keySarana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('X:Y')->getAlignment()->setWrapText(true);
    
        foreach (range('X', 'Y') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headerExampleTable = ['No.', 'Kode Aset', 'ID Aset', 'ID Prasarana', 'ID Sumber Dana', 'ID Kategori Manajemen', 'Tahun Pengadaan', 'Sarana Layak', 'Sarana Rusak', 'Total Sarana', 'Link Dokumentasi', 'Kode Prasarana'];
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->kodeRincianAset);
            $exampleSheet->setCellValue('C'.($index + 2), $value->idIdentitasSarana);
            $exampleSheet->setCellValue('D'.($index + 2), $value->idIdentitasPrasarana);
            $exampleSheet->setCellValue('E'.($index + 2), $value->idSumberDana);
            $exampleSheet->setCellValue('F'.($index + 2), $value->idKategoriManajemen);
            $exampleSheet->setCellValue('G'.($index + 2), $value->tahunPengadaan);
            $exampleSheet->setCellValue('H'.($index + 2), $value->saranaLayak);
            $exampleSheet->setCellValue('I'.($index + 2), $value->saranaRusak);
            $exampleSheet->setCellValue('J'.($index + 2), $value->totalSarana);
            $exampleSheet->setCellValue('K'.($index + 2), $value->bukti);
            $exampleSheet->setCellValue('L'.($index + 2), $value->kodePrasarana);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $exampleSheet->getStyle('A1:L1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $exampleSheet->getStyle('A1:L'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:L')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'L') as $column) {
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
                $idIdentitasSarana      = $value[2] ?? null;
                $idSumberDana           = $value[4] ?? null;
                $idKategoriManajemen    = $value[5] ?? null;
                $tahunPengadaan         = $value[6] ?? null;
                $saranaLayak            = $value[7] ?? null;
                $saranaRusak            = $value[8] ?? null;
                $totalSarana            = $value[9] ?? null;
                $bukti                  = $value[10] ?? null;
                $kodePrasarana          = $value[11] ?? null;

                if ($kodeRincianAset !== null) {
                    $data = [
                        'kodeRincianAset' => $kodeRincianAset,
                        'idIdentitasSarana' => $idIdentitasSarana,
                        'idSumberDana' => $idSumberDana,
                        'idKategoriManajemen' => $idKategoriManajemen,
                        'kodePrasarana' => $kodePrasarana,
                        'tahunPengadaan' => $tahunPengadaan,
                        'saranaLayak' => $saranaLayak,
                        'saranaRusak' => $saranaRusak,
                        'spesifikasi' => '',
                        'totalSarana' => $totalSarana,
                        'bukti' => $bukti,
                    ];
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