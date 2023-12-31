<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\IdentitasGedungModels; 
use App\Models\IdentitasLantaiModels; 
use App\Models\IdentitasLabModels; 
use App\Models\UserActionLogsModels; 
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
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['pdf', 'custom']);
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
        activityLogs($this->userActionLogsModel, "Soft Delete", "Melakukan soft delete data Master - Identitas Laboraotrium dengan id $id");
        return redirect()->to(site_url('identitasLab'));
    }

    public function trash() {
        $data['dataIdentitasLab'] = $this->identitasLabModel->onlyDeleted()->getRecycle();
        return view('master/identitasLabView/trash', $data);
    } 

    public function restore($id = null) {
        $affectedRows = restoreData('tblIdentitasLab', 'idIdentitasLab', $id, $this->userActionLogsModel, 'Master - Identitas Laboraotriumoratorium');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('identitasLab'))->with('success', 'Data berhasil direstore');
        }
    
        return redirect()->to(site_url('identitasLab/trash'))->with('error', 'Tidak ada data untuk direstore');
    }

    public function deletePermanent($id = null) {
        $affectedRows = deleteData('tblIdentitasLab', 'idIdentitasLab', $id, $this->userActionLogsModel, 'Master - Identitas Laboratorium');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('identitasLab'))->with('success', 'Data berhasil dihapus');
        } 
    
        return redirect()->to(site_url('identitasLab/trash'))->with('error', 'Tidak ada data untuk dihapus');
    } 

    
    public function export() {
        $data = $this->identitasLabModel->getAll();
        $keyGedung = $this->identitasGedungModel->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
        
        $headerInputTable = ['No.', 'Kode', 'Nama Lab', 'Luas', 'Lokasi Gedung', 'Lokasi Lantai'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->kodeLab);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaLab);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->luas . ' m²');
            $activeWorksheet->setCellValue('E'.($index + 2), $value->namaGedung);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaLantai);
            
            // $activeWorksheet->getStyle('B'.($index + 2))
            //     ->getAlignment()
            //     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                
            $columns = ['A', 'B', 'C', 'D', 'E', 'F'];
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
        header('Content-Disposition: attachment;filename=Data Master - Identitas Laboratorium.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function generatePDF() {
        $dataIdentitasLab = $this->identitasLabModel->getAll();
        $title = "MASTER - IDENTITAS LABORATORIUM";
        
        if (!$dataIdentitasLab) {
            return view('error/404');
        }
    
        $pdfData = pdfMasterIdentitasLab($dataIdentitasLab, $title);
    
        $filename = 'Master - Identitas Laboratorium' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }

    public function createTemplate() {
        $data = $this->identitasLabModel->getAll();
        $keyGedung = $this->identitasGedungModel->findAll();
        $keyLantai = $this->identitasLantaiModel->findAll();
        $keyLab = $this->identitasLabModel->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
        
        $headerInputTable = ['No.', 'Kode Lab', 'Nama Lab', 'Luas', 'Lokasi Gedung', 'Lokasi Lantai'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerGedungID = ['ID Identitas Gedung', 'Nama Gedung'];
        $activeWorksheet->fromArray([$headerGedungID], NULL, 'L1');
        $activeWorksheet->getStyle('L1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerLantaiID = ['ID Identitas Lantai', 'Nama Lantai'];
        $activeWorksheet->fromArray([$headerLantaiID], NULL, 'O1');
        $activeWorksheet->getStyle('O1:P1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerLabID = ['ID Identitas Laboratorium', 'Nama Lab'];
        $activeWorksheet->fromArray([$headerLabID], NULL, 'R1');
        $activeWorksheet->getStyle('R1:S1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $latestID = null;

        foreach ($data as $index => $value) {
            if ($index >= 1) {
                break;
            }
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), '');
            $activeWorksheet->setCellValue('C'.($index + 2), '');
            $activeWorksheet->setCellValue('D'.($index + 2), '');
            $activeWorksheet->setCellValue('E'.($index + 2), '');
            $activeWorksheet->setCellValue('F'.($index + 2), '');
            
            

            $columns = ['A', 'B', 'C', 'D', 'E', 'F'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }
    
        $activeWorksheet->getStyle('A1:F1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:F'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:F')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'F') as $column) {
            if ($column === 'D' || $column === 'E') {
                $activeWorksheet->getColumnDimension($column)->setWidth(20);
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
        }
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

        $headerExampleTable = ['No.', 'Kode Lab', 'Nama Lab',  'Luas', 'Lokasi Gedung', 'Lokasi Lantai'];
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
        header('Content-Disposition: attachment;filename=Identitas Laboraotrium Example.xlsx');
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
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
    
            $spreadsheet = $reader->load($file);
            $theFile = $spreadsheet->getActiveSheet()->toArray();
    
            foreach ($theFile as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $kodeLab = $value[1] ?? null;
                $namaLab = $value[2] ?? null;
                $luas = $value[3] ?? null;
                $idIdentitasGedung = $value[4] ?? null;
                $idIdentitasLantai = $value[5] ?? null;
    
                $data = [
                    'namaLab' => $namaLab,
                    'luas' => $luas,
                    'idIdentitasGedung' => $idIdentitasGedung,
                    'idIdentitasLantai' => $idIdentitasLantai,
                    'kodeLab' => $kodeLab,
                ];
    
                if (!empty($data['namaLab']) && !empty($data['luas'])
                && !empty($data['idIdentitasGedung']) && !empty($data['idIdentitasLantai']) 
                && !empty($data['kodeLab'])) {
                    $this->identitasLabModel->insert($data);
                } else {
                    return redirect()->to(site_url('identitasLab'))->with('success', 'Data berhasil diimport');
                }
            }
            return redirect()->to(site_url('identitasLab'))->with('error', 'Pastikan semua data telah diisi!');
        } else {
            return redirect()->to(site_url('identitasLab'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }
    
}

