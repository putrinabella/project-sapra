<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\KategoriManajemenModels;
use App\Models\UserActionLogsModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class KategoriManajemen extends ResourcePresenter
{
    function __construct() {
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['pdf', 'custom']);
    }

    public function index()
    {
        // Line 21 katalog 1
        $data['dataKategoriManajemen'] = $this->kategoriManajemenModel->findAll();
        return view('master/kategoriManajemenView/index', $data);
    }

    public function show($id = null)
    {
        //
    }

    public function new()
    {
        return view('master/kategoriManajemenView/new');
    }

    public function create() {
        $data = $this->request->getPost();
    
        $kodeKategoriManajemen = $data['kodeKategoriManajemen'];
        $namaKategoriManajemen = $data['namaKategoriManajemen'];
    
        if ($this->kategoriManajemenModel->isDuplicate($kodeKategoriManajemen, $namaKategoriManajemen)) {
            return redirect()->to(site_url('kategoriManajemen'))->with('error', 'Ditemukan duplikat data! Masukkan data yang berbeda.');
        } else {
            $this->kategoriManajemenModel->insert($data);
            return redirect()->to(site_url('kategoriManajemen'))->with('success', 'Data berhasil disimpan');
        }
    }

    public function edit($id = null)
    {
        if ($id != null) {
            $dataKategoriManajemen = $this->kategoriManajemenModel->where('idKategoriManajemen', $id)->first();
    
            if (is_object($dataKategoriManajemen)) {
                $data['dataKategoriManajemen'] = $dataKategoriManajemen;
                return view('master/kategoriManajemenView/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function update($id = null)
    {
        if ($id != null) {
        $data = $this->request->getPost();
    
        $kodeKategoriManajemen = $data['kodeKategoriManajemen'];
        $namaKategoriManajemen = $data['namaKategoriManajemen'];
    
            $existingData = $this->kategoriManajemenModel->find($id);
            if ($existingData->kodeKategoriManajemen != $kodeKategoriManajemen && $existingData->namaKategoriManajemen != $namaKategoriManajemen ) {
                if ($this->kategoriManajemenModel->isDuplicate($kodeKategoriManajemen, $namaKategoriManajemen)) {
                    return redirect()->to(site_url('kategoriManajemen'))->with('error', 'Gagal update karena ditemukan duplikat data!');
                }
            } else if ($existingData->kodeKategoriManajemen != $kodeKategoriManajemen) {
                if ($this->kategoriManajemenModel->kodeKategoriManajemenDuplicate($kodeKategoriManajemen)) {
                    return redirect()->to(site_url('kategoriManajemen'))->with('error', 'Gagal update karena kode prasarana duplikat!');
                }
            } else if ($existingData->namaKategoriManajemen != $namaKategoriManajemen) {
                if ($this->kategoriManajemenModel->namaKategoriManajemenDuplicate($namaKategoriManajemen)) {
                    return redirect()->to(site_url('kategoriManajemen'))->with('error', 'Gagal update karena nama prasarana duplikat!');
                }
            }       
            $this->kategoriManajemenModel->update($id, $data);
            return redirect()->to(site_url('kategoriManajemen'))->with('success', 'Data berhasil update');
        }
}

    public function remove($id = null)
    {
        //
    }

    public function delete($id = null) {
        $this->kategoriManajemenModel->delete($id);
        activityLogs($this->userActionLogsModel, "Soft Delete", "Melakukan soft delete data Master - Kategori Manajemen dengan id $id");
        return redirect()->to(site_url('kategoriManajemen'));
    }

    public function trash() {
        $data['dataKategoriManajemen'] = $this->kategoriManajemenModel->onlyDeleted()->findAll();
        return view('master/kategoriManajemenView/trash', $data);
    } 

    public function restore($id = null) {
        $affectedRows = restoreData('tblKategoriManajemen', 'idKategoriManajemen', $id, $this->userActionLogsModel, 'Master - Kategori Manajemen');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('kategoriManajemen'))->with('success', 'Data berhasil direstore');
        }
    
        return redirect()->to(site_url('kategoriManajemen/trash'))->with('error', 'Tidak ada data untuk direstore');
    }

    public function deletePermanent($id = null) {
        $affectedRows = deleteData('tblKategoriManajemen', 'idKategoriManajemen', $id, $this->userActionLogsModel, 'Master - Kategori Manajemen');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('kategoriManajemen'))->with('success', 'Data berhasil dihapus');
        } 
    
        return redirect()->to(site_url('kategoriManajemen/trash'))->with('error', 'Tidak ada data untuk dihapus');
    } 

    public function export() {
        $data = $this->kategoriManajemenModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
    
        $headers = ['No.', 'Kode', 'Nama Kategori Barang'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            // $idKategoriManajemen = str_pad($value->idKategoriManajemen, 3, '0', STR_PAD_LEFT);
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->kodeKategoriManajemen);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaKategoriManajemen);
    
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
        header('Content-Disposition: attachment;filename=Data Master - Kategori Barang.xlsx');
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
            
                $kodeKategoriManajemen = $value[1] ?? null;
                $namaKategoriManajemen = $value[2] ?? null;
            
                $data = [
                    'kodeKategoriManajemen' => $kodeKategoriManajemen,
                    'namaKategoriManajemen' => $namaKategoriManajemen,
                ];
                if (!empty($data['kodeKategoriManajemen']) && !empty($data['namaKategoriManajemen'])) {
                    $this->kategoriManajemenModel->insert($data);
                } else {
                    return redirect()->to(site_url('kategoriManajemen'))->with('success', 'Data berhasil diimport');
                }
            }
            return redirect()->to(site_url('kategoriManajemen'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('kategoriManajemen'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }

    public function createTemplate() {
        $data = $this->kategoriManajemenModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');

        $headers = ['No.', 'Kode' , 'Nama Kategori Barang'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), '');
            $activeWorksheet->setCellValue('C'.($index + 2), '');
    
            $columns = ['A', 'B', 'C'];

            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }     
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

        $headers = ['No.', 'Kode' , 'Nama Kategori Barang'];
        $exampleSheet->fromArray([$headers], NULL, 'A1');
        $exampleSheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            if ($index >= 5) {
                break;
            }
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->kodeKategoriManajemen);
            $exampleSheet->setCellValue('C'.($index + 2), $value->namaKategoriManajemen);
    
            $columns = ['A', 'B', 'C'];

            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }     
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
        header('Content-Disposition: attachment;filename=Kategori Manajemen Example.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function generatePDF() {
        $dataKategoriManajemen = $this->kategoriManajemenModel->findAll();
        $title = "MASTER - KATEGORI MANAJEMEN";
        
        if (!$dataKategoriManajemen) {
            return view('error/404');
        }
    
        $pdfData = pdfMasterKategoriManajemen($dataKategoriManajemen, $title);
    
        $filename = 'Master - Kategori Manajemen' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }
}
