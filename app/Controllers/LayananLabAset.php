<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\LayananLabAsetModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\StatusLayananModels; 
use App\Models\SumberDanaModels; 
use App\Models\RincianLabAsetModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasLabModels; 
use App\Models\UserActionLogsModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class LayananLabAset extends ResourceController
{
    
     function __construct() {
        $this->layananLabAsetModel = new LayananLabAsetModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->statusLayananModel = new StatusLayananModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->rincianLabAsetModel = new RincianLabAsetModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['pdf', 'custom']);
    }



    public function index() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $formattedStartDate = !empty($startDate) ? date('d F Y', strtotime($startDate)) : '';
        $formattedEndDate = !empty($endDate) ? date('d F Y', strtotime($endDate)) : '';

        $tableHeading = "";
        if (!empty($formattedStartDate) && !empty($formattedEndDate)) {
            $tableHeading = " $formattedStartDate - $formattedEndDate";
        }
        

        $data['tableHeading'] = $tableHeading;
        $data['dataLayananLabAset'] = $this->layananLabAsetModel->getAll($startDate, $endDate);

        return view('labView/layananLabAset/index', $data);
    }

    
    public function show($id = null) {
        if ($id != null) {
            $dataLayananLabAset = $this->layananLabAsetModel->find($id);
        
            if (is_object($dataLayananLabAset)) {
                $buktiUrl = $this->generateFileId($dataLayananLabAset->bukti);

                $data = [
                    'dataLayananLabAset'     => $dataLayananLabAset,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasLab'    => $this->identitasLabModel->findAll(),
                    'dataStatusLayanan'         => $this->statusLayananModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                ];

                return view('labView/layananLabAset/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

        public function new() {
        $data = [
            'dataIdentitsaSarana'       => $this->layananLabAsetModel->getSarana(),
            'dataIdentitasLab'    => $this->identitasLabModel->findAll(),
            'dataStatusLayanan'         => $this->statusLayananModel->findAll(),
            'dataSumberDana'            => $this->sumberDanaModel->findAll(),
            'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
        ];
        
        return view('labView/layananLabAset/new', $data);        
    }

    public function getKodeRincianLabAsetBySarana() {
        $selectedIdIdentitasSarana = $this->request->getPost('idIdentitasSarana');
        $kodeRincianLabAsetOptions = $this->layananLabAsetModel->getKodeRincianLabAsetBySarana($selectedIdIdentitasSarana);
        return $this->response->setJSON($kodeRincianLabAsetOptions);
    }

    public function getAllKodeRincianLabAset($idIdentitasSarana) {
        $kodeRincianLabAsetOptions = $this->layananLabAsetModel->getKodeRincianLabAsetBySarana($selectedIdIdentitasSarana);
        return $this->response->setJSON($kodeRincianLabAsetOptions);
    }
    
    public function getIdentitasLabByKodeRincianLabAset() {
        $kodeRincianLabAset = $this->request->getPost('kodeRincianLabAset');
        $layananLabAsetModel = new \App\Models\LayananLabAsetModels();
        $idIdentitasLab = $layananLabAsetModel->getIdentitasLabByKodeRincianLabAset($kodeRincianLabAset);
        $namaLab = $layananLabAsetModel->getNamaLabById($idIdentitasLab);
        return $this->response->setJSON(['idIdentitasLab' => $idIdentitasLab, 'namaLab' => $namaLab]);
    }
        
    public function getKategoriManajemenByKodeRincianLabAset()
    {
        $kodeRincianLabAset = $this->request->getPost('kodeRincianLabAset');
        $layananLabAsetModel = new \App\Models\LayananLabAsetModels();
        $idKategoriManajemen = $layananLabAsetModel->getKategoriManajemenByKodeRincianLabAset($kodeRincianLabAset);
        $namaKategoriManajemen = $layananLabAsetModel->getNamaKategoriManajemenById($idKategoriManajemen);
        return $this->response->setJSON(['idKategoriManajemen' => $idKategoriManajemen, 'namaKategoriManajemen' => $namaKategoriManajemen]);
    }

    public function getIdRincianLabAsetByKodeRincianLabAset()
    {
        $kodeRincianLabAset = $this->request->getPost('kodeRincianLabAset');
        $layananLabAsetModel = new \App\Models\LayananLabAsetModels();
        $idRincianLabAset = $layananLabAsetModel->getIdRincianLabAsetByKodeRincianLabAset($kodeRincianLabAset);
        return $this->response->setJSON(['idRincianLabAset' => $idRincianLabAset]);
    }

    
    public function create() {
        $data = $this->request->getPost();
     
        if (!empty($data['idRincianLabAset']) && !empty($data['idStatusLayanan']) && !empty($data['idSumberDana']) && !empty($data['biaya'])) {
            $this->layananLabAsetModel->insert($data);
            return redirect()->to(site_url('layananLabAset'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('layananLabAset'))->with('error', 'Pastikan semua data sudah terisi!');
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
            $this->layananLabAsetModel->update($id, $data);
            return redirect()->to(site_url('layananLabAset'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }

    
    public function edit($id = null) {
        if ($id != null) {
            $dataLayananLabAset = $this->layananLabAsetModel->find($id);
    
            if (is_object($dataLayananLabAset)) {
                $data = [
                    'dataLayananLabAset'     => $dataLayananLabAset,
                    'dataIdentitsaSarana'       => $this->layananLabAsetModel->getSarana(),
                    'dataIdentitasLab'    => $this->identitasLabModel->findAll(),
                    'dataStatusLayanan'         => $this->statusLayananModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                ];
                return view('labView/layananLabAset/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->layananLabAsetModel->delete($id);
        return redirect()->to(site_url('layananLabAset'));
    }

    public function trash() {
        $data['dataLayananLabAset'] = $this->layananLabAsetModel->onlyDeleted()->getRecycle();
        return view('labView/LayananLabAset/trash', $data);
    } 

    public function restore($id = null) {
        $affectedRows = restoreData('tblLayananLabAset', 'idLayananLabAset', $id, $this->userActionLogsModel, 'Laboratorium - Layanan Aset');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('layananLabAset'))->with('success', 'Data berhasil direstore');
        }
    
        return redirect()->to(site_url('layananLabAset/trash'))->with('error', 'Tidak ada data untuk direstore');
    }
    
    public function deletePermanent($id = null) {
        $affectedRows = deleteData('tblLayananLabAset', 'idLayananLabAset', $id, $this->userActionLogsModel, 'Laboratorium - Layanan Aset');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('layananLabAset'))->with('success', 'Data berhasil dihapus');
        } 
    
        return redirect()->to(site_url('layananLabAset/trash'))->with('error', 'Tidak ada data untuk dihapus');
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
        
        $data = $this->layananLabAsetModel->getAll($startDate, $endDate);
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Layanan Aset');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Tanggal', 'Nama Aset', 'Lokasi', 'Status Layanan', 'Kategori Manajemen', 'Sumber Dana', 'Biaya', 'Bukti', 'Keterangan'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $biayaFormatted = 'Rp ' . number_format($value->biaya, 0, ',', '.');
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $date);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->namaLab);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->namaStatusLayanan);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaKategoriManajemen);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('H'.($index + 2), $biayaFormatted);
            $linkValue = $value->bukti; 
            $linkTitle = 'Click here'; 
            $hyperlinkFormula = '=HYPERLINK("' . $linkValue . '", "' . $linkTitle . '")';
            $activeWorksheet->setCellValue('I'.($index + 2), $hyperlinkFormula);
            $activeWorksheet->setCellValue('J' . ($index + 2), $value->keterangan);
            $activeWorksheet->getStyle('J' . ($index + 2))->getAlignment()->setWrapText(true);

        
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J']; 
        
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $activeWorksheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }
        }
        
        $activeWorksheet->getStyle('A1:J1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:J'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:J')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'J') as $column) {
            if ($column == 'J') {
                $activeWorksheet->getColumnDimension($column)->setWidth(35);
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
        }
        
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Layanan Aset Laboratorium.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->layananLabAsetModel->getDataTemplate();
        $keyAset = $this->rincianLabAsetModel->getAll(); 
        $keyStatusLayanan = $this->statusLayananModel->orderBy('idStatusLayanan', 'asc')->findAll();
        $keySumberDana = $this->sumberDanaModel->orderBy('idSumberDana', 'asc')->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
        
        $headerInputTable = ['No.', 'Tanggal', 'ID Rincian Aset', 'ID Sumber Dana', 'ID Status Layanan', 'Biaya', 'Bukti', 'Keterangan'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerSaranaID = ['ID Rincian Aset', 'Kode Aset', 'Nama Aset'];
        $activeWorksheet->fromArray([$headerSaranaID], NULL, 'J1');
        $activeWorksheet->getStyle('J1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerLayananID = ['ID Status Layanan', 'Nama Layanan'];
        $activeWorksheet->fromArray([$headerLayananID], NULL, 'Q1');
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
            $activeWorksheet->setCellValue('J'.($index + 2), $value->idRincianLabAset);
            $activeWorksheet->setCellValue('K'.($index + 2), $value->kodeRincianLabAset);
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
            $exampleSheet->setCellValue('C'.($index + 2), $value->idRincianLabAset);
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
        header('Content-Disposition: attachment;filename=Laboratorium - Layanan Aset Example.xlsx');
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
                $idRincianLabAset       = $value[2] ?? null;
                $idSumberDana           = $value[3] ?? null;
                $idStatusLayanan        = $value[4] ?? null;
                $biaya                  = $value[5] ?? null;
                $bukti                  = $value[6] ?? null;
                $keterangan             = $value[7] ?? null;

                if ($idRincianLabAset === null || $idRincianLabAset === '') {
                    continue; 
                }
                $data = [
                    'tanggal' => $tanggal,
                    'idRincianLabAset' => $idRincianLabAset,
                    'idSumberDana' => $idSumberDana,
                    'idStatusLayanan' => $idStatusLayanan,
                    'biaya' => $biaya,
                    'bukti' => $bukti,
                    'keterangan' => $keterangan,
                    ];

                    if (!empty($data['tanggal']) && !empty($data['idRincianLabAset'])
                    && !empty($data['idSumberDana']) && !empty($data['idStatusLayanan']) 
                    && !empty($data['biaya']) && !empty($data['bukti'])
                    && !empty($data['keterangan'])) {
                        $this->layananLabAsetModel->insert($data);
                } else {
                    return redirect()->to(site_url('layananLabAset'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('layananLabAset'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('layananLabAset'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
        $dataLayananLabAset = $this->layananLabAsetModel->getAll($startDate, $endDate);
        $title = "REPORT LAYANAN ASET";
        if (!$dataLayananLabAset) {
            return view('error/404');
        }
    
        $data = [
            'dataLayananLabAset' => $dataLayananLabAset,
        ];
    
    
        $pdfData = pdf_layananasetlab($dataLayananLabAset, $title, $startDate, $endDate);
    
        
        $filename = 'Laboratorium - Layanan Aset' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }
}

