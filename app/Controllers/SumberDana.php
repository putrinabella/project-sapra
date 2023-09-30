<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\SumberDanaModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class SumberDana extends ResourcePresenter
{
    function __construct() {
        $this->sumberDanaModel = new SumberDanaModels();
    }
        public function index()
    {
        $data['dataSumberDana'] = $this->sumberDanaModel->findAll();
        return view('master/sumberDanaView/index', $data);
    }

        public function show($id = null)
    {
        //
    }

        public function new()
    {
        return view('master/sumberDanaView/new');
    }

        public function create()
    {
        $data = $this->request->getPost();
        $this->sumberDanaModel->insert($data);
        return redirect()->to(site_url('sumberDana'))->with('success', 'Data berhasil disimpan');
    }

        public function edit($id = null)
    {
        if ($id != null) {
            $dataSumberDana = $this->sumberDanaModel->where('idSumberDana', $id)->first();
    
            if (is_object($dataSumberDana)) {
                $data['dataSumberDana'] = $dataSumberDana;
                return view('master/sumberDanaView/edit', $data);
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
        $this->sumberDanaModel->update($id, $data);
        return redirect()->to(site_url('sumberDana'))->with('success', 'Data berhasil update');
    }

        public function remove($id = null)
    {
        //
    }

        public function delete($id = null)
    {
        $this->sumberDanaModel->delete($id);
        return redirect()->to(site_url('sumberDana'));
    }

    public function trash() {
        $data['dataSumberDana'] = $this->sumberDanaModel->onlyDeleted()->findAll();
        return view('master/sumberDanaView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblSumberDana')
                ->set('deleted_at', null, true)
                ->where(['idSumberDana' => $id])
                ->update();
        } else {
            $this->db->table('tblSumberDana')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('sumberDana'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('sumberDana/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->sumberDanaModel->delete($id, true);
        return redirect()->to(site_url('sumberDana/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->sumberDanaModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->sumberDanaModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('sumberDana/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('sumberDana/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  

    public function export() {
        $data = $this->sumberDanaModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
    
        $headers = ['No.', 'ID Sumber Dana', 'Nama Sumber Dana'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $idSumberDana = str_pad($value->idSumberDana, 3, '0', STR_PAD_LEFT);
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), 'SD'.$idSumberDana);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaSumberDana);
    
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
        header('Content-Disposition: attachment;filename=Sumber Dana.xlsx');
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
            
                $namaSumberDana = $value[1] ?? null;
            
                if ($namaSumberDana !== null) {
                    $data = [
                        'namaSumberDana' => $namaSumberDana,
                    ];
                    
                    $this->sumberDanaModel->insert($data);
                }
            }
            return redirect()->to(site_url('sumberDana'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('sumberDana'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }

    public function generatePDF()
    {
        $filePath = APPPATH . 'Views/master/sumberDanaView/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataSumberDana'] = $this->sumberDanaModel->findAll();

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
        $filename = 'Sumber Dana Report.pdf';
        $dompdf->stream($filename);
    }
}
