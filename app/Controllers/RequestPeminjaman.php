<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\RequestPeminjamanModels;
use App\Models\IdentitasSaranaModels;
use App\Models\SumberDanaModels;
use App\Models\KategoriManajemenModels;
use App\Models\IdentitasLabModels;
use App\Models\ManajemenPeminjamanModels;
use App\Models\DataSiswaModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Helpers\PdfHelper;

class RequestPeminjaman extends ResourceController
{

    function __construct()
    {
        $this->manajemenPeminjamanModel = new ManajemenPeminjamanModels();
        $this->requestPeminjamanModel = new RequestPeminjamanModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->dataSiswaModel = new DataSiswaModels();
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
        $data['dataRequestPeminjaman'] = $this->requestPeminjamanModel->getAll($startDate, $endDate);

        return view('labView/requestPeminjaman/index', $data);
    }

    public function show($id = null)
    {
        if ($id != null) {
            $dataRequestPeminjaman = $this->requestPeminjamanModel->find($id);
            $dataItemDipinjam = $this->requestPeminjamanModel->getBorrowItems($dataRequestPeminjaman->idRequestPeminjaman);
            if (is_object($dataRequestPeminjaman)) {
                $data = [
                    'dataRequestPeminjaman' => $dataRequestPeminjaman,
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                    'dataItemDipinjam' => $dataItemDipinjam,
                ];
                return view('labView/requestPeminjaman/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function edit($id = null)
    {
        if ($id != null) {
            $dataRequestPeminjaman = $this->requestPeminjamanModel->find($id);
            $dataItemDipinjam = $this->requestPeminjamanModel->getBorrowItems($dataRequestPeminjaman->idRequestPeminjaman);
            // var_dump($dataItemDipinjam);
            // die;
            if (is_object($dataRequestPeminjaman)) {
                $data = [
                    'dataRequestPeminjaman' => $dataRequestPeminjaman,
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                    'dataItemDipinjam' => $dataItemDipinjam,
                ];
                return view('labView/requestPeminjaman/edit', $data);
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
            var_dump($data);
            die;
            $loanStatuses = $data['loanStatus'];
            $idRincianLabAsets = $data['idRincianLabAset'];
            $updateData = [
                'loanStatus' => 'Pengembalian',
                'namaPenerima' => $data['namaPenerima'],
                'tanggalPengembalian' => $data['tanggalPengembalian'],
            ];

            $getIdManajemenPeminjaman = [
                'idRequestPeminjaman' => $data['idRequestPeminjaman'],
            ];

            foreach ($loanStatuses as $index => $loanStatus) {
                $idRincianLabAset = $idRincianLabAsets[$index];

                $this->requestPeminjamanModel->updateReturnStatus($idRincianLabAset, $loanStatus);
                $this->requestPeminjamanModel->updateReturnSectionAset($idRincianLabAset);
                $this->requestPeminjamanModel->updateDetailReturnStatus($idRincianLabAset, $getIdManajemenPeminjaman, $loanStatus);
            }
            die;
            $this->requestPeminjamanModel->update($id, $updateData);
            return redirect()->to(site_url('requestPeminjaman'))->with('success', 'Aset berhasil dikembalikan');
        } else {
            return view('error/404');
        }
    }
    
    
    public function processLoan() {
        $data = $this->request->getPost();
        $idRincianLabAset = $_POST['selectedRows'];
        $idRequestPeminjaman = $data['idRequestPeminjaman'];
        $sectionAsetValue = 'Dipinjam';
        $requestStatus = 'Approve';
        
        if (!empty($data['asalPeminjam'])) {
            $this->manajemenPeminjamanModel->insert($data);
            $idManajemenPeminjaman = $this->db->insertID();
            
            foreach ($idRincianLabAset as $idRincianLabAset) {
                // die;
                $detailData = [
                    'idRincianLabAset' => $idRincianLabAset,
                    'idManajemenPeminjaman' => $idManajemenPeminjaman,
                ];
                $this->requestPeminjamanModel->approveDetailRequestPeminjaman($idRequestPeminjaman, $requestStatus, $idRincianLabAset);
                // die;
                $this->manajemenPeminjamanModel->updateSectionAset($detailData, $sectionAsetValue);
                $this->db->table('tblDetailManajemenPeminjaman')->insert($detailData);
                
            }
            $this->requestPeminjamanModel->updateRequestPeminjaman($idRequestPeminjaman, $requestStatus);

            return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Peminjaman sudah disetujui');
        } else {
            return redirect()->to(site_url('requestPeminjaman'))->with('error', 'Semua field harus terisi');
        }
    }

    public function rejectLoan($idRequestPeminjaman) {
        $requestStatus = 'Reject';
        $this->requestPeminjamanModel->updateRequestPeminjaman($idRequestPeminjaman, $requestStatus);
        return redirect()->to(site_url('requestPeminjaman'))->with('success', 'Request peminjaman berhasil ditolak');
    }

    public function export() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $dataRequest = $this->requestPeminjamanModel->getDataRequest($startDate, $endDate);
        $spreadsheet = new Spreadsheet();
        $requestSheet = $spreadsheet->getActiveSheet();
        $requestSheet->setTitle('Request');
        $requestSheet->getTabColor()->setRGB('6DB9EF');

        $headers = ['No.', 'ID Request', 'Tanggal', 'NIS/NIP', 'Nama', 'Siswa/Karyawan', 'Kode Aset', 'Nama Aset', 'Lokasi', 'Kondisi', 'Ketersediaan', 'Keperluan Alat', 'Lama Pinjam'];
        $requestSheet->fromArray([$headers], NULL, 'A1');
        $requestSheet->getStyle('A1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($dataRequest as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $requestSheet->setCellValue('A' . ($index + 2), $index + 1);
            $requestSheet->setCellValue('B' . ($index + 2), $value->idRequestPeminjaman);
            $requestSheet->setCellValue('C' . ($index + 2), $date);
            $requestSheet->setCellValueExplicit('D' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $requestSheet->setCellValue('E' . ($index + 2), $value->namaSiswa);
            $requestSheet->setCellValue('F' . ($index + 2), $value->namaKelas);
            $requestSheet->setCellValue('G' . ($index + 2), $value->kodeRincianLabAset);
            $requestSheet->setCellValue('H' . ($index + 2), $value->namaSarana);
            $requestSheet->setCellValue('I' . ($index + 2), $value->namaLab);
            $requestSheet->setCellValue('J' . ($index + 2), $value->status);
        
            if ($value->sectionAset == 'None') {
                $requestSheet->setCellValue('K' . ($index + 2), 'Tersedia');
            } else {
                $requestSheet->setCellValue('K' . ($index + 2), 'Tidak Tersedia');
            }
        
            $requestSheet->setCellValue('L' . ($index + 2), $value->keperluanAlat);
            $requestSheet->setCellValue('M' . ($index + 2), $value->lamaPinjam . ' hari');
        
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];
        
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $requestSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        
                if ($column === 'A' || $column === 'B') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            }
        }
        
        $requestSheet->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $requestSheet->getStyle('A1:M1')->getFont()->setBold(true);
        $requestSheet->getStyle('A1:M' . $requestSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $requestSheet->getStyle('A:M')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'M') as $column) {
            if ($column === 'L') {
                $requestSheet->getColumnDimension($column)->setWidth(40);
            } else {
                $requestSheet->getColumnDimension($column)->setAutoSize(true);
            }
        }
        
        $dataApprove = $this->requestPeminjamanModel->getDataApprove($startDate, $endDate);
        $approveSheet = $spreadsheet->createSheet();
        $approveSheet->setTitle('Approve');
        $approveSheet->getTabColor()->setRGB('5D9C59');

        $headerApprove = ['No.', 'ID Request', 'Tanggal', 'NIS/NIP', 'Nama', 'Siswa/Karyawan', 'Kode Aset', 'Nama Aset', 'Lokasi', 'Kondisi', 'Status', 'Keperluan Alat', 'Lama Pinjam'];
        $approveSheet->fromArray([$headerApprove], NULL, 'A1');
        $approveSheet->getStyle('A1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($dataApprove as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $approveSheet->setCellValue('A' . ($index + 2), $index + 1);
            $approveSheet->setCellValue('B' . ($index + 2), $value->idRequestPeminjaman);
            $approveSheet->setCellValue('C' . ($index + 2), $date);
            $approveSheet->setCellValueExplicit('D' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $approveSheet->setCellValue('E' . ($index + 2), $value->namaSiswa);
            $approveSheet->setCellValue('F' . ($index + 2), $value->namaKelas);
            $approveSheet->setCellValue('G' . ($index + 2), $value->kodeRincianLabAset);
            $approveSheet->setCellValue('H' . ($index + 2), $value->namaSarana);
            $approveSheet->setCellValue('I' . ($index + 2), $value->namaLab);
            $approveSheet->setCellValue('J' . ($index + 2), $value->status);
        
            if ($value->requestItemStatus == 'Approve') {
                $approveSheet->setCellValue('K' . ($index + 2), 'Dipinjamkan');
            } else {
                $approveSheet->setCellValue('K' . ($index + 2), 'Tidak Dipinjamkan');
            }
        
            $approveSheet->setCellValue('L' . ($index + 2), $value->keperluanAlat);
            $approveSheet->setCellValue('M' . ($index + 2), $value->lamaPinjam  . ' hari');
        
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];
        
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $approveSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        
                if ($column === 'A' || $column === 'B') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            }
        }
        
        $approveSheet->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $approveSheet->getStyle('A1:M1')->getFont()->setBold(true);
        $approveSheet->getStyle('A1:M' . $approveSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $approveSheet->getStyle('A:M')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'M') as $column) {
            if ($column === 'L') {
                $approveSheet->getColumnDimension($column)->setWidth(40);
            } else {
                $approveSheet->getColumnDimension($column)->setAutoSize(true);
            }
        }
        
        $dataReject = $this->requestPeminjamanModel->getDataReject($startDate, $endDate);
        $rejectSheet = $spreadsheet->createSheet();
        $rejectSheet->setTitle('Reject');
        $rejectSheet->getTabColor()->setRGB('DF2E38');
        $headerReject = ['No.', 'ID Request', 'Tanggal', 'NIS/NIP', 'Nama', 'Siswa/Karyawan', 'Kode Aset', 'Nama Aset', 'Lokasi', 'Keperluan Alat', 'Lama Pinjam'];
        $rejectSheet->fromArray([$headerReject], NULL, 'A1');
        $rejectSheet->getStyle('A1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($dataReject as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $rejectSheet->setCellValue('A' . ($index + 2), $index + 1);
            $rejectSheet->setCellValue('B' . ($index + 2), $value->idRequestPeminjaman);
            $rejectSheet->setCellValue('C' . ($index + 2), $date);
            $rejectSheet->setCellValueExplicit('D' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $rejectSheet->setCellValue('E' . ($index + 2), $value->namaSiswa);
            $rejectSheet->setCellValue('F' . ($index + 2), $value->namaKelas);
            $rejectSheet->setCellValue('G' . ($index + 2), $value->kodeRincianLabAset);
            $rejectSheet->setCellValue('H' . ($index + 2), $value->namaSarana);
            $rejectSheet->setCellValue('I' . ($index + 2), $value->namaLab);
            $rejectSheet->setCellValue('J' . ($index + 2), $value->keperluanAlat);
            $rejectSheet->setCellValue('K' . ($index + 2), $value->lamaPinjam  . ' hari');

            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $rejectSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

                if ($column === 'A' || $column === 'B') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            }
        }

        $rejectSheet->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $rejectSheet->getStyle('A1:K1')->getFont()->setBold(true);
        $rejectSheet->getStyle('A1:K' . $rejectSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $rejectSheet->getStyle('A:K')->getAlignment()->setWrapText(true);

        foreach (range('A', 'K') as $column) {
            if ($column === 'J') {
                $rejectSheet->getColumnDimension($column)->setWidth(40);
            } else {
                $rejectSheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        $dataCancel = $this->requestPeminjamanModel->getDataCancel($startDate, $endDate);
        $cancelSheet = $spreadsheet->createSheet();
        $cancelSheet->setTitle('Cancel by User');
        $cancelSheet->getTabColor()->setRGB('FFFF8F');
        $headerCancel = ['No.', 'ID Request', 'Tanggal', 'NIS/NIP', 'Nama', 'Siswa/Karyawan', 'Kode Aset', 'Nama Aset', 'Lokasi', 'Keperluan Alat', 'Lama Pinjam'];
        $cancelSheet->fromArray([$headerCancel], NULL, 'A1');
        $cancelSheet->getStyle('A1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($dataCancel as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $cancelSheet->setCellValue('A' . ($index + 2), $index + 1);
            $cancelSheet->setCellValue('B' . ($index + 2), $value->idRequestPeminjaman);
            $cancelSheet->setCellValue('C' . ($index + 2), $date);
            $cancelSheet->setCellValueExplicit('D' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $cancelSheet->setCellValue('E' . ($index + 2), $value->namaSiswa);
            $cancelSheet->setCellValue('F' . ($index + 2), $value->namaKelas);
            $cancelSheet->setCellValue('G' . ($index + 2), $value->kodeRincianLabAset);
            $cancelSheet->setCellValue('H' . ($index + 2), $value->namaSarana);
            $cancelSheet->setCellValue('I' . ($index + 2), $value->namaLab);
            $cancelSheet->setCellValue('J' . ($index + 2), $value->keperluanAlat);
            $cancelSheet->setCellValue('K' . ($index + 2), $value->lamaPinjam  . ' hari');

            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $cancelSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

                if ($column === 'A' || $column === 'B') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            }
        }

        $cancelSheet->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $cancelSheet->getStyle('A1:K1')->getFont()->setBold(true);
        $cancelSheet->getStyle('A1:K' . $cancelSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $cancelSheet->getStyle('A:K')->getAlignment()->setWrapText(true);

        foreach (range('A', 'K') as $column) {
            if ($column === 'J') {
                $cancelSheet->getColumnDimension($column)->setWidth(40);
            } else {
                $cancelSheet->getColumnDimension($column)->setAutoSize(true);
            }
        }
        
        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Laboratorium - Request Peminjaman.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function generatePDF() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $dataRequest = $this->requestPeminjamanModel->getDataRequest($startDate, $endDate);
        $dataApprove = $this->requestPeminjamanModel->getDataApprove($startDate, $endDate);
        $dataReject = $this->requestPeminjamanModel->getDataReject($startDate, $endDate);

        $title = "REPORT REQUEST PEMINJAMAN";
        if (!$dataRequest && !$dataApprove && !$dataReject) {
            return view('error/404');
        }
    
        $pdfData = pdfRequestPeminjaman($dataRequest, $dataApprove, $dataReject, $title, $startDate, $endDate);
    
        $filename = 'Laboratorium - Request Peminjaman' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }
}