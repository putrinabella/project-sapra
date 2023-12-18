<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\NonInventarisModels; 
use App\Models\UserActionLogsModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class NonInventaris extends ResourceController
{
    
    function __construct() {
        $this->nonInventarisModel = new NonInventarisModels();
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['pdf', 'custom']);
    }
    public function index() {
        $data['dataNonInventaris'] = $this->nonInventarisModel->getAll();
        return view('master/nonInventarisView/index', $data);
    }
    
    public function show($id = null) {
        if ($id != null) {
            $dataNonInventaris = $this->nonInventarisModel->find($id);
        
            if (is_object($dataNonInventaris)) {
                $spesifikasiMarkup = $dataNonInventaris->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataNonInventaris->bukti);
                $data = [
                    'dataNonInventaris'           => $dataNonInventaris,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];
                return view('master/nonInventarisView/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data['dataNonInventaris'] = $this->nonInventarisModel->findAll();
        
        return view('master/nonInventarisView/new', $data);        
    }

    
    public function create() {
        $data = $this->request->getPost(); 
        if (!empty($data['nama'])  && !empty($data['satuan']) ) {
            $this->nonInventarisModel->insert($data);
            return redirect()->to(site_url('nonInventaris'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('nonInventaris'))->with('error', 'Semua field harus terisi');
        }
    }

    public function edit($id = null) {
        if ($id != null) {
            $dataNonInventaris = $this->nonInventarisModel->find($id);
    
            if (is_object($dataNonInventaris)) {
                $data = [
                    'dataNonInventaris' => $dataNonInventaris,
                ];
                return view('master/nonInventarisView/edit', $data);
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
            if (!empty($data['nama'])  && !empty($data['satuan']) ) {
                $this->nonInventarisModel->update($id, $data);
                return redirect()->to(site_url('nonInventaris'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to(site_url('nonInventaris'))->with('error', 'Semua data harus diisi');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->nonInventarisModel->delete($id);
        activityLogs($this->userActionLogsModel, "Soft Delete", "Melakukan soft delete data Master - Non Inventaris dengan id $id");
        return redirect()->to(site_url('nonInventaris'));
    }

    public function trash() {
        $data['dataNonInventaris'] = $this->nonInventarisModel->onlyDeleted()->getRecycle();
        return view('master/nonInventarisView/trash', $data);
    } 

    public function restore($id = null) {
        $affectedRows = restoreData('tblNonInventaris', 'idNonInventaris', $id, $this->userActionLogsModel, 'Master - Non Inventaris');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('nonInventaris'))->with('success', 'Data berhasil direstore');
        }
    
        return redirect()->to(site_url('nonInventaris/trash'))->with('error', 'Tidak ada data untuk direstore');
    }

    public function deletePermanent($id = null) {
        $affectedRows = deleteData('tblNonInventaris', 'idNonInventaris', $id, $this->userActionLogsModel, 'Master - Non Inventaris');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('nonInventaris'))->with('success', 'Data berhasil dihapus');
        } 
    
        return redirect()->to(site_url('nonInventaris/trash'))->with('error', 'Tidak ada data untuk dihapus');
    }
    public function export() {
        $data = $this->nonInventarisModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
    
        $headers = ['No.', 'Nama', 'Satuan'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->nama);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->satuan);

            $activeWorksheet->getStyle('A'.($index + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $activeWorksheet->getStyle('B'.($index + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
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
        header('Content-Disposition: attachment;filename=Data Master - Non Inventaris.xlsx');
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
            
                $nama = $value[1] ?? null;
                $satuan = $value[2] ?? null;
            
                $data = [
                    'nama' => $nama,
                    'satuan' => $satuan,
                ];

                if (!empty($data['nama']) && !empty($data['satuan'])  ) {
                        $this->nonInventarisModel->insert($data);
                } else {
                    return redirect()->to(site_url('nonInventaris'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('nonInventaris'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('nonInventaris'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }

    public function createTemplate() {
        $data = $this->nonInventarisModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
        $headers = ['No.', 'Nama', 'Satuan'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), "");
            $activeWorksheet->setCellValue('C'.($index + 2), "");

            $activeWorksheet->getStyle('A'.($index + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $activeWorksheet->getStyle('B'.($index + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $activeWorksheet->getStyle('C'.($index + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        }
    
        $activeWorksheet->getStyle('A1:C1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:C'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:C')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'C') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');
        $exampleSheet->fromArray([$headers], NULL, 'A1');
        $exampleSheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->nama);
            $exampleSheet->setCellValue('C'.($index + 2), $value->satuan);

            $exampleSheet->getStyle('A'.($index + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $exampleSheet->getStyle('B'.($index + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $exampleSheet->getStyle('C'.($index + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        }
    
        $exampleSheet->getStyle('A1:C1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $exampleSheet->getStyle('A1:C'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:C')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'C') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Identitas Non Inventaris Example.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function generatePDF() {
        $dataNonInventaris = $this->nonInventarisModel->findAll();
        $title = "MASTER - NON INVENTARIS";
        
        if (!$dataNonInventaris) {
            return view('error/404');
        }
    
        $pdfData = pdfMasterNonInventaris($dataNonInventaris, $title);
    
        $filename = 'Master - Status Layanan' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }
}