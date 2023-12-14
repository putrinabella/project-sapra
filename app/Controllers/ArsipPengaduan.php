<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FormPengaduanModels;
use App\Models\DataSiswaModels;
use App\Models\PertanyaanPengaduanModels;


class ArsipPengaduan extends ResourceController
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
            'dataPengaduan' => $this->formPengaduanModel->getAll($startDate, $endDate, $idUser)
        ];

        return view('saranaView/dataPengaduan/view', $data);
    }
    
    public function detail($id) {
        if ($id != null) {
            $dataPengaduan = $this->formPengaduanModel->getDetailDataPengaduan($id);
            $identitasUser = $this->formPengaduanModel->getIdentitas($id);
            if (!empty($dataPengaduan)) {
                $data = [
                    'dataPengaduan' => $dataPengaduan,
                    'identitasUser' => $identitasUser
                ];
                return view('saranaView/dataPengaduan/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function processPengaduan() {
        echo "berhasil";
    }
}
