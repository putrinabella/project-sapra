<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\KategoriMepModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class KategoriMep extends ResourcePresenter
{
    function __construct() {
        $this->kategoriMepModel = new KategoriMepModels();
    }

    public function index()
    {
        $data['dataKategoriMep'] = $this->kategoriMepModel->findAll();
        return view('master/kategoriMepView/index', $data);
    }

    public function show($id = null)
    {
        //
    }

    public function new()
    {
        return view('master/kategoriMepView/new');
    }

    public function create() {
        $data = $this->request->getPost();
    
        $namaKategoriMep = $data['namaKategoriMep'];
    
        if ($this->kategoriMepModel->isDuplicate($namaKategoriMep)) {
            return redirect()->to(site_url('kategoriMep'))->with('error', 'Ditemukan duplikat data! Masukkan data yang berbeda.');
        } else {
            $this->kategoriMepModel->insert($data);
            return redirect()->to(site_url('kategoriMep'))->with('success', 'Data berhasil disimpan');
        }
    }

    public function edit($id = null)
    {
        if ($id != null) {
            $dataKategoriMep = $this->kategoriMepModel->where('idKategoriMep', $id)->first();
    
            if (is_object($dataKategoriMep)) {
                $data['dataKategoriMep'] = $dataKategoriMep;
                return view('master/kategoriMepView/edit', $data);
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
    
        $namaKategoriMep = $data['namaKategoriMep'];
    
        if ($this->kategoriMepModel->isDuplicate($namaKategoriMep)) {
            return redirect()->to(site_url('kategoriMep'))->with('error', 'Ditemukan duplikat data! Masukkan data yang berbeda.');
        } else {
            $this->kategoriMepModel->update($id, $data);
            return redirect()->to(site_url('kategoriMep'))->with('success', 'Data berhasil update');
        }
    }

    public function remove($id = null)
    {
        //
    }

    public function delete($id = null)
    {
        $this->kategoriMepModel->where('idKategoriMep', $id)->delete();
        return redirect()->to(site_url('kategoriMep'));
    }

    public function trash() {
        $data['dataKategoriMep'] = $this->kategoriMepModel->onlyDeleted()->findAll();
        return view('master/kategoriMepView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblKategoriMep')
                ->set('deleted_at', null, true)
                ->where(['idKategoriMep' => $id])
                ->update();
        } else {
            $this->db->table('tblKategoriMep')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
        }

        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('kategoriMep'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('kategoriMep/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->kategoriMepModel->delete($id, true);
        return redirect()->to(site_url('kategoriMep/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->kategoriMepModel->onlyDeleted()->countAllResults();
            
            if ($countInTrash > 0) {
                $this->kategoriMepModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('kategoriMep/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('kategoriMep/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  

    public function export() {
        $data = $this->kategoriMepModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
    
        $headers = ['No.', 'Nama Kategori MEP'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->namaKategoriMep);
    
            $columns = ['A', 'B'];

            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }     
        }
    
        $activeWorksheet->getStyle('A1:B1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $activeWorksheet->getStyle('A1:B'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:B')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'B') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Data Master - Kategori MEP.xlsx');
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
            
                $namaKategoriMep = $value[1] ?? null;
            
                $data = [
                    'namaKategoriMep' => $namaKategoriMep,
                ];
                if (!empty($data['namaKategoriMep'])) {
                    $this->kategoriMepModel->insert($data);
                } else {
                    return redirect()->to(site_url('kategoriMep'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('kategoriMep'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('kategoriMep'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }

    public function createTemplate() {
        $data = $this->kategoriMepModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');

        $headers = ['No.', 'Nama Kategori MEP'];
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
        $activeWorksheet->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $activeWorksheet->getStyle('A1:B'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:B')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'B') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headers = ['No.', 'Nama Kategori MEP'];
        $exampleSheet->fromArray([$headers], NULL, 'A1');
        $exampleSheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            if ($index >= 5) {
                break;
            }
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->namaKategoriMep);
    
            $columns = ['A', 'B'];

            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }     
        }
    
        $exampleSheet->getStyle('A1:B1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $exampleSheet->getStyle('A1:B'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:B')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'B') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Kategori MEP Example.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function generatePDF() {
        $filePath = APPPATH . 'Views/master/kategoriMepView/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataKategoriMep'] = $this->kategoriMepModel->findAll();

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
        $filename = 'Kategori MEP Report.pdf';
        $dompdf->stream($filename);
    }
}
