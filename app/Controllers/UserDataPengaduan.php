<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FormPengaduanModels;
use App\Models\DataSiswaModels;
use App\Models\PertanyaanPengaduanModels;


class UserDataPengaduan extends ResourceController
{

    function __construct()
    {
        $this->formPengaduanModel = new FormPengaduanModels();
        $this->dataSiswaModel = new DataSiswaModels();
        $this->pertanyaanPengaduanModel = new PertanyaanPengaduanModels();
        $this->db = \Config\Database::connect();
        helper(['pdf','custom']);
    }

    public function view()
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
            'dataPengaduan' => $this->formPengaduanModel->getData($startDate, $endDate, $idUser)
        ];

        return view('userView/dataPengaduan/view', $data);
    }
    
    public function detail($id) {
        if ($id != null) {
            $idUser = $this->dataSiswaModel->getIdByUsername(session('username'));
            $dataPengaduan = $this->formPengaduanModel->getDetailDataPengaduan($id);
            $identitasUser = $this->formPengaduanModel->getIdentitasUser($id);
            // var_dump($identitasUser);
            // die;
            if (!empty($dataPengaduan)) {
                $data = [
                    'dataPengaduan' => $dataPengaduan,
                    'identitasUser' => $identitasUser
                ];
                return view('userView/dataPengaduan/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }
}
