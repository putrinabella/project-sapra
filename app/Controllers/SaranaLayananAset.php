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
use App\Models\UserActionLogsModels; 
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
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['pdf', 'custom']);
    }

    // public function index() {
    //     $data['dataSaranaLayananAset'] = $this->saranaLayananAsetModel->getAll();
    //     return view('saranaView/layananAset/index', $data);
    // }
    
    public function index()
    {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $formattedStartDate = !empty($startDate) ? date('d F Y', strtotime($startDate)) : '';
        $formattedEndDate = !empty($endDate) ? date('d F Y', strtotime($endDate)) : '';

        $tableHeading = "";
        if (!empty($formattedStartDate) && !empty($formattedEndDate)) {
            $tableHeading = " $formattedStartDate - $formattedEndDate";
        }
        

        $data['tableHeading'] = $tableHeading;
        $data['dataSaranaLayananAset'] = $this->saranaLayananAsetModel->getAll($startDate, $endDate);

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

    public function getIdRincianAsetByKodeRincianAset()
    {
        $kodeRincianAset = $this->request->getPost('kodeRincianAset');
        $saranaLayananAsetModel = new \App\Models\SaranaLayananAsetModels();
        $idRincianAset = $saranaLayananAsetModel->getIdRincianAsetByKodeRincianAset($kodeRincianAset);
        return $this->response->setJSON(['idRincianAset' => $idRincianAset]);
    }

    
    public function create() {
        $data = $this->request->getPost();
     
        if (!empty($data['idRincianAset']) && !empty($data['idStatusLayanan']) && !empty($data['idSumberDana']) && !empty($data['biaya'])) {
            $this->saranaLayananAsetModel->insert($data);
            return redirect()->to(site_url('saranaLayananAset'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('saranaLayananAset'))->with('error', 'Pastikan semua data sudah terisi!');
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
                    'dataIdentitsaSarana'       => $this->saranaLayananAsetModel->getSarana(),
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'dataStatusLayanan'         => $this->statusLayananModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
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
        $affectedRows = restoreData('tblSaranaLayananAset', 'idSaranaLayananAset', $id, $this->userActionLogsModel, 'Sarana - Layanan Aset');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('saranaLayananAset'))->with('success', 'Data berhasil direstore');
        }
    
        return redirect()->to(site_url('saranaLayananAset/trash'))->with('error', 'Tidak ada data untuk direstore');
    }
    
    public function deletePermanent2($id = null) {
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

    public function deletePermanent($id = null) {
        $affectedRows = deleteData('tblSaranaLayananAset', 'idSaranaLayananAset', $id, $this->userActionLogsModel, 'Sarana - Layanan Aset');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('saranaLayananAset'))->with('success', 'Data berhasil dihapus');
        } 
    
        return redirect()->to(site_url('saranaLayananAset/trash'))->with('error', 'Tidak ada data untuk dihapus');
    }
    

    private function htmlConverter($html) {
        $plainText = strip_tags(str_replace('<br />', "\n", $html));
        $plainText = preg_replace('/\n+/', "\n", $plainText);
        return $plainText;  
    }

    public function export() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $formattedStartDate = !empty($startDate) ? date('d F Y', strtotime($startDate)) : '';
        $formattedEndDate = !empty($endDate) ? date('d F Y', strtotime($endDate)) : '';
        
        $data = $this->saranaLayananAsetModel->getAll($startDate, $endDate);
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Layanan Aset');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Tanggal', 'Nama Aset', 'Lokasi', 'Status Layanan', 'Kategori Manajemen', 'Sumber Dana', 'Biaya', 'Bukti'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $biayaFormatted = 'Rp ' . number_format($value->biaya, 0, ',', '.');
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $date);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->namaPrasarana);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->namaStatusLayanan);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaKategoriManajemen);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('H'.($index + 2), $biayaFormatted);
            $activeWorksheet->setCellValue('I'.($index + 2), $value->bukti);
            $linkValue = $value->bukti; 
            $linkTitle = 'Click here'; 

            $hyperlinkFormula = '=HYPERLINK("' . $linkValue . '", "' . $linkTitle . '")';
            $activeWorksheet->setCellValue('I'.($index + 2), $hyperlinkFormula);
        
            $columns = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];  // Exclude column A
        
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }
        
            // Center align cell A
            $activeWorksheet->getStyle('A' . ($index + 2))
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }
        
        $activeWorksheet->getStyle('A1:I1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:I'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:I')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'I') as $column) {
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
        $data = $this->saranaLayananAsetModel->getDataTemplate();
        $keyAset = $this->rincianAsetModel->getAll();
        $keyStatusLayanan = $this->statusLayananModel->findAll();
        $keySumberDana = $this->sumberDanaModel->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
        
        $headerInputTable = ['No.', 'Tanggal', 'ID Rincian Prasarana Aset', 'ID Sumber Dana', 'ID Status Layanan', 'Biaya', 'Bukti', 'Keterangan'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerSaranaID = ['ID Rincian Aset', 'Kode Aset', 'Nama Aset'];
        $activeWorksheet->fromArray([$headerSaranaID], NULL, 'J1');
        $activeWorksheet->getStyle('J1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerPrasaranaID = ['ID Status Layanan', 'Nama Layanan'];
        $activeWorksheet->fromArray([$headerPrasaranaID], NULL, 'Q1');
        $activeWorksheet->getStyle('Q1:R1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerSumberDanaID = ['ID Sumber Dana', 'Sumber Dana'];
        $activeWorksheet->fromArray([$headerSumberDanaID], NULL, 'N1');
        $activeWorksheet->getStyle('N1:O1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            };
            
            $currentDate = date('Y-m-d');
            $activeWorksheet->setCellValue('A' . ($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B' . ($index + 2), $currentDate);
            $activeWorksheet->setCellValue('C'.($index + 2), '');
            $activeWorksheet->setCellValue('D'.($index + 2), '');
            $activeWorksheet->setCellValue('E'.($index + 2), '');
            $activeWorksheet->setCellValue('F'.($index + 2), '');
            $activeWorksheet->setCellValue('G'.($index + 2), '');
            $activeWorksheet->setCellValue('H'.($index + 2), '');

            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $activeWorksheet->getStyle($cellReference)->getAlignment();
            
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            }
        }
    
        $activeWorksheet->getStyle('A1:H1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:H'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:H')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'H') as $column) {
            if ($column === 'H') {
                $activeWorksheet->getColumnDimension($column)->setWidth(50);
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        foreach ($keyAset as $index => $value) {
            $activeWorksheet->setCellValue('J'.($index + 2), $value->idRincianAset);
            $activeWorksheet->setCellValue('K'.($index + 2), $value->kodeRincianAset);
            $activeWorksheet->setCellValue('L'.($index + 2), $value->namaSarana);
    
            $columns = ['J', 'K', 'L'];
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $activeWorksheet->getStyle($cellReference)->getAlignment();
            
                if ($column === 'J') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            }            
        }

        $activeWorksheet->getStyle('J1:L1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('J1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('J1:L'.(count($keyAset) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('J:L')->getAlignment()->setWrapText(true);
    
        foreach (range('J', 'L') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        foreach ($keySumberDana as $index => $value) {
            $activeWorksheet->setCellValue('N'.($index + 2), $value->idSumberDana);
            $activeWorksheet->setCellValue('O'.($index + 2), $value->namaSumberDana);
    
            $columns = ['N', 'O'];
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $activeWorksheet->getStyle($cellReference)->getAlignment();
            
                if ($column === 'N') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            }
        }

        $activeWorksheet->getStyle('N1:O1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('N1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('N1:O'.(count($keySumberDana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('N:O')->getAlignment()->setWrapText(true);
    
        foreach (range('N', 'O') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keyStatusLayanan as $index => $value) {
            $activeWorksheet->setCellValue('Q'.($index + 2), $value->idStatusLayanan);
            $activeWorksheet->setCellValue('R'.($index + 2), $value->namaStatusLayanan);
    
            $columns = ['Q', 'R'];
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $activeWorksheet->getStyle($cellReference)->getAlignment();
            
                if ($column === 'Q') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            } 
        }

        $activeWorksheet->getStyle('Q1:R1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('Q1:R1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('Q1:R'.(count($keyStatusLayanan) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('Q:R')->getAlignment()->setWrapText(true);
    
        foreach (range('Q', 'R') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headerExampleTable =  ['No.', 'Tanggal', 'ID Rincian Aset', 'ID Sumber Dana', 'ID Status Layanan', 'Biaya', 'Bukti', 'Keterangan'];
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            };

            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->tanggal);
            $exampleSheet->setCellValue('C'.($index + 2), $value->idRincianAset);
            $exampleSheet->setCellValue('D'.($index + 2), $value->idSumberDana);
            $exampleSheet->setCellValue('E'.($index + 2), $value->idStatusLayanan);
            $exampleSheet->setCellValue('F'.($index + 2), $value->biaya);
            $exampleSheet->setCellValue('G'.($index + 2), $value->bukti);
            $exampleSheet->setCellValue('H'.($index + 2), $value->keterangan);

            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $exampleSheet->getStyle($cellReference)->getAlignment();
            
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            }
        }
    
        $exampleSheet->getStyle('A1:H1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $exampleSheet->getStyle('A1:H'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:H')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'H') as $column) {
            if ($column === 'H') {
                $exampleSheet->getColumnDimension($column)->setWidth(50);
            } else {
                $exampleSheet->getColumnDimension($column)->setAutoSize(true);
            }
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
                $idRincianAset          = $value[2] ?? null;
                $idSumberDana           = $value[3] ?? null;
                $idStatusLayanan        = $value[4] ?? null;
                $biaya                  = $value[5] ?? null;
                $bukti                  = $value[6] ?? null;
                $keterangan             = $value[7] ?? null;

                if ($idRincianAset=== null || $idRincianAset=== '') {
                    continue; 
                }
                $data = [
                    'tanggal' => $tanggal,
                    'idRincianAset' => $idRincianAset,
                    'idSumberDana' => $idSumberDana,
                    'idStatusLayanan' => $idStatusLayanan,
                    'biaya' => $biaya,
                    'bukti' => $bukti,
                    'keterangan' => $keterangan,
                    ];

                    if (!empty($data['tanggal']) && !empty($data['idRincianAset'])
                    && !empty($data['idSumberDana']) && !empty($data['idStatusLayanan']) 
                    && !empty($data['biaya']) && !empty($data['bukti'])
                    && !empty($data['keterangan'])) {
                        $this->saranaLayananAsetModel->insert($data);
                } else {
                    return redirect()->to(site_url('saranaLayananAset'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('saranaLayananAset'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('saranaLayananAset'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }



    public function generatePDFTDOMPDF() {
        $filePath = APPPATH . 'Views/saranaView/layananAset/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataSaranaLayananAset'] = $this->saranaLayananAsetModel->getAll($startDate, $endDate);

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

    public function generatePDF() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
        $dataSaranaLayananAset = $this->saranaLayananAsetModel->getAll($startDate, $endDate);
        $title = "REPORT LAYANAN ASET";
        if (!$dataSaranaLayananAset) {
            return view('error/404');
        }
    
        $data = [
            'dataSaranaLayananAset' => $dataSaranaLayananAset,
        ];
    
    
        $pdfData = pdf_layananaset($dataSaranaLayananAset, $title, $startDate, $endDate);
    
        
        $filename = 'Sarana - Layanan Aset - ' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }
}

