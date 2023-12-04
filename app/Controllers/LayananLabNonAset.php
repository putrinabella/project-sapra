<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\LayananLabNonAsetModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\StatusLayananModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriMepModels; 
use App\Models\IdentitasLabModels; 
use App\Models\UserActionLogsModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class LayananLabNonAset extends ResourceController
{
    
     function __construct() {
        $this->layananLabNonAsetModel = new LayananLabNonAsetModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->statusLayananModel = new StatusLayananModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriMepModel = new KategoriMepModels();
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
        $data['dataLayananLabNonAset'] = $this->layananLabNonAsetModel->getAll($startDate, $endDate);

        return view('labView/layananLabNonAset/index', $data);
    }


    
    public function show($id = null) {
        if ($id != null) {
            $dataLayananLabNonAset = $this->layananLabNonAsetModel->find($id);
        
            if (is_object($dataLayananLabNonAset)) {
                $spesifikasiMarkup = $dataLayananLabNonAset->spesifikasi; 
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $buktiUrl = $this->generateFileId($dataLayananLabNonAset->bukti);

                $data = [
                    'dataLayananLabNonAset'  => $dataLayananLabNonAset,
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriMep'           => $this->kategoriMepModel->findAll(),
                    'dataIdentitasLab'    => $this->identitasLabModel->findAll(),
                    'dataStatusLayanan'         => $this->statusLayananModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];

                return view('labView/layananLabNonAset/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

        public function new() {
        $data = [
            'dataIdentitasLab'    => $this->identitasLabModel->findAll(),
            'dataStatusLayanan'         => $this->statusLayananModel->findAll(),
            'dataSumberDana'            => $this->sumberDanaModel->findAll(),
            'dataKategoriMep'           => $this->kategoriMepModel->findAll(),
        ];
        
        return view('labView/layananLabNonAset/new', $data);        
    }

    
    public function create() {
        $data = $this->request->getPost();
        if (!empty($data['idIdentitasLab']) && !empty($data['idStatusLayanan']) && !empty($data['idSumberDana']) && !empty($data['idKategoriMep'])) {
            $this->layananLabNonAsetModel->insert($data);
            return redirect()->to(site_url('layananLabNonAset'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('layananLabNonAset'))->with('error', 'Pastikan semua data sudah terisi!');
        }
    }
    
    private function uploadFile($fieldName) {
        $file = $this->request->getFile($fieldName);
        if ($file !== null) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(ROOTPATH . 'public/uploads', $newName);
                return 'uploads/' . $newName;
            } else {
                return null;
            }
        } else {
            return null;
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
            $this->layananLabNonAsetModel->update($id, $data);
            return redirect()->to(site_url('layananLabNonAset'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }

    
    public function edit($id = null) {
        if ($id != null) {
            $dataLayananLabNonAset = $this->layananLabNonAsetModel->find($id);
    
            if (is_object($dataLayananLabNonAset)) {
                $data = [
                    'dataLayananLabNonAset'     => $dataLayananLabNonAset,
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriMep'     => $this->kategoriMepModel->findAll(),
                    'dataIdentitasLab'    => $this->identitasLabModel->findAll(),
                    'dataStatusLayanan'         => $this->statusLayananModel->findAll(),
                ];
                return view('labView/layananLabNonAset/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->layananLabNonAsetModel->delete($id);
        return redirect()->to(site_url('layananLabNonAset'));
    }

    public function trash() {
        $data['dataLayananLabNonAset'] = $this->layananLabNonAsetModel->onlyDeleted()->getRecycle();
        return view('labView/layananLabNonAset/trash', $data);
    } 

    public function restore($id = null) {
        $affectedRows = restoreData('tblLayananLabNonAset', 'idLayananLabNonAset', $id, $this->userActionLogsModel, 'Laboratorium - Layanan Non Aset');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('layananLabNonAset'))->with('success', 'Data berhasil direstore');
        }
    
        return redirect()->to(site_url('layananLabNonAset/trash'))->with('error', 'Tidak ada data untuk direstore');
    }

    public function deletePermanent($id = null) {
        $affectedRows = deleteData('tblLayananLabNonAset', 'idLayananLabNonAset', $id, $this->userActionLogsModel, 'Laboratorium - Layanan Non Aset');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('layananLabNonAset'))->with('success', 'Data berhasil dihapus');
        } 
    
        return redirect()->to(site_url('layananLabNonAset/trash'))->with('error', 'Tidak ada data untuk dihapus');
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
        
        $data = $this->layananLabNonAsetModel->getAll($startDate, $endDate);
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Layanan Non Aset');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Tanggal', 'Lokasi', 'Status Layanan', 'Kategori MEP', 'Sumber Dana', 'Biaya', 'Link Dokumentasi', 'Keterangan'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $biayaFormatted = 'Rp ' . number_format($value->biaya, 0, ',', '.');
            
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $date);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaLab);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->namaStatusLayanan);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->namaKategoriMep);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('G'.($index + 2), $biayaFormatted);
            $linkValue = $value->bukti; 
            $linkTitle = 'Click here'; 
            $hyperlinkFormula = '=HYPERLINK("' . $linkValue . '", "' . $linkTitle . '")';
            $activeWorksheet->setCellValue('H'.($index + 2), $hyperlinkFormula);
            $activeWorksheet->setCellValue('I' . ($index + 2), $value->spesifikasi);
            $activeWorksheet->getStyle('I' . ($index + 2))->getAlignment()->setWrapText(true);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
            
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
        
        $activeWorksheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:I1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:I'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:I')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'I') as $column) {
            if ($column === 'H') {
                $activeWorksheet->getColumnDimension($column)->setWidth(20);
            } else if ($column === 'I') {
                $activeWorksheet->getColumnDimension($column)->setWidth(40); 
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
        }
        
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Laboratorium - Layanan Non Aset.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->layananLabNonAsetModel->getDataTemplate();
        $keyLab = $this->identitasLabModel->orderBy('idIdentitasLab', 'asc')->findAll();
        $keyStatusLayanan = $this->statusLayananModel->orderBy('idStatusLayanan', 'asc')->findAll();
        $keySumberDana = $this->sumberDanaModel->orderBy('idSumberDana', 'asc')->findAll();
        $keyKategoriMep = $this->kategoriMepModel->orderBy('idKategoriMep', 'asc')->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
        
        $headerInputTable = ['No.', 'Tanggal', 'Lokasi', 'Status Layanan', 'Kategori MEP', 'Sumber Dana', 'Biaya', 'Link Dokumentasi', 'Keterangan'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerLabID = ['ID Lab', 'Nama Lab'];
        $activeWorksheet->fromArray([$headerLabID], NULL, 'K1');
        $activeWorksheet->getStyle('K1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerKategoriMepID = ['ID Kategori MEP', 'Kategori MEP'];
        $activeWorksheet->fromArray([$headerKategoriMepID], NULL, 'N1');
        $activeWorksheet->getStyle('N1:O1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerLabID = ['ID Status Layanan', 'Nama Layanan'];
        $activeWorksheet->fromArray([$headerLabID], NULL, 'Q1');
        $activeWorksheet->getStyle('Q1:R1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerSumberDanaID = ['ID Sumber Dana', 'Sumber Dana'];
        $activeWorksheet->fromArray([$headerSumberDanaID], NULL, 'T1');
        $activeWorksheet->getStyle('T1:U1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            };

            $currentDate = date('Y-m-d');
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
                $cellReference = $column . ($index + 2);
                $alignment = $activeWorksheet->getStyle($cellReference)->getAlignment();
            
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            }
        }
    
        $activeWorksheet->getStyle('A1:I1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:I'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:I')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'I') as $column) {
            if ($column === 'G') {
                $activeWorksheet->getColumnDimension($column)->setWidth(20);
            } else if ($column === 'H') {
                $activeWorksheet->getColumnDimension($column)->setWidth(50); 
            } else if ($column === 'I') {
                $activeWorksheet->getColumnDimension($column)->setWidth(40); 
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        foreach ($keyLab as $index => $value) {
            $activeWorksheet->setCellValue('K'.($index + 2), $value->idIdentitasLab);
            $activeWorksheet->setCellValue('L'.($index + 2), $value->namaLab);
    
            $columns = ['K', 'L'];
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $activeWorksheet->getStyle($cellReference)->getAlignment();
            
                if ($column === 'K') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            }
        }

        $activeWorksheet->getStyle('K1:L1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('K1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('K1:L'.(count($keyLab) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('K:L')->getAlignment()->setWrapText(true);
    
        foreach (range('K', 'L') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        foreach ($keyKategoriMep as $index => $value) {
            $activeWorksheet->setCellValue('N'.($index + 2), $value->idKategoriMep);
            $activeWorksheet->setCellValue('O'.($index + 2), $value->namaKategoriMep);
    
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
        $activeWorksheet->getStyle('N1:O'.(count($keyKategoriMep) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
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

        foreach ($keySumberDana as $index => $value) {
            $activeWorksheet->setCellValue('T'.($index + 2), $value->idSumberDana);
            $activeWorksheet->setCellValue('U'.($index + 2), $value->namaSumberDana);
    
            $columns = ['T', 'U'];
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $activeWorksheet->getStyle($cellReference)->getAlignment();
            
                if ($column === 'T') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            }   
        }

        $activeWorksheet->getStyle('T1:U1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('T1:U1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('T1:U'.(count($keySumberDana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('T:U')->getAlignment()->setWrapText(true);
    
        foreach (range('T', 'U') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headerExampleTable = ['No.', 'Tanggal', 'Lokasi', 'Status Layanan', 'Kategori MEP', 'Sumber Dana', 'Biaya', 'Link Dokumentasi', 'Keterangan'];
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            };

            $currentDate = date('Y-m-d');
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $currentDate);
            $exampleSheet->setCellValue('C'.($index + 2), $value->namaLab);
            $exampleSheet->setCellValue('D'.($index + 2), $value->namaStatusLayanan);
            $exampleSheet->setCellValue('E'.($index + 2), $value->namaKategoriMep);
            $exampleSheet->setCellValue('F'.($index + 2), $value->namaSumberDana);
            $exampleSheet->setCellValue('G'.($index + 2), $value->biaya);
            $exampleSheet->setCellValue('H'.($index + 2), $value->bukti);
            $exampleSheet->setCellValue('I'.($index + 2), $value->spesifikasi);

            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H' ,'I'];
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
        header('Content-Disposition: attachment;filename=Laboratorium - Layanan Non Aset Example.xlsx');
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
                $idIdentitasLab   = $value[2] ?? null;
                $idStatusLayanan        = $value[3] ?? null;
                $idKategoriMep    = $value[4] ?? null;
                $idSumberDana           = $value[5] ?? null;
                $biaya                  = $value[6] ?? null;
                $bukti                  = $value[7] ?? null;
                $spesifikasi            = $value[8] ?? null;

                if ($idIdentitasLab === null || $idIdentitasLab === '') {
                    continue; 
                }
                $data = [
                    'tanggal' => $tanggal,
                    'idIdentitasLab' => $idIdentitasLab,
                    'idStatusLayanan' => $idStatusLayanan,
                    'idKategoriMep' => $idKategoriMep,
                    'idSumberDana' => $idSumberDana,
                    'biaya' => $biaya,
                    'bukti' => $bukti,
                    'spesifikasi' => $spesifikasi,
                ];

                if (!empty($data['tanggal']) && !empty($data['idIdentitasLab'])
                    && !empty($data['idStatusLayanan']) && !empty($data['idKategoriMep']) 
                    && !empty($data['idSumberDana']) && !empty($data['biaya'])
                    && !empty($data['bukti']) && !empty($data['spesifikasi']) ) {
                        $this->layananLabNonAsetModel->insert($data);
                } else {
                    return redirect()->to(site_url('layananLabNonAset'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('layananLabNonAset'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('layananLabNonAset'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
        $dataLayananLabNonAset = $this->layananLabNonAsetModel->getAll($startDate, $endDate);
        $title = "REPORT LAYANAN NON ASET LABORATORIUM";
        if (!$dataLayananLabNonAset) {
            return view('error/404');
        }
    
        $data = [
            'dataLayananLabNonAset' => $dataLayananLabNonAset,
        ];
    
        $pdfData = pdf_layanannonasetlab($dataLayananLabNonAset, $title, $startDate, $endDate);
    
        
        $filename = 'Laboratorium - Layanan Non Aset' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }


    
}

