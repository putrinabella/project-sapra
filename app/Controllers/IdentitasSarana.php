<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\IdentitasSaranaModels;
use App\Models\UserActionLogsModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class IdentitasSarana extends ResourcePresenter
{
    function __construct() {
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['pdf', 'custom']);
    }

    public function index()
    {
        $data['dataIdentitasSarana'] = $this->identitasSaranaModel->findAll();
        return view('master/identitasSaranaView/index', $data);
    }

    public function show($id = null)
    {
        //
    }

    public function new() {
        return view('master/identitasSaranaView/new');
    }

    public function create() {
        $data = $this->request->getPost();

        $kodeSarana = $data['kodeSarana'];
        $namaSarana = $data['namaSarana'];
        $perangkatIT = isset($data['perangkatIT']) ? 1 : 0;
        
        if ($this->identitasSaranaModel->isDuplicate($kodeSarana, $namaSarana)) {
            return redirect()->to(site_url('identitasSarana'))->with('error', 'Ditemukan duplikat data! Masukkan data yang berbeda.');
        } else {
            $dataToInsert = [
                'kodeSarana' => $kodeSarana,
                'namaSarana' => $namaSarana,
                'perangkatIT' => $perangkatIT,
            ];
        
            $this->identitasSaranaModel->insert($dataToInsert);
            return redirect()->to(site_url('identitasSarana'))->with('success', 'Data berhasil disimpan');
        }
        
    }

    public function edit($id = null) {
        if ($id != null) {
            $dataIdentitasSarana = $this->identitasSaranaModel->where('idIdentitasSarana', $id)->first();
    
            if (is_object($dataIdentitasSarana)) {
                $data['dataIdentitasSarana'] = $dataIdentitasSarana;
                return view('master/identitasSaranaView/edit', $data);
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
            $kodeSarana = $data['kodeSarana'];
            $namaSarana = $data['namaSarana'];
            $perangkatIT = isset($data['perangkatIT']) ? 1 : 0;
    
            $existingData = $this->identitasSaranaModel->find($id);
            if ($existingData->kodeSarana != $kodeSarana && $existingData->namaSarana != $namaSarana) {
                if ($this->identitasSaranaModel->isDuplicate($kodeSarana, $namaSarana)) {
                    return redirect()->to(site_url('identitasSarana'))->with('error', 'Gagal update karena ditemukan duplikat data!');
                }
            } else if ($existingData->kodeSarana != $kodeSarana) {
                if ($this->identitasSaranaModel->kodeSaranaDuplicate($kodeSarana)) {
                    return redirect()->to(site_url('identitasSarana'))->with('error', 'Gagal update karena kode sarana duplikat!');
                }
            } else if ($existingData->namaSarana != $namaSarana) {
                if ($this->identitasSaranaModel->namaSaranaDuplicate($namaSarana)) {
                    return redirect()->to(site_url('identitasSarana'))->with('error', 'Gagal update karena nama sarana duplikat!');
                }
            }
            $dataToUpdate = [
                'kodeSarana' => $kodeSarana,
                'namaSarana' => $namaSarana,
                'perangkatIT' => $perangkatIT,
            ];
    
    
            $this->identitasSaranaModel->update($id, $dataToUpdate);
            return redirect()->to(site_url('identitasSarana'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }
    
    public function remove($id = null) {
        //
    }

    public function delete($id = null) {
        $this->identitasSaranaModel->delete($id);
        activityLogs($this->userActionLogsModel, "Soft Delete", "Melakukan soft delete data Master - Identitas Sarana dengan id $id");
        return redirect()->to(site_url('identitasSarana'));
    }

    public function trash() {
        $data['dataIdentitasSarana'] = $this->identitasSaranaModel->onlyDeleted()->findAll();
        return view('master/identitasSaranaView/trash', $data);
    } 

    public function restore($id = null) {
        $affectedRows = restoreData('tblIdentitasSarana', 'idIdentitasSarana', $id, $this->userActionLogsModel, 'Master - Identitas Sarana');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('identitasSarana'))->with('success', 'Data berhasil direstore');
        }
    
        return redirect()->to(site_url('identitasSarana/trash'))->with('error', 'Tidak ada data untuk direstore');
    }

    public function deletePermanent($id = null) {
        $affectedRows = deleteData('tblIdentitasSarana', 'idIdentitasSarana', $id, $this->userActionLogsModel, 'Master - Identitas Sarana');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('identitasSarana'))->with('success', 'Data berhasil dihapus');
        } 
    
        return redirect()->to(site_url('identitasSarana/trash'))->with('error', 'Tidak ada data untuk dihapus');
    } 


    public function export() {
        $data = $this->identitasSaranaModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
    
        $headers = ['No.', 'Kode', 'Nama Spesifikasi', 'Tipe'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->kodeSarana);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaSarana);
            if ($value->perangkatIT == 1) {
                $activeWorksheet->setCellValue('D'.($index + 2), 'Perangkat IT');
            } else {
                $activeWorksheet->setCellValue('D'.($index + 2), 'Bukan Perangkat IT');
            }
    
            $columns = ['A', 'B'];

            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }     
            $activeWorksheet->getStyle('C'.($index + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $activeWorksheet->getStyle('D'.($index + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        }
    
        $activeWorksheet->getStyle('A1:D1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:D'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:D')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'D') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Data Master - Identitas Spesifikasi.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function createTemplate() {
        $data = $this->identitasSaranaModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');

        $headers = ['No.', 'Kode' , 'Nama Spesifikasi', 'Perangkat IT', 'Status'];
        $headersType = ['Kode', 'Tipe'];
        $headersData = ['No.', 'Kode' , 'Nama Spesifikasi'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        $activeWorksheet->fromArray([$headersType], NULL, 'G1');
        $activeWorksheet->getStyle('G1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        $activeWorksheet->fromArray([$headersData], NULL, 'J1');
        $activeWorksheet->getStyle('J1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            if ($index >= 1) {
                break;
            }

            $getFormula = '=IF(AND(B2<>"",C2<>"",D2<>""),IF(OR(ISNUMBER(MATCH(B2,$K$2:$K$'.(count($data)+1).',0)),ISNUMBER(MATCH(C2,$L$2:$L$'.(count($data)+1).',0))),"ERROR: Duplicate Data",IF(AND(ISNUMBER(MATCH(D2,$G$2:$G$3,0))),"CORRECT","ERROR: Tipe tidak sesuai")),"ERROR: Empty data")';
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), '');
            $activeWorksheet->setCellValue('C'.($index + 2), '');
            $activeWorksheet->setCellValue('D'.($index + 2), '');
            $activeWorksheet->setCellValue('E'.($index + 2), $getFormula);
    
            $columns = ['A', 'B', 'C', 'D', 'E'];

            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }     
        }
    
        $activeWorksheet->getStyle('A1:E1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:E'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:E')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'E') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        $activeWorksheet->setCellValue('G2', '0');
        $activeWorksheet->setCellValue('G3', '1');

        $activeWorksheet->getStyle('G1')
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $activeWorksheet->getStyle('G1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('G1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $activeWorksheet->getStyle('G2:G3')
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $activeWorksheet->getStyle('G2:G3')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $activeWorksheet->getStyle('G')->getAlignment()->setWrapText(true);
        $activeWorksheet->getColumnDimension('G')->setAutoSize(true);

        $activeWorksheet->setCellValue('H2', 'Bukan Perangkat IT');
        $activeWorksheet->setCellValue('H3', 'Perangkat IT');

        $activeWorksheet->getStyle('H1')
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $activeWorksheet->getStyle('H1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('C7E8CA');
        $activeWorksheet->getStyle('H1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $activeWorksheet->getStyle('H2:H3')
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $activeWorksheet->getStyle('H2:H3')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $activeWorksheet->getStyle('H')->getAlignment()->setWrapText(true);
        $activeWorksheet->getColumnDimension('H')->setAutoSize(true);

        foreach ($data as $index => $value) {

            $activeWorksheet->setCellValue('J'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('K'.($index + 2), $value->kodeSarana);
            $activeWorksheet->setCellValue('L'.($index + 2), $value->namaSarana);
    
            $columns = ['J', 'K', 'L'];

            foreach ($columns as $column) {
                $alignment = ($column === 'L') ? \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT : \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal($alignment);
            }
            
        }
    
        $activeWorksheet->getStyle('J1:L1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('J1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('C7E8CA');
        $activeWorksheet->getStyle('J1:L'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('J:L')->getAlignment()->setWrapText(true);
    
        foreach (range('J', 'L') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headerExample = ['No.', 'Kode' , 'Nama Spesifikasi', 'Perangkat IT'];
        $exampleSheet->fromArray([$headerExample], NULL, 'A1');
        $exampleSheet->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            if ($index >= 5) {
                break;
            }
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->kodeSarana);
            $exampleSheet->setCellValue('C'.($index + 2), $value->namaSarana);
            $exampleSheet->setCellValue('D'.($index + 2), 0);
    
            $columns = ['A', 'B', 'C', 'D'];

            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }     
        }
    
        $exampleSheet->getStyle('A1:D1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $exampleSheet->getStyle('A1:D'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:D')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'D') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Identitas Spesifikasi Example.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function import() {
        $file = $this->request->getFile('formExcel');
        $extension = $file->getClientExtension();
        $hasErrors = false;
        $errorMessage = '';
        
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

                $kodeSarana = $value[1] ?? null;
                $namaSarana = $value[2] ?? null;
                $perangkatIT = $value[3] ?? null;
                $status = $value[4] ?? null;
            
                $data = [
                    'kodeSarana' => $kodeSarana,
                    'namaSarana' => $namaSarana,
                    'perangkatIT' => $perangkatIT,
                    'status' => $status,
                ];

                if ($status == 'CORRECT') {
                    if ($this->identitasSaranaModel->isDuplicate($kodeSarana, $namaSarana)) {
                        $hasErrors = true;
                        $errorMessage = 'Ditemukan duplikat data! Masukkan data yang berbeda.';
                        break;
                    } else {
                        $this->identitasSaranaModel->insert($data);
                    }
                } else if ($status == 'ERROR: Empty data') {
                    $hasErrors = true;
                    $errorMessage = 'Pastikan semua data telah terisi.';
                    break;
                } else if ($status == 'ERROR: Duplicate Data') {
                    $hasErrors = true;
                    $errorMessage = 'Ditemukan duplikat data! Masukkan data yang berbeda.';
                    break;
                } else if ($status == 'ERROR: Tipe tidak sesuai') {
                    $hasErrors = true;
                    $errorMessage = 'ERROR: Tipe tidak sesuai!';
                    break;
                }
            }
            if ($hasErrors) {
                return redirect()->to(site_url('identitasSarana'))->with('error', $errorMessage);
            } else {
                return redirect()->to(site_url('identitasSarana'))->with('success', 'Data berhasil diimport');
            }
        } else {
            return redirect()->to(site_url('identitasSarana'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }
    
    public function generatePDF() {
        $dataIdentitasSarana = $this->identitasSaranaModel->findAll();
        $title = "MASTER - IDENTITAS SARANA";
        
        if (!$dataIdentitasSarana) {
            return view('error/404');
        }
    
        $pdfData = pdfMasterIdentitasSarana($dataIdentitasSarana, $title);
    
        $filename = 'Master - Identitas Sarana' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }
}
