<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DataPeminjamanModels;
use App\Models\RequestPeminjamanModels;
use App\Models\IdentitasLabModels;
use App\Models\DataSiswaModels;
use App\Models\UserActionLogsModels;

class UserDataLabPeminjaman extends ResourceController
{

    function __construct() {
        $this->dataPeminjamanModel = new DataPeminjamanModels();
        $this->requestPeminjamanModel = new RequestPeminjamanModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->dataSiswaModel = new DataSiswaModels();
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['pdf', 'custom']);
    }
    
    public function user() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
        $idUser = $this->dataSiswaModel->getIdByUsername(session('username'));

        $formattedStartDate = !empty($startDate) ? date('d F Y', strtotime($startDate)) : '';
        $formattedEndDate = !empty($endDate) ? date('d F Y', strtotime($endDate)) : '';

        $tableHeading = "";
        if (!empty($formattedStartDate) && !empty($formattedEndDate)) {
            $tableHeading = " $formattedStartDate - $formattedEndDate";
        }

        $dataPeminjaman = $this->dataPeminjamanModel->getDataSiswa($startDate, $endDate, $idUser);
        $dataRequest = $this->requestPeminjamanModel->getDataRequestUser($startDate, $endDate, $idUser);

        $data = [
            'tableHeading' => $tableHeading,
            'dataPeminjaman' => $dataPeminjaman,
            'dataRequest' => $dataRequest,
        ];
        return view('userView/dataPeminjamanLab/user', $data);
    }

    public function getUserLoanHistory($id = null) {
        if ($id != null) {
            $dataDataPeminjaman = $this->dataPeminjamanModel->findHistory($id);
            $dataRincianLabAset = $this->dataPeminjamanModel->getRincianLabAset($id);
            if (is_object($dataDataPeminjaman)) {
                $data = [
                    'dataDataPeminjaman' => $dataDataPeminjaman,
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                    'dataRincianLabAset' => $dataRincianLabAset,
                ];
                // Untuk data spesifik per lokasi
                // return view('userView/dataPeminjamanLab/showUser', $data);

                // Untuk data general
                return view('userView/dataPeminjamanLab/showUserGeneral', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function getuserRequestDetail($id = null) {
        if ($id != null) {
            $dataRequestPeminjaman = $this->requestPeminjamanModel->find($id);
            $dataItemDipinjam = $this->requestPeminjamanModel->getBorrowItems($dataRequestPeminjaman->idRequestPeminjaman);
            if (is_object($dataRequestPeminjaman)) {
                $data = [
                    'dataRequestPeminjaman' => $dataRequestPeminjaman,
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                    'dataItemDipinjam' => $dataItemDipinjam,
                ];
                // Untuk peminjaman spesifik per lokasi
                // return view('userView/dataPeminjamanLab/userRequestDetail', $data);

                // Untuk peminjaman general
                return view('userView/dataPeminjamanLab/userRequestGeneral', $data);
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

    public function revokeLoan($idRequestPeminjaman = null) {
        if ($idRequestPeminjaman != null) {
            $dataItemRequest = $this->dataPeminjamanModel->getRequestItems($idRequestPeminjaman);
            
            foreach ($dataItemRequest as $data) {
                $this->dataPeminjamanModel->updateRequestSectionAset($data->idDetailRequestPeminjaman);
            }

            $this->dataPeminjamanModel->updateRevokeRequest($idRequestPeminjaman);
            return redirect()->to(site_url('dataLabPeminjaman'))->with('success', 'Request berhasil dibatalkan');
        } else {
            return view('error/404');
        }
    }
}