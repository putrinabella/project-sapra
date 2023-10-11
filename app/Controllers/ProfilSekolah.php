<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProfilSekolahModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class ProfilSekolah extends ResourceController
{
    
     function __construct() {
        $this->profilSekolahModel = new ProfilSekolahModels();
    }

    public function index() {
        $dataProfilSekolah = $this->profilSekolahModel->findAll();
        $firstRecord = $this->profilSekolahModel->first();
        $firstRecordId = $firstRecord ? $firstRecord->idProfilSekolah : null;
        $rowCount =  $this->profilSekolahModel->getCount();
        $data = [
            'rowCount'              => $rowCount,
            'firstRecordId'        => $firstRecordId,
            'dataProfilSekolah'     => $dataProfilSekolah,
        ];
        return view('profilSekolahView/profilSekolah/index', $data);
    }
    

    public function show($id = null) {
        if ($id != null) {
            $dataProfilSekolah = $this->profilSekolahModel->find($id);
            $rowCount =  $this->profilSekolahModel->getCount();
            if (is_object($dataProfilSekolah)) {
                $data = [
                    'dataProfilSekolah'     => $dataProfilSekolah,
                    'rowCount'              => $rowCount,

                ];
                return view('profilSekolahView/profilSekolah/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data['dataProfilSekolah'] = $this->profilSekolahModel->findAll();
        return view('profilSekolahView/profilSekolah/new', $data);        
    }

    
    public function create() {
        $data = $this->request->getPost(); 
        if (!empty($data['npsn'])) {
            $this->profilSekolahModel->insert($data);
            return redirect()->to(site_url('profilSekolah'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('profilSekolah'))->with('error', 'Semua field harus terisi');
        }
    }

    public function edit($id = null) {
        if ($id != null) {
            $dataProfilSekolah = $this->profilSekolahModel->find($id);
    
            if (is_object($dataProfilSekolah)) {
                $data = [
                    'dataProfilSekolah' => $dataProfilSekolah,
                ];
                return view('profilSekolahView/profilSekolah/edit', $data);
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
            if (!empty($data['npsn'])) {
                $this->profilSekolahModel->update($id, $data);
                return redirect()->to(site_url('profilSekolah'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to
                (site_url('profilSekolah/edit/'.$id))->with('error', 'Id Sarana dan Lantai harus diisi.');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->profilSekolahModel->delete($id);
        return redirect()->to(site_url('profilSekolah'));
    }

    
    public function export() {
        $data = $this->profilSekolahModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('IT - ProfilSekolah');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Aplikasi Sosial Media', 'Username', 'Link'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->namaProfilSekolah);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->usernameProfilSekolah);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->linkProfilSekolah);

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
        header('Content-Disposition: attachment;filename=IT - ProfilSekolah.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->profilSekolahModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('IT - ProfilSekolah');
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
            $exampleSheet->setCellValue('B'.($index + 2), $value->namaProfilSekolah);
            $exampleSheet->setCellValue('C'.($index + 2), $value->usernameProfilSekolah);
            $exampleSheet->setCellValue('D'.($index + 2), $value->linkProfilSekolah);

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
        header('Content-Disposition: attachment;filename=IT - ProfilSekolah Example.xlsx');
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
                $namaProfilSekolah            = $value[1] ?? null;
                $usernameProfilSekolah        = $value[2] ?? null;
                $linkProfilSekolah            = $value[3] ?? null;

                if ($namaProfilSekolah !== null) {
                    $data = [
                        'namaProfilSekolah'       => $namaProfilSekolah,
                        'usernameProfilSekolah'   => $usernameProfilSekolah,
                        'linkProfilSekolah'       => $linkProfilSekolah,

                    ];
                    $this->profilSekolahModel->insert($data);
                }
            }
            return redirect()->to(site_url('profilSekolah'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('profilSekolah'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function print($id = null) {
        $dataProfilSekolah = $this->profilSekolahModel->find($id);
        
        if (!is_object($dataProfilSekolah)) {
            return view('error/404');
        }

        $data = [
            'dataProfilSekolah'         => $dataProfilSekolah,
        ];

        $filePath = APPPATH . 'Views/profilSekolahView/profilSekolah/print.php';

        if (!file_exists($filePath)) {
            return view('error/404');
        }

        ob_start();

        $includeFile = function ($filePath, $data) {
            include $filePath;
        };

        $includeFile($filePath, $data);

        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $filename = "Profil - Identitas Sekolah.pdf";
        $dompdf->stream($filename);
    }
}