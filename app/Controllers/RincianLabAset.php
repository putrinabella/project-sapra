<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\RincianLabAsetModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasLabModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class RincianLabAset extends ResourceController
{
    
     function __construct() {
        $this->rincianLabAsetModel = new RincianLabAsetModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataRincianLabAset'] = $this->rincianLabAsetModel->getAll();
        return view('labView/rincianLabAset/index', $data);
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
                $data = [
                    'dataRincianLabAset'        => $dataRincianLabAset,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasLab'          => $this->identitasLabModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];
                return view('labView/rincianLabAset/show', $data);
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
            'dataIdentitasLab' => $this->identitasLabModel->findAll(),
        ];
        
        return view('labView/rincianLabAset/new', $data);        
    }

    
    public function create() {
        $data = $this->request->getPost(); 
        if (!empty($data['idIdentitasSarana']) && !empty($data['tahunPengadaan']) && !empty($data['idSumberDana']) && !empty($data['idIdentitasLab'])) {
            // $totalSarana =  $this->rincianLabAsetModel->calculateTotalSarana($data['saranaLayak'], $data['saranaRusak']);
            // $data['totalSarana'] = $totalSarana;
            $this->rincianLabAsetModel->insert($data);
            $this->rincianLabAsetModel->setKodeLabAset();
            return redirect()->to(site_url('rincianLabAset'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('rincianLabAset'))->with('error', 'Semua field harus terisi');
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
            if (!empty($data['idIdentitasSarana']) && !empty($data['tahunPengadaan']) && !empty($data['idSumberDana']) && !empty($data['idIdentitasLab'])) {
                // if ($uploadedFilePath !== null) {
                //     $data['bukti'] = $uploadedFilePath;
                // }

                // $totalSarana =  $this->rincianLabAsetModel->calculateTotalSarana($data['saranaLayak'], $data['saranaRusak']);
                // $data['totalSarana'] = $totalSarana;

                $this->rincianLabAsetModel->update($id, $data);
                $this->rincianLabAsetModel->updateKodeLabAset($id);
                return redirect()->to(site_url('rincianLabAset'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to
                (site_url('rincianLabAset/edit/'.$id))->with('error', 'Id Sarana dan Lantai harus diisi.');
            }
        } else {
            return view('error/404');
        }
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
                return view('labView/rincianLabAset/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->rincianLabAsetModel->delete($id);
        return redirect()->to(site_url('rincianLabAset'));
    }

    public function trash() {
        $data['dataRincianLabAset'] = $this->rincianLabAsetModel->onlyDeleted()->getRecycle();
        return view('labView/rincianLabAset/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblRincianLabAset')
                ->set('deleted_at', null, true)
                ->where(['idRincianLabAset' => $id])
                ->update();
        } else {
            $this->db->table('tblRincianLabAset')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('rincianLabAset'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('rincianLabAset/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->rincianLabAsetModel->delete($id, true);
        return redirect()->to(site_url('rincianLabAset/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->rincianLabAsetModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->rincianLabAsetModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('rincianLabAset/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('rincianLabAset/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 

    private function htmlConverter($html) {
        $plainText = strip_tags(str_replace('<br />', "\n", $html));
        $plainText = preg_replace('/\n+/', "\n", $plainText);
        return $plainText;  
    }
    
    public function export() {
        $data = $this->rincianLabAsetModel->getAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Rincian Aset Lab');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Kode Aset', 'Nama Aset', 'Lokasi','Tahun Pengadaan', 'Kategori Manajemen', 'Sumber Dana', 'Aset Layak', 'Aset Rusak', 'Aset Hilang', 'Total Aset' , 'Link Dokumentasi', 'Spesifikasi'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:Q1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $spesifikasiMarkup = $value->spesifikasi; 
            $parsedown = new Parsedown();
            $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
            $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
            
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->kodeRincianLabAset);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->namaLab);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->tahunPengadaan);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaKategoriManajemen);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('H'.($index + 2), $value->saranaLayak);
            $activeWorksheet->setCellValue('I'.($index + 2), $value->saranaRusak);
            $activeWorksheet->setCellValue('J'.($index + 2), $value->saranaHilang);
            $activeWorksheet->setCellValue('K'.($index + 2), $totalSarana = $value->saranaLayak + $value->saranaRusak + $value->saranaHilang);
            $activeWorksheet->setCellValue('L'.($index + 2), $value->bukti);
            $activeWorksheet->setCellValue('M'.($index + 2), $spesifikasiText);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I' ,'J', 'K', 'L'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }
        $activeWorksheet->getStyle('M')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $activeWorksheet->getStyle('M')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        
        $activeWorksheet->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:M1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:M'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:M')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'M') as $column) {
            if ($column === 'K') {
                $activeWorksheet->getColumnDimension($column)->setWidth(20);
            } else if ($column === 'M') {
                $activeWorksheet->getColumnDimension($column)->setWidth(40); 
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }

        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Rincian Aset Laboratorium.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->rincianLabAsetModel->getAll();
        $keyLab = $this->identitasLabModel->findAll();
        $keySumberDana = $this->sumberDanaModel->findAll();
        $keyKategoriManajemen = $this->kategoriManajemenModel->findAll();
        $keySarana = $this->identitasSaranaModel->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
        
        $headerInputTable = ['No.', 'Kode Aset', 'ID Aset', 'ID Lab', 'ID Sumber Dana', 'ID Kategori Manajemen', 'Tahun Pengadaan', 'Sarana Layak', 'Sarana Rusak', 'Total Sarana', 'Link Dokumentasi', 'Kode Lab'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerSumberDanaID = ['ID Sumber Dana', 'Sumber Dana'];
        $activeWorksheet->fromArray([$headerSumberDanaID], NULL, 'N1');
        $activeWorksheet->getStyle('N1:O1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerKategoriManajemenID = ['ID Kategori Manajemen', 'Kategori Manajemen'];
        $activeWorksheet->fromArray([$headerKategoriManajemenID], NULL, 'Q1');
        $activeWorksheet->getStyle('Q1:R1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerLabID = ['ID Lab', 'Nama Lab', 'Kode Lab'];
        $activeWorksheet->fromArray([$headerLabID], NULL, 'T1');
        $activeWorksheet->getStyle('T1:V1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerSaranaID = ['ID Aset', 'Nama Aset'];
        $activeWorksheet->fromArray([$headerSaranaID], NULL, 'X1');
        $activeWorksheet->getStyle('X1:Y1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }

            $generateID = '=CONCAT("A", TEXT(C'.($index + 2).', "000"), "/", G'.($index + 2).', "/SD", TEXT(E'.($index + 2).', "00"), "/", L'.($index + 2).')';
            $getKodeLab = '=INDEX($V$2:$V$'.(count($keyLab) + 1).', MATCH(D'.($index + 2).', $T$2:$T$'.(count($keyLab) + 1).', 0))';
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
            $activeWorksheet->setCellValue('L'.($index + 2), $getKodeLab);
            
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

        foreach ($keyLab as $index => $value) {
            $activeWorksheet->setCellValue('T'.($index + 2), $value->idIdentitasLab);
            $activeWorksheet->setCellValue('U'.($index + 2), $value->namaLab);
            $activeWorksheet->setCellValue('V'.($index + 2), $value->idIdentitasLab);
    
            $columns = ['T', 'U', 'V'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('T1:V1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('T1:V1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('T1:V'.(count($keyLab) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
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

        $headerExampleTable = ['No.', 'Kode Aset', 'ID Aset', 'ID Lab', 'ID Sumber Dana', 'ID Kategori Manajemen', 'Tahun Pengadaan', 'Sarana Layak', 'Sarana Rusak', 'Total Sarana', 'Link Dokumentasi', 'Kode Lab'];
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->kodeRincianLabAset);
            $exampleSheet->setCellValue('C'.($index + 2), $value->idIdentitasSarana);
            $exampleSheet->setCellValue('D'.($index + 2), $value->idIdentitasLab);
            $exampleSheet->setCellValue('E'.($index + 2), $value->idSumberDana);
            $exampleSheet->setCellValue('F'.($index + 2), $value->idKategoriManajemen);
            $exampleSheet->setCellValue('G'.($index + 2), $value->tahunPengadaan);
            $exampleSheet->setCellValue('H'.($index + 2), $value->saranaLayak);
            $exampleSheet->setCellValue('I'.($index + 2), $value->saranaRusak);
            $exampleSheet->setCellValue('J'.($index + 2), $value->totalSarana);
            $exampleSheet->setCellValue('K'.($index + 2), $value->bukti);
            $exampleSheet->setCellValue('L'.($index + 2), $value->idIdentitasLab);
            
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
        header('Content-Disposition: attachment;filename=Laboratorium - Rincian Aset Lab Example.xlsx');
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
                $kodeRincianLabAset        = $value[1] ?? null;
                $idIdentitasSarana      = $value[2] ?? null;
                $idSumberDana           = $value[4] ?? null;
                $idKategoriManajemen    = $value[5] ?? null;
                $tahunPengadaan         = $value[6] ?? null;
                $saranaLayak            = $value[7] ?? null;
                $saranaRusak            = $value[8] ?? null;
                $totalSarana            = $value[9] ?? null;
                $bukti                  = $value[10] ?? null;
                $idIdentitasLab          = $value[11] ?? null;

                    $data = [
                        'kodeRincianLabAset' => $kodeRincianLabAset,
                        'idIdentitasSarana' => $idIdentitasSarana,
                        'idSumberDana' => $idSumberDana,
                        'idKategoriManajemen' => $idKategoriManajemen,
                        'idIdentitasLab' => $idIdentitasLab,
                        'tahunPengadaan' => $tahunPengadaan,
                        'saranaLayak' => $saranaLayak,
                        'saranaRusak' => $saranaRusak,
                        'spesifikasi' => '',
                        'totalSarana' => $totalSarana,
                        'bukti' => $bukti,
                    ];
                    if (!empty($data['kodeRincianLabAset']) && !empty($data['idIdentitasSarana'])
                        && !empty($data['idSumberDana']) && !empty($data['idKategoriManajemen']) 
                        && !empty($data['idIdentitasLab']) && !empty($data['tahunPengadaan'])
                        && !empty($data['saranaLayak']) && !empty($data['saranaRusak']) 
                        && !empty($data['spesifikasi']) && !empty($data['totalSarana']) 
                        && !empty($data['bukti'])) {
                        if ($status == 'ERROR') {
                            return redirect()->to(site_url('rincianLabAset'))->with('error', 'Pastikan excel sudah benar');
                        } else {
                            $this->rincianLabAsetModel->insert($data);
                        }
                    } else {
                        return redirect()->to(site_url('rincianLabAset'))->with('error', 'Pastikan semua data telah diisi!');
                    }
            }
            return redirect()->to(site_url('rincianLabAset'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('rincianLabAset'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $filePath = APPPATH . 'Views/labView/rincianLabAset/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataRincianLabAset'] = $this->rincianLabAsetModel->getAll();

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
        $filename = 'Laboratorium - Rincian Aset Report.pdf';
        $dompdf->stream($filename);
    }


    public function print($id = null) {
        $dataRincianLabAset = $this->rincianLabAsetModel->find($id);
        
        if (!is_object($dataRincianLabAset)) {
            return view('error/404');
        }

        $spesifikasiMarkup = $dataRincianLabAset->spesifikasi; 
        $parsedown = new Parsedown();
        $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
        $buktiUrl = $this->generateFileId($dataRincianLabAset->bukti);

        $data = [
            'dataRincianLabAset'           => $dataRincianLabAset,
            'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
            'dataSumberDana'            => $this->sumberDanaModel->findAll(),
            'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
            'dataIdentitasLab'          => $this->identitasLabModel->findAll(),
            'buktiUrl'                  => $buktiUrl,
            'spesifikasiHtml'           => $spesifikasiHtml,
        ];

        $filePath = APPPATH . 'Views/labView/rincianLabAset/printInfo.php';

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
        // $filename = 'Laboratorium - Detail Rincian Aset Lab.pdf';
        $namaSarana = $data['dataRincianLabAset']->namaSarana;
        $filename = "Laboratorium - Detail Rincian Aset Lab $namaSarana.pdf";
        $dompdf->stream($filename);
    }
}