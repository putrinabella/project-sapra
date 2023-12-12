<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\RequestAsetPeminjamanModels;
use App\Models\IdentitasSaranaModels;
use App\Models\SumberDanaModels;
use App\Models\KategoriManajemenModels;
use App\Models\IdentitasLabModels;
use App\Models\ManajemenAsetPeminjamanModels;
use App\Models\DataSiswaModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Helpers\PdfHelper;

class RequestAsetPeminjaman extends ResourceController
{

    function __construct()
    {
        $this->manajemenAsetPeminjamanModel = new ManajemenAsetPeminjamanModels();
        $this->requestAsetPeminjamanModel = new RequestAsetPeminjamanModels();
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
        $data['dataRequestAsetPeminjaman'] = $this->requestAsetPeminjamanModel->getAll($startDate, $endDate);

        return view('saranaView/requestAsetPeminjaman/index', $data);
    }

    public function show($id = null)
    {
        if ($id != null) {
            $dataRequestAsetPeminjaman = $this->requestAsetPeminjamanModel->find($id);
            $dataItemDipinjam = $this->requestAsetPeminjamanModel->getBorrowItems($dataRequestAsetPeminjaman->idRequestAsetPeminjaman);
            if (is_object($dataRequestAsetPeminjaman)) {
                $data = [
                    'dataRequestAsetPeminjaman' => $dataRequestAsetPeminjaman,
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                    'dataItemDipinjam' => $dataItemDipinjam,
                ];
                return view('saranaView/requestAsetPeminjaman/show', $data);
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
            $dataRequestAsetPeminjaman = $this->requestAsetPeminjamanModel->find($id);
            $dataItemDipinjam = $this->requestAsetPeminjamanModel->getBorrowItems($dataRequestAsetPeminjaman->idRequestAsetPeminjaman);
            // var_dump($dataItemDipinjam);
            // die;
            if (is_object($dataRequestAsetPeminjaman)) {
                $data = [
                    'dataRequestAsetPeminjaman' => $dataRequestAsetPeminjaman,
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                    'dataItemDipinjam' => $dataItemDipinjam,
                ];
                return view('saranaView/requestAsetPeminjaman/edit', $data);
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
            $idRincianAsets = $data['idRincianAset'];
            $updateData = [
                'loanStatus' => 'Pengembalian',
                'namaPenerima' => $data['namaPenerima'],
                'tanggalPengembalian' => $data['tanggalPengembalian'],
            ];

            $getIdManajemenAsetPeminjaman = [
                'idRequestAsetPeminjaman' => $data['idRequestAsetPeminjaman'],
            ];

            foreach ($loanStatuses as $index => $loanStatus) {
                $idRincianAset = $idRincianAsets[$index];

                $this->requestAsetPeminjamanModel->updateReturnStatus($idRincianAset, $loanStatus);
                $this->requestAsetPeminjamanModel->updateReturnSectionAset($idRincianAset);
                $this->requestAsetPeminjamanModel->updateDetailReturnStatus($idRincianAset, $getIdManajemenAsetPeminjaman, $loanStatus);
            }
            die;
            $this->requestAsetPeminjamanModel->update($id, $updateData);
            return redirect()->to(site_url('requestAsetPeminjaman'))->with('success', 'Aset berhasil dikembalikan');
        } else {
            return view('error/404');
        }
    }
    
    public function processLoan() {
        $data = $this->request->getPost();
        $idRincianAset = $_POST['selectedRows'];
        $idRequestAsetPeminjaman = $data['idRequestAsetPeminjaman'];
        $sectionAsetValue = 'Dipinjam';
        $requestStatus = 'Approve';
        
        if (!empty($data['asalPeminjam'])) {
            $this->manajemenAsetPeminjamanModel->insert($data);
            $idManajemenAsetPeminjaman = $this->db->insertID();
            foreach ($idRincianAset as $idData) {
                $detailData = [
                    'idRincianAset' => $idData,
                    'idManajemenAsetPeminjaman' => $idManajemenAsetPeminjaman,
                ];
                $this->requestAsetPeminjamanModel->approveDetailRequestAsetPeminjaman($idRequestAsetPeminjaman, $requestStatus, $idRincianAset);
                $this->manajemenAsetPeminjamanModel->updateSectionAset($detailData, $sectionAsetValue);
                $this->db->table('tblDetailManajemenAsetPeminjaman')->insert($detailData);
                
            }
            $this->requestAsetPeminjamanModel->updateRequestAsetPeminjaman($idRequestAsetPeminjaman, $requestStatus);

            return redirect()->to(site_url('dataAsetPeminjaman'))->with('success', 'Peminjaman sudah disetujui');
        } else {
            return redirect()->to(site_url('requestAsetPeminjaman'))->with('error', 'Semua field harus terisi');
        }
    }

    public function rejectLoan($idRequestAsetPeminjaman) {
        $requestStatus = 'Reject';
        $this->requestAsetPeminjamanModel->updateRequestAsetPeminjaman($idRequestAsetPeminjaman, $requestStatus);
        return redirect()->to(site_url('requestAsetPeminjaman'))->with('success', 'Request peminjaman berhasil ditolak');
    }

    public function export() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $dataRequest = $this->requestAsetPeminjamanModel->getDataRequest($startDate, $endDate);
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
            $requestSheet->setCellValue('B' . ($index + 2), $value->idRequestAsetPeminjaman);
            $requestSheet->setCellValue('C' . ($index + 2), $date);
            $requestSheet->setCellValueExplicit('D' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $requestSheet->setCellValue('E' . ($index + 2), $value->namaSiswa);
            $requestSheet->setCellValue('F' . ($index + 2), $value->namaKelas);
            $requestSheet->setCellValue('G' . ($index + 2), $value->kodeRincianAset);
            $requestSheet->setCellValue('H' . ($index + 2), $value->namaSarana);
            $requestSheet->setCellValue('I' . ($index + 2), $value->namaPrasarana);
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
        
        $dataApprove = $this->requestAsetPeminjamanModel->getDataApprove($startDate, $endDate);
        $approveSheet = $spreadsheet->createSheet();
        $approveSheet->setTitle('Approve');
        $approveSheet->getTabColor()->setRGB('5D9C59');

        $headerApprove = ['No.', 'ID Request', 'Tanggal', 'NIS/NIP', 'Nama', 'Siswa/Karyawan', 'Kode Aset', 'Nama Aset', 'Lokasi', 'Kondisi', 'Status', 'Keperluan Alat', 'Lama Pinjam'];
        $approveSheet->fromArray([$headerApprove], NULL, 'A1');
        $approveSheet->getStyle('A1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($dataApprove as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $approveSheet->setCellValue('A' . ($index + 2), $index + 1);
            $approveSheet->setCellValue('B' . ($index + 2), $value->idRequestAsetPeminjaman);
            $approveSheet->setCellValue('C' . ($index + 2), $date);
            $approveSheet->setCellValueExplicit('D' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $approveSheet->setCellValue('E' . ($index + 2), $value->namaSiswa);
            $approveSheet->setCellValue('F' . ($index + 2), $value->namaKelas);
            $approveSheet->setCellValue('G' . ($index + 2), $value->kodeRincianAset);
            $approveSheet->setCellValue('H' . ($index + 2), $value->namaSarana);
            $approveSheet->setCellValue('I' . ($index + 2), $value->namaPrasarana);
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
        
        $dataReject = $this->requestAsetPeminjamanModel->getDataReject($startDate, $endDate);
        $rejectSheet = $spreadsheet->createSheet();
        $rejectSheet->setTitle('Reject');
        $rejectSheet->getTabColor()->setRGB('DF2E38');
        $headerReject = ['No.', 'ID Request', 'Tanggal', 'NIS/NIP', 'Nama', 'Siswa/Karyawan', 'Kode Aset', 'Nama Aset', 'Lokasi', 'Keperluan Alat', 'Lama Pinjam'];
        $rejectSheet->fromArray([$headerReject], NULL, 'A1');
        $rejectSheet->getStyle('A1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($dataReject as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $rejectSheet->setCellValue('A' . ($index + 2), $index + 1);
            $rejectSheet->setCellValue('B' . ($index + 2), $value->idRequestAsetPeminjaman);
            $rejectSheet->setCellValue('C' . ($index + 2), $date);
            $rejectSheet->setCellValueExplicit('D' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $rejectSheet->setCellValue('E' . ($index + 2), $value->namaSiswa);
            $rejectSheet->setCellValue('F' . ($index + 2), $value->namaKelas);
            $rejectSheet->setCellValue('G' . ($index + 2), $value->kodeRincianAset);
            $rejectSheet->setCellValue('H' . ($index + 2), $value->namaSarana);
            $rejectSheet->setCellValue('I' . ($index + 2), $value->namaPrasarana);
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

        $dataCancel = $this->requestAsetPeminjamanModel->getDataCancel($startDate, $endDate);
        $cancelSheet = $spreadsheet->createSheet();
        $cancelSheet->setTitle('Cancel by User');
        $cancelSheet->getTabColor()->setRGB('FFFF8F');
        $headerCancel = ['No.', 'ID Request', 'Tanggal', 'NIS/NIP', 'Nama', 'Siswa/Karyawan', 'Kode Aset', 'Nama Aset', 'Lokasi', 'Keperluan Alat', 'Lama Pinjam'];
        $cancelSheet->fromArray([$headerCancel], NULL, 'A1');
        $cancelSheet->getStyle('A1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($dataCancel as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $cancelSheet->setCellValue('A' . ($index + 2), $index + 1);
            $cancelSheet->setCellValue('B' . ($index + 2), $value->idRequestAsetPeminjaman);
            $cancelSheet->setCellValue('C' . ($index + 2), $date);
            $cancelSheet->setCellValueExplicit('D' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $cancelSheet->setCellValue('E' . ($index + 2), $value->namaSiswa);
            $cancelSheet->setCellValue('F' . ($index + 2), $value->namaKelas);
            $cancelSheet->setCellValue('G' . ($index + 2), $value->kodeRincianAset);
            $cancelSheet->setCellValue('H' . ($index + 2), $value->namaSarana);
            $cancelSheet->setCellValue('I' . ($index + 2), $value->namaPrasarana);
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
        header('Content-Disposition: attachment;filename=Sarana - Request Peminjaman.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function generatePDF() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $dataRequest = $this->requestAsetPeminjamanModel->getDataRequest($startDate, $endDate);
        $dataApprove = $this->requestAsetPeminjamanModel->getDataApprove($startDate, $endDate);
        $dataReject = $this->requestAsetPeminjamanModel->getDataReject($startDate, $endDate);

        $title = "REPORT REQUEST PEMINJAMAN";
        if (!$dataRequest && !$dataApprove && !$dataReject) {
            return view('error/404');
        }
    
        $pdfData = pdfRequestAsetPeminjaman($dataRequest, $dataApprove, $dataReject, $title, $startDate, $endDate);
    
        $filename = 'Sarana - Request Peminjaman' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }
}