<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TagihanListrikModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class TagihanListrik extends ResourceController
{
    
     function __construct() {
        $this->tagihanListrikModel = new TagihanListrikModels();
        $this->db = \Config\Database::connect();
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

        $dataTagihanListrik = $this->tagihanListrikModel->getData($startYear, $endYear);
        
        foreach ($dataTagihanListrik as $value) {
            $value->bulanPemakaianListrik = $this->tagihanListrikModel->convertMonth($value->bulanPemakaianListrik);
        }
        
        $data['dataTagihanListrik'] = $dataTagihanListrik;
    
        $chartDataResult = $dataTagihanListrik; 
        $chartBiaya = $this->chartBiaya($chartDataResult);
        $chartPemakaian = $this->chartPemakaian($chartDataResult);
    
        $data = array_merge($data, $chartBiaya, $chartPemakaian);
    
        return view('profilSekolahView/tagihanListrik/index', $data);
    }
    
    private function chartBiaya($chartDataResult) {
        $chartBiaya = [
            'categories' => [],
            'biaya' => [],
        ];
    
        if ($chartDataResult) {
            foreach ($chartDataResult as $row) {
                $category = $row->bulanPemakaianListrik . ' (' . $row->tahunPemakaianListrik . ')';
                $chartBiaya['categories'][] = $category;
                $chartBiaya['biaya'][] = (int) $row->biaya;
            }
        }
        return $chartBiaya;
    }

    private function chartPemakaian($chartDataResult) {
        $chartPemakaian = [
            'categories' => [],
            'pemakaianListrik' => [],
        ];
    
        if ($chartDataResult) {
            foreach ($chartDataResult as $row) {
                $category = $row->bulanPemakaianListrik . ' (' . $row->tahunPemakaianListrik . ')';
                $chartPemakaian['categories'][] = $category;
                $chartPemakaian['pemakaianListrik'][] = $row->pemakaianListrik;
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
    //     $data['dataTagihanListrik'] = $this->tagihanListrikModel->getData($startYear, $endYear);
    
    //     $chartDataResult = $this->tagihanListrikModel->getData($startYear, $endYear);
    //     $chartData = $this->chartPemakaian($chartDataResult);
    
    //     $data = array_merge($data, $chartData);
    
    //     return view('profilSekolahView/tagihanListrik/index', $data);
    // }
    

    
    public function show($id = null) {
        if ($id != null) {
            $dataTagihanListrik = $this->tagihanListrikModel->find($id);
        
            if (is_object($dataTagihanListrik)) {
                $spesifikasiMarkup = $dataTagihanListrik->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataTagihanListrik->bukti);
                $data = [
                    'dataTagihanListrik'           => $dataTagihanListrik,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];
                return view('profilSekolahView/tagihanListrik/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data['dataTagihanListrik'] = $this->tagihanListrikModel->findAll();
        
        return view('profilSekolahView/tagihanListrik/new', $data);        
    }

    
    public function create() {
        $data = $this->request->getPost(); 
        if (!empty($data['pemakaianListrik'])  && !empty($data['bulanPemakaianListrik'])  && !empty($data['tahunPemakaianListrik']) && !empty($data['biaya']) ) {
            $this->tagihanListrikModel->insert($data);
            return redirect()->to(site_url('tagihanListrik'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('tagihanListrik'))->with('error', 'Semua field harus terisi');
        }
    }

    public function edit($id = null) {
        if ($id != null) {
            $dataTagihanListrik = $this->tagihanListrikModel->find($id);
    
            if (is_object($dataTagihanListrik)) {
                $data = [
                    'dataTagihanListrik' => $dataTagihanListrik,
                ];
                return view('profilSekolahView/tagihanListrik/edit', $data);
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
            if (!empty($data['pemakaianListrik'])  && !empty($data['bulanPemakaianListrik'])  && !empty($data['tahunPemakaianListrik']) && !empty($data['biaya']) ) {
                $this->tagihanListrikModel->update($id, $data);
                return redirect()->to(site_url('tagihanListrik'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to(site_url('tagihanListrik'))->with('error', 'Semua data harus diisi');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->tagihanListrikModel->delete($id);
        return redirect()->to(site_url('tagihanListrik'));
    }

    public function trash() {
        $data['dataTagihanListrik'] = $this->tagihanListrikModel->onlyDeleted()->getRecycle();
        return view('profilSekolahView/tagihanListrik/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblTagihanListrik')
                ->set('deleted_at', null, true)
                ->where(['idTagihanListrik' => $id])
                ->update();
        } else {
            $this->db->table('tblTagihanListrik')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('tagihanListrik'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('tagihanListrik/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->tagihanListrikModel->delete($id, true);
        return redirect()->to(site_url('tagihanListrik/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->tagihanListrikModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->tagihanListrikModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('tagihanListrik/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('tagihanListrik/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 
    
    public function export() {
        $startYear = $this->request->getVar('startYear');
        $endYear = $this->request->getVar('endYear');

        $data = $this->tagihanListrikModel->getData($startYear, $endYear);
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('TagihanListrik');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Bulan', 'Tahun', 'Pemakaian Listrik (kWh)',  'Biaya Tagihan'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->bulanPemakaianListrik);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->tahunPemakaianListrik);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->pemakaianListrik);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->biaya);

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
        $activeWorksheet->getStyle('A:E')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'E') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Tagihan - Tagihan Listrik.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->tagihanListrikModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Bulan', 'Tahun', 'Pemakaian Listrik (kWh)',  'Biaya Tagihan'];
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

        $headers = ['No.', 'Bulan', 'Tahun', 'Pemakaian Listrik (kWh)',  'Biaya Tagihan'];
        $exampleSheet->fromArray([$headers], NULL, 'A1');
        $exampleSheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->bulanPemakaianListrik);
            $exampleSheet->setCellValue('C'.($index + 2), $value->tahunPemakaianListrik);
            $exampleSheet->setCellValue('D'.($index + 2), $value->pemakaianListrik);
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
        header('Content-Disposition: attachment;filename=Tagihan - Tagihan Listrik Example.xlsx');
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
                $pemakaianListrik            = $value[3] ?? null;
                $bulanPemakaianListrik       = $value[1] ?? null;
                $tahunPemakaianListrik       = $value[2] ?? null;
                $biaya                   = $value[4] ?? null;

                $data = [
                    'pemakaianListrik'             => $pemakaianListrik,
                    'bulanPemakaianListrik'        => $bulanPemakaianListrik,
                    'tahunPemakaianListrik'        => $tahunPemakaianListrik,
                    'biaya'                    => $biaya,
                ];

                if (!empty($data['pemakaianListrik'])  && !empty($data['bulanPemakaianListrik'])  && !empty($data['tahunPemakaianListrik']) && !empty($data['biaya']) ) {
                        $this->tagihanListrikModel->insert($data);
                } else {
                    return redirect()->to(site_url('tagihanListrik'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('tagihanListrik'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('tagihanListrik'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $startYear = $this->request->getVar('startYear');
        $endYear = $this->request->getVar('endYear');
        

        $filePath = APPPATH . 'Views/profilSekolahView/tagihanListrik/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataTagihanListrik'] = $this->tagihanListrikModel->getData($startYear, $endYear);

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
        $filename = 'Tagihan - Tagihan Listrik Report.pdf';
        $dompdf->stream($filename);
    }
}