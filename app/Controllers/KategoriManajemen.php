<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\KategoriManajemenModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class KategoriManajemen extends ResourcePresenter
{
    function __construct() {
        $this->kategoriManajemenModel = new KategoriManajemenModels();
    }

    public function index()
    {
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

    public function create()
    {
        $data = $this->request->getPost();
        $this->kategoriManajemenModel->insert($data);
        return redirect()->to(site_url('kategoriManajemen'))->with('success', 'Data berhasil disimpan');
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
        $data = $this->request->getPost();
        $this->kategoriManajemenModel->update($id, $data);
        return redirect()->to(site_url('kategoriManajemen'))->with('success', 'Data berhasil update');
    }

    public function remove($id = null)
    {
        //
    }

    public function delete($id = null)
    {
        $this->kategoriManajemenModel->where('idKategoriManajemen', $id)->delete();
        return redirect()->to(site_url('kategoriManajemen'));
    }

    public function trash() {
        $data['dataKategoriManajemen'] = $this->kategoriManajemenModel->onlyDeleted()->findAll();
        return view('master/kategoriManajemenView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblKategoriManajemen')
                ->set('deleted_at', null, true)
                ->where(['idKategoriManajemen' => $id])
                ->update();
        } else {
            $this->db->table('tblKategoriManajemen')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
        }

        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('kategoriManajemen'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('kategoriManajemen/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->kategoriManajemenModel->delete($id, true);
        return redirect()->to(site_url('kategoriManajemen/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->kategoriManajemenModel->onlyDeleted()->countAllResults();
            
            if ($countInTrash > 0) {
                $this->kategoriManajemenModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('kategoriManajemen/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('kategoriManajemen/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  

    public function export() {
        $data = $this->kategoriManajemenModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
    
        $headers = ['No.', 'ID Kategori Manajemen', 'Nama KategoriManajemen'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $idKategoriManajemen = str_pad($value->idKategoriManajemen, 3, '0', STR_PAD_LEFT);
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), 'KM'.$idKategoriManajemen);
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
        $activeWorksheet->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $activeWorksheet->getStyle('A1:C'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:C')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'C') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Kategori Manajemen.xlsx');
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
            
                $namaKategoriManajemen = $value[1] ?? null;
            
                if ($namaKategoriManajemen !== null) {
                    $data = [
                        'namaKategoriManajemen' => $namaKategoriManajemen,
                    ];
                    
                    $this->kategoriManajemenModel->insert($data);
                }
            }
            return redirect()->to(site_url('kategoriManajemen'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('kategoriManajemen'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }

    public function generatePDF() {
        $filePath = APPPATH . 'Views/master/kategoriManajemenView/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataKategoriManajemen'] = $this->kategoriManajemenModel->findAll();

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
        $filename = 'Kategori Manajemen Report.pdf';
        $dompdf->stream($filename);
    }
}
