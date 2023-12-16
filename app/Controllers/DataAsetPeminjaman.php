<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DataAsetPeminjamanModels;
use App\Models\RequestPeminjamanModels;
use App\Models\IdentitasSaranaModels;
use App\Models\SumberDanaModels;
use App\Models\KategoriManajemenModels;
use App\Models\IdentitasLabModels;
use App\Models\ManajemenAsetPeminjamanModels;
use App\Models\DetailManajemenAsetPeminjamanModels;
use App\Models\DataSiswaModels;
use App\Models\UserActionLogsModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;
use App\Helpers\PdfHelper;

class DataAsetPeminjaman extends ResourceController
{

    function __construct()
    {
        $this->manajemenAsetPeminjamanModel = new ManajemenAsetPeminjamanModels();
        $this->detailManajemenAsetPeminjamanModel = new DetailManajemenAsetPeminjamanModels();
        $this->dataAsetPeminjamanModel = new DataAsetPeminjamanModels();
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
        $data['dataDataAsetPeminjaman'] = $this->dataAsetPeminjamanModel->getAll($startDate, $endDate);

        // Untuk data peminjaman spesifik (peminjaman berdasarkan lokasi)
        // return view('saranaView/dataAsetPeminjaman/dataAsetPeminjamanSpesifik/index', $data);

        // Untuk data peminjaman general (peminjaman tidak berdasarkan lokasi)
        return view('saranaView/dataAsetPeminjaman/index', $data);
    }

