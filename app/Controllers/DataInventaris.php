<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DataInventarisModels; 
use App\Models\InventarisModels; 
use App\Models\UserActionLogsModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class DataInventaris extends ResourceController
{
    
     function __construct() {
        $this->dataInventarisModel = new DataInventarisModels();
        $this->inventarisModel = new InventarisModels();
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['custom']);
    }

    public function index() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
    
        $formattedStartDate = !empty($startDate) ? $startDate : '';
        $formattedEndDate = !empty($endDate) ? $endDate : '';
    
        $tableHeading = "";
        if (!empty($formattedStartDate) && !empty($formattedEndDate)) {
            $tableHeading = "$formattedStartDate - $formattedEndDate";
        }
    
        $data['tableHeading'] = $tableHeading;

        $dataDataInventaris = $this->dataInventarisModel->getData($startDate, $endDate);
        
        $data['dataDataInventaris'] = $dataDataInventaris;
    
        return view('saranaView/dataInventaris/index', $data);
    }
    
    public function show($id = null) {
        if ($id != null) {
            $dataDataInventaris = $this->dataInventarisModel->find($id);
        
            if (is_object($dataDataInventaris)) {
                $spesifikasiMarkup = $dataDataInventaris->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataDataInventaris->bukti);
                $data = [
                    'dataDataInventaris'           => $dataDataInventaris,
                    'dataInventaris'       => $this->dataInventarisModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];
                return view('saranaView/dataInventaris/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data = [
            'dataInventaris' => $this->inventarisModel->findAll(),
            'dataDataInventaris' => $this->dataInventarisModel->getAll(),
        ];
        
        return view('saranaView/dataInventaris/new', $data);        
    }

    
    public function create() {
        $data = $this->request->getPost(); 
        if (!empty($data['idInventaris'])  && !empty($data['tipeDataInventaris'])  && !empty($data['jumlahDataInventaris']) ) {
            $this->dataInventarisModel->insert($data);
            return redirect()->to(site_url('dataInventaris'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('dataInventaris'))->with('error', 'Semua field harus terisi');
        }
    }

    public function edit($id = null) {
        if ($id != null) {
            $dataDataInventaris = $this->dataInventarisModel->find($id);
    
            if (is_object($dataDataInventaris)) {
                $data = [
                    'dataInventaris' => $this->inventarisModel->findAll(),
                    'dataDataInventaris' => $dataDataInventaris,
                ];
                return view('saranaView/dataInventaris/edit', $data);
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
            if (!empty($data['idInventaris'])  && !empty($data['tipeDataInventaris'])  && !empty($data['jumlahDataInventaris']) ) {
                $this->dataInventarisModel->update($id, $data);
                return redirect()->to(site_url('dataInventaris'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to(site_url('dataInventaris'))->with('error', 'Semua data harus diisi');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->dataInventarisModel->delete($id);
        return redirect()->to(site_url('dataInventaris'));
    }

    public function trash() {
        $data['dataDataInventaris'] = $this->dataInventarisModel->onlyDeleted()->getRecycle();
        return view('saranaView/dataInventaris/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblDataInventaris')
                ->set('deleted_at', null, true)
                ->where(['idDataInventaris' => $id])
                ->update();

                activityLogs($this->userActionLogsModel, "Restore", "Melakukan restore data inventaris dengan id " .$id);
            } else {
            $this->db->table('tblDataInventaris')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();

                activityLogs($this->userActionLogsModel, "Restore All", "Melakukan restore semua data inventaris");
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('dataInventaris'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('dataInventaris/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->dataInventarisModel->delete($id, true);
        return redirect()->to(site_url('dataInventaris/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->dataInventarisModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->dataInventarisModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('dataInventaris/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('dataInventaris/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 
    
    public function export() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $data = $this->dataInventarisModel->getData($startDate, $endDate);
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('DataInventaris');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Tanggal', 'Nama', 'Satuan',  'Tipe', 'Jumlah'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->tanggalDataInventaris);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaInventaris);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->satuan);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->tipeDataInventaris);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->jumlahDataInventaris);

            $columns = ['A', 'B', 'C', 'D', 'E', 'F'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }
        
        $activeWorksheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:F1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:F'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:F')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'F') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Sarana - Data Inventaris.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->dataInventarisModel->findAll();
        $keyAset = $this->inventarisModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
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
        $validation->setPrompt('Please pick a value from the drop-down list.');
        $validation->setFormula1('"'.implode(',', $enumValues).'"');

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $currentDate = date('d F Y');
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $currentDate);
            $activeWorksheet->setCellValue('C'.($index + 2), '');
            $activeWorksheet->setCellValue('D'.($index + 2), '');
            $activeWorksheet->setCellValue('E'.($index + 2), '');

            $columns = ['A', 'B', 'C', 'D', 'E'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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
            $activeWorksheet->getColumnDimension($column)->setWidth(30);
        }

        foreach ($keyAset as $index => $value) {
            $activeWorksheet->setCellValue('G'.($index + 2), $value->idInventaris);
            $activeWorksheet->setCellValue('H'.($index + 2), $value->namaInventaris);
            $activeWorksheet->setCellValue('I'.($index + 2), $value->satuan);
    
            $columns = ['G', 'I'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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
            $exampleSheet->setCellValue('B'.($index + 2), $value->tanggalDataInventaris);
            $exampleSheet->setCellValue('C'.($index + 2), $value->idInventaris);
            $exampleSheet->setCellValue('D'.($index + 2), $value->tipeDataInventaris);
            $exampleSheet->setCellValue('E'.($index + 2), $value->jumlahDataInventaris);

            $columns = ['A', 'B', 'C', 'D', 'E'];
            
            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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
        header('Content-Disposition: attachment;filename=Sarana - Data Inventaris Example.xlsx');
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
                $tanggalDataInventaris              = $value[1] ?? null;
                $idInventaris                       = $value[2] ?? null;
                $tipeDataInventaris                 = $value[3] ?? null;
                $jumlahDataInventaris               = $value[4] ?? null;
                if ($idInventaris === null || $idInventaris === '') {
                    continue; 
                }
                $data = [
                    'idInventaris'              => $idInventaris,
                    'tanggalDataInventaris'     => $tanggalDataInventaris,
                    'tipeDataInventaris'        => $tipeDataInventaris,
                    'jumlahDataInventaris'      => $jumlahDataInventaris,
                ];

                if (!empty($data['idInventaris'])  && !empty($data['tanggalDataInventaris'])  && !empty($data['tipeDataInventaris']) && !empty($data['jumlahDataInventaris']) ) {
                        $this->dataInventarisModel->insert($data);
                } else {
                    return redirect()->to(site_url('dataInventaris'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('dataInventaris'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('dataInventaris'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
        

        $filePath = APPPATH . 'Views/saranaView/dataInventaris/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataDataInventaris'] = $this->dataInventarisModel->getData($startDate, $endDate);

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
        $filename = 'Sarana - Data Inventaris Report.pdf';
        $dompdf->stream($filename);
    }
}