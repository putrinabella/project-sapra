<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DataPeminjamanModels;
use App\Models\IdentitasSaranaModels;
use App\Models\SumberDanaModels;
use App\Models\KategoriManajemenModels;
use App\Models\IdentitasLabModels;
use App\Models\ManajemenPeminjamanModels;
use App\Models\DataSiswaModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;
use App\Helpers\PdfHelper;

class DataPeminjaman extends ResourceController
{

    function __construct()
    {
        $this->manajemenPeminjamanModel = new ManajemenPeminjamanModels();
        $this->dataPeminjamanModel = new DataPeminjamanModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->dataSiswaModel = new DataSiswaModels();
        $this->db = \Config\Database::connect();
        helper(['pdf']);
    }

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
        $data['dataDataPeminjaman'] = $this->dataPeminjamanModel->getData($startDate, $endDate);

        return view('labView/dataPeminjaman/index', $data);
    }
    
    public function user()
    {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
        $idUser = $this->dataSiswaModel->getIdByUsername(session('username'));

        $formattedStartDate = !empty($startDate) ? date('d F Y', strtotime($startDate)) : '';
        $formattedEndDate = !empty($endDate) ? date('d F Y', strtotime($endDate)) : '';

        $tableHeading = "";
        if (!empty($formattedStartDate) && !empty($formattedEndDate)) {
            $tableHeading = " $formattedStartDate - $formattedEndDate";
        }

        $data = [
            'tableHeading' => $tableHeading,
            'dataDataPeminjaman' => $this->dataPeminjamanModel->getDataSiswa($startDate, $endDate, $idUser),
        ];
        return view('labView/dataPeminjaman/user', $data);
    }

    public function index2() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $formattedStartDate = !empty($startDate) ? date('d F Y', strtotime($startDate)) : '';
        $formattedEndDate = !empty($endDate) ? date('d F Y', strtotime($endDate)) : '';

        $tableHeading = "";
        if (!empty($formattedStartDate) && !empty($formattedEndDate)) {
            $tableHeading = " $formattedStartDate - $formattedEndDate";
        }

        $dataDataPeminjaman = $this->dataPeminjamanModel->getData($startDate, $endDate);

        $idManajemenPeminjamanArray = [];

        foreach ($dataDataPeminjaman as $peminjaman) {
            $idManajemenPeminjamanArray[] = $this->dataPeminjamanModel->getIdManajemenPeminjaman($peminjaman->namaPeminjam);
        }

        $data['idManajemenPeminjamanArray'] = $idManajemenPeminjamanArray;
        $data['tableHeading'] = $tableHeading;
        $data['dataDataPeminjaman'] = $dataDataPeminjaman;

        return view('labView/dataPeminjaman/index', $data);
    }

    public function edit($id = null)
    {
        if ($id != null) {
            $dataDataPeminjaman = $this->dataPeminjamanModel->find($id);
            $dataItemDipinjam = $this->dataPeminjamanModel->getBorrowItems($dataDataPeminjaman->idManajemenPeminjaman);
            if (is_object($dataDataPeminjaman)) {
                $data = [
                    'dataDataPeminjaman' => $dataDataPeminjaman,
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                    'dataItemDipinjam' => $dataItemDipinjam,
                ];
                return view('labView/dataPeminjaman/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }


    public function getLoanHistory($id = null) {
        if ($id != null) {
            $dataDataPeminjaman = $this->dataPeminjamanModel->findHistory($id);
            $dataRincianLabAset = $this->dataPeminjamanModel->getRincianLabAset($id);
            if (is_object($dataDataPeminjaman)) {
                $data = [
                    'dataDataPeminjaman' => $dataDataPeminjaman,
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                    'dataRincianLabAset' => $dataRincianLabAset,
                ];
                return view('labView/dataPeminjaman/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function print($id = null) {
        $dataDataPeminjaman = $this->dataPeminjamanModel->findHistory($id);
        $dataRincianLabAset = $this->dataPeminjamanModel->getRincianItem($id);
    
        if (!$dataDataPeminjaman || empty($dataRincianLabAset)) {
            return view('error/404');
        }
    
        $data = [
            'dataDataPeminjaman' => $dataDataPeminjaman,
            'dataIdentitasLab' => $this->identitasLabModel->findAll(),
            'dataRincianLabAset' => $dataRincianLabAset,
        ];
    
    
        $pdfData = pdf_suratpeminjaman($dataDataPeminjaman, $dataRincianLabAset);
    
        $tanggal = date('d F Y', strtotime($dataDataPeminjaman->tanggal));
        
        $filename = 'Formulir Peminjaman Aset - ' . $dataDataPeminjaman->namaSiswa . " (" . $tanggal . ")" . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();

    }

    public function printAll() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $dataPeminjaman = $this->dataPeminjamanModel->findAllHistory($startDate, $endDate);

    
        if (empty($dataPeminjaman)) {
            return redirect()->to(site_url('dataPeminjaman'))->with('error', 'Tidak ada dokumen untuk didownload');
        }
    
        $zip = new \ZipArchive();
        $zipFilename = 'Formulir Pengembalian.zip';
    
        if ($zip->open($zipFilename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return view('error/500'); 
        }
    
        foreach ($dataPeminjaman as $peminjaman) {
            $dataDataPeminjaman = $this->dataPeminjamanModel->findHistory($peminjaman->idManajemenPeminjaman);
            $dataRincianLabAset = $this->dataPeminjamanModel->getRincianItem($peminjaman->idManajemenPeminjaman);
    
            if (!$dataDataPeminjaman || empty($dataRincianLabAset)) {
                continue;
            }
    
            $data = [
                'dataDataPeminjaman' => $dataDataPeminjaman,
                'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                'dataRincianLabAset' => $dataRincianLabAset,
            ];
    
            $pdfData = pdf_suratpeminjaman($dataDataPeminjaman, $dataRincianLabAset);
            $tanggal = date('d F Y', strtotime($dataDataPeminjaman->tanggal));
            
            $filename = 'Formulir Peminjaman Aset - ' . $dataDataPeminjaman->namaSiswa . " (" . $tanggal . ")" . ".pdf";
    
            $zip->addFromString($filename, $pdfData);
        }
    
        $zip->close();
    
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/zip');
        $response->setHeader('Content-Disposition', 'attachment; filename="' . $zipFilename . '"');
        $response->setBody(file_get_contents($zipFilename));
        $response->send();
    
        unlink($zipFilename);
    }
    
  
    public function update($id = null)
    {
        if ($id != null) {
            $data = $this->request->getPost();
            $statuses = $data['status'];
            $idRincianLabAsets = $data['idRincianLabAset'];
            $updateData = [
                'loanStatus' => 'Pengembalian',
                'namaPenerima' => $data['namaPenerima'],
                'tanggalPengembalian' => $data['tanggalPengembalian'],
            ];

            $getIdManajemenPeminjaman = [
                'idManajemenPeminjaman' => $data['idManajemenPeminjaman'],
            ];
            // $this->dataPeminjamanModel->update($id, $updateData);

            foreach ($statuses as $index => $status) {
                $idRincianLabAset = $idRincianLabAsets[$index];

                $this->dataPeminjamanModel->updateReturnStatus($idRincianLabAset, $status);
                $this->dataPeminjamanModel->updateReturnSectionAset($idRincianLabAset);
                $this->dataPeminjamanModel->updateDetailReturnStatus($idRincianLabAset, $getIdManajemenPeminjaman, $status);
            }
            $this->dataPeminjamanModel->update($id, $updateData);
            return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Aset berhasil dikembalikan');
        } else {
            return view('error/404');
        }
    }

    // public function update($id = null) {
    //     $data = $this->request->getPost();
    //     $idRincianLabAset = $data['idRincianLabAset'];
    //     $idManajemenPeminjaman = $data['idManajemenPeminjaman'];
    //     $idIdentitasSarana = $data['idIdentitasSarana'];
    //     $idIdentitasLab = $data['idIdentitasLab'];
    //     $sectionAsetValue = 'None';
    //     $jumlah = $data['jumlahPeminjaman'];


    //     if ($data['loanStatus'] === 'Pengembalian') {
    //         $jumlahBarangDikembalikan = $data['jumlahBarangDikembalikan'];
    //         $jumlahBarangRusak = $data['jumlahBarangRusak'];
    //         $jumlahBarangHilang = $data['jumlahBarangHilang'];

    //         $assetsToBorrow = $this->manajemenPeminjamanModel->getBorrowedItems($idIdentitasSarana, $jumlah, $idIdentitasLab);

    //         $updateRusak = 0;
    //         $updateHilang = 0;

    //         foreach ($assetsToBorrow as $asset) {
    //             if ($jumlahBarangRusak > $updateRusak) {
    //                 $this->manajemenPeminjamanModel->updateReturnStatus($asset['idIdentitasSarana'], 'Rusak', 1, $asset['idIdentitasLab'], $asset['idManajemenPeminjaman']);
    //                 $this->manajemenPeminjamanModel->updateReturnSectionAsetRusak($idIdentitasSarana, $sectionAsetValue, $idIdentitasLab, $jumlah, $idManajemenPeminjaman, "Rusak");
    //                 $updateRusak++;
    //             } 

    //             if ($jumlahBarangHilang > $updateHilang) {
    //                 $this->manajemenPeminjamanModel->updateReturnStatus($asset['idIdentitasSarana'], 'Hilang', 1, $asset['idIdentitasLab'], $asset['idManajemenPeminjaman']);
    //                 $this->manajemenPeminjamanModel->updateReturnSectionAsetHilang($idIdentitasSarana, $sectionAsetValue, $idIdentitasLab, $jumlah, $idManajemenPeminjaman, "Hilang");
    //                 $updateHilang++;
    //             }
    //         }
    //     } 

    //     $this->manajemenPeminjamanModel->updateReturnSectionAset($idIdentitasSarana, $sectionAsetValue, $idIdentitasLab, $jumlah, $idManajemenPeminjaman);
    //     $this->dataPeminjamanModel->update($id, $data);

    //     return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Return data berhasil disimpan');
    // }

    public function delete($id = null)
    {
        $this->dataPeminjamanModel->delete($id);
        return redirect()->to(site_url('dataPeminjaman'));
    }

    public function trash()
    {
        $data['dataDataPeminjaman'] = $this->dataPeminjamanModel->onlyDeleted()->getRecycle();
        return view('labView/dataPeminjaman/trash', $data);
    }

    public function restore($id = null)
    {
        $this->db = \Config\Database::connect();
        if ($id != null) {
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
        if ($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Data berhasil direstore');
        }
        return redirect()->to(site_url('dataPeminjaman/trash'))->with('error', 'Tidak ada data untuk direstore');
    }

    public function deletePermanent($id = null)
    {
        if ($id != null) {
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
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $formattedStartDate = !empty($startDate) ? date('d F Y', strtotime($startDate)) : '';
        $formattedEndDate = !empty($endDate) ? date('d F Y', strtotime($endDate)) : '';
        $data = $this->dataPeminjamanModel->getDataExcel($startDate, $endDate);
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Data Pengembalian');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');

        $headers = ['No.', 'Tanggal', 'NIS/NIP', 'Nama', 'Siswa/Karyawan', 'Barang yang dipinjam', 'Lokasi', 'Status', 'Kondisi Awal', 'Kondisi Pengembalian', 'Tanggal Pengembalian'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:K1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A' . ($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B' . ($index + 2), $value->tanggal);
            $activeWorksheet->setCellValueExplicit('C' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $activeWorksheet->setCellValue('D' . ($index + 2), $value->namaSiswa);
            $activeWorksheet->setCellValue('E' . ($index + 2), $value->namaKelas);
            $activeWorksheet->setCellValue('F' . ($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('G' . ($index + 2), $value->namaLab);
            if ($value->loanStatus == "Peminjaman") {
                $activeWorksheet->setCellValue('H' . ($index + 2), 'Sudah Dikembalikan');
            } 
            $activeWorksheet->setCellValue('I' . ($index + 2), "Bagus");
            $activeWorksheet->setCellValue('J' . ($index + 2), $value->statusSetelahPengembalian);
            $activeWorksheet->setCellValue('K' . ($index + 2), $value->tanggalPengembalian);


            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }
        }
        $activeWorksheet->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:K1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:K' . $activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:K')->getAlignment()->setWrapText(true);

        foreach (range('A', 'K') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        $dataPeminjaman = $this->dataPeminjamanModel->getDataExcelPeminjaman($startDate, $endDate);
        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Data Peminjaman');
        $exampleSheet->getTabColor()->setRGB('767870');
        $headerExampleTable = ['No.', 'Tanggal', 'NIS/NIP', 'Nama', 'Siswa/Karyawan', 'Barang yang dipinjam', 'Lokasi', 'Status', 'Kondisi Awal'];
     
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        foreach ($dataPeminjaman as $index => $value) {

            $exampleSheet->setCellValue('A' . ($index + 2), $index + 1);
            $exampleSheet->setCellValue('B' . ($index + 2), $value->tanggal);
            $exampleSheet->setCellValueExplicit('C' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $exampleSheet->setCellValue('D' . ($index + 2), $value->namaSiswa);
            $exampleSheet->setCellValue('E' . ($index + 2), $value->namaKelas);
            $exampleSheet->setCellValue('F' . ($index + 2), $value->namaSarana);
            $exampleSheet->setCellValue('G' . ($index + 2), $value->namaLab);
            if ($value->loanStatus == "Peminjaman") {
                $exampleSheet->setCellValue('H' . ($index + 2), 'Sedang Dipinjam');
            }
            $exampleSheet->setCellValue('I' . ($index + 2), "Bagus");

            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }
        }
        $exampleSheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $exampleSheet->getStyle('A1:I1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:I' . $exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:I')->getAlignment()->setWrapText(true);

        foreach (range('A', 'I') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Laboratorium - Data Peminjaman.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function changeStatus($idRicianLabAset)
    {
        $newSectionAset = $this->request->getPost('sectionAset');
        $namaAkun = $this->request->getPost('namaAkun');
        $kodeAkun = $this->request->getPost('kodeAkun');

        if ($this->rincianAsetModel->updateSectionAset($idRicianLabAset, $newSectionAset, $namaAkun, $kodeAkun)) {
            if ($newSectionAset === 'Dimusnahkan') {
                return redirect()->to(site_url('rincianAset'))->with('success', 'Aset berhasil dimusnahkan');
            } elseif ($newSectionAset === 'None') {
                return redirect()->to(site_url('rincianAset'))->with('success', 'Aset berhasil dikembalikan');
            }
        } else {
            return redirect()->to(site_url('rincianAset'))->with('error', 'Aset batal dimusnahkan');
        }
    }
}