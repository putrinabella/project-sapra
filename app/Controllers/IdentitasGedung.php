<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\IdentitasGedungModels;
use App\Models\UserActionLogsModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class IdentitasGedung extends ResourcePresenter
{
    function __construct() {
        $this->identitasGedungModel = new IdentitasGedungModels();
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['pdf', 'custom']);
    }

    public function index()
    {
        $data['dataIdentitasGedung'] = $this->identitasGedungModel->findAll();
        return view('master/identitasGedungView/index', $data);
    }


    public function new()
    {
        return view('master/identitasGedungView/new');
    }

    public function create()
    {
        $data = $this->request->getPost();
        $this->identitasGedungModel->insert($data);
        return redirect()->to(site_url('identitasGedung'))->with('success', 'Data berhasil disimpan');
    }

    public function edit($id = null)
    {
        if ($id != null) {
            $dataIdentitasGedung = $this->identitasGedungModel->where('idIdentitasGedung', $id)->first();
    
            if (is_object($dataIdentitasGedung)) {
                $data['dataIdentitasGedung'] = $dataIdentitasGedung;
                return view('master/identitasGedungView/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        $this->identitasGedungModel->update($id, $data);
        return redirect()->to(site_url('identitasGedung'))->with('success', 'Data berhasil update');
    }

    public function remove($id = null)
    {
        //
    }

    public function delete($id = null)
    {
        $this->identitasGedungModel->where('idIdentitasGedung', $id)->delete();
        activityLogs($this->userActionLogsModel, "Soft Delete", "Melakukan soft delete data Master - Identitas Gedung dengan id $id");
        return redirect()->to(site_url('identitasGedung'));
    }

    public function trash() {
        $data['dataIdentitasGedung'] = $this->identitasGedungModel->onlyDeleted()->findAll();
        return view('master/identitasGedungView/trash', $data);
    } 

    public function restore($id = null) {
        $affectedRows = restoreData('tblIdentitasGedung', 'idIdentitasGedung', $id, $this->userActionLogsModel, 'Master - Identitas Gedung');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('identitasGedung'))->with('success', 'Data berhasil direstore');
        }
    
        return redirect()->to(site_url('identitasGedung/trash'))->with('error', 'Tidak ada data untuk direstore');
    }

    public function deletePermanent($id = null) {
        $affectedRows = deleteData('tblIdentitasGedung', 'idIdentitasGedung', $id, $this->userActionLogsModel, 'Master - Identitas Gedung');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('identitasGedung'))->with('success', 'Data berhasil dihapus');
        } 
    
        return redirect()->to(site_url('identitasGedung/trash'))->with('error', 'Tidak ada data untuk dihapus');
    } 

    public function export() {
        $data = $this->identitasGedungModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
    
        $headers = ['No.', 'ID Identitas Gedung', 'Nama Gedung'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $idIdentitasGedung = str_pad($value->idIdentitasGedung, 3, '0', STR_PAD_LEFT);
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), 'G'.$idIdentitasGedung);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaGedung);
    
            $columns = ['A', 'B'];

            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }     
            $activeWorksheet->getStyle('C'.($index + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        }
    
        $activeWorksheet->getStyle('A1:C1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:C'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:C')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'C') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Data Master - Identitas Gedung.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function createTemplate() {
        $data = $this->identitasGedungModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');

        $headers = ['No.', 'Nama Gedung'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), '');
    
            $columns = ['A', 'B'];

            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }     
        }
    
        $activeWorksheet->getStyle('A1:B1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:B'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:B')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'B') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headers = ['No.', 'Nama Gedung'];
        $exampleSheet->fromArray([$headers], NULL, 'A1');
        $exampleSheet->getStyle('A1:B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->namaGedung);
    
            $columns = ['A', 'B'];

            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }     
        }
    
        $exampleSheet->getStyle('A1:B1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $exampleSheet->getStyle('A1:B'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:B')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'B') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Identitas Gedung Example.xlsx');
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
            
                $namaGedung = $value[1] ?? null;

                $data = [
                    'namaGedung' => $namaGedung,
                ];
                
                if (!empty($data['namaGedung'])) {
                    $this->identitasGedungModel->insert($data);
                } else {
                    return redirect()->to(site_url('identitasGedung'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('identitasGedung'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('identitasGedung'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }

    public function generatePDF() {
        $dataIdentitasGedung = $this->identitasGedungModel->findAll();
        $title = "MASTER - IDENTITAS GEDUNG";
        
        if (!$dataIdentitasGedung) {
            return view('error/404');
        }
    
        $pdfData = pdfMasterIdentitasGedung($dataIdentitasGedung, $title);
    
        $filename = 'Master - Identitas Gedung' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }
}
