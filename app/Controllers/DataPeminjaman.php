<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DataPeminjamanModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasLabModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class DataPeminjaman extends ResourceController
{
    
     function __construct() {
        $this->dataPeminjamanModel = new DataPeminjamanModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataDataPeminjaman'] = $this->dataPeminjamanModel->getAll();
        return view('labView/dataPeminjaman/index', $data);
    }

    public function edit($id = null) {
        if ($id != null) {
            $dataDataPeminjaman = $this->dataPeminjamanModel->find($id);
    
            if (is_object($dataDataPeminjaman)) {                
                $data = [
                    'dataDataPeminjaman' => $dataDataPeminjaman,
                    'dataIdentitasSarana' => $this->dataPeminjamanModel->getPerangkatIT(),
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                ];
                return view('labView/dataPeminjaman/edit', $data);
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
   
            $this->dataPeminjamanModel->update($id, $data);
            return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }


    public function delete($id = null) {
        $this->dataPeminjamanModel->delete($id);
        return redirect()->to(site_url('dataPeminjaman'));
    }

    public function trash() {
        $data['dataDataPeminjaman'] = $this->dataPeminjamanModel->onlyDeleted()->getRecycle();
        return view('labView/dataPeminjaman/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblManajemenPeminjaman')
                ->set('deleted_at', null, true)
                ->where(['idManajemenPeminjaman' => $id])
                ->update();
        } else {
            $this->db->table('tblManajemenPeminjaman')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('dataPeminjaman/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->dataPeminjamanModel->delete($id, true);
        return redirect()->to(site_url('dataPeminjaman/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->dataPeminjamanModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->dataPeminjamanModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('dataPeminjaman/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('dataPeminjaman/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 

    
    public function export() {
        $data = $this->dataPeminjamanModel->getAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Data Peminjaman');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Tanggal', 'Nama', 'Asal', 'Barang yangn dipinjam', 'Lokasi', 'Jumlah', 'Status', 'Tanggal Pengembalian'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {           
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->tanggal);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaPeminjam);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->asalPeminjam);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaLab);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->jumlah);
            $activeWorksheet->setCellValue('H'.($index + 2), $value->status);
            $activeWorksheet->setCellValue('I'.($index + 2), $value->tanggalPengembalian);

            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }
        $activeWorksheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:I1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:I'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:I')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'I') as $column) {
            if ($column === 'K') {
                $activeWorksheet->getColumnDimension($column)->setWidth(20);
            } else if ($column === 'L') {
                $activeWorksheet->getColumnDimension($column)->setWidth(40); 
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }

        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Laboratorium - Data Peminjaman.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function generatePDF() {
        $filePath = APPPATH . 'Views/labView/dataPeminjaman/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataDataPeminjaman'] = $this->dataPeminjamanModel->getAll();

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
        $filename = "Laboratorium - Data Peminjaman.pdf";
        $dompdf->stream($filename);
    }
}