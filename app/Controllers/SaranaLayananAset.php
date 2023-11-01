<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SaranaLayananAsetModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\StatusLayananModels; 
use App\Models\SumberDanaModels; 
use App\Models\RincianAsetModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasPrasaranaModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class SaranaLayananAset extends ResourceController
{
    
     function __construct() {
        $this->saranaLayananAsetModel = new SaranaLayananAsetModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->statusLayananModel = new StatusLayananModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->rincianAsetModel = new RincianAsetModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataSaranaLayananAset'] = $this->saranaLayananAsetModel->getAll();
        return view('saranaView/layananAset/index', $data);
    }
    
    public function show($id = null) {
        if ($id != null) {
            $dataSaranaLayananAset = $this->saranaLayananAsetModel->find($id);
        
            if (is_object($dataSaranaLayananAset)) {
                $buktiUrl = $this->generateFileId($dataSaranaLayananAset->bukti);

                $data = [
                    'dataSaranaLayananAset'     => $dataSaranaLayananAset,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'dataStatusLayanan'         => $this->statusLayananModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                ];

                return view('saranaView/layananAset/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

        public function new() {
        $data = [
            'dataIdentitsaSarana'       => $this->saranaLayananAsetModel->getSarana(),
            'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
            'dataStatusLayanan'         => $this->statusLayananModel->findAll(),
            'dataSumberDana'            => $this->sumberDanaModel->findAll(),
            'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
        ];
        
        return view('saranaView/layananAset/new', $data);        
    }

    public function getKodeRincianAsetBySarana() {
        $selectedIdIdentitasSarana = $this->request->getPost('idIdentitasSarana');
        $kodeRincianAsetOptions = $this->saranaLayananAsetModel->getKodeRincianAsetBySarana($selectedIdIdentitasSarana);
        return $this->response->setJSON($kodeRincianAsetOptions);
    }
    
    public function getIdentitasPrasaranaByKodeRincianAset() {
        $kodeRincianAset = $this->request->getPost('kodeRincianAset');
        $saranaLayananAsetModel = new \App\Models\SaranaLayananAsetModels();
        $idIdentitasPrasarana = $saranaLayananAsetModel->getIdentitasPrasaranaByKodeRincianAset($kodeRincianAset);
        $namaPrasarana = $saranaLayananAsetModel->getNamaPrasaranaById($idIdentitasPrasarana);
        return $this->response->setJSON(['idIdentitasPrasarana' => $idIdentitasPrasarana, 'namaPrasarana' => $namaPrasarana]);
    }
        
    public function getKategoriManajemenByKodeRincianAset()
    {
        $kodeRincianAset = $this->request->getPost('kodeRincianAset');
        $saranaLayananAsetModel = new \App\Models\SaranaLayananAsetModels();
        $idKategoriManajemen = $saranaLayananAsetModel->getKategoriManajemenByKodeRincianAset($kodeRincianAset);
        $namaKategoriManajemen = $saranaLayananAsetModel->getNamaKategoriManajemenById($idKategoriManajemen);
        return $this->response->setJSON(['idKategoriManajemen' => $idKategoriManajemen, 'namaKategoriManajemen' => $namaKategoriManajemen]);
    }

    
    public function create() {
        $data = $this->request->getPost();
        // $buktiPath = $this->uploadFile('bukti'); 
        if (!empty($data['idIdentitasSarana']) && !empty($data['idIdentitasPrasarana']) && !empty($data['idStatusLayanan']) && !empty($data['idSumberDana']) && !empty($data['idKategoriManajemen'])) {
            // $data['bukti'] = $buktiPath;
            $this->saranaLayananAsetModel->insert($data);
            return redirect()->to(site_url('saranaLayananAset'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('saranaLayananAset'))->with('error', 'File error');
        }
    }

    private function generateFileId($url) {
        preg_match('/\/file\/d\/(.*?)\//', $url, $matches);
        
        if (isset($matches[1])) {
            $fileId = $matches[1];
            return "https://drive.google.com/uc?export=view&id=" . $fileId;
        } else {
            return "Invalid Google Drive URL";
        }
    }
    
    public function update($id = null) {
        if ($id != null) {
            $data = $this->request->getPost();
            // $uploadedFilePath = $this->uploadFile('bukti');
            // if ($uploadedFilePath !== null) {
            //     $data['bukti'] = $uploadedFilePath;
            // }
            $this->saranaLayananAsetModel->update($id, $data);
            return redirect()->to(site_url('saranaLayananAset'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }

    
    public function edit($id = null) {
        if ($id != null) {
            $dataSaranaLayananAset = $this->saranaLayananAsetModel->find($id);
    
            if (is_object($dataSaranaLayananAset)) {
                $data = [
                    'dataSaranaLayananAset'     => $dataSaranaLayananAset,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'dataStatusLayanan'         => $this->statusLayananModel->findAll(),
                ];
                return view('saranaView/layananAset/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->saranaLayananAsetModel->delete($id);
        return redirect()->to(site_url('saranaLayananAset'));
    }

    public function trash() {
        $data['dataSaranaLayananAset'] = $this->saranaLayananAsetModel->onlyDeleted()->getRecycle();
        return view('saranaView/LayananAset/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblSaranaLayananAset')
                ->set('deleted_at', null, true)
                ->where(['idSaranaLayananAset' => $id])
                ->update();
        } else {
            $this->db->table('tblSaranaLayananAset')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('saranaLayananAset'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('saranaLayananAset/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->saranaLayananAsetModel->delete($id, true);
        return redirect()->to(site_url('saranaLayananAset/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->saranaLayananAsetModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->saranaLayananAsetModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('saranaLayananAset/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('saranaLayananAset/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  

    private function htmlConverter($html) {
        $plainText = strip_tags(str_replace('<br />', "\n", $html));
        $plainText = preg_replace('/\n+/', "\n", $plainText);
        return $plainText;  
    }

    public function export() {
        $data = $this->saranaLayananAsetModel->getAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Layanan Aset');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Tanggal', 'Nama Aset', 'Lokasi', 'Status Layanan', 'Kategori Manajemen', 'Sumber Dana', 'Biaya', 'Bukti', 'Kode Lokasi'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->tanggal);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->namaPrasarana);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->namaStatusLayanan);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaKategoriManajemen);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('H'.($index + 2), $value->biaya);
            $activeWorksheet->setCellValue('I'.($index + 2), $value->bukti);
            $activeWorksheet->setCellValue('J'.($index + 2), $value->kodePrasarana);
    
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I' ,'J'];

            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }
    
        $activeWorksheet->getStyle('A1:J1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $activeWorksheet->getStyle('A1:J'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:J')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'J') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Layanan Aset Sarana.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->saranaLayananAsetModel->getAll();
        $keyPrasarana = $this->identitasPrasaranaModel->findAll();
        $keySarana = $this->identitasSaranaModel->findAll();
        $keyStatusLayanan = $this->statusLayananModel->findAll();
        $keySumberDana = $this->sumberDanaModel->findAll();
        $keyKategoriManajemen = $this->kategoriManajemenModel->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
        
        $headerInputTable = ['No.', 'Tanggal', 'ID Aset', 'ID Prasarana', 'ID Status Layanan', 'ID Sumber Dana', 'ID Kategori Manajemen', 'Biaya', 'Bukti'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerSaranaID = ['ID Aset', 'Nama Aset'];
        $activeWorksheet->fromArray([$headerSaranaID], NULL, 'K1');
        $activeWorksheet->getStyle('K1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerPrasaranaID = ['ID Prasarana', 'Nama Prasarana'];
        $activeWorksheet->fromArray([$headerPrasaranaID], NULL, 'N1');
        $activeWorksheet->getStyle('N1:O1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerPrasaranaID = ['ID Status Layanan', 'Nama Layanan'];
        $activeWorksheet->fromArray([$headerPrasaranaID], NULL, 'Q1');
        $activeWorksheet->getStyle('Q1:R1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerSumberDanaID = ['ID Sumber Dana', 'Sumber Dana'];
        $activeWorksheet->fromArray([$headerSumberDanaID], NULL, 'T1');
        $activeWorksheet->getStyle('T1:U1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerKategoriManajemenID = ['ID Kategori Manajemen', 'Kategori Manajemen'];
        $activeWorksheet->fromArray([$headerKategoriManajemenID], NULL, 'W1');
        $activeWorksheet->getStyle('W1:X1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            };

            $currentDate = '=TEXT(DATE(' . date('Y') . ',' . date('m') . ',' . date('d') . '),"yyyy-mm-dd")';
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $currentDate);
            $activeWorksheet->setCellValue('C'.($index + 2), '');
            $activeWorksheet->setCellValue('D'.($index + 2), '');
            $activeWorksheet->setCellValue('E'.($index + 2), '');
            $activeWorksheet->setCellValue('F'.($index + 2), '');
            $activeWorksheet->setCellValue('G'.($index + 2), '');
            $activeWorksheet->setCellValue('H'.($index + 2), '');
            $activeWorksheet->setCellValue('I'.($index + 2), '');
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H' ,'I'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }
    
        $activeWorksheet->getStyle('A1:I1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:I'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:I')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'I') as $column) {
            if ($column === 'H') {
                $activeWorksheet->getColumnDimension($column)->setWidth(50);
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        foreach ($keySarana as $index => $value) {
            $activeWorksheet->setCellValue('K'.($index + 2), $value->idIdentitasSarana);
            $activeWorksheet->setCellValue('L'.($index + 2), $value->namaSarana);
    
            $columns = ['K', 'L'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('K1:L1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('K1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('K1:L'.(count($keySarana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('K:L')->getAlignment()->setWrapText(true);
    
        foreach (range('K', 'L') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        foreach ($keyPrasarana as $index => $value) {
            $activeWorksheet->setCellValue('N'.($index + 2), $value->idIdentitasPrasarana);
            $activeWorksheet->setCellValue('O'.($index + 2), $value->namaPrasarana);
    
            $columns = ['N', 'O'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('N1:O1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('N1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('N1:O'.(count($keyPrasarana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('N:O')->getAlignment()->setWrapText(true);
    
        foreach (range('N', 'O') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }


        foreach ($keyStatusLayanan as $index => $value) {
            $activeWorksheet->setCellValue('Q'.($index + 2), $value->idStatusLayanan);
            $activeWorksheet->setCellValue('R'.($index + 2), $value->namaStatusLayanan);
    
            $columns = ['Q', 'R'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('Q1:R1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('Q1:R1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('Q1:R'.(count($keyStatusLayanan) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('Q:R')->getAlignment()->setWrapText(true);
    
        foreach (range('Q', 'R') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keySumberDana as $index => $value) {
            $activeWorksheet->setCellValue('T'.($index + 2), $value->idSumberDana);
            $activeWorksheet->setCellValue('U'.($index + 2), $value->namaSumberDana);
    
            $columns = ['T', 'U'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('T1:U1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('T1:U1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('T1:U'.(count($keySumberDana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('T:U')->getAlignment()->setWrapText(true);
    
        foreach (range('T', 'U') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keyKategoriManajemen as $index => $value) {
            $activeWorksheet->setCellValue('W'.($index + 2), $value->idKategoriManajemen);
            $activeWorksheet->setCellValue('X'.($index + 2), $value->namaKategoriManajemen);
    
            $columns = ['W', 'X'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('W1:X1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('W1:X1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('W1:X'.(count($keyKategoriManajemen) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('W:X')->getAlignment()->setWrapText(true);
    
        foreach (range('W', 'X') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headerExampleTable = ['No.', 'Tanggal', 'ID Aset', 'ID Prasarana', 'ID Status Layanan', 'ID Sumber Dana', 'ID Kategori Manajemen', 'Biaya', 'Bukti'];
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
        };

            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->tanggal);
            $exampleSheet->setCellValue('C'.($index + 2), $value->idIdentitasSarana);
            $exampleSheet->setCellValue('D'.($index + 2), $value->idIdentitasPrasarana);
            $exampleSheet->setCellValue('E'.($index + 2), $value->idStatusLayanan);
            $exampleSheet->setCellValue('F'.($index + 2), $value->idSumberDana);
            $exampleSheet->setCellValue('G'.($index + 2), $value->idKategoriManajemen);
            $exampleSheet->setCellValue('H'.($index + 2), $value->biaya);
            $exampleSheet->setCellValue('I'.($index + 2), $value->bukti);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H' ,'I'];
            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }
    
        $exampleSheet->getStyle('A1:I1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $exampleSheet->getStyle('A1:I'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:I')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'I') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Sarana - Layanan Aset Example.xlsx');
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
                $tanggal                = $value[1] ?? null;
                $idIdentitasSarana      = $value[2] ?? null;
                $idIdentitasPrasarana   = $value[3] ?? null;
                $idStatusLayanan        = $value[4] ?? null;
                $idSumberDana           = $value[5] ?? null;
                $idKategoriManajemen    = $value[6] ?? null;
                $biaya                  = $value[7] ?? null;
                $bukti                  = $value[8] ?? null;

                if ($idIdentitasSarana === null || $idIdentitasSarana === '') {
                    continue; 
                }
                $data = [
                    'tanggal' => $tanggal,
                    'idIdentitasSarana' => $idIdentitasSarana,
                    'idIdentitasPrasarana' => $idIdentitasPrasarana,
                    'idStatusLayanan' => $idStatusLayanan,
                    'idSumberDana' => $idSumberDana,
                    'idKategoriManajemen' => $idKategoriManajemen,
                    'biaya' => $biaya,
                    'bukti' => $bukti,
                    ];

                    if (!empty($data['tanggal']) && !empty($data['idIdentitasSarana'])
                    && !empty($data['idIdentitasPrasarana']) && !empty($data['idStatusLayanan']) 
                    && !empty($data['idSumberDana']) && !empty($data['idKategoriManajemen'])
                    && !empty($data['biaya']) && !empty($data['bukti'])) {
                    if ($status == 'ERROR') {
                        return redirect()->to(site_url('saranaLayananAset'))->with('error', 'Pastikan excel sudah benar');
                    } else {
                        $this->saranaLayananAsetModel->insert($data);
                    }
                } else {
                    return redirect()->to(site_url('saranaLayananAset'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('saranaLayananAset'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('saranaLayananAset'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $filePath = APPPATH . 'Views/saranaView/layananAset/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataSaranaLayananAset'] = $this->saranaLayananAsetModel->getAll();

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
        $filename = 'Sarana - Layanan Aset Report.pdf';
        $dompdf->stream($filename);
    }

    
    public function fetchKategoriManajemen()
    {
        $idIdentitasPrasarana = $this->request->getPost('idIdentitasPrasarana');
        $kategoriManajemen =  $this->saranaLayananAsetModel->getKategoriManajemen($idIdentitasPrasarana);

        return $this->response->setJSON($kategoriManajemen);
    }

    public function setKategoriManajemen()
    {
        $dataPaket = new RincianAsetModels();
        $idIdentitasPrasarana = $this->request->getVar('idIdentitasPrasarana');

        $kategoriManajemenLoad = $dataPaket->select('idKategoriManajemen, namaKategoriManajemen')->where(
            'idIdentitasPrasarana',
            $idIdentitasPrasarana
        )->orderBy('namaKategoriManajemen')->findAll();
        $data = [];
        foreach ($kategoriManajemenLoad as $value) {
            $data[] = [
                'id' => $value->idKategoriManajemen,
                'text' => $value->namaKategoriManajemen
            ];
        }
        $response['data'] = $data;
        echo $data;
        die;
        return $this->response->setJSON($response);
    }
}

