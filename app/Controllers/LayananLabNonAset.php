<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\LayananLabNonAsetModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\StatusLayananModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasLabModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class LayananLabNonAset extends ResourceController
{
    
     function __construct() {
        $this->layananLabNonAsetModel = new LayananLabNonAsetModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->statusLayananModel = new StatusLayananModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataLayananLabNonAset'] = $this->layananLabNonAsetModel->getAll();
        return view('labView/layananLabNonAset/index', $data);
    }

    
    public function show($id = null) {
        if ($id != null) {
            $dataLayananLabNonAset = $this->layananLabNonAsetModel->find($id);
        
            if (is_object($dataLayananLabNonAset)) {
                $spesifikasiMarkup = $dataLayananLabNonAset->spesifikasi; 
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $buktiUrl = $this->generateFileId($dataLayananLabNonAset->bukti);

                $data = [
                    'dataLayananLabNonAset'  => $dataLayananLabNonAset,
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasLab'    => $this->identitasLabModel->findAll(),
                    'dataStatusLayanan'         => $this->statusLayananModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];

                return view('labView/layananLabNonAset/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

        public function new() {
        $data = [
            'dataIdentitasLab'    => $this->identitasLabModel->findAll(),
            'dataStatusLayanan'         => $this->statusLayananModel->findAll(),
            'dataSumberDana'            => $this->sumberDanaModel->findAll(),
            'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
        ];
        
        return view('labView/layananLabNonAset/new', $data);        
    }

    
    public function create() {
        $data = $this->request->getPost();
        if (!empty($data['idIdentitasLab']) && !empty($data['idStatusLayanan']) && !empty($data['idSumberDana']) && !empty($data['idKategoriManajemen'])) {
            $this->layananLabNonAsetModel->insert($data);
            return redirect()->to(site_url('layananLabNonAset'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('layananLabNonAset'))->with('error', 'File error');
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
            $this->layananLabNonAsetModel->update($id, $data);
            return redirect()->to(site_url('layananLabNonAset'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }

    
    public function edit($id = null) {
        if ($id != null) {
            $dataLayananLabNonAset = $this->layananLabNonAsetModel->find($id);
    
            if (is_object($dataLayananLabNonAset)) {
                $data = [
                    'dataLayananLabNonAset'     => $dataLayananLabNonAset,
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasLab'    => $this->identitasLabModel->findAll(),
                    'dataStatusLayanan'         => $this->statusLayananModel->findAll(),
                ];
                return view('labView/layananLabNonAset/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->layananLabNonAsetModel->delete($id);
        return redirect()->to(site_url('layananLabNonAset'));
    }

    public function trash() {
        $data['dataLayananLabNonAset'] = $this->layananLabNonAsetModel->onlyDeleted()->getRecycle();
        return view('labView/layananLabNonAset/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblLayananLabNonAset')
                ->set('deleted_at', null, true)
                ->where(['idLayananLabNonAset' => $id])
                ->update();
        } else {
            $this->db->table('tblLayananLabNonAset')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('layananLabNonAset'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('layananLabNonAset/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->layananLabNonAsetModel->delete($id, true);
        return redirect()->to(site_url('layananLabNonAset/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->layananLabNonAsetModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->layananLabNonAsetModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('layananLabNonAset/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('layananLabNonAset/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  

    private function htmlConverter($html) {
        $plainText = strip_tags(str_replace('<br />', "\n", $html));
        $plainText = preg_replace('/\n+/', "\n", $plainText);
        return $plainText;  
    }
    
    public function export() {
        $data = $this->layananLabNonAsetModel->getAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Layanan Non Aset');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Tanggal', 'Lokasi', 'Status Layanan', 'Kategori Manajemen', 'Sumber Dana', 'Biaya', 'Link Dokumentasi', 'Spesifikasi'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $spesifikasiMarkup = $value->spesifikasi; 
            $parsedown = new Parsedown();
            $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
            $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
            
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->tanggal);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaLab);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->namaStatusLayanan);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->namaKategoriManajemen);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->biaya);
            $activeWorksheet->setCellValue('H'.($index + 2), $value->bukti);
            $activeWorksheet->setCellValue('I'.($index + 2), $spesifikasiText);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
            
            $activeWorksheet->getStyle('I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }
        
        $activeWorksheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:I1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:I'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:I')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'I') as $column) {
            if ($column === 'H') {
                $activeWorksheet->getColumnDimension($column)->setWidth(20);
            } else if ($column === 'I') {
                $activeWorksheet->getColumnDimension($column)->setWidth(40); 
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
        }
        
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Laboratorium - Layanan Non Aset.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->layananLabNonAsetModel->getAll();
        $keyLab = $this->identitasLabModel->findAll();
        $keyStatusLayanan = $this->statusLayananModel->findAll();
        $keySumberDana = $this->sumberDanaModel->findAll();
        $keyKategoriManajemen = $this->kategoriManajemenModel->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
        
        $headerInputTable = ['No.', 'Tanggal', 'Lokasi', 'Status Layanan', 'Kategori Manajemen', 'Sumber Dana', 'Biaya', 'Link Dokumentasi', 'Spesifikasi', 'Status'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerLabID = ['ID Lab', 'Nama Lab'];
        $activeWorksheet->fromArray([$headerLabID], NULL, 'L1');
        $activeWorksheet->getStyle('L1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerKategoriManajemenID = ['ID Kategori Manajemen', 'Kategori Manajemen'];
        $activeWorksheet->fromArray([$headerKategoriManajemenID], NULL, 'O1');
        $activeWorksheet->getStyle('O1:P1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerLabID = ['ID Status Layanan', 'Nama Layanan'];
        $activeWorksheet->fromArray([$headerLabID], NULL, 'R1');
        $activeWorksheet->getStyle('R1:S1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerSumberDanaID = ['ID Sumber Dana', 'Sumber Dana'];
        $activeWorksheet->fromArray([$headerSumberDanaID], NULL, 'U1');
        $activeWorksheet->getStyle('U1:V1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            };
            $getStatus = '=IFERROR(IF(AND(ISNUMBER(MATCH(C'.($index + 2).', $L$2:$L$'.(count($keyLab) + 1).', 0)), ISNUMBER(MATCH(D'.($index + 2).', $O$2:$O$'.(count($keyKategoriManajemen) + 1).', 0)), ISNUMBER(MATCH(E'.($index + 2).', $R$2:$R$'.(count($keyStatusLayanan) + 1).', 0)), ISNUMBER(MATCH(F'.($index + 2).', $U$2:$U$'.(count($keySumberDana) + 1).', 0)), IF(OR(B'.($index + 2).'="", G'.($index + 2).'="", H'.($index + 2).'="", I'.($index + 2).'=""), FALSE, TRUE)), "CORRECT", "ERROR"), "ERROR")';
            // $getStatus = '=IFERROR(IF(AND(ISNUMBER(MATCH(C'.($index + 2).', $K$2:$K$'.(count($keyLab) + 1).', 0)), ISNUMBER(MATCH(D'.($index + 2).', $N$2:$N$'.(count($keyKategoriManajemen) + 1).', 0)), ISNUMBER(MATCH(E'.($index + 2).', $Q$2:$Q$'.(count($keyStatusLayanan) + 1).', 0)), ISNUMBER(MATCH(F'.($index + 2).', $T$2:$T$'.(count($keySumberDana) + 1).', 0))), "CORRECT", "ERROR"), "ERROR")';
            $currentDate = '=TEXT(DATE(' . date('Y') . ',' . date('m') . ',' . date('d') . '),"yyyy-mm-dd")';
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $currentDate);
            $activeWorksheet->setCellValue('C'.($index + 2), '');
            $activeWorksheet->setCellValue('D'.($index + 2), '');
            $activeWorksheet->setCellValue('E'.($index + 2), '');
            $activeWorksheet->setCellValue('F'.($index + 2), '');
            $activeWorksheet->setCellValue('G'.($index + 2), '');
            $activeWorksheet->setCellValue('H'.($index + 2), '');
            $activeWorksheet->setCellValue('I'.($index + 2), '');
            $activeWorksheet->setCellValue('J'.($index + 2), $getStatus);

            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H' ,'I', 'J'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }
    
        $activeWorksheet->getStyle('A1:J1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:J'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:J')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'J') as $column) {
            if ($column === 'G') {
                $activeWorksheet->getColumnDimension($column)->setWidth(20);
            } else if ($column === 'H') {
                $activeWorksheet->getColumnDimension($column)->setWidth(50); 
            } else if ($column === 'I') {
                $activeWorksheet->getColumnDimension($column)->setWidth(40); 
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        foreach ($keyLab as $index => $value) {
            $activeWorksheet->setCellValue('L'.($index + 2), $value->idIdentitasLab);
            $activeWorksheet->setCellValue('M'.($index + 2), $value->namaLab);
    
            $columns = ['L', 'M'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('L1:M1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('L1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('L1:M'.(count($keyLab) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('L:M')->getAlignment()->setWrapText(true);
    
        foreach (range('L', 'M') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        foreach ($keyKategoriManajemen as $index => $value) {
            $activeWorksheet->setCellValue('O'.($index + 2), $value->idKategoriManajemen);
            $activeWorksheet->setCellValue('P'.($index + 2), $value->namaKategoriManajemen);
    
            $columns = ['O', 'P'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('O1:P1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('O1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('O1:P'.(count($keyKategoriManajemen) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('O:P')->getAlignment()->setWrapText(true);
    
        foreach (range('O', 'P') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }


        foreach ($keyStatusLayanan as $index => $value) {
            $activeWorksheet->setCellValue('R'.($index + 2), $value->idStatusLayanan);
            $activeWorksheet->setCellValue('S'.($index + 2), $value->namaStatusLayanan);
    
            $columns = ['R', 'S'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('R1:S1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('R1:S1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('R1:S'.(count($keyStatusLayanan) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('R:S')->getAlignment()->setWrapText(true);
    
        foreach (range('R', 'S') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keySumberDana as $index => $value) {
            $activeWorksheet->setCellValue('U'.($index + 2), $value->idSumberDana);
            $activeWorksheet->setCellValue('V'.($index + 2), $value->namaSumberDana);
    
            $columns = ['U', 'V'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('U1:V1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('U1:V1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('U1:V'.(count($keySumberDana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('U:V')->getAlignment()->setWrapText(true);
    
        foreach (range('U', 'V') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headerExampleTable = ['No.', 'Tanggal', 'Lokasi', 'Status Layanan', 'Kategori Manajemen', 'Sumber Dana', 'Biaya', 'Link Dokumentasi', 'Spesifikasi'];
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            };

            $currentDate = '=TEXT(DATE(' . date('Y') . ',' . date('m') . ',' . date('d') . '),"yyyy-mm-dd")';
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $currentDate);
            $exampleSheet->setCellValue('C'.($index + 2), $value->namaLab);
            $exampleSheet->setCellValue('D'.($index + 2), $value->namaStatusLayanan);
            $exampleSheet->setCellValue('E'.($index + 2), $value->namaKategoriManajemen);
            $exampleSheet->setCellValue('F'.($index + 2), $value->namaSumberDana);
            $exampleSheet->setCellValue('G'.($index + 2), $value->biaya);
            $exampleSheet->setCellValue('H'.($index + 2), $value->bukti);
            $exampleSheet->setCellValue('I'.($index + 2), $value->spesifikasi);
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H' ,'I'];
            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }
    
        $exampleSheet->getStyle('A1:I1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $exampleSheet->getStyle('A1:I'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:I')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'I') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Laboratorium - Layanan Non Aset Example.xlsx');
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
                $tanggal                = $value[1] ?? null;
                $idIdentitasLab         = $value[2] ?? null;
                $idStatusLayanan        = $value[3] ?? null;
                $idKategoriManajemen    = $value[4] ?? null;
                $idSumberDana           = $value[5] ?? null;
                $biaya                  = $value[6] ?? null;
                $bukti                  = $value[7] ?? null;
                $spesifikasi            = $value[8] ?? null;

                if ($idIdentitasLab === null || $idIdentitasLab === '') {
                    continue; 
                }

                
                $data = [
                    'tanggal' => $tanggal,
                    'idIdentitasLab' => $idIdentitasLab,
                    'idStatusLayanan' => $idStatusLayanan,
                    'idKategoriManajemen' => $idKategoriManajemen,
                    'idSumberDana' => $idSumberDana,
                    'biaya' => $biaya,
                    'bukti' => $bukti,
                    'spesifikasi' => $spesifikasi,
                    ];

                    if (!empty($data['tanggal']) && !empty($data['idIdentitasLab']) && !empty($data['idStatusLayanan']) && !empty($data['idKategoriManajemen']) && !empty($data['idSumberDana']) && !empty($data['biaya']) && !empty($data['bukti']) && !empty($data['spesifikasi'])) {
                        $this->layananLabNonAsetModel->insert($data);
                    } else {
                        return redirect()->to(site_url('layananLabNonAset'))->with('error', 'Pastikan semua data telah diisi!');
                    }
            }
            return redirect()->to(site_url('layananLabNonAset'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('layananLabNonAset'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $filePath = APPPATH . 'Views/labView/layananLabNonAset/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataLayananLabNonAset'] = $this->layananLabNonAsetModel->getAll();

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
        $filename = 'Laboratorium - Layanan Non Aset Report.pdf';
        $dompdf->stream($filename);
    }

    
}

