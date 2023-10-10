<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SosialMediaModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasPrasaranaModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class SosialMedia extends ResourceController
{
    
     function __construct() {
        $this->sosialMediaModel = new SosialMediaModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataSosialMedia'] = $this->sosialMediaModel->findAll();
        return view('itView/sosialMedia/index', $data);
    }

    public function show($id = null) {
        if ($id != null) {
            $dataSosialMedia = $this->sosialMediaModel->find($id);
        
            if (is_object($dataSosialMedia)) {
                $spesifikasiMarkup = $dataSosialMedia->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataSosialMedia->bukti);
                $data = [
                    'dataSosialMedia'           => $dataSosialMedia,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];
                return view('itView/sosialMedia/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data['dataSosialMedia'] = $this->sosialMediaModel->findAll();
        
        return view('itView/sosialMedia/new', $data);        
    }

    
    public function create() {
        $data = $this->request->getPost(); 
        if (!empty($data['namaSosialMedia']) && !empty($data['usernameSosialMedia']) && !empty($data['linkSosialMedia'])) {
            $this->sosialMediaModel->insert($data);
            return redirect()->to(site_url('sosialMedia'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('sosialMedia'))->with('error', 'Semua field harus terisi');
        }
    }

    public function edit($id = null) {
        if ($id != null) {
            $dataSosialMedia = $this->sosialMediaModel->find($id);
    
            if (is_object($dataSosialMedia)) {
                $data = [
                    'dataSosialMedia' => $dataSosialMedia,
                ];
                return view('itView/sosialMedia/edit', $data);
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
            if (!empty($data['namaSosialMedia']) && !empty($data['usernameSosialMedia']) && !empty($data['linkSosialMedia'])) {
                $this->sosialMediaModel->update($id, $data);
                return redirect()->to(site_url('sosialMedia'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to
                (site_url('sosialMedia/edit/'.$id))->with('error', 'Id Sarana dan Lantai harus diisi.');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->sosialMediaModel->delete($id);
        return redirect()->to(site_url('sosialMedia'));
    }

    public function trash() {
        $data['dataSosialMedia'] = $this->sosialMediaModel->onlyDeleted()->getRecycle();
        return view('itView/sosialMedia/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblSosialMedia')
                ->set('deleted_at', null, true)
                ->where(['idSosialMedia' => $id])
                ->update();
        } else {
            $this->db->table('tblSosialMedia')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('sosialMedia'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('sosialMedia/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->sosialMediaModel->delete($id, true);
        return redirect()->to(site_url('sosialMedia/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->sosialMediaModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->sosialMediaModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('sosialMedia/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('sosialMedia/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 
    
    public function export() {
        $data = $this->sosialMediaModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('IT - SosialMedia');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Aplikasi Sosial Media', 'Username', 'Link'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->namaSosialMedia);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->usernameSosialMedia);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->linkSosialMedia);

            $activeWorksheet->getStyle('A'.($index + 2))
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $columns = ['B', 'C', 'D'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }            
        }
        
        $activeWorksheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:D1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:D'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:D')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'D') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=IT - SosialMedia.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->sosialMediaModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('IT - SosialMedia');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Aplikasi Sosial Media', 'Username', 'Link'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), '');
            $activeWorksheet->setCellValue('C'.($index + 2), '');
            $activeWorksheet->setCellValue('D'.($index + 2), '');

            $activeWorksheet->getStyle('A'.($index + 2))
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $columns = ['B', 'C', 'D'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }           
        }
        
        $activeWorksheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:D1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:D'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:D')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'D') as $column) {
            if ($column === 'A') {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
            $activeWorksheet->getColumnDimension($column)->setWidth(30);
        }

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headers = ['No.', 'Aplikasi Sosial Media', 'Username', 'Link'];
        $exampleSheet->fromArray([$headers], NULL, 'A1');
        $exampleSheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->namaSosialMedia);
            $exampleSheet->setCellValue('C'.($index + 2), $value->usernameSosialMedia);
            $exampleSheet->setCellValue('D'.($index + 2), $value->linkSosialMedia);

            $exampleSheet->getStyle('A'.($index + 2))
                            ->getAlignment()
                            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $columns = ['B', 'C', 'D'];
            
            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }            
        }
        
        $exampleSheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $exampleSheet->getStyle('A1:D1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:D'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:D')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'D') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=IT - SosialMedia Example.xlsx');
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
                $namaSosialMedia            = $value[1] ?? null;
                $usernameSosialMedia        = $value[2] ?? null;
                $linkSosialMedia            = $value[3] ?? null;

                if ($namaSosialMedia !== null) {
                    $data = [
                        'namaSosialMedia'       => $namaSosialMedia,
                        'usernameSosialMedia'   => $usernameSosialMedia,
                        'linkSosialMedia'       => $linkSosialMedia,

                    ];
                    $this->sosialMediaModel->insert($data);
                }
            }
            return redirect()->to(site_url('sosialMedia'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('sosialMedia'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $filePath = APPPATH . 'Views/itView/sosialMedia/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataSosialMedia'] = $this->sosialMediaModel->findAll();

        ob_start();

        $includeFile = function ($filePath, $data) {
            include $filePath;
        };
    
        $includeFile($filePath, $data);
    
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $filename = 'IT - SosialMedia Report.pdf';
        $dompdf->stream($filename);
    }
}