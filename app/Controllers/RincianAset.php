<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\RincianAsetModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasPrasaranaModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class RincianAset extends ResourceController
{
    
     function __construct() {
        $this->rincianAsetModel = new RincianAsetModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataRincianAset'] = $this->rincianAsetModel->getAll();
        return view('saranaView/rincianAset/index', $data);
    }

    public function show($id = null) {
        if ($id != null) {
            $dataRincianAset = $this->rincianAsetModel->find($id);
        
            if (is_object($dataRincianAset)) {
                $spesifikasiMarkup = $dataRincianAset->spesifikasi; 
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);

                $buktiUrl = $this->generateFileId($dataRincianAset->bukti);
                $data = [
                    'dataRincianAset'           => $dataRincianAset,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];
                return view('saranaView/rincianAset/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data = [
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataSumberDana' => $this->sumberDanaModel->findAll(),
            'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
            'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
        ];
        
        return view('saranaView/rincianAset/new', $data);        
    }

    
    public function create() {
        $data = $this->request->getPost();
        // $buktiPath = $this->uploadFile('bukti'); 
        if (!empty($data['idIdentitasSarana']) && !empty($data['tahunPengadaan']) && !empty($data['idSumberDana']) && !empty($data['kodePrasarana'])) {
            // $data['bukti'] = $buktiPath;
            $totalSarana =  $this->rincianAsetModel->calculateTotalSarana($data['saranaLayak'], $data['saranaRusak']);
            $data['totalSarana'] = $totalSarana;
            $this->rincianAsetModel->insert($data);
            $this->rincianAsetModel->setKodeAset();
            return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('rincianAset'))->with('error', 'Semua field harus terisi');
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
    

    public function edit($id = null) {
        if ($id != null) {
            $dataRincianAset = $this->rincianAsetModel->find($id);
    
            if (is_object($dataRincianAset)) {
                $data = [
                    'dataRincianAset' => $dataRincianAset,
                    'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana' => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
                ];
                return view('saranaView/rincianAset/edit', $data);
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
            // $uploadedFilePath = $this->uploadFile('bukti');
            if (!empty($data['idIdentitasSarana']) && !empty($data['tahunPengadaan']) && !empty($data['idSumberDana']) && !empty($data['kodePrasarana'])) {
                // if ($uploadedFilePath !== null) {
                //     $data['bukti'] = $uploadedFilePath;
                // }

                $totalSarana =  $this->rincianAsetModel->calculateTotalSarana($data['saranaLayak'], $data['saranaRusak']);
                $data['totalSarana'] = $totalSarana;

                $this->rincianAsetModel->update($id, $data);
                $this->rincianAsetModel->updateKodeAset($id);
                return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to
                (site_url('rincianAset/edit/'.$id))->with('error', 'Id Sarana dan Lantai harus diisi.');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->rincianAsetModel->delete($id);
        return redirect()->to(site_url('rincianAset'));
    }

    public function trash() {
        $data['dataRincianAset'] = $this->rincianAsetModel->onlyDeleted()->getRecycle();
        return view('saranaView/rincianAset/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblRincianAset')
                ->set('deleted_at', null, true)
                ->where(['idRincianAset' => $id])
                ->update();
        } else {
            $this->db->table('tblRincianAset')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('rincianAset/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->rincianAsetModel->delete($id, true);
        return redirect()->to(site_url('rincianAset/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->rincianAsetModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->rincianAsetModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('rincianAset/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('rincianAset/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 

    private function html2text($html) {
        $plainText = strip_tags(str_replace('<br />', "\n", $html));
        $plainText = preg_replace('/\n+/', "\n", $plainText);
        return $plainText;
    }
    
    public function export() {
        $data = $this->rincianAsetModel->getAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Rincian Aset');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Kode Aset', 'Nama Aset', 'Tahun Pengadaan', 'Kategori Manajemen', 'Sumber Dana', 'Aset Layak', 'Aset Rusak', 'Total Aset' , 'Link Dokumentasi', 'Spesifikasi'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $spesifikasiMarkup = $value->spesifikasi; 
            $parsedown = new Parsedown();
            $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
            $spesifikasiText = $this->html2text($spesifikasiHtml);
            
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->kodeRincianAset);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->tahunPengadaan);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->namaKategoriManajemen);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->saranaLayak);
            $activeWorksheet->setCellValue('H'.($index + 2), $value->saranaRusak);
            $activeWorksheet->setCellValue('I'.($index + 2), $value->totalSarana);
            $activeWorksheet->setCellValue('J'.($index + 2), $value->bukti);
            $activeWorksheet->setCellValue('K'.($index + 2), $spesifikasiText);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I' ,'J'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }
        $activeWorksheet->getStyle('K')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        
        $activeWorksheet->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:K1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:K'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:K')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'K') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Rincian Aset.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    

    public function generatePDF() {
        $filePath = APPPATH . 'Views/saranaView/rincianAset/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataRincianAset'] = $this->rincianAsetModel->getAll();

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
        $filename = 'Sarana - Rincian Aset Report.pdf';
        $dompdf->stream($filename);
    }


    public function print($id = null) {
        $dataRincianAset = $this->rincianAsetModel->find($id);
        
        if (!is_object($dataRincianAset)) {
            return view('error/404');
        }

        $spesifikasiMarkup = $dataRincianAset->spesifikasi; 
        $parsedown = new Parsedown();
        $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
        $buktiUrl = $this->generateFileId($dataRincianAset->bukti);

        $data = [
            'dataRincianAset'           => $dataRincianAset,
            'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
            'dataSumberDana'            => $this->sumberDanaModel->findAll(),
            'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
            'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
            'buktiUrl'                  => $buktiUrl,
            'spesifikasiHtml'           => $spesifikasiHtml,
        ];

        $filePath = APPPATH . 'Views/saranaView/rincianAset/printInfo.php';

        if (!file_exists($filePath)) {
            return view('error/404');
        }

        ob_start();

        $includeFile = function ($filePath, $data) {
            include $filePath;
        };

        $includeFile($filePath, $data);

        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $filename = 'Sarana - Detail Rincian Aset.pdf';
        $namaSarana = $data['dataRincianAset']->namaSarana;
        $filename = "Sarana - Detail Rincian Aset $namaSarana.pdf";
        $dompdf->stream($filename);
    }
}