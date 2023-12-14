<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FormFeedbackModels;
use App\Models\DataSiswaModels;
use App\Models\PertanyaanFeedbackModels;


class UserFormFeedback extends ResourceController
{

    function __construct()
    {
        $this->formFeedbackModel = new FormFeedbackModels();
        $this->dataSiswaModel = new DataSiswaModels();
        $this->pertanyaanFeedbackModel = new PertanyaanFeedbackModels();
        $this->db = \Config\Database::connect();
        helper(['custom']);
    }

    public function feedback()
    {
        $data = [
            'dataPertanyaanFeedback' => $this->pertanyaanFeedbackModel->findAll()
        ];
        return view('userView/formFeedback/user', $data);
    }
    
    public function tambahFeedback() {
        $data = $this->request->getPost();
        var_dump($data);
        die;
        $idPertanyaanFeedbackArray = $this->request->getPost('idPertanyaanFeedback');
        $isiFeedbackArray = $this->request->getPost('isiFeedback');
        
        $tanggal = date('Y-m-d');
        $idDataSiswa = $this->dataSiswaModel->getIdByUsername(session('username'));
        $statusFeedback = 'request';

        $dataFeedback = [
            'idDataSiswa' => $idDataSiswa,
            'tanggal' => $tanggal,
            'statusFeedback' => $statusFeedback,
        ];
        $this->formFeedbackModel->insert($dataFeedback);
        $idFormFeedback = $this->db->insertID();
        foreach ($idPertanyaanFeedbackArray as $idPertanyaanFeedback) {
            $detailData = [
                'idFormFeedback' => $idFormFeedback,
                'idPertanyaanFeedback' => $idPertanyaanFeedback,
                'isiFeedback' => $isiFeedbackArray[$idPertanyaanFeedback],
            ];
            $this->db->table('tblDetailFormFeedback')->insert($detailData);
        }
        return redirect()->to(site_url('dataFeedbackUser'))->with('success', 'Data berhasil disimpan');
    }
}
