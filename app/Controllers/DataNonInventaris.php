<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DataNonInventarisModels; 
use App\Models\NonInventarisModels; 
use App\Models\UserActionLogsModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class DataNonInventaris extends ResourceController
{
    
     function __construct() {
        $this->dataNonInventarisModel = new DataNonInventarisModels();
        $this->nonInventarisModel = new NonInventarisModels();
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
        $data['dataNonInventaris'] = $this->dataNonInventarisModel->getAll($startDate, $endDate);

        return view('saranaView/nonInventaris/index', $data);
    }
    
    public function show($id = null) {
        if ($id != null) {
            $dataNonInventaris = $this->dataNonInventarisModel->find($id);
        
            if (is_object($dataNonInventaris)) {
                $spesifikasiMarkup = $dataNonInventaris->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataNonInventaris->bukti);
                $data = [
                    'dataNonInventaris'           => $dataNonInventaris,
                    'dataNonInventaris'       => $this->dataNonInventarisModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];
                return view('saranaView/nonInventaris/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data = [
            'nonInventaris' => $this->nonInventarisModel->findAll(),
            'dataNonInventaris' => $this->dataNonInventarisModel->findAll(),
        ];
        
        return view('saranaView/nonInventaris/new', $data);        
    }

    public function create() {
        $data = $this->request->getPost(); 
        // var_dump($data);
        // die;
        if (!empty($data['idNonInventaris'])  && !empty($data['tipe'])  && !empty($data['jumlah']) ) {
            $this->dataNonInventarisModel->insert($data);
            return redirect()->to(site_url('dataNonInventaris'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('dataNonInventaris'))->with('error', 'Semua field harus terisi');
        }
    }

    public function edit($id = null) {
        if ($id != null) {
            $dataNonInventaris = $this->dataNonInventarisModel->find($id);
    
            if (is_object($dataNonInventaris)) {
                $data = [
                    'nonInventaris' => $this->nonInventarisModel->findAll(),
                    'dataNonInventaris' => $dataNonInventaris,
                ];
                return view('saranaView/nonInventaris/edit', $data);
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
            if (!empty($data['idNonInventaris'])  && !empty($data['tipe'])  && !empty($data['jumlah']) ) {
                $this->dataNonInventarisModel->update($id, $data);
                return redirect()->to(site_url('dataNonInventaris'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to(site_url('dataNonInventaris'))->with('error', 'Semua data harus diisi');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->dataNonInventarisModel->delete($id);
        return redirect()->to(site_url('dataNonInventaris'));
    }

    public function trash() {
        $data['dataNonInventaris'] = $this->dataNonInventarisModel->onlyDeleted()->getRecycle();
        return view('saranaView/nonInventaris/trash', $data);
    } 

    public function restore($id = null) {
        $affectedRows = restoreData('tblDataNonInventaris', 'idDataNonInventaris', $id, $this->userActionLogsModel, 'Sarana - Non Inventaris');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('dataNonInventaris'))->with('success', 'Data berhasil direstore');
        }
    
        return redirect()->to(site_url('dataNonInventaris/trash'))->with('error', 'Tidak ada data untuk direstore');
    }

    public function deletePermanent($id = null) {
        $affectedRows = deleteData('tblDataNonInventaris', 'idDataNonInventaris', $id, $this->userActionLogsModel, 'Sarana - Non Inventaris');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('dataNonInventaris'))->with('success', 'Data berhasil dihapus');
        } 
    
        return redirect()->to(site_url('dataNonInventaris/trash'))->with('error', 'Tidak ada data untuk dihapus');
    }
    
    public function export() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $data = $this->dataNonInventarisModel->getAll($startDate, $endDate);

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Data Non Inventaris');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Tanggal', 'Nama', 'Satuan',  'Tipe', 'Jumlah'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $date);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->nama);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->satuan);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->tipe);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->jumlah);

            $columns = ['A', 'B', 'C', 'D', 'E', 'F'];
            
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $activeWorksheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A' || $column === 'F' ) {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }              
        }
        
        $activeWorksheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:F1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:F'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:F')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'F') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $dataPengeluaran = $this->dataNonInventarisModel->getPengeluaran($startDate, $endDate);
        $pengeluaranSheet = $spreadsheet->createSheet();
        $pengeluaranSheet->setTitle('Pengeluaran');
        $pengeluaranSheet->getTabColor()->setRGB('767870');

        $headerPengeluaranSheet = ['No.', 'Tanggal', 'Nama', 'Satuan', 'Jumlah'];
        $pengeluaranSheet->fromArray([$headerPengeluaranSheet], NULL, 'A1');
        $pengeluaranSheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        foreach ($dataPengeluaran as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $pengeluaranSheet->setCellValue('A'.($index + 2), $index + 1);
            $pengeluaranSheet->setCellValue('B'.($index + 2), $date);
            $pengeluaranSheet->setCellValue('C'.($index + 2), $value->nama);
            $pengeluaranSheet->setCellValue('D'.($index + 2), $value->satuan);
            $pengeluaranSheet->setCellValue('E'.($index + 2), $value->jumlah);

            $columns = ['A', 'B', 'C', 'D', 'E'];
            
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $pengeluaranSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A' || $column === 'E' ) {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }      
        }
        
        $pengeluaranSheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $pengeluaranSheet->getStyle('A1:E1')->getFont()->setBold(true);
        $pengeluaranSheet->getStyle('A1:E'.$pengeluaranSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $pengeluaranSheet->getStyle('A:E')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'E') as $column) {
            $pengeluaranSheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        $dataPemasukan = $this->dataNonInventarisModel->getPemasukan($startDate, $endDate);
        $pemasukanSheet = $spreadsheet->createSheet();
        $pemasukanSheet->setTitle('Pemasukan');
        $pemasukanSheet->getTabColor()->setRGB('767870');
        $pemasukanSheet->fromArray([$headerPengeluaranSheet], NULL, 'A1');
        $pemasukanSheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        foreach ($dataPemasukan  as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $pemasukanSheet->setCellValue('A'.($index + 2), $index + 1);
            $pemasukanSheet->setCellValue('B'.($index + 2), $date);
            $pemasukanSheet->setCellValue('C'.($index + 2), $value->nama);
            $pemasukanSheet->setCellValue('D'.($index + 2), $value->satuan);
            $pemasukanSheet->setCellValue('E'.($index + 2), $value->jumlah);

            $columns = ['A', 'B', 'C', 'D', 'E'];
            
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $pemasukanSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A' || $column === 'E' ) {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }   
        }
        
        $pemasukanSheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $pemasukanSheet->getStyle('A1:E1')->getFont()->setBold(true);
        $pemasukanSheet->getStyle('A1:E'.$pemasukanSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $pemasukanSheet->getStyle('A:E')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'E') as $column) {
            $pemasukanSheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Sarana - Data Non Inventaris.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->dataNonInventarisModel->findAll();
        $keyAset = $this->nonInventarisModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Tanggal', 'ID Aset Non Invetaris', 'Tipe',  'Jumlah'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerAsetID = ['ID', 'Nama', 'Satuan'];
        $activeWorksheet->fromArray([$headerAsetID], NULL, 'G1');
        $activeWorksheet->getStyle('G1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $enumValues = ['Pemasukan', 'Pengeluaran'];

        $validation = $activeWorksheet->getCell('D2')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Value is not in list.');
        $validation->setPromptTitle('Pick from list');
        $validation->setFormula1('"'.implode(',', $enumValues).'"');

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $currentDate = date('Y-m-d');
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $currentDate);
            $activeWorksheet->setCellValue('C'.($index + 2), '');
            $activeWorksheet->setCellValue('D'.($index + 2), '');
            $activeWorksheet->setCellValue('E'.($index + 2), '');

            $columns = ['A', 'B', 'C', 'D', 'E'];
            
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
        
        $activeWorksheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:E1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:E'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:E')->getAlignment()->setWrapText(true);;
    
        foreach (range('A', 'E') as $column) {
            if ($column === 'A') {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
            $activeWorksheet->getColumnDimension($column)->setWidth(20);
        }

        foreach ($keyAset as $index => $value) {
            $activeWorksheet->setCellValue('G'.($index + 2), $value->idNonInventaris);
            $activeWorksheet->setCellValue('H'.($index + 2), $value->nama);
            $activeWorksheet->setCellValue('I'.($index + 2), $value->satuan);
    
            $columns = ['G', 'I'];
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $activeWorksheet->getStyle($cellReference)->getAlignment();
            
                if ($column === 'G') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            }   
        }

        $activeWorksheet->getStyle('G1:I1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('G1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('G1:I'.(count($keyAset) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('G:I')->getAlignment()->setWrapText(true);
    
        foreach (range('G', 'I') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headers =  ['No.', 'Tanggal', 'ID Aset Non Invetaris', 'Tipe',  'Jumlah'];
        $exampleSheet->fromArray([$headers], NULL, 'A1');
        $exampleSheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->tanggal);
            $exampleSheet->setCellValue('C'.($index + 2), $value->idNonInventaris);
            $exampleSheet->setCellValue('D'.($index + 2), $value->tipe);
            $exampleSheet->setCellValue('E'.($index + 2), $value->jumlah);

            $columns = ['A', 'B', 'C', 'D', 'E'];
            
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
        header('Content-Disposition: attachment;filename=Sarana - Data Non Inventaris Example.xlsx');
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
                $tanggal              = $value[1] ?? null;
                $idNonInventaris                       = $value[2] ?? null;
                $tipe                 = $value[3] ?? null;
                $jumlah               = $value[4] ?? null;
                if ($idNonInventaris === null || $idNonInventaris === '') {
                    continue; 
                }
                $data = [
                    'idNonInventaris'              => $idNonInventaris,
                    'tanggal'     => $tanggal,
                    'tipe'        => $tipe,
                    'jumlah'      => $jumlah,
                ];

                if (!empty($data['idNonInventaris'])  && !empty($data['tanggal'])  && !empty($data['tipe']) && !empty($data['jumlah']) ) {
                        $this->dataNonInventarisModel->insert($data);
                } else {
                    return redirect()->to(site_url('dataNonInventaris'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('dataNonInventaris'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('dataNonInventaris'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }

    public function generatePDF() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $dataPemasukan = $this->dataNonInventarisModel->getPemasukan($startDate, $endDate);
        $dataPengeluaran = $this->dataNonInventarisModel->getPengeluaran($startDate, $endDate);
        $title = "REPORT DATA NON INVENTARIS";
        if (!$dataPemasukan || !$dataPengeluaran) {
            return view('error/404');
        }
        
        // $data = [
        //     'dataPemasukan' => $dataPemasukan,
        //     'dataPengeluaran' => $dataPengeluaran,
        // ];
    
        $pdfData = pdf_noninventaris($dataPemasukan, $dataPengeluaran, $title, $startDate, $endDate);
    
        
        $filename = 'Sarana - Non Inventaris' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }

    public function generatePDF2() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $dataNonInventaris = $this->dataNonInventarisModel->getPemasukan($startDate, $endDate);
        $title = "REPORT DATA NON INVENTARIS";
        if (!$dataNonInventaris) {
            return view('error/404');
        }
    
        $data = [
            'dataNonInventaris' => $dataNonInventaris,
        ];
    
        $pdfData = pdf_noninventaris($dataNonInventaris, $title, $startDate, $endDate);
    
        
        $filename = 'Sarana - Non Inventaris' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }
}