<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\IdentitasSaranaModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class IdentitasSarana extends ResourcePresenter
{
    function __construct() {
        $this->identitasSaranaModel = new IdentitasSaranaModels();
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

    public function new()
    {
        return view('master/identitasSaranaView/new');
    }

    public function create() {
        $data = [
        'namaSarana' => $this->request->getPost('namaSarana'),
        'perangkatIT' => $this->request->getPost('perangkatIT') ? 1 : 0,
        ];

        $this->identitasSaranaModel->insert($data);
        return redirect()->to(site_url('identitasSarana'))->with('success', 'Data berhasil disimpan');
    }

    public function edit($id = null)
    {
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

    public function update($id = null)
    {
        $data = [
            'namaSarana' => $this->request->getPost('namaSarana'),
            'perangkatIT' => $this->request->getPost('perangkatIT') ? 1 : 0,
            ];
    
        $this->identitasSaranaModel->update($id, $data);
        return redirect()->to(site_url('identitasSarana'))->with('success', 'Data berhasil update');
    }

    public function remove($id = null)
    {
        //
    }

    public function delete($id = null)
    {
        $this->identitasSaranaModel->where('idIdentitasSarana', $id)->delete();
        return redirect()->to(site_url('identitasSarana'));
    }

    public function trash() {
        $data['dataIdentitasSarana'] = $this->identitasSaranaModel->onlyDeleted()->findAll();
        return view('master/identitasSaranaView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblIdentitasSarana')
                ->set('deleted_at', null, true)
                ->where(['idIdentitasSarana' => $id])
                ->update();
        } else {
            $this->db->table('tblIdentitasSarana')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
        }

        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('identitasSarana'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('identitasSarana/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->identitasSaranaModel->delete($id, true);
        return redirect()->to(site_url('identitasSarana/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->identitasSaranaModel->onlyDeleted()->countAllResults();
            
            if ($countInTrash > 0) {
                $this->identitasSaranaModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('identitasSarana/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('identitasSarana/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  

    public function export() {
        $data = $this->identitasSaranaModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
    
        $headers = ['No.', 'ID Identitas Sarana', 'Nama Sarana', 'Tipe'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $idIdentitasSarana = str_pad($value->idIdentitasSarana, 3, '0', STR_PAD_LEFT);
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), 'S'.$idIdentitasSarana);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaSarana);
            if ($value->perangkatIT == 1) {
                $activeWorksheet->setCellValue('D'.($index + 2), 'Perangkat IT');
            } else {
                $activeWorksheet->setCellValue('D'.($index + 2), 'Bukan Perangkat IT');
            }
    
            $columns = ['A', 'B', 'D'];

            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }     
            $activeWorksheet->getStyle('C'.($index + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        }
    
        $activeWorksheet->getStyle('A1:D1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $activeWorksheet->getStyle('A1:D'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:D')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'D') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Identitas Sarana.xlsx');
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
            
                $namaSarana = $value[1] ?? null;
                $perangkatIT = $value[2] ?? null;
            
                if ($namaSarana !== null) {
                    $data = [
                        'namaSarana' => $namaSarana,
                        'perangkatIT' => $perangkatIT,
                    ];
                    
                    $this->identitasSaranaModel->insert($data);
                }
            }
            return redirect()->to(site_url('identitasSarana'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('identitasSarana'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }

    public function generatePDF() {
        $filePath = APPPATH . 'Views/master/identitasSaranaView/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataIdentitasSarana'] = $this->identitasSaranaModel->findAll();

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
        $filename = 'Identitas Sarana Report.pdf';
        $dompdf->stream($filename);
    }
}
