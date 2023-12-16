<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DataPeminjamanModels;
use App\Models\RequestPeminjamanModels;
use App\Models\IdentitasSaranaModels;
use App\Models\SumberDanaModels;
use App\Models\KategoriManajemenModels;
use App\Models\IdentitasLabModels;
use App\Models\ManajemenPeminjamanModels;
use App\Models\DetailManajemenPeminjamanModels;
use App\Models\DataSiswaModels;
use App\Models\UserActionLogsModels;
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
        $this->detailManajemenPeminjamanModel = new DetailManajemenPeminjamanModels();
        $this->dataPeminjamanModel = new DataPeminjamanModels();
        $this->requestPeminjamanModel = new RequestPeminjamanModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->dataSiswaModel = new DataSiswaModels();
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['pdf', 'custom']);
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
        $data['dataDataPeminjaman'] = $this->dataPeminjamanModel->getAll($startDate, $endDate);

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
                // Untuk data peminjaman spesifik (peminjaman berdasarkan lokasi)
                // return view('labView/dataPeminjaman/dataPeminjamanSpesifik/edit', $data);

                // Untuk data peminjaman general (peminjaman tidak berdasarkan lokasi)
                return view('labView/dataPeminjaman/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
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

    public function revokeLoan($idManajemenPeminjaman = null) {
        if ($idManajemenPeminjaman != null) {
            $dataItemDipinjam = $this->dataPeminjamanModel->getBorrowItems($idManajemenPeminjaman);

            foreach ($dataItemDipinjam as $data) {
                $this->dataPeminjamanModel->updateReturnSectionAset($data->idRincianLabAset);
            }
            $this->dataPeminjamanModel->updateRevokeLoan($idManajemenPeminjaman);
            return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Peminjaman berhasil dibatalkan');
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
                // Untuk data peminjaman spesifik (peminjaman berdasarkan lokasi)
                // return view('labView/dataPeminjaman/dataPeminjamanSpesifik/show', $data);

                // Untuk data peminjaman general (peminjaman tidak berdasarkan lokasi)
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
    
        if (!$dataDataPeminjaman && !($dataRincianLabAset)) {
            return view('error/404');
        }
    
        $data = [
            'dataDataPeminjaman' => $dataDataPeminjaman,
            'dataIdentitasLab' => $this->identitasLabModel->findAll(),
            'dataRincianLabAset' => $dataRincianLabAset,
        ];
    
    
        $pdfData = pdfSuratPeminjaman($dataDataPeminjaman, $dataRincianLabAset);
    
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
    
            $pdfData = pdfSuratPeminjaman($dataDataPeminjaman, $dataRincianLabAset);
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

    public function delete($id = null)
    {
        $dataDetailManajemenPeminjaman = $this->dataPeminjamanModel->getIdDetailManajemenPeminjaman($id);

        foreach ($dataDetailManajemenPeminjaman as $data) {
            $this->detailManajemenPeminjamanModel->delete($data->idDetailManajemenPeminjaman);
        }
        $this->dataPeminjamanModel->delete($id);
        activityLogs($this->userActionLogsModel, "Soft Delete", "Melakukan soft delete data Laboratorium - Peminjaman dengan id $id");
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

        // Check if $id is not null and not empty
        if ($id != null) {
            // Retrieve details of the deleted records based on $id
            $dataDetailManajemenPeminjaman = $this->dataPeminjamanModel->getIdDetailManajemenPeminjaman($id);
            
            // Iterate through each detail record and restore it
            foreach ($dataDetailManajemenPeminjaman as $data) {
                $this->db->table('tblDetailManajemenPeminjaman')
                ->set('deleted_at', null, true)
                ->where(['idDetailManajemenPeminjaman' => $data->idDetailManajemenPeminjaman])
                ->update();
            }

            // Restore the main record
            $this->db->table('tblManajemenPeminjaman')
                ->set('deleted_at', null, true)
                ->where(['idManajemenPeminjaman' => $id])
                ->update();

            // Log the restore action
            activityLogs($this->userActionLogsModel, "Restore", "Melakukan restore data Laboratorium - Peminjaman dengan id $id");
        } else {
            // Restore all deleted detail records
            $this->db->table('tblDetailManajemenPeminjaman')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();

            // Restore all deleted main records
            $this->db->table('tblManajemenPeminjaman')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();

                // Log the restore all action
                activityLogs($this->userActionLogsModel, "Restore All", "Melakukan restore semua data Laboratorium - Peminjaman");
        }
        // Check if any rows were affected
        if ($this->db->affectedRows() > 0) {
            // Redirect with success message
            return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Data berhasil direstore');
        }
        // Redirect with error message if no rows were affected
        return redirect()->to(site_url('dataPeminjaman/trash'))->with('error', 'Tidak ada data untuk direstore');
    }

    public function deletePermanent($id = null)
    {
        // Retrieve details of the deleted records based on $id
        $dataDetailManajemenPeminjaman = $this->dataPeminjamanModel->getIdDetailManajemenPeminjaman($id);
    
        // Check if $id is not null and not empty
        if ($id !== null && !empty($id)) {

            // Iterate through each detail record and permanently delete it
            foreach ($dataDetailManajemenPeminjaman as $data) {
                // Use the correct ID for each iteration
                $idDetailManajemenPeminjaman = $data->idDetailManajemenPeminjaman;
                $this->detailManajemenPeminjamanModel->delete($idDetailManajemenPeminjaman, true);
            }
    
            // Permanently delete the main record
            $this->dataPeminjamanModel->delete($id, true);
    
            // Log the permanent delete action for the main record
            activityLogs($this->userActionLogsModel, "Delete", "Melakukan delete data Laboratorium - Peminjaman dengan id $id");
    
            // Redirect with success message
            return redirect()->to(site_url('dataPeminjaman/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            // Count deleted records in the trash before purging
            $countInTrash = $this->dataPeminjamanModel->onlyDeleted()->countAllResults();
    
            // Check if there are records in the trash
            if ($countInTrash > 0) {
                // Permanently delete all detail records in the trash
                $this->detailManajemenPeminjamanModel->onlyDeleted()->purgeDeleted();
    
                // Permanently delete all main records in the trash
                $this->dataPeminjamanModel->onlyDeleted()->purgeDeleted();
    
                // Log the permanent delete action for all records in the trash
                activityLogs($this->userActionLogsModel, "Delete All", "Mengosongkan tempat sampah Laboratorium - Peminjaman");
    
                // Redirect with success message
                return redirect()->to(site_url('dataPeminjaman/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                // Redirect with error message if the trash is already empty
                return redirect()->to(site_url('dataPeminjaman/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }

    public function export() {
        // Retrieve start and end dates from the request
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
    
        // Retrieve data for export from the model
        $data = $this->dataPeminjamanModel->getDataExcel($startDate, $endDate);
    
        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Data Pengembalian');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        // Set headers for the worksheet
        $headers = ['No.', 'Tanggal', 'NIS/NIP', 'Nama', 'Siswa/Karyawan', 'Barang yang dipinjam', 'Lokasi', 'Kondisi Awal', 'Kondisi Pengembalian', 'Tanggal Pengembalian'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        // Populate the worksheet with data
        foreach ($data as $index => $value) {
            // Format dates for display
            $date = date('d F Y', strtotime($value->tanggal));
            $returnDate = date('d F Y', strtotime($value->tanggalPengembalian));
    
            // Set cell values
            $activeWorksheet->setCellValue('A' . ($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B' . ($index + 2), $date);
            $activeWorksheet->setCellValueExplicit('C' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $activeWorksheet->setCellValue('D' . ($index + 2), $value->namaSiswa);
            $activeWorksheet->setCellValue('E' . ($index + 2), $value->namaKelas);
            $activeWorksheet->setCellValue('F' . ($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('G' . ($index + 2), $value->namaLab);
            $activeWorksheet->setCellValue('H' . ($index + 2), "Bagus");
            $activeWorksheet->setCellValue('I' . ($index + 2), $value->statusSetelahPengembalian);
            $activeWorksheet->setCellValue('J' . ($index + 2), $returnDate);
    
            // Set cell formatting
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
    
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $activeWorksheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
    
                // Set horizontal alignment based on column
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            }
        }
    
        // Set additional styling for the worksheet
        $activeWorksheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:J1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:J' . $activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:J')->getAlignment()->setWrapText(true);
    
        // Set column widths to auto-size
        foreach (range('A', 'J') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        // Create a new worksheet for Data Peminjaman
        $dataPeminjaman = $this->dataPeminjamanModel->getDataExcelPeminjaman($startDate, $endDate);
        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Data Peminjaman');
        $exampleSheet->getTabColor()->setRGB('767870');
        $headerExampleTable = ['No.', 'Tanggal', 'NIS/NIP', 'Nama', 'Siswa/Karyawan', 'Barang yang dipinjam', 'Lokasi', 'Kondisi Awal'];
    
        // Set headers for the Data Peminjaman worksheet
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        // Populate the Data Peminjaman worksheet with data
        foreach ($dataPeminjaman as $index => $value) {
            // Format date for display
            $date = date('d F Y', strtotime($value->tanggal));
    
            // Set cell values
            $exampleSheet->setCellValue('A' . ($index + 2), $index + 1);
            $exampleSheet->setCellValue('B' . ($index + 2), $date);
            $exampleSheet->setCellValueExplicit('C' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $exampleSheet->setCellValue('D' . ($index + 2), $value->namaSiswa);
            $exampleSheet->setCellValue('E' . ($index + 2), $value->namaKelas);
            $exampleSheet->setCellValue('F' . ($index + 2), $value->namaSarana);
            $exampleSheet->setCellValue('G' . ($index + 2), $value->namaLab);
            $exampleSheet->setCellValue('H' . ($index + 2), "Bagus");
    
            // Set cell formatting
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $exampleSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
    
                // Set horizontal alignment based on column
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            }
        }
    
        // Set additional styling for the Data Peminjaman worksheet
        $exampleSheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $exampleSheet->getStyle('A1:H1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:H' . $exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:H')->getAlignment()->setWrapText(true);
    
        // Set column widths to auto-size
        foreach (range('A', 'H') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        // Set the active sheet to the first one
        $spreadsheet->setActiveSheetIndex(0);
    
        // Create a writer for exporting
        $writer = new Xlsx($spreadsheet);
    
        // Set headers for file download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Laboratorium - Data Peminjaman.xlsx');
        header('Cache-Control: max-age=0');
    
        // Save the spreadsheet to output
        $writer->save('php://output');
    
        // Exit to prevent additional output
        exit();
    }
}