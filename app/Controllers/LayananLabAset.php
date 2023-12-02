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
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataLayananLabAset'] = $this->layananLabAsetModel->getAll();
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
            // $uploadedFilePath = $this->uploadFile('bukti');
            // if ($uploadedFilePath !== null) {
            //     $data['bukti'] = $uploadedFilePath;
            // }
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
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblLayananLabAset')
                ->set('deleted_at', null, true)
                ->where(['idLayananLabAset' => $id])
                ->update();
        } else {
            $this->db->table('tblLayananLabAset')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('layananLabAset'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('layananLabAset/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->layananLabAsetModel->delete($id, true);
        return redirect()->to(site_url('layananLabAset/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->layananLabAsetModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->layananLabAsetModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('layananLabAset/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('layananLabAset/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  

    private function htmlConverter($html) {
        $plainText = strip_tags(str_replace('<br />', "\n", $html));
        $plainText = preg_replace('/\n+/', "\n", $plainText);
        return $plainText;  
    }

    public function export() {
        $data = $this->layananLabAsetModel->getAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Layanan Aset');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Tanggal', 'Nama Aset', 'Lokasi', 'Status Layanan', 'Kategori Manajemen', 'Sumber Dana', 'Biaya', 'Bukti', 'Keterangan'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->tanggal);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->namaLab);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->namaStatusLayanan);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaKategoriManajemen);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('H'.($index + 2), $value->biaya);
            $activeWorksheet->setCellValue('I'.($index + 2), $value->bukti);
            $activeWorksheet->setCellValue('J'.($index + 2), $value->keterangan);
    
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }
    
        $activeWorksheet->getStyle('A1:J1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:J'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:J')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'J') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Layanan Aset Laboratorium.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->layananLabAsetModel->getAll();
        $keyAset = $this->rincianLabAsetModel->getAll();
        $keyLab = $this->identitasLabModel->findAll();
        $keyStatusLayanan = $this->statusLayananModel->findAll();
        $keySumberDana = $this->sumberDanaModel->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
        
        $headerInputTable = ['No.', 'Tanggal', 'ID Rincian Lab Aset', 'ID Sumber Dana', 'ID Status Layanan', 'Biaya', 'Bukti', 'Keterangan'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerSaranaID = ['ID Rincian Lab Aset', 'Kode Aset', 'Nama Aset'];
        $activeWorksheet->fromArray([$headerSaranaID], NULL, 'J1');
        $activeWorksheet->getStyle('J1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerLabID = ['ID Status Layanan', 'Nama Layanan'];
        $activeWorksheet->fromArray([$headerLabID], NULL, 'Q1');
        $activeWorksheet->getStyle('Q1:R1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerSumberDanaID = ['ID Sumber Dana', 'Sumber Dana'];
        $activeWorksheet->fromArray([$headerSumberDanaID], NULL, 'N1');
        $activeWorksheet->getStyle('N1:O1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
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
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headerExampleTable =  ['No.', 'Tanggal', 'ID Rincian Lab Aset', 'ID Sumber Dana', 'ID Status Layanan', 'Biaya', 'Bukti', 'Keterangan'];
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            };

            $currentDate = date('Y-m-d');
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
                $exampleSheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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
        $filePath = APPPATH . 'Views/labView/layananLabAset/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataLayananLabAset'] = $this->layananLabAsetModel->getAll();

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
        $filename = 'Laboratorium - Layanan Aset Report.pdf';
        $dompdf->stream($filename);
    }

    
    // public function fetchKategoriManajemen()
    // {
    //     $idIdentitasLab = $this->request->getPost('idIdentitasLab');
    //     $kategoriManajemen =  $this->layananLabAsetModel->getKategoriManajemen($idIdentitasLab);

    //     return $this->response->setJSON($kategoriManajemen);
    // }

    // public function setKategoriManajemen()
    // {
    //     $dataPaket = new RincianLabAsetModels();
    //     $idIdentitasLab = $this->request->getVar('idIdentitasLab');

    //     $kategoriManajemenLoad = $dataPaket->select('idKategoriManajemen, namaKategoriManajemen')->where(
    //         'idIdentitasLab',
    //         $idIdentitasLab
    //     )->orderBy('namaKategoriManajemen')->findAll();
    //     $data = [];
    //     foreach ($kategoriManajemenLoad as $value) {
    //         $data[] = [
    //             'id' => $value->idKategoriManajemen,
    //             'text' => $value->namaKategoriManajemen
    //         ];
    //     }
    //     $response['data'] = $data;
    //     echo $data;
    //     die;
    //     return $this->response->setJSON($response);
    // }
}

