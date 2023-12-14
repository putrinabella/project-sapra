<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FormPengaduanModels;
use App\Models\FormFeedbackModels;
use App\Models\DataSiswaModels;
use App\Models\PertanyaanPengaduanModels;
use App\Models\PertanyaanFeedbackModels;


class ArsipFeedback extends ResourceController
{
    function __construct()
    {
        $this->formPengaduanModel = new FormPengaduanModels();
        $this->formFeedbackModel = new FormFeedbackModels();
        $this->dataSiswaModel = new DataSiswaModels();
        $this->pertanyaanPengaduanModel = new PertanyaanPengaduanModels();
        $this->pertanyaanFeedbackModel = new PertanyaanFeedbackModels();
        $this->db = \Config\Database::connect();
        helper(['pdf','custom']);
    }

    public function index() {
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
            'dataFeedback' => $this->formFeedbackModel->getAll($startDate, $endDate, $idUser),
            'feedbackPercentages' => $this->formFeedbackModel->getFeedbackPercentages()
        ];
        return view('saranaView/dataFeedback/view', $data);
    }
    
    public function edit($id = null) {
        if ($id != null) {
            $dataFeedback = $this->formFeedbackModel->getDetailDataFeedback($id);
            $identitasUser = $this->formFeedbackModel->getIdentitas($id);
            if (!empty($dataFeedback)) {
                $data = [
                    'dataFeedback' => $dataFeedback,
                    'identitasUser' => $identitasUser
                ];
                return view('saranaView/dataFeedback/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function show($id = null) {
        if ($id != null) {
            $dataFeedback = $this->formFeedbackModel->getDetailDataFeedback($id);
            $identitasUser = $this->formFeedbackModel->getIdentitas($id);
            if (!empty($dataFeedback)) {
                $data = [
                    'dataFeedback' => $dataFeedback,
                    'identitasUser' => $identitasUser
                ];
                return view('saranaView/dataFeedback/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function update($id = null) {
        if ($id != null) {
            $idPertanyaanFeedbackArray = $this->request->getPost('idPertanyaanFeedback');
            $idFormPeminjaman = $this->request->getPost('idFormPeminjaman');
            $dataSiswa = $this->formFeedbackModel->getIdentitas($id);
            $idDataSiswaValue = $dataSiswa->idDataSiswa;
            $tanggalSelesai = date('Y-m-d');
            $statusFeedback = 'done';
            $statusFeedback = 'empty';

            $dataFeedback = [
                'idDataSiswa' => $idDataSiswaValue,
                'tanggalSelesai' => $tanggalSelesai,
                'statusFeedback' => $statusFeedback,
            ];
            $this->formFeedbackModel->update($id, $dataFeedback);

            $dataFeedback = [
                'idDataSiswa' => $idDataSiswaValue,
                'statusFeedback' => $statusFeedback,
            ];
            $this->formFeedbackModel->insert($dataFeedback);
            $idFormFeedback  = $this->db->insertID();
            $pertanyaanFeedback = $this->pertanyaanFeedbackModel->findAll();
            foreach ($pertanyaanFeedback as $value) {
                $idPertanyaanFeedback =  $value->idPertanyaanFeedback;

                $detailFeedback = [
                    'idFormFeedback' => $idFormFeedback,
                    'idPertanyaanFeedback' => $idPertanyaanFeedback,
                ];
                $this->db->table('tblDetailFormFeedback')->insert($detailFeedback);
            }
            return redirect()->to(site_url('arsipFeedback'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }
}