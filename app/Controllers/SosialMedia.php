<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SosialMediaModels; 
use App\Models\UserActionLogsModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Parsedown;

class SosialMedia extends ResourceController
{
    
     function __construct() {
        $this->sosialMediaModel = new SosialMediaModels();
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['pdf', 'custom']);
    }

    public function index() {
        $data['dataSosialMedia'] = $this->sosialMediaModel->findAll();
        return view('profilSekolahView/sosialMedia/index', $data);
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
                return view('profilSekolahView/sosialMedia/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data['dataSosialMedia'] = $this->sosialMediaModel->findAll();
        
        return view('profilSekolahView/sosialMedia/new', $data);        
    }

    
    public function create() {
        $data = $this->request->getPost(); 
        if (!empty($data['namaSosialMedia']) && !empty($data['usernameSosialMedia']) && !empty($data['linkSosialMedia']) && !empty($data['picSosialMedia'])) {
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
                return view('profilSekolahView/sosialMedia/edit', $data);
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
            if (!empty($data['namaSosialMedia']) && !empty($data['usernameSosialMedia']) && !empty($data['linkSosialMedia']) && !empty($data['picSosialMedia'])) {
                $this->sosialMediaModel->update($id, $data);
                return redirect()->to(site_url('sosialMedia'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to
                (site_url('sosialMedia'))->with('error', 'Semua data harus diisi');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->sosialMediaModel->delete($id);
        activityLogs($this->userActionLogsModel, "Soft Delete", "Melakukan soft delete data Platfrom Digital - Sosial Media dengan id $id");
        return redirect()->to(site_url('sosialMedia'));
    }

    public function trash() {
        $data['dataSosialMedia'] = $this->sosialMediaModel->onlyDeleted()->getRecycle();
        return view('profilSekolahView/sosialMedia/trash', $data);
    } 

    public function restore($id = null) {
        $affectedRows = restoreData('tblSosialMedia', 'idSosialMedia', $id, $this->userActionLogsModel, 'Platfrom Digital - Sosial Media');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('sosialMedia'))->with('success', 'Data berhasil direstore');
        }
    
        return redirect()->to(site_url('sosialMedia/trash'))->with('error', 'Tidak ada data untuk direstore');
    }

    public function deletePermanent($id = null) {
        $affectedRows = deleteData('tblSosialMedia', 'idSosialMedia', $id, $this->userActionLogsModel, 'Platfrom Digital - Sosial Media');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('sosialMedia'))->with('success', 'Data berhasil dihapus');
        } 
    
        return redirect()->to(site_url('sosialMedia/trash'))->with('error', 'Tidak ada data untuk dihapus');
    }
    
    public function export() {
        $data = $this->sosialMediaModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('SosialMedia');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Aplikasi Sosial Media', 'Username', 'Link', 'PIC'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->namaSosialMedia);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->usernameSosialMedia);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->linkSosialMedia);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->picSosialMedia);

            $activeWorksheet->getStyle('A'.($index + 2))
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $columns = ['B', 'C', 'D', 'E'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }            
        }
        
        $activeWorksheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:E1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:E'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:E')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'E') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Platform Digital - Sosial Media.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->sosialMediaModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Aplikasi Sosial Media', 'Username', 'Link', 'PIC'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), '');
            $activeWorksheet->setCellValue('C'.($index + 2), '');
            $activeWorksheet->setCellValue('D'.($index + 2), '');
            $activeWorksheet->setCellValue('E'.($index + 2), '');

            $activeWorksheet->getStyle('A'.($index + 2))
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $columns = ['B', 'C', 'D', 'E'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }           
        }
        
        $activeWorksheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:E1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:E'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:E')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'E') as $column) {
            if ($column === 'A') {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
            $activeWorksheet->getColumnDimension($column)->setWidth(30);
        }

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headers = ['No.', 'Aplikasi Sosial Media', 'Username', 'Link', 'PIC'];
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
            $exampleSheet->setCellValue('E'.($index + 2), $value->picSosialMedia);

            $exampleSheet->getStyle('A'.($index + 2))
                            ->getAlignment()
                            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $columns = ['B', 'C', 'D', 'E'];
            
            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }            
        }
        
        $exampleSheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $exampleSheet->getStyle('A1:E1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:E'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:E')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'E') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Platform Digital - Sosial Media Example.xlsx');
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
                $picSosialMedia             = $value[4] ?? null;

                $data = [
                    'namaSosialMedia'       => $namaSosialMedia,
                    'usernameSosialMedia'   => $usernameSosialMedia,
                    'linkSosialMedia'       => $linkSosialMedia,
                    'picSosialMedia'        => $picSosialMedia,

                ];

                if (!empty($data['namaSosialMedia']) && !empty($data['usernameSosialMedia'])
                    && !empty($data['linkSosialMedia']) && !empty($data['picSosialMedia'])) {
                        $this->sosialMediaModel->insert($data);
                } else {
                    return redirect()->to(site_url('sosialMedia'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('sosialMedia'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('sosialMedia'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }

    public function generatePDF() {
        $dataSosialMedia = $this->sosialMediaModel->findAll();
        $title = "PLATFORM DIGITAL - SOSIAL MEDIA";
        
        if (!$dataSosialMedia) {
            return view('error/404');
        }
    
        $pdfData = pdfPlatformDigitalSosialMedia($dataSosialMedia, $title);
    
        
        $filename = 'Platfrom Digital - Sosial Media' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }
}