    public function edit($id = null)
    {
        if ($id != null) {
            $dataDataAsetPeminjaman = $this->dataAsetPeminjamanModel->find($id);
            $dataItemDipinjam = $this->dataAsetPeminjamanModel->getBorrowItems($dataDataAsetPeminjaman->idManajemenAsetPeminjaman);
            if (is_object($dataDataAsetPeminjaman)) {
                $data = [
                    'dataDataAsetPeminjaman' => $dataDataAsetPeminjaman,
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                    'dataItemDipinjam' => $dataItemDipinjam,
                ];
                // Untuk data peminjaman spesifik (peminjaman berdasarkan lokasi)
                // return view('saranaView/dataAsetPeminjaman/dataAsetPeminjamanSpesifik/edit', $data);

                // Untuk data peminjaman general (peminjaman tidak berdasarkan lokasi)
                return view('saranaView/dataAsetPeminjaman/edit', $data);
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
            $idRincianAsets = $data['idRincianAset'];
            $updateData = [
                'loanStatus' => 'Pengembalian',
                'namaPenerima' => $data['namaPenerima'],
                'tanggalPengembalian' => $data['tanggalPengembalian'],
            ];

            $getIdManajemenAsetPeminjaman = [
                'idManajemenAsetPeminjaman' => $data['idManajemenAsetPeminjaman'],
            ];

            foreach ($statuses as $index => $status) {
                $idRincianAset = $idRincianAsets[$index];

                $this->dataAsetPeminjamanModel->updateReturnStatus($idRincianAset, $status);
                $this->dataAsetPeminjamanModel->updateReturnSectionAset($idRincianAset);
                $this->dataAsetPeminjamanModel->updateDetailReturnStatus($idRincianAset, $getIdManajemenAsetPeminjaman, $status);
            }
            $this->dataAsetPeminjamanModel->update($id, $updateData);
            return redirect()->to(site_url('dataAsetPeminjaman'))->with('success', 'Aset berhasil dikembalikan');
        } else {
            return view('error/404');
        }
    }

    public function revokeLoan($idManajemenAsetPeminjaman = null) {
        if ($idManajemenAsetPeminjaman != null) {
            $dataItemDipinjam = $this->dataAsetPeminjamanModel->getBorrowItems($idManajemenAsetPeminjaman);

            foreach ($dataItemDipinjam as $data) {
                $this->dataAsetPeminjamanModel->updateReturnSectionAset($data->idRincianAset);
            }
            $this->dataAsetPeminjamanModel->updateRevokeLoan($idManajemenAsetPeminjaman);
            return redirect()->to(site_url('dataAsetPeminjaman'))->with('success', 'Peminjaman berhasil dibatalkan');
        } else {
            return view('error/404');
        }
    }

    public function getLoanHistory($id = null) {
        if ($id != null) {
            $dataDataAsetPeminjaman = $this->dataAsetPeminjamanModel->findHistory($id);
            $dataRincianAset = $this->dataAsetPeminjamanModel->getRincianAset($id);
            if (is_object($dataDataAsetPeminjaman)) {
                $data = [
                    'dataDataAsetPeminjaman' => $dataDataAsetPeminjaman,
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                    'dataRincianAset' => $dataRincianAset,
                ];
                // Untuk data peminjaman spesifik (peminjaman berdasarkan lokasi)
                // return view('saranaView/dataAsetPeminjaman/dataAsetPeminjamanSpesifik/show', $data);

                // Untuk data peminjaman general (peminjaman tidak berdasarkan lokasi)
                return view('saranaView/dataAsetPeminjaman/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }


    public function print($id = null) {
        $dataDataAsetPeminjaman = $this->dataAsetPeminjamanModel->findHistory($id);
        $dataRincianAset = $this->dataAsetPeminjamanModel->getRincianItem($id);
    
        if (!$dataDataAsetPeminjaman && !($dataRincianAset)) {
            return view('error/404');
        }
    
        $data = [
            'dataDataAsetPeminjaman' => $dataDataAsetPeminjaman,
            'dataIdentitasLab' => $this->identitasLabModel->findAll(),
            'dataRincianAset' => $dataRincianAset,
        ];
    
    
        $pdfData = pdfSuratAsetPeminjaman($dataDataAsetPeminjaman, $dataRincianAset);
    
        $tanggal = date('d F Y', strtotime($dataDataAsetPeminjaman->tanggal));
        
        $filename = 'Formulir Peminjaman Aset - ' . $dataDataAsetPeminjaman->namaSiswa . " (" . $tanggal . ")" . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();

    }

    public function printAll() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $dataAsetPeminjaman = $this->dataAsetPeminjamanModel->findAllHistory($startDate, $endDate);

    
        if (empty($dataAsetPeminjaman)) {
            return redirect()->to(site_url('dataAsetPeminjaman'))->with('error', 'Tidak ada dokumen untuk didownload');
        }
    
        $zip = new \ZipArchive();
        $zipFilename = 'Formulir Pengembalian.zip';
    
        if ($zip->open($zipFilename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return view('error/500'); 
        }
    
        foreach ($dataAsetPeminjaman as $peminjaman) {
            $dataDataAsetPeminjaman = $this->dataAsetPeminjamanModel->findHistory($peminjaman->idManajemenAsetPeminjaman);
            $dataRincianAset = $this->dataAsetPeminjamanModel->getRincianItem($peminjaman->idManajemenAsetPeminjaman);
    
            if (!$dataDataAsetPeminjaman || empty($dataRincianAset)) {
                continue;
            }
    
            $data = [
                'dataDataAsetPeminjaman' => $dataDataAsetPeminjaman,
                'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                'dataRincianAset' => $dataRincianAset,
            ];
    
            $pdfData = pdfSuratAsetPeminjaman($dataDataAsetPeminjaman, $dataRincianAset);
            $tanggal = date('d F Y', strtotime($dataDataAsetPeminjaman->tanggal));
            
            $filename = 'Formulir Peminjaman Aset - ' . $dataDataAsetPeminjaman->namaSiswa . " (" . $tanggal . ")" . ".pdf";
    
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
        $dataDetailManajemenAsetPeminjaman = $this->dataAsetPeminjamanModel->getIdDetailManajemenAsetPeminjaman($id);

        foreach ($dataDetailManajemenAsetPeminjaman as $data) {
            $this->detailManajemenAsetPeminjamanModel->delete($data->idDetailManajemenAsetPeminjaman);
        }
        $this->dataAsetPeminjamanModel->delete($id);
        activityLogs($this->userActionLogsModel, "Soft Delete", "Melakukan soft delete data Sarana - Peminjaman dengan id $id");
        return redirect()->to(site_url('dataAsetPeminjaman'));
    }

    public function trash()
    {
        $data['dataDataAsetPeminjaman'] = $this->dataAsetPeminjamanModel->onlyDeleted()->getRecycle();
        return view('saranaView/dataAsetPeminjaman/trash', $data);
    }

    public function restore($id = null)
    {
        $this->db = \Config\Database::connect();

        // Check if $id is not null and not empty
        if ($id != null) {
            // Retrieve details of the deleted records based on $id
            $dataDetailManajemenAsetPeminjaman = $this->dataAsetPeminjamanModel->getIdDetailManajemenAsetPeminjaman($id);
            
            // Iterate through each detail record and restore it
            foreach ($dataDetailManajemenAsetPeminjaman as $data) {
                $this->db->table('tblDetailManajemenAsetPeminjaman')
                ->set('deleted_at', null, true)
                ->where(['idDetailManajemenAsetPeminjaman' => $data->idDetailManajemenAsetPeminjaman])
                ->update();
            }

            // Restore the main record
            $this->db->table('tblManajemenAsetPeminjaman')
                ->set('deleted_at', null, true)
                ->where(['idManajemenAsetPeminjaman' => $id])
                ->update();

            // Log the restore action
            activityLogs($this->userActionLogsModel, "Restore", "Melakukan restore data Sarana - Peminjaman dengan id $id");
        } else {
            // Restore all deleted detail records
            $this->db->table('tblDetailManajemenAsetPeminjaman')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();

            // Restore all deleted main records
            $this->db->table('tblManajemenAsetPeminjaman')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();

                // Log the restore all action
                activityLogs($this->userActionLogsModel, "Restore All", "Melakukan restore semua data Sarana - Peminjaman");
        }
        // Check if any rows were affected
        if ($this->db->affectedRows() > 0) {
            // Redirect with success message
            return redirect()->to(site_url('dataAsetPeminjaman'))->with('success', 'Data berhasil direstore');
        }
        // Redirect with error message if no rows were affected
        return redirect()->to(site_url('dataAsetPeminjaman/trash'))->with('error', 'Tidak ada data untuk direstore');
    }

    public function deletePermanent($id = null)
    {
        // Retrieve details of the deleted records based on $id
        $dataDetailManajemenAsetPeminjaman = $this->dataAsetPeminjamanModel->getIdDetailManajemenAsetPeminjaman($id);
    
        // Check if $id is not null and not empty
        if ($id !== null && !empty($id)) {

            // Iterate through each detail record and permanently delete it
            foreach ($dataDetailManajemenAsetPeminjaman as $data) {
                // Use the correct ID for each iteration
                $idDetailManajemenAsetPeminjaman = $data->idDetailManajemenAsetPeminjaman;
                $this->detailManajemenAsetPeminjamanModel->delete($idDetailManajemenAsetPeminjaman, true);
            }
    
            // Permanently delete the main record
            $this->dataAsetPeminjamanModel->delete($id, true);
    
            // Log the permanent delete action for the main record
            activityLogs($this->userActionLogsModel, "Delete", "Melakukan delete data Sarana - Peminjaman dengan id $id");
    
            // Redirect with success message
            return redirect()->to(site_url('dataAsetPeminjaman/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            // Count deleted records in the trash before purging
            $countInTrash = $this->dataAsetPeminjamanModel->onlyDeleted()->countAllResults();
    
            // Check if there are records in the trash
            if ($countInTrash > 0) {
                // Permanently delete all detail records in the trash
                $this->detailManajemenAsetPeminjamanModel->onlyDeleted()->purgeDeleted();
    
                // Permanently delete all main records in the trash
                $this->dataAsetPeminjamanModel->onlyDeleted()->purgeDeleted();
    
                // Log the permanent delete action for all records in the trash
                activityLogs($this->userActionLogsModel, "Delete All", "Mengosongkan tempat sampah Sarana - Peminjaman");
    
                // Redirect with success message
                return redirect()->to(site_url('dataAsetPeminjaman/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                // Redirect with error message if the trash is already empty
                return redirect()->to(site_url('dataAsetPeminjaman/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }

    public function export() {
        // Retrieve start and end dates from the request
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
    
        // Retrieve data for export from the model
        $data = $this->dataAsetPeminjamanModel->getDataExcel($startDate, $endDate);
    
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
            $activeWorksheet->setCellValue('G' . ($index + 2), $value->namaPrasarana);
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
        $dataAsetPeminjaman = $this->dataAsetPeminjamanModel->getDataExcelPeminjaman($startDate, $endDate);
        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Data Peminjaman');
        $exampleSheet->getTabColor()->setRGB('767870');
        $headerExampleTable = ['No.', 'Tanggal', 'NIS/NIP', 'Nama', 'Siswa/Karyawan', 'Barang yang dipinjam', 'Lokasi', 'Kondisi Awal'];
    
        // Set headers for the Data Peminjaman worksheet
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        // Populate the Data Peminjaman worksheet with data
        foreach ($dataAsetPeminjaman as $index => $value) {
            // Format date for display
            $date = date('d F Y', strtotime($value->tanggal));
    
            // Set cell values
            $exampleSheet->setCellValue('A' . ($index + 2), $index + 1);
            $exampleSheet->setCellValue('B' . ($index + 2), $date);
            $exampleSheet->setCellValueExplicit('C' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $exampleSheet->setCellValue('D' . ($index + 2), $value->namaSiswa);
            $exampleSheet->setCellValue('E' . ($index + 2), $value->namaKelas);
            $exampleSheet->setCellValue('F' . ($index + 2), $value->namaSarana);
            $exampleSheet->setCellValue('G' . ($index + 2), $value->namaPrasarana);
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
        header('Content-Disposition: attachment;filename=Sarana - Data Peminjaman.xlsx');
        header('Cache-Control: max-age=0');
    
        // Save the spreadsheet to output
        $writer->save('php://output');
    
        // Exit to prevent additional output
        exit();
    }
}