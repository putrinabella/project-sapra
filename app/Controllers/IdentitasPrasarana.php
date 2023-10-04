<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\IdentitasGedungModels; 
use App\Models\IdentitasLantaiModels; 
use App\Models\IdentitasPrasaranaModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class IdentitasPrasarana extends ResourceController
{
    
     function __construct() {
        $this->identitasGedungModel = new IdentitasGedungModels();
        $this->identitasLantaiModel = new IdentitasLantaiModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataIdentitasPrasarana'] = $this->identitasPrasaranaModel->getAll();
        return view('master/identitasPrasaranaView/index', $data);
    }

        public function show($id = null) {
        //
    }

        public function new() {
        $data = [
            'dataIdentitasGedung' => $this->identitasGedungModel->findAll(),
            'dataIdentitasLantai' => $this->identitasLantaiModel->findAll(),
        ];
        
        return view('master/identitasPrasaranaView/new', $data);        
    }

    
    public function create() {
        $data = $this->request->getPost();
            unset($data['idIdentitasPrasarana']);
            $this->identitasPrasaranaModel->insert($data);
            $this->identitasPrasaranaModel->setKodePrasarana();
            return redirect()->to(site_url('identitasPrasarana'))->with('success', 'Data berhasil disimpan');
    }


    public function edit($id = null) {
        if ($id != null) {
            $dataIdentitasPrasarana = $this->identitasPrasaranaModel->find($id);
    
            if (is_object($dataIdentitasPrasarana)) {
                $data = [
                    'dataIdentitasPrasarana' => $dataIdentitasPrasarana,
                    'dataIdentitasGedung' => $this->identitasGedungModel->findAll(),
                    'dataIdentitasLantai' => $this->identitasLantaiModel->findAll(),
                ];
                return view('master/identitasPrasaranaView/edit', $data);
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
                $this->identitasPrasaranaModel->update($id, $data);
                $this->identitasPrasaranaModel->updateKodePrasarana($id);
                return redirect()->to(site_url('identitasPrasarana'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->identitasPrasaranaModel->delete($id);
        return redirect()->to(site_url('identitasPrasarana'));
    }

    public function trash() {
        $data['dataIdentitasPrasarana'] = $this->identitasPrasaranaModel->onlyDeleted()->getRecycle();
        return view('master/identitasPrasaranaView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblIdentitasPrasarana')
                ->set('deleted_at', null, true)
                ->where(['idIdentitasPrasarana' => $id])
                ->update();
        } else {
            $this->db->table('tblIdentitasPrasarana')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('identitasPrasarana'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('identitasPrasarana/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->identitasPrasaranaModel->delete($id, true);
        return redirect()->to(site_url('identitasPrasarana/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->identitasPrasaranaModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->identitasPrasaranaModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('identitasPrasarana/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('identitasPrasarana/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  

    public function export() {
        $data = $this->identitasPrasaranaModel->getAll();
        $keyGedung = $this->identitasGedungModel->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
        
        $headerInputTable = ['No.', 'Kode', 'Nama Prasarana', 'Luas', 'Lokasi Gedung', 'Lokasi Lantai'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->kodePrasarana);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaPrasarana);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->luas . ' m²');
            $activeWorksheet->setCellValue('E'.($index + 2), $value->namaGedung);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaLantai);
            
            $activeWorksheet->getStyle('B'.($index + 2))
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                
            $columns = ['A', 'C', 'D', 'E', 'F'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }
    
        $activeWorksheet->getStyle('A1:F1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:F'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:F')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'F') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Identitas Prasarana.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    

    public function createTemplate() {
        $data = $this->identitasPrasaranaModel->getAll();
        $keyGedung = $this->identitasGedungModel->findAll();
        $keyLantai = $this->identitasLantaiModel->findAll();
        $keyPrasarana = $this->identitasPrasaranaModel->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
        
        $headerInputTable = ['No.', 'Kode', 'Nama Prasarana', 'Luas', 'Lokasi Gedung', 'Lokasi Lantai' ,'ID Prasarana'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerGedungID = ['ID Identitas Gedung', 'Nama Gedung'];
        $activeWorksheet->fromArray([$headerGedungID], NULL, 'I1');
        $activeWorksheet->getStyle('I1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerLantaiID = ['ID Identitas Lantai', 'Nama Lantai'];
        $activeWorksheet->fromArray([$headerLantaiID], NULL, 'L1');
        $activeWorksheet->getStyle('L1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerPrasaranaID = ['ID Identitas Prasarana', 'Nama Prasarana'];
        $activeWorksheet->fromArray([$headerLantaiID], NULL, 'O1');
        $activeWorksheet->getStyle('O1:P1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            if ($index >= 1) {
                break;
            }

            $formula = '=CONCAT("P", TEXT(G'.($index + 2).', "000"), "/G", TEXT(E'.($index + 2).', "00"), "/L", TEXT(F'.($index + 2).', "00"))';
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $formula);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaPrasarana);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->luas);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->idIdentitasGedung);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->idIdentitasLantai);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->idIdentitasPrasarana);
            
            $activeWorksheet->getStyle('B'.($index + 2))
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                
            $columns = ['A', 'C', 'D', 'E', 'F', 'G'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }
    
        $activeWorksheet->getStyle('A1:G1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:G'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:G')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'G') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        foreach ($keyGedung as $index => $value) {
            $activeWorksheet->setCellValue('I'.($index + 2), $value->idIdentitasGedung);
            $activeWorksheet->setCellValue('J'.($index + 2), $value->namaGedung);
    
            $columns = ['I', 'J'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('I1:J1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('I1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('I1:J'.(count($keyGedung) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('I:J')->getAlignment()->setWrapText(true);
    
        foreach (range('I', 'J') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keyLantai as $index => $value) {
            $activeWorksheet->setCellValue('L'.($index + 2), $value->idIdentitasLantai);
            $activeWorksheet->setCellValue('M'.($index + 2), $value->namaLantai);
    
            $columns = ['L', 'M'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('L1:M1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('L1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('L1:M'.(count($keyLantai) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('L:M')->getAlignment()->setWrapText(true);
    
        foreach (range('L', 'M') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keyPrasarana as $index => $value) {
            $activeWorksheet->setCellValue('O'.($index + 2), $value->idIdentitasPrasarana);
            $activeWorksheet->setCellValue('P'.($index + 2), $value->namaPrasarana);
    
            $columns = ['O', 'P'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('O1:P1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('O1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('O1:P'.(count($keyPrasarana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('O:P')->getAlignment()->setWrapText(true);
    
        foreach (range('O', 'P') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headerExampleTable = ['No.', 'Kode', 'Nama Prasarana', 'Luas', 'Lokasi Gedung', 'Lokasi Lantai' ,'ID Prasarana'];
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->kodePrasarana);
            $exampleSheet->setCellValue('C'.($index + 2), $value->namaPrasarana);
            $exampleSheet->setCellValue('D'.($index + 2), $value->luas);
            $exampleSheet->setCellValue('E'.($index + 2), $value->idIdentitasGedung);
            $exampleSheet->setCellValue('F'.($index + 2), $value->idIdentitasLantai);
            $exampleSheet->setCellValue('G'.($index + 2), $value->idIdentitasPrasarana);
            
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
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Identitas Prasarana Example.xlsx');
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
                $namaPrasarana      = $value[2] ?? null;
                $luas               = $value[3] ?? null;
                $idIdentitasGedung  = $value[4] ?? null;
                $idIdentitasLantai  = $value[5] ?? null;
                $kodePrasarana      = $value[1] ?? null;

                if ($namaPrasarana !== null) {
                    $data = [
                        'namaPrasarana' => $namaPrasarana,
                        'luas' => $luas,
                        'idIdentitasGedung' => $idIdentitasGedung,
                        'idIdentitasLantai' => $idIdentitasLantai,
                        'kodePrasarana' => $kodePrasarana,
                    ];
                    $this->identitasPrasaranaModel->insert($data);
                }
            }
            return redirect()->to(site_url('identitasPrasarana'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('identitasPrasarana'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }

    public function generatePDF() {
        $filePath = APPPATH . 'Views/master/identitasPrasaranaView/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataidentitasPrasarana'] = $this->identitasPrasaranaModel->getAll();

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
        $filename = 'Identitas Prasarana Report.pdf';
        $dompdf->stream($filename);
    }
}

