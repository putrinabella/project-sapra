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
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;
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

    public function processLoan() {
        $data = $this->request->getPost();
        $idRincianLabAset = $_POST['selectedRows'];
        $idRequestPeminjaman = $data['idRequestPeminjaman'];
        $sectionAsetValue = 'Dipinjam';
        $requestStatus = 'Approve';
        
        if (!empty($data['asalPeminjam'])) {
            $this->manajemenPeminjamanModel->insert($data);
            $idManajemenPeminjaman = $this->db->insertID();
            
            foreach ($idRincianLabAset as $idRincianAset) {
                // die;
                $detailData = [
                    'idRincianLabAset' => $idRincianAset,
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
        // var_dump($idRequestPeminjaman);
        // die;
        $requestStatus = 'Reject';
        $this->requestPeminjamanModel->updateRequestPeminjaman($idRequestPeminjaman, $requestStatus);
        return redirect()->to(site_url('requestPeminjaman'))->with('success', 'Request peminjaman berhasil ditolak');
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

    public function getLoanHistory($id = null) {
        if ($id != null) {
            $dataRequestPeminjaman = $this->requestPeminjamanModel->findHistory($id);
            $dataRincianLabAset = $this->requestPeminjamanModel->getRincianLabAset($id);
            if (is_object($dataRequestPeminjaman)) {
                $data = [
                    'dataRequestPeminjaman' => $dataRequestPeminjaman,
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                    'dataRincianLabAset' => $dataRincianLabAset,
                ];
                return view('labView/requestPeminjaman/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function print($id = null) {
        $dataRequestPeminjaman = $this->requestPeminjamanModel->findHistory($id);
        $dataRincianLabAset = $this->requestPeminjamanModel->getRincianItem($id);
    
        if (!$dataRequestPeminjaman || empty($dataRincianLabAset)) {
            return view('error/404');
        }
    
        $data = [
            'dataRequestPeminjaman' => $dataRequestPeminjaman,
            'dataIdentitasLab' => $this->identitasLabModel->findAll(),
            'dataRincianLabAset' => $dataRincianLabAset,
        ];
    
    
        $pdfData = pdf_suratpeminjaman($dataRequestPeminjaman, $dataRincianLabAset);
    
        $tanggal = date('d F Y', strtotime($dataRequestPeminjaman->tanggal));
        
        $filename = 'Formulir Peminjaman Aset - ' . $dataRequestPeminjaman->namaSiswa . " (" . $tanggal . ")" . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }

    public function printAll() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $requestPeminjaman = $this->requestPeminjamanModel->findAllHistory($startDate, $endDate);

    
        if (empty($requestPeminjaman)) {
            return redirect()->to(site_url('requestPeminjaman'))->with('error', 'Tidak ada dokumen untuk didownload');
        }
    
        $zip = new \ZipArchive();
        $zipFilename = 'Formulir Pengembalian.zip';
    
        if ($zip->open($zipFilename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return view('error/500'); 
        }
    
        foreach ($requestPeminjaman as $peminjaman) {
            $dataRequestPeminjaman = $this->requestPeminjamanModel->findHistory($peminjaman->idRequestPeminjaman);
            $dataRincianLabAset = $this->requestPeminjamanModel->getRincianItem($peminjaman->idRequestPeminjaman);
    
            if (!$dataRequestPeminjaman || empty($dataRincianLabAset)) {
                continue;
            }
    
            $data = [
                'dataRequestPeminjaman' => $dataRequestPeminjaman,
                'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                'dataRincianLabAset' => $dataRincianLabAset,
            ];
    
            $pdfData = pdf_suratpeminjaman($dataRequestPeminjaman, $dataRincianLabAset);
            $tanggal = date('d F Y', strtotime($dataRequestPeminjaman->tanggal));
            
            $filename = 'Formulir Peminjaman Aset - ' . $dataRequestPeminjaman->namaSiswa . " (" . $tanggal . ")" . ".pdf";
    
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
        $this->requestPeminjamanModel->delete($id);
        return redirect()->to(site_url('requestPeminjaman'));
    }

    public function trash()
    {
        $data['dataRequestPeminjaman'] = $this->requestPeminjamanModel->onlyDeleted()->getRecycle();
        return view('labView/requestPeminjaman/trash', $data);
    }

    public function restore($id = null)
    {
        $this->db = \Config\Database::connect();
        if ($id != null) {
            $this->db->table('tblManajemenPeminjaman')
                ->set('deleted_at', null, true)
                ->where(['idRequestPeminjaman' => $id])
                ->update();
        } else {
            $this->db->table('tblManajemenPeminjaman')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
        }
        if ($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('requestPeminjaman'))->with('success', 'Data berhasil direstore');
        }
        return redirect()->to(site_url('requestPeminjaman/trash'))->with('error', 'Tidak ada data untuk direstore');
    }

    public function deletePermanent($id = null)
    {
        if ($id != null) {
            $this->requestPeminjamanModel->delete($id, true);
            return redirect()->to(site_url('requestPeminjaman/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->requestPeminjamanModel->onlyDeleted()->countAllResults();

            if ($countInTrash > 0) {
                $this->requestPeminjamanModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('requestPeminjaman/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('requestPeminjaman/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }


    public function export() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $dataRequest = $this->requestPeminjamanModel->getDataRequest($startDate, $endDate);
        $spreadsheet = new Spreadsheet();
        $requestSheet = $spreadsheet->getActiveSheet();
        $requestSheet->setTitle('Request');
        $requestSheet->getTabColor()->setRGB('6DB9EF');

        $headers = ['No.', 'Tanggal', 'NIS/NIP', 'Nama', 'Siswa/Karyawan', 'Kode Aset', 'Nama Aset', 'Lokasi', 'Kondisi', 'Ketersediaan'];
        $requestSheet->fromArray([$headers], NULL, 'A1');
        $requestSheet->getStyle('A1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($dataRequest as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $requestSheet->setCellValue('A' . ($index + 2), $index + 1);
            $requestSheet->setCellValue('B' . ($index + 2), $date);
            $requestSheet->setCellValueExplicit('C' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $requestSheet->setCellValue('D' . ($index + 2), $value->namaSiswa);
            $requestSheet->setCellValue('E' . ($index + 2), $value->namaKelas);
            $requestSheet->setCellValue('F' . ($index + 2), $value->kodeRincianLabAset);
            $requestSheet->setCellValue('G' . ($index + 2), $value->namaSarana);
            $requestSheet->setCellValue('H' . ($index + 2), $value->namaLab);
            $requestSheet->setCellValue('I' . ($index + 2), $value->status);
            if ($value->sectionAset == 'None') {
                $requestSheet->setCellValue('J' . ($index + 2), 'Tersedia');
            } else {
                $requestSheet->setCellValue('J' . ($index + 2), 'Tidak Tersedia');
            }


            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $requestSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }    
        }
        $requestSheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $requestSheet->getStyle('A1:J1')->getFont()->setBold(true);
        $requestSheet->getStyle('A1:J' . $requestSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $requestSheet->getStyle('A:J')->getAlignment()->setWrapText(true);

        foreach (range('A', 'J') as $column) {
            $requestSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $dataApprove = $this->requestPeminjamanModel->getDataApprove($startDate, $endDate);
        $approveSheet = $spreadsheet->createSheet();
        $approveSheet->setTitle('Approve');
        $approveSheet->getTabColor()->setRGB('5D9C59');
        $headerApprove = ['No.', 'Tanggal', 'NIS/NIP', 'Nama', 'Siswa/Karyawan', 'Kode Aset', 'Nama Aset', 'Lokasi', 'Kondisi', 'Status'];
        $approveSheet->fromArray([$headerApprove], NULL, 'A1');
        $approveSheet->getStyle('A1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        foreach ($dataApprove as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $approveSheet->setCellValue('A' . ($index + 2), $index + 1);
            $approveSheet->setCellValue('B' . ($index + 2), $date);
            $approveSheet->setCellValueExplicit('C' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $approveSheet->setCellValue('D' . ($index + 2), $value->namaSiswa);
            $approveSheet->setCellValue('E' . ($index + 2), $value->namaKelas);
            $approveSheet->setCellValue('F' . ($index + 2), $value->kodeRincianLabAset);
            $approveSheet->setCellValue('G' . ($index + 2), $value->namaSarana);
            $approveSheet->setCellValue('H' . ($index + 2), $value->namaLab);
            $approveSheet->setCellValue('I' . ($index + 2), $value->status);
            if ($value->requestItemStatus == 'Approve') {
                $approveSheet->setCellValue('J' . ($index + 2), 'Dipinjamkan');
            } else {
                $approveSheet->setCellValue('J' . ($index + 2), 'Tidak Dipinjamkan');
            }

            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
            
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $approveSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }    
        }
        $approveSheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $approveSheet->getStyle('A1:J1')->getFont()->setBold(true);
        $approveSheet->getStyle('A1:J' . $approveSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $approveSheet->getStyle('A:J')->getAlignment()->setWrapText(true);

        foreach (range('A', 'J') as $column) {
            $approveSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $dataReject = $this->requestPeminjamanModel->getDataReject($startDate, $endDate);
        $rejectSheet = $spreadsheet->createSheet();
        $rejectSheet->setTitle('Reject');
        $rejectSheet->getTabColor()->setRGB('DF2E38');

        $headerReject = ['No.', 'Tanggal', 'NIS/NIP', 'Nama', 'Siswa/Karyawan', 'Kode Aset', 'Nama Aset', 'Lokasi', 'Kondisi'];
        $rejectSheet->fromArray([$headerReject], NULL, 'A1');
        $rejectSheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($dataReject as $index => $value) {
            $date = date('d F Y', strtotime($value->tanggal));
            $rejectSheet->setCellValue('A' . ($index + 2), $index + 1);
            $rejectSheet->setCellValue('B' . ($index + 2), $date);
            $rejectSheet->setCellValueExplicit('C' . ($index + 2), $value->nis, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $rejectSheet->setCellValue('D' . ($index + 2), $value->namaSiswa);
            $rejectSheet->setCellValue('E' . ($index + 2), $value->namaKelas);
            $rejectSheet->setCellValue('F' . ($index + 2), $value->kodeRincianLabAset);
            $rejectSheet->setCellValue('G' . ($index + 2), $value->namaSarana);
            $rejectSheet->setCellValue('H' . ($index + 2), $value->namaLab);
            $rejectSheet->setCellValue('I' . ($index + 2), $value->status);

            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $rejectSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }    
        }
        $rejectSheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $rejectSheet->getStyle('A1:I1')->getFont()->setBold(true);
        $rejectSheet->getStyle('A1:I' . $rejectSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $rejectSheet->getStyle('A:I')->getAlignment()->setWrapText(true);

        foreach (range('A', 'I') as $column) {
            $rejectSheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Laboratorium - Request Peminjaman.xlsx');
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