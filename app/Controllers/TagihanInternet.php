<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TagihanInternetModels; 
use App\Models\UserActionLogsModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class TagihanInternet extends ResourceController
{
    
     function __construct() {
        $this->tagihanInternetModel = new TagihanInternetModels();
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['pdf', 'custom']);
    }

    public function index() {
        $startYear = $this->request->getVar('startYear');
        $endYear = $this->request->getVar('endYear');
    
        $formattedStartYear = !empty($startYear) ? $startYear : '';
        $formattedEndYear = !empty($endYear) ? $endYear : '';
    
        $tableHeading = "";
        if (!empty($formattedStartYear) && !empty($formattedEndYear)) {
            $tableHeading = "Tahun $formattedStartYear - $formattedEndYear";
        }
    
        $data['tableHeading'] = $tableHeading;

        $dataTagihanInternet = $this->tagihanInternetModel->getData($startYear, $endYear);
        
        foreach ($dataTagihanInternet as $value) {
            $value->bulanPemakaianInternet = $this->tagihanInternetModel->convertMonth($value->bulanPemakaianInternet);
        }
        
        $data['dataTagihanInternet'] = $dataTagihanInternet;
    
        $chartDataResult = $dataTagihanInternet; 
        $chartBiaya = $this->chartBiaya($chartDataResult);
        $chartPemakaian = $this->chartPemakaian($chartDataResult);
    
        $data = array_merge($data, $chartBiaya, $chartPemakaian);
    
        return view('profilSekolahView/tagihanInternet/index', $data);
    }
    
    private function chartBiaya($chartDataResult) {
        $chartBiaya = [
            'categories' => [],
            'biaya' => [],
        ];
    
        if ($chartDataResult) {
            foreach ($chartDataResult as $row) {
                $category = $row->bulanPemakaianInternet . ' (' . $row->tahunPemakaianInternet . ')';
                $chartBiaya['categories'][] = $category;
                $chartBiaya['biaya'][] = (int) $row->biaya;
            }
        }
        return $chartBiaya;
    }

    private function chartPemakaian($chartDataResult) {
        $chartPemakaian = [
            'categories' => [],
            'pemakaianInternet' => [],
        ];
    
        if ($chartDataResult) {
            foreach ($chartDataResult as $row) {
                $category = $row->bulanPemakaianInternet . ' (' . $row->tahunPemakaianInternet . ')';
                $chartPemakaian['categories'][] = $category;
                $chartPemakaian['pemakaianInternet'][] = $row->pemakaianInternet;
            }
        }
        return $chartPemakaian;
    }
    
        // public function index() {
    //     $startYear = $this->request->getVar('startYear');
    //     $endYear = $this->request->getVar('endYear');
        
    //     $formattedStartYear = !empty($startYear) ? $startYear : '';
    //     $formattedEndYear = !empty($endYear) ? $endYear : '';
        
    //     $tableHeading = "";
    //     if (!empty($formattedStartYear) && !empty($formattedEndYear)) {
    //         $tableHeading = "Tahun $formattedStartYear - $formattedEndYear";
    //     }
    
    //     $data['tableHeading'] = $tableHeading;
    //     $data['dataTagihanInternet'] = $this->tagihanInternetModel->getData($startYear, $endYear);
    
    //     $chartDataResult = $this->tagihanInternetModel->getData($startYear, $endYear);
    //     $chartData = $this->chartPemakaian($chartDataResult);
    
    //     $data = array_merge($data, $chartData);
    
    //     return view('profilSekolahView/tagihanInternet/index', $data);
    // }
    

    
    public function show($id = null) {
        if ($id != null) {
            $dataTagihanInternet = $this->tagihanInternetModel->find($id);
        
            if (is_object($dataTagihanInternet)) {
                $spesifikasiMarkup = $dataTagihanInternet->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataTagihanInternet->bukti);
                $data = [
                    'dataTagihanInternet'           => $dataTagihanInternet,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];
                return view('profilSekolahView/tagihanInternet/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data['dataTagihanInternet'] = $this->tagihanInternetModel->findAll();
        
        return view('profilSekolahView/tagihanInternet/new', $data);        
    }

    
    public function create() {
        $data = $this->request->getPost(); 
        if (!empty($data['pemakaianInternet'])  && !empty($data['bulanPemakaianInternet'])  && !empty($data['tahunPemakaianInternet']) && !empty($data['biaya']) ) {
            $this->tagihanInternetModel->insert($data);
            return redirect()->to(site_url('tagihanInternet'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('tagihanInternet'))->with('error', 'Semua field harus terisi');
        }
    }

    public function edit($id = null) {
        if ($id != null) {
            $dataTagihanInternet = $this->tagihanInternetModel->find($id);
    
            if (is_object($dataTagihanInternet)) {
                $data = [
                    'dataTagihanInternet' => $dataTagihanInternet,
                ];
                return view('profilSekolahView/tagihanInternet/edit', $data);
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
            if (!empty($data['pemakaianInternet'])  && !empty($data['bulanPemakaianInternet'])  && !empty($data['tahunPemakaianInternet']) && !empty($data['biaya']) ) {
                $this->tagihanInternetModel->update($id, $data);
                return redirect()->to(site_url('tagihanInternet'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to(site_url('tagihanInternet'))->with('error', 'Semua data harus diisi');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->tagihanInternetModel->delete($id);
        return redirect()->to(site_url('tagihanInternet'));
    }

    public function trash() {
        $data['dataTagihanInternet'] = $this->tagihanInternetModel->onlyDeleted()->getRecycle();
        return view('profilSekolahView/tagihanInternet/trash', $data);
    } 

    
    public function restore($id = null) {
        $affectedRows = restoreData('tblTagihanInternet', 'idTagihanInternet', $id, $this->userActionLogsModel, 'Sekolah - Tagihan Internet');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('tagihanInternet'))->with('success', 'Data berhasil direstore');
        }
    
        return redirect()->to(site_url('tagihanInternet/trash'))->with('error', 'Tidak ada data untuk direstore');
    }

    public function deletePermanent($id = null) {
        $affectedRows = deleteData('tblTagihanInternet', 'idTagihanInternet', $id, $this->userActionLogsModel, 'Sekolah - Tagihan Internet');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('tagihanInternet'))->with('success', 'Data berhasil dihapus');
        } 
    
        return redirect()->to(site_url('tagihanInternet/trash'))->with('error', 'Tidak ada data untuk dihapus');
    }
    
    public function export() {
        $startYear = $this->request->getVar('startYear');
        $endYear = $this->request->getVar('endYear');

        $data = $this->tagihanInternetModel->getData($startYear, $endYear);
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('TagihanInternet');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Bulan', 'Tahun', 'Pemakaian Internet ',  'Biaya Tagihan'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->bulanPemakaianInternet);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->tahunPemakaianInternet);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->pemakaianInternet);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->biaya);
            $activeWorksheet->getStyle('E' . ($index + 2))->getNumberFormat()->setFormatCode("Rp#,##0");
            $columns = ['A', 'B', 'C', 'D', 'E'];
            
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
        
        $activeWorksheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:E1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:E'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:E')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'E') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Tagihan - Tagihan Internet.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->tagihanInternetModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Bulan', 'Tahun', 'Pemakaian Internet ',  'Biaya Tagihan'];
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

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headers = ['No.', 'Bulan', 'Tahun', 'Pemakaian Internet ',  'Biaya Tagihan'];
        $exampleSheet->fromArray([$headers], NULL, 'A1');
        $exampleSheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->bulanPemakaianInternet);
            $exampleSheet->setCellValue('C'.($index + 2), $value->tahunPemakaianInternet);
            $exampleSheet->setCellValue('D'.($index + 2), $value->pemakaianInternet);
            $exampleSheet->setCellValue('E'.($index + 2), $value->biaya);

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
        header('Content-Disposition: attachment;filename=Tagihan - Tagihan Internet Example.xlsx');
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
                $pemakaianInternet            = $value[3] ?? null;
                $bulanPemakaianInternet       = $value[1] ?? null;
                $tahunPemakaianInternet       = $value[2] ?? null;
                $biaya                   = $value[4] ?? null;

                $data = [
                    'pemakaianInternet'             => $pemakaianInternet,
                    'bulanPemakaianInternet'        => $bulanPemakaianInternet,
                    'tahunPemakaianInternet'        => $tahunPemakaianInternet,
                    'biaya'                    => $biaya,
                ];

                if (!empty($data['pemakaianInternet'])  && !empty($data['bulanPemakaianInternet'])  && !empty($data['tahunPemakaianInternet']) && !empty($data['biaya']) ) {
                        $this->tagihanInternetModel->insert($data);
                } else {
                    return redirect()->to(site_url('tagihanInternet'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('tagihanInternet'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('tagihanInternet'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $startYear = $this->request->getVar('startYear');
        $endYear = $this->request->getVar('endYear');
        $dataTagihanInternet = $this->tagihanInternetModel->getData($startYear, $endYear);
        $title = "REPORT TAGIHAN INTERNET";
        if (!$dataTagihanInternet) {
            return view('error/404');
        }
    
        $data = [
            'dataTagihanInternet' => $dataTagihanInternet,
        ];
    
        $pdfData = pdfTagihanInternet($dataTagihanInternet, $title, $startYear, $endYear);
    
        
        $filename = 'Sekolah - Tagihan Internet' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }

    public function generatePDF2() {
        $startYear = $this->request->getVar('startYear');
        $endYear = $this->request->getVar('endYear');
        

        $filePath = APPPATH . 'Views/profilSekolahView/tagihanInternet/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataTagihanInternet'] = $this->tagihanInternetModel->getData($startYear, $endYear);

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
        $filename = 'Tagihan - Tagihan Internet Report.pdf';
        $dompdf->stream($filename);
    }
}