<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProfilSekolahModels; 
use App\Models\DokumenSekolahModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class ProfilSekolah extends ResourceController
{
    
     function __construct() {
        $this->profilSekolahModel = new ProfilSekolahModels();
        $this->dokumenSekolahModel = new DokumenSekolahModels();
    }

    public function index() {
        $dataProfilSekolah = $this->profilSekolahModel->findAll();
        $dataDokumenSekolah = $this->dokumenSekolahModel->findAll();

        $firstRecord = $this->profilSekolahModel->first();
        $firstRecordId = $firstRecord ? $firstRecord->idProfilSekolah : null;
        $rowCount =  $this->profilSekolahModel->getCount();
        $data = [
            'rowCount'              => $rowCount,
            'firstRecordId'         => $firstRecordId,
            'dataProfilSekolah'     => $dataProfilSekolah,
            'dataDokumenSekolah'    => $dataDokumenSekolah,
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

    public function newDokumen() {
        $data['dataDokumenSekolah'] = $this->dokumenSekolahModel->findAll();
        return view('profilSekolahView/profilSekolah/newDokumen', $data);        
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

    public function createDokumen() {
        $data = $this->request->getPost(); 
        if (!empty($data['namaDokumenSekolah'])) {
            $this->dokumenSekolahModel->insert($data);
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

    public function editDokumen($id = null) {
        if ($id != null) {
            $dataDokumenSekolah = $this->dokumenSekolahModel->find($id);
    
            if (is_object($dataDokumenSekolah)) {
                $data = [
                    'dataDokumenSekolah' => $dataDokumenSekolah,
                ];
                return view('profilSekolahView/profilSekolah/editDokumen', $data);
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
                (site_url('profilSekolah/edit/'.$id))->with('error', 'Semua data harus diisi.');
            }
        } else {
            return view('error/404');
        }
    }

    public function updateDokumen($id = null) {
        echo "Updating document with ID: $id";
        if ($id != null) {
            $data = $this->request->getPost();
            if (!empty($data['namaDokumenSekolah'])) {
                $this->dokumenSekolahModel->update($id, $data);
                return redirect()->to(site_url('profilSekolah'))->with('success', 'Dokumen berhasil diupdate');
            } else {
                return redirect()->to(site_url('profilSekolah/editDokumen/'.$id))->with('error', 'Semua data harus diisi.');
            }
        } else {
            return view('error/404');
        }
    }
    

    public function delete($id = null) {
        $this->profilSekolahModel->delete($id);
        return redirect()->to(site_url('profilSekolah'));
    }

    public function deleteDokumen($id = null) {
        $this->dokumenSekolahModel->delete($id);
        return redirect()->to(site_url('profilSekolah'));
    }

    public function trashDokumen() {
        $data['dataDokumenSekolah'] = $this->dokumenSekolahModel->onlyDeleted()->getRecycle();
        return view('profilSekolahView/profilSekolah/trashDokumen', $data);
    } 

    public function restoreDokumen($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblDokumenSekolah')
                ->set('deleted_at', null, true)
                ->where(['idDokumenSekolah' => $id])
                ->update();
        } else {
            $this->db->table('tblDokumenSekolah')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('profilSekolah'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('profilSekolah/trashDokumen'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->dokumenSekolahModel->delete($id, true);
        return redirect()->to(site_url('profilSekolah/trashDokumen'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->dokumenSekolahModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->dokumenSekolahModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('profilSekolah/trashDokumen'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('profilSekolah/trashDokumen'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 
    
    public function exportDokumen() {
        $data = $this->dokumenSekolahModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Dokumen Sekolah');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Nama', 'Link'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->namaDokumenSekolah);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->linkDokumenSekolah);

            $activeWorksheet->getStyle('A'.($index + 2))
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $columns = ['B', 'C'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }            
        }
        
        $activeWorksheet->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:C1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:C'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:C')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'C') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Profil Sekolah - Dokumen Sekolah.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplateDokumen() {
        $data = $this->dokumenSekolahModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Nama', 'Link'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), '');
            $activeWorksheet->setCellValue('C'.($index + 2), '');

            $activeWorksheet->getStyle('A'.($index + 2))
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $columns = ['B', 'C'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }            
        }
        
        $activeWorksheet->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:C1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:C'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:C')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'C') as $column) {
            if ($column === 'A') {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            } else if ($column === 'B') {
                $activeWorksheet->getColumnDimension($column)->setWidth(30);
            } else if ($column === 'C') {
                $activeWorksheet->getColumnDimension($column)->setWidth(60);
            }
        }
        

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');
    
        $headers = ['No.', 'Nama', 'Link'];
        $exampleSheet->fromArray([$headers], NULL, 'A1');
        $exampleSheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->namaDokumenSekolah);
            $exampleSheet->setCellValue('C'.($index + 2), $value->linkDokumenSekolah);

            $exampleSheet->getStyle('A'.($index + 2))
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $columns = ['B', 'C'];
            
            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }            
        }
        
        $exampleSheet->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $exampleSheet->getStyle('A1:C1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:C'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:C')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'C') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Profil Sekolah - Dokumen Sekolah Example.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function importDokumen() {
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
                $namaDokumenSekolah            = $value[1] ?? null;
                $linkDokumenSekolah            = $value[2] ?? null;

                    $data = [
                        'namaDokumenSekolah'       => $namaDokumenSekolah,
                        'linkDokumenSekolah'       => $linkDokumenSekolah,

                    ];
                    if (!empty($data['namaDokumenSekolah']) && !empty($data['linkDokumenSekolah'])) {
                        $this->dokumenSekolahModel->insert($data);
                    } else {
                        return redirect()->to(site_url('profilSekolah'))->with('error', 'Pastikan semua data telah diisi!');
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

    public function generatePDFDokumen() {
        $filePath = APPPATH . 'Views/profilSekolahView/profilSekolah/printDokumen.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataDokumenSekolah'] = $this->dokumenSekolahModel->findAll();

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
        $filename = 'Profil Sekolah - Dokumen Sekolah.pdf';
        $dompdf->stream($filename);
    }
}