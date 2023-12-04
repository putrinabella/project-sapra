<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\KategoriPegawaiModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class KategoriPegawai extends ResourcePresenter
{
    function __construct() {
        $this->kategoriPegawaiModel = new KategoriPegawaiModels();
    }

    public function index()
    {
        $data['dataKategoriPegawai'] = $this->kategoriPegawaiModel->findAll();
        return view('master/kategoriPegawaiView/index', $data);
    }

    public function show($id = null)
    {
        //
    }

    public function new()
    {
        return view('master/kategoriPegawaiView/new');
    }

    public function create()
    {
        $data = $this->request->getPost();
        $this->kategoriPegawaiModel->insert($data);
        return redirect()->to(site_url('kategoriPegawai'))->with('success', 'Data berhasil disimpan');
    }

    public function edit($id = null)
    {
        if ($id != null) {
            $dataKategoriPegawai = $this->kategoriPegawaiModel->where('idKategoriPegawai', $id)->first();
    
            if (is_object($dataKategoriPegawai)) {
                $data['dataKategoriPegawai'] = $dataKategoriPegawai;
                return view('master/kategoriPegawaiView/edit', $data);
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
        $this->kategoriPegawaiModel->update($id, $data);
        return redirect()->to(site_url('kategoriPegawai'))->with('success', 'Data berhasil update');
    }

    public function remove($id = null)
    {
        //
    }

    public function delete($id = null)
    {
        $this->kategoriPegawaiModel->where('idKategoriPegawai', $id)->delete();
        return redirect()->to(site_url('kategoriPegawai'));
    }

    public function trash() {
        $data['dataKategoriPegawai'] = $this->kategoriPegawaiModel->onlyDeleted()->findAll();
        return view('master/kategoriPegawaiView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblKategoriPegawai')
                ->set('deleted_at', null, true)
                ->where(['idKategoriPegawai' => $id])
                ->update();
        } else {
            $this->db->table('tblKategoriPegawai')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
        }

        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('kategoriPegawai'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('kategoriPegawai/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->kategoriPegawaiModel->delete($id, true);
        return redirect()->to(site_url('kategoriPegawai/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->kategoriPegawaiModel->onlyDeleted()->countAllResults();
            
            if ($countInTrash > 0) {
                $this->kategoriPegawaiModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('kategoriPegawai/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('kategoriPegawai/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  

    public function export() {
        $data = $this->kategoriPegawaiModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
    
        $headers = ['No.', 'ID Kategori Pegawai', 'Nama Kategori Pegawai'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $idKategoriPegawai = str_pad($value->idKategoriPegawai, 3, '0', STR_PAD_LEFT);
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $idKategoriPegawai);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaKategoriPegawai);
    
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
        header('Content-Disposition: attachment;filename=Data Master - Kategori Pegawai.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function createTemplate() {
        $data = $this->kategoriPegawaiModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');

        $headers = ['No.', 'Nama Kategori Pegawai'];
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

        $headers = ['No.', 'Nama Kategori Pegawai'];
        $exampleSheet->fromArray([$headers], NULL, 'A1');
        $exampleSheet->getStyle('A1:B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->namaKategoriPegawai);
    
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
        header('Content-Disposition: attachment;filename=Kategori Pegawai Example.xlsx');
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
            
                $namaKategoriPegawai = $value[1] ?? null;

                $data = [
                    'namaKategoriPegawai' => $namaKategoriPegawai,
                ];
                
                if (!empty($data['namaKategoriPegawai'])) {
                    $this->kategoriPegawaiModel->insert($data);
                } else {
                    return redirect()->to(site_url('kategoriPegawai'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('kategoriPegawai'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('kategoriPegawai'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }

    public function generatePDF() {
        $filePath = APPPATH . 'Views/master/kategoriPegawaiView/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataKategoriPegawai'] = $this->kategoriPegawaiModel->findAll();

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
        $filename = 'Data Master - Kategori Pegawai.pdf';
        $dompdf->stream($filename);
    }

    
}
