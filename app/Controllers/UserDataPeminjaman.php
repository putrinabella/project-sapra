<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DataAsetPeminjamanModels;
use App\Models\RequestAsetPeminjamanModels;
use App\Models\IdentitasPrasaranaModels;
use App\Models\DataSiswaModels;
use App\Models\UserActionLogsModels;

class UserDataPeminjaman extends ResourceController
{

    function __construct() {
        $this->dataAsetPeminjamanModel = new DataAsetPeminjamanModels();
        $this->requestAsetPeminjamanModel = new RequestAsetPeminjamanModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->dataSiswaModel = new DataSiswaModels();
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['pdf', 'custom']);
    }

    // Separate Data
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

        $dataAsetPeminjaman = $this->dataAsetPeminjamanModel->getDataSiswa($startDate, $endDate, $idUser);
        $dataRequest = $this->requestAsetPeminjamanModel->getDataRequestUser($startDate, $endDate, $idUser);

        // $dataUser = array_merge($dataAsetPeminjaman, $dataRequest);

        $data = [
            'tableHeading' => $tableHeading,
            'dataAsetPeminjaman' => $dataAsetPeminjaman,
            'dataRequest' => $dataRequest,
        ];
        return view('userView/dataPeminjaman/user', $data);
    }

    public function getUserLoanHistory($id = null) {
        if ($id != null) {
            $dataPeminjamanAsetUser = $this->dataAsetPeminjamanModel->findHistory($id);
            $dataRincianAset = $this->dataAsetPeminjamanModel->getRincianAset($id);
            if (is_object($dataPeminjamanAsetUser)) {
                $data = [
                    'dataPeminjamanAsetUser' => $dataPeminjamanAsetUser,
                    'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
                    'dataRincianAset' => $dataRincianAset,
                ];
                // Untuk data spesifik per lokasi
                // return view('userView/dataPeminjaman/showUser', $data);

                // Untuk data general
                return view('userView/dataPeminjaman/showUserGeneral', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function getuserRequestDetail($id = null) {
        if ($id != null) {
            $dataPeminjamanAset = $this->requestAsetPeminjamanModel->find($id);
            $dataItemDipinjam = $this->requestAsetPeminjamanModel->getBorrowItems($dataPeminjamanAset->idRequestAsetPeminjaman);
            if (is_object($dataPeminjamanAset)) {
                $data = [
                    'dataPeminjamanAset' => $dataPeminjamanAset,
                    'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
                    'dataItemDipinjam' => $dataItemDipinjam,
                ];
                // Untuk peminjaman spesifik per lokasi
                // return view('userView/dataPeminjaman/userRequestDetail', $data);

                // Untuk peminjaman general 
                return view('userView/dataPeminjaman/userRequestGeneral', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function print($id = null) {
        $dataPeminjamanAsetUser = $this->dataAsetPeminjamanModel->findHistory($id);
        $dataRincianAset = $this->dataAsetPeminjamanModel->getRincianItem($id);
    
        if (!$dataPeminjamanAsetUser && !($dataRincianAset)) {
            return view('error/404');
        }
    
        $data = [
            'dataPeminjamanAsetUser' => $dataPeminjamanAsetUser,
            'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
            'dataRincianAset' => $dataRincianAset,
        ];
    
    
        $pdfData = pdfSuratAsetPeminjaman($dataPeminjamanAsetUser, $dataRincianAset);
    
        $tanggal = date('d F Y', strtotime($dataPeminjamanAsetUser->tanggal));
        
        $filename = 'Formulir Peminjaman Aset - ' . $dataPeminjamanAsetUser->namaSiswa . " (" . $tanggal . ")" . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }

    public function revokeLoan($idRequestAsetPeminjaman = null) {
        if ($idRequestAsetPeminjaman != null) {
            $dataItemRequest = $this->dataAsetPeminjamanModel->getRequestItems($idRequestAsetPeminjaman);
            
            foreach ($dataItemRequest as $data) {
                $this->dataAsetPeminjamanModel->updateRequestSectionAset($data->idDetailRequestAsetPeminjaman);
            }
            
            $this->dataAsetPeminjamanModel->updateRevokeRequest($idRequestAsetPeminjaman);
            return redirect()->to(site_url('peminjamanAset'))->with('success', 'Request berhasil dibatalkan');
        } else {
            return view('error/404');
        }
    }
}