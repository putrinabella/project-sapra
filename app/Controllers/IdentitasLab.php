<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\IdentitasGedungModels; 
use App\Models\IdentitasLantaiModels; 
use App\Models\IdentitasLabModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class IdentitasLab extends ResourceController
{
    
     function __construct() {
        $this->identitasGedungModel = new IdentitasGedungModels();
        $this->identitasLantaiModel = new IdentitasLantaiModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataIdentitasLab'] = $this->identitasLabModel->getAll();
        return view('master/identitasLabView/index', $data);
    }

        public function show($id = null) {
        //
    }

        public function new() {
        $data = [
            'dataIdentitasGedung' => $this->identitasGedungModel->findAll(),
            'dataIdentitasLantai' => $this->identitasLantaiModel->findAll(),
        ];
        
        return view('master/identitasLabView/new', $data);        
    }

    public function create() {
        $data = $this->request->getPost();
    
        $kodeLab = $data['kodeLab'];
        $namaLab = $data['namaLab'];
    
        if ($this->identitasLabModel->isDuplicate($kodeLab, $namaLab)) {
            return redirect()->to(site_url('identitasLab'))->with('error', 'Ditemukan duplikat data! Masukkan data yang berbeda.');
        } else {
            unset($data['idIdentitasLab']);
            $this->identitasLabModel->insert($data);
            return redirect()->to(site_url('identitasLab'))->with('success', 'Data berhasil disimpan');
        }
    }
    

    public function edit($id = null) {
        if ($id != null) {
            $dataIdentitasLab = $this->identitasLabModel->find($id);
    
            if (is_object($dataIdentitasLab)) {
                $data = [
                    'dataIdentitasLab' => $dataIdentitasLab,
                    'dataIdentitasGedung' => $this->identitasGedungModel->findAll(),
                    'dataIdentitasLantai' => $this->identitasLantaiModel->findAll(),
                ];
                return view('master/identitasLabView/edit', $data);
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
            $kodeLab = $data['kodeLab'];
            $namaLab = $data['namaLab'];
    
            $existingData = $this->identitasLabModel->find($id);
            if ($existingData->kodeLab != $kodeLab && $existingData->namaLab != $namaLab ) {
                if ($this->identitasLabModel->isDuplicate($kodeLab, $namaLab)) {
                    return redirect()->to(site_url('identitasLab'))->with('error', 'Gagal update karena ditemukan duplikat data!');
                }
            } else if ($existingData->kodeLab != $kodeLab) {
                if ($this->identitasLabModel->kodeLabDuplicate($kodeLab)) {
                    return redirect()->to(site_url('identitasLab'))->with('error', 'Gagal update karena kode lab duplikat!');
                }
            } else if ($existingData->namaLab != $namaLab) {
                if ($this->identitasLabModel->namaLabDuplicate($namaLab)) {
                    return redirect()->to(site_url('identitasLab'))->with('error', 'Gagal update karena nama lab duplikat!');
                }
            }
            $this->identitasLabModel->update($id, $data);
            return redirect()->to(site_url('identitasLab'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }
    


    public function delete($id = null) {
        $this->identitasLabModel->delete($id);
        return redirect()->to(site_url('identitasLab'));
    }

    public function trash() {
        $data['dataIdentitasLab'] = $this->identitasLabModel->onlyDeleted()->getRecycle();
        return view('master/identitasLabView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblIdentitasLab')
                ->set('deleted_at', null, true)
                ->where(['idIdentitasLab' => $id])
                ->update();
        } else {
            $this->db->table('tblIdentitasLab')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('identitasLab'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('identitasLab/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->identitasLabModel->delete($id, true);
        return redirect()->to(site_url('identitasLab/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->identitasLabModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->identitasLabModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('identitasLab/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('identitasLab/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  

    public function export() {
        $data = $this->identitasLabModel->getAll();
        $keyGedung = $this->identitasGedungModel->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
        
        $headerInputTable = ['No.', 'Kode', 'Nama Lab', 'Tipe', 'Luas', 'Lokasi Gedung', 'Lokasi Lantai'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->kodeLab);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaLab);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->luas . ' m²');
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaGedung);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->namaLantai);
            
            // $activeWorksheet->getStyle('B'.($index + 2))
            //     ->getAlignment()
            //     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                
            $columns = ['A', 'B', 'C', 'D', 'E', 'F' ,'G'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }
    
        $activeWorksheet->getStyle('A1:G1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:G'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:G')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'G') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Data Master - Identitas Lab.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    

    public function createTemplate() {
        $data = $this->identitasLabModel->getAll();
        $keyGedung = $this->identitasGedungModel->findAll();
        $keyLantai = $this->identitasLantaiModel->findAll();
        $keyLab = $this->identitasLabModel->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
        
        $headerInputTable = ['No.', 'Kode Lab', 'Nama Lab', 'Tipe', 'Luas', 'Lokasi Gedung', 'Lokasi Lantai' ,'Status'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerTipeLab = ['Tipe'];
        $activeWorksheet->fromArray([$headerTipeLab], NULL, 'J1');
        $activeWorksheet->getStyle('J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerGedungID = ['ID Identitas Gedung', 'Nama Gedung'];
        $activeWorksheet->fromArray([$headerGedungID], NULL, 'L1');
        $activeWorksheet->getStyle('L1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerLantaiID = ['ID Identitas Lantai', 'Nama Lantai'];
        $activeWorksheet->fromArray([$headerLantaiID], NULL, 'O1');
        $activeWorksheet->getStyle('O1:P1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerLabID = ['ID Identitas Lab', 'Nama Lab'];
        $activeWorksheet->fromArray([$headerLabID], NULL, 'R1');
        $activeWorksheet->getStyle('R1:S1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $latestID = null;

        foreach ($data as $index => $value) {
            if ($index >= 1) {
                break;
            }
            $getFormula = '=IF(AND(B2<>"",C2<>"",D2<>"",E2<>"",F2<>"",G2<>""),IF(OR(ISNUMBER(MATCH(B2,$R$2:$R$'.(count($keyLab)+1).',0)),ISNUMBER(MATCH(C2,$S$2:$S$'.(count($keyLab)+1).',0))),"ERROR: Duplicate Data",IF(AND(ISNUMBER(MATCH(D2,$J$2:$J$3,0)),ISNUMBER(MATCH(F2,$L$2:$L$'.(count($keyGedung)+1).',0)),ISNUMBER(MATCH(G2,$O$2:$O$'.(count($keyLantai)+1).',0))),"CORRECT","ERROR: Tipe, ID Lantai atau ID Gedung tidak sesuai")),"ERROR: Empty data")';

            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), '');
            $activeWorksheet->setCellValue('C'.($index + 2), '');
            $activeWorksheet->setCellValue('D'.($index + 2), '');
            $activeWorksheet->setCellValue('E'.($index + 2), '');
            $activeWorksheet->setCellValue('F'.($index + 2), '');
            $activeWorksheet->setCellValue('G'.($index + 2), '');
            $activeWorksheet->setCellValue('H'.($index + 2), $getFormula);
            
            

            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G' ,'H'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }
    
        $activeWorksheet->getStyle('A1:H1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:H'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:H')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'H') as $column) {
            if ($column === 'D' || $column === 'E') {
                $activeWorksheet->getColumnDimension($column)->setWidth(20);
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        $activeWorksheet->setCellValue('J2', 'Ruangan');
        $activeWorksheet->setCellValue('J3', 'Non Ruangan');

        $activeWorksheet->getStyle('J1')
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $activeWorksheet->getStyle('J1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('J1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $activeWorksheet->getStyle('J2:J3')
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $activeWorksheet->getStyle('J2:J3')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $activeWorksheet->getStyle('J')->getAlignment()->setWrapText(true);
        $activeWorksheet->getColumnDimension('J')->setAutoSize(true);

        foreach ($keyGedung as $index => $value) {
            $activeWorksheet->setCellValue('L'.($index + 2), $value->idIdentitasGedung);
            $activeWorksheet->setCellValue('M'.($index + 2), $value->namaGedung);
    
            $columns = ['L', 'M'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('L1:M1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('L1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('L1:M'.(count($keyGedung) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('L:M')->getAlignment()->setWrapText(true);
    
        foreach (range('L', 'M') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keyLantai as $index => $value) {
            $activeWorksheet->setCellValue('O'.($index + 2), $value->idIdentitasLantai);
            $activeWorksheet->setCellValue('P'.($index + 2), $value->namaLantai);
    
            $columns = ['O', 'P'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('O1:P1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('O1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('O1:P'.(count($keyLantai) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('O:P')->getAlignment()->setWrapText(true);
    
        foreach (range('O', 'P') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keyLab as $index => $value) {
            $activeWorksheet->setCellValue('R'.($index + 2), $value->idIdentitasLab);
            $activeWorksheet->setCellValue('S'.($index + 2), $value->namaLab);
    
            $columns = ['R', 'S'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('R1:S1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('R1:S1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('R1:S'.(count($keyLab) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('R:S')->getAlignment()->setWrapText(true);
    
        foreach (range('R', 'S') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headerExampleTable = ['No.', 'Kode Lab', 'Nama Lab', 'Tipe', 'Luas', 'Lokasi Gedung', 'Lokasi Lantai'];
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->kodeLab);
            $exampleSheet->setCellValue('C'.($index + 2), $value->namaLab);
            $exampleSheet->setCellValue('E'.($index + 2), $value->luas);
            $exampleSheet->setCellValue('F'.($index + 2), $value->idIdentitasGedung);
            $exampleSheet->setCellValue('G'.($index + 2), $value->idIdentitasLantai);
            
            $exampleSheet->getStyle('B'.($index + 2))
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                
            $columns = ['A', 'C', 'D', 'E', 'F', 'G'];
            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $exampleSheet->getStyle('A1:G1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $exampleSheet->getStyle('A1:G'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:G')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'G') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Identitas Lab Example.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    
    public function import() {
        $file = $this->request->getFile('formExcel');
        $extension = $file->getClientExtension();
        $hasErrors = false;
        $errorMessage = '';
    
        if ($extension == 'xlsx' || $extension == 'xls') {
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    
            $spreadsheet = $reader->load($file);
            $theFile = $spreadsheet->getActiveSheet()->toArray();
    
            foreach ($theFile as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $kodeLab = $value[1] ?? null;
                $namaLab = $value[2] ?? null;
                $luas = $value[4] ?? null;
                $idIdentitasGedung = $value[5] ?? null;
                $idIdentitasLantai = $value[6] ?? null;
                $status = $value[7] ?? null;
    
                $data = [
                    'namaLab' => $namaLab,
                    'luas' => $luas,
                    'idIdentitasGedung' => $idIdentitasGedung,
                    'idIdentitasLantai' => $idIdentitasLantai,
                    'kodeLab' => $kodeLab,
                    'status' => $status,
                ];
    
                if ($status == 'CORRECT') {
                    if ($this->identitasLabModel->isDuplicate($kodeLab, $namaLab)) {
                        $hasErrors = true;
                        $errorMessage = 'Ditemukan duplikat data! Masukkan data yang berbeda.';
                        break;
                    } else {
                        $this->identitasLabModel->insert($data);
                    }
                } else if ($status == 'ERROR: Empty data') {
                    $hasErrors = true;
                    $errorMessage = 'Pastikan semua data telah terisi.';
                    break;
                } else if ($status == 'ERROR: Duplicate Data') {
                    $hasErrors = true;
                    $errorMessage = 'Ditemukan duplikat data! Masukkan data yang berbeda.';
                    break;
                } else if ($status == 'ERROR: Tipe, ID Lantai atau ID Gedung tidak sesuai') {
                    $errorMessage = 'ERROR: Tipe, ID Lantai atau ID Gedung tidak sesuai!';
                    break;
                }
            }
    
            if ($hasErrors) {
                return redirect()->to(site_url('identitasLab'))->with('error', $errorMessage);
            } else {
                return redirect()->to(site_url('identitasLab'))->with('success', 'Data berhasil diimport');
            }
        } else {
            return redirect()->to(site_url('identitasLab'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }
    


    public function generatePDF() {
        $filePath = APPPATH . 'Views/master/identitasLabView/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataidentitasLab'] = $this->identitasLabModel->getAll();

        ob_start();

        $includeFile = function ($filePath, $data) {
            include $filePath;
        };
    
        $includeFile($filePath, $data);
    
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $filename = 'Data Master - Identitas Lab.pdf';
        $dompdf->stream($filename);
    }
}

