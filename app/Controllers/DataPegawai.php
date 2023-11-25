<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DataPegawaiModels; 
use App\Models\KategoriPegawaiModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class DataPegawai extends ResourceController
{
    
     function __construct() {
        $this->dataPegawaiModel = new DataPegawaiModels();
        $this->kategoriPegawaiModel = new KategoriPegawaiModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataDataPegawai'] = $this->dataPegawaiModel->getAll();
        return view('master/dataPegawaiView/index', $data);
    }

    public function show($id = null) {
        if ($id != null) {
            $dataDataPegawai = $this->dataPegawaiModel->find($id);
        
            if (is_object($dataDataPegawai)) {
                $spesifikasiMarkup = $dataDataPegawai->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataDataPegawai->bukti);
                $data = [
                    'dataDataPegawai'           => $dataDataPegawai,
                    'dataKategoriPegawai'       => $this->kategoriPegawaiModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];
                return view('master/dataPegawaiView/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data = [
            'dataDataPegawai' =>  $this->dataPegawaiModel->findAll(),
            'dataKategoriPegawai' => $this->kategoriPegawaiModel->findAll(),
        ];
        
        return view('master/dataPegawaiView/new', $data);       
    }

    public function create() {
        $data = $this->request->getPost();
    
        $nip = $data['nip'];
    
        if ($this->dataPegawaiModel->isDuplicate($nip)) {
            return redirect()->to(site_url('dataPegawai'))->with('error', 'Ditemukan duplikat data! Masukkan data yang berbeda.');
        } else {
            unset($data['idIdentitasPrasarana']);
            $this->dataPegawaiModel->insert($data);
            return redirect()->to(site_url('dataPegawai'))->with('success', 'Data berhasil disimpan');
        }
    }
    
    public function edit($id = null) {
        if ($id != null) {
            $dataDataPegawai = $this->dataPegawaiModel->find($id);
    
            if (is_object($dataDataPegawai)) {
                $data = [
                    'dataDataPegawai' => $dataDataPegawai,
                    'dataKategoriPegawai' => $this->kategoriPegawaiModel->findAll(),
                ];
                return view('master/dataPegawaiView/edit', $data);
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
            $nip = $data['nip'];
    
            $existingData = $this->dataPegawaiModel->find($id);
            if ($existingData->nip != $nip) {
                if ($this->dataPegawaiModel->isDuplicate($nip)) {
                    return redirect()->to(site_url('dataPegawai'))->with('error', 'Gagal update karena ditemukan duplikat data!');
                }
            }
            $this->dataPegawaiModel->update($id, $data);
            return redirect()->to(site_url('dataPegawai'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }
    


    public function delete($id = null) {
        $this->dataPegawaiModel->delete($id);
        return redirect()->to(site_url('dataPegawai'));
    }

    public function trash() {
        $data['dataDataPegawai'] = $this->dataPegawaiModel->onlyDeleted()->getRecycle();
        
        return view('master/dataPegawaiView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblDataPegawai')
                ->set('deleted_at', null, true)
                ->where(['idDataPegawai' => $id])
                ->update();
        } else {
            $this->db->table('tblDataPegawai')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('dataPegawai'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('dataPegawai/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->dataPegawaiModel->delete($id, true);
        return redirect()->to(site_url('dataPegawai/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->dataPegawaiModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->dataPegawaiModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('dataPegawai/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('dataPegawai/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 
    
    public function export() {
        $data = $this->dataPegawaiModel->getAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('DataPegawai');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'NIS', 'Nama', 'Kategori Pegawai'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->nip);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaPegawai);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->namaKategoriPegawai);

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
        header('Content-Disposition: attachment;filename=Profil - Data Pegawai.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->dataPegawaiModel->getAll();
        $keyKelas = $this->kategoriPegawaiModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Data Pegawai');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'NIS', 'Nama', 'Id Kategori Pegawai'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerKelasID = ['ID Kategori Pegawai', 'Kategori Pegawai'];
        $activeWorksheet->fromArray([$headerKelasID], NULL, 'F1');
        $activeWorksheet->getStyle('F1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

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
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keyKelas as $index => $value) {
            $activeWorksheet->setCellValue('F'.($index + 2), $value->idKategoriPegawai);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->namaKategoriPegawai);
    
            $columns = ['F', 'G'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('F1:G1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('F1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('F1:G'.(count($keyKelas) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('F:G')->getAlignment()->setWrapText(true);
    
        foreach (range('F', 'G') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }


        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headers = ['No.', 'NIS', 'Nama', 'ID Kategori Pegawai'];
        $exampleSheet->fromArray([$headers], NULL, 'A1');
        $exampleSheet->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->nip);
            $exampleSheet->setCellValue('C'.($index + 2), $value->namaPegawai);
            $exampleSheet->setCellValue('D'.($index + 2), $value->idKategoriPegawai);

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
        header('Content-Disposition: attachment;filename=Profil - Data Pegawai Example.xlsx');
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
                $nip                    = $value[1] ?? null;
                $namaPegawai              = $value[2] ?? null;
                $idKategoriPegawai            = $value[3] ?? null;
                if ($nip === null || $nip === '') {
                    continue; 
                }
                $data = [
                    'nip'   => $nip,
                    'namaPegawai' => $namaPegawai,
                    'idKategoriPegawai'   => $idKategoriPegawai,
                ];

                if (!empty($data['nip']) && !empty($data['namaPegawai'])
                && !empty($data['idKategoriPegawai'])) {
                        $this->dataPegawaiModel->insert($data);
                } else {
                    return redirect()->to(site_url('dataPegawai'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('dataPegawai'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('dataPegawai'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $filePath = APPPATH . 'Views/master/dataPegawaiView/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataDataPegawai'] = $this->dataPegawaiModel->getAll();

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
        $filename = 'Profil - DataPegawai Report.pdf';
        $dompdf->stream($filename);
    }
}