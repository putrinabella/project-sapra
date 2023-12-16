<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FormFeedbackModels;
use App\Models\DataSiswaModels;
use App\Models\PertanyaanFeedbackModels;


class UserDataFeedback extends ResourceController
{

    function __construct()
    {
        $this->formFeedbackModel = new FormFeedbackModels();
        $this->dataSiswaModel = new DataSiswaModels();
        $this->pertanyaanFeedbackModel = new PertanyaanFeedbackModels();
        $this->db = \Config\Database::connect();
        helper(['pdf','custom']);
    }

    public function view()
    {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
        $idUser = $this->dataSiswaModel->getIdByUsername(session('username'));
        $dataFeedback = $this->formFeedbackModel->getData($startDate, $endDate, $idUser);
        $feedbackPercentages = $this->formFeedbackModel->getFeedbackPercentages();
        $averageFeedbackPercentages = $this->formFeedbackModel->getAverageFeedbackPercentages();
        
        $formattedStartDate = !empty($startDate) ? date('d F Y', strtotime($startDate)) : '';
        $formattedEndDate = !empty($endDate) ? date('d F Y', strtotime($endDate)) : '';

        $tableHeading = "";
        if (!empty($formattedStartDate) && !empty($formattedEndDate)) {
            $tableHeading = " $formattedStartDate - $formattedEndDate";
        }

        $data = [
            'tableHeading' => $tableHeading,
            'dataFeedback' => $dataFeedback,
            'feedbackPercentages' => $feedbackPercentages,
            'averageFeedbackPercentages' => $averageFeedbackPercentages,
        ];

        return view('userView/dataFeedback/view', $data);
    }
    
    public function detail($id) {
        if ($id != null) {
            $dataFeedback = $this->formFeedbackModel->getDetailDataFeedback($id);
            $identitasUser = $this->formFeedbackModel->getIdentitasUser($id);
            $feedbackPercentages = $this->formFeedbackModel->getFeedbackPercentagesUser($id);

            if (!empty($dataFeedback)) {
                $data = [
                    'dataFeedback' => $dataFeedback,
                    'identitasUser' => $identitasUser,
                    'feedbackPercentages' => $feedbackPercentages
                ];
                return view('userView/dataFeedback/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function edit($id = null) {
        if ($id != null) {
            $idFormFeedback = $id;
            $dataFeedback = $this->formFeedbackModel->getDetailDataFeedback($id);
            $identitasUser = $this->formFeedbackModel->getIdentitasUser($id);
            $data = [
                'dataFeedback' => $dataFeedback,
                'identitasUser' => $identitasUser,
                'idFormFeedback' => $idFormFeedback,
            ];
            return view('userView/dataFeedback/edit', $data);
        } else {
            return view('error/404');
        }
    }

    public function addFeedback($id = null) {
        $idPertanyaanFeedbackArray = $this->request->getPost('idPertanyaanFeedback');
        $isiFeedbackArray = $this->request->getPost('isiFeedback');
        $statusFeedback = 'done';
        $tanggal = date('Y-m-d');

        $idDetailFormFeedbackArray = $this->formFeedbackModel->getIdDetailFeedback($id);

        $updateFormFeedback = [
            'statusFeedback' => $statusFeedback,
            'tanggal' => $tanggal,
        ];
        $this->formFeedbackModel->update($id, $updateFormFeedback);

        foreach ($idDetailFormFeedbackArray as $key => $value) {
            $idDetailFormFeedback = $value->idDetailFormFeedback;
            $detailData = [
                'isiFeedback' => $isiFeedbackArray[$key+1],
            ];
            $this->db->table('tblDetailFormFeedback')
            ->where('idDetailFormFeedback', $idDetailFormFeedback)
            ->update($detailData);
        }
        return redirect()->to(site_url('dataFeedbackUser'))->with('success', 'Data berhasil disimpan');
    }
}
