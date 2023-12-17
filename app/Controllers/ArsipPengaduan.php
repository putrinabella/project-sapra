<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FormPengaduanModels;
use App\Models\FormFeedbackModels;
use App\Models\DataSiswaModels;
use App\Models\PertanyaanPengaduanModels;
use App\Models\PertanyaanFeedbackModels;


class ArsipPengaduan extends ResourceController
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
            'dataPengaduan' => $this->formPengaduanModel->getAll($startDate, $endDate, $idUser)
        ];

        return view('saranaView/dataPengaduan/view', $data);
    }
    
    public function edit($id = null) {
        if ($id != null) {
            $dataPengaduan = $this->formPengaduanModel->getDetailDataPengaduan($id);
            $identitasUser = $this->formPengaduanModel->getIdentitas($id);
            if (!empty($dataPengaduan)) {
                $data = [
                    'dataPengaduan' => $dataPengaduan,
                    'identitasUser' => $identitasUser
                ];
                return view('saranaView/dataPengaduan/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function show($id = null) {
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

    public function update($id = null) {
        if ($id != null) {
            $idPertanyaanPengaduanArray = $this->request->getPost('idPertanyaanPengaduan');
            $idFormPengaduan = $this->request->getPost('idFormPengaduan');
            $dataSiswa = $this->formPengaduanModel->getIdentitas($id);
            $idDataSiswaValue = $dataSiswa->idDataSiswa;
            $tanggalSelesai = date('Y-m-d');
            $statusPengaduan = 'done';
            $statusFeedback = 'empty';

            $dataPengaduan = [
                'idDataSiswa' => $idDataSiswaValue,
                'tanggalSelesai' => $tanggalSelesai,
                'statusPengaduan' => $statusPengaduan,
            ];
            $this->formPengaduanModel->update($id, $dataPengaduan);

            $dataFeedback = [
                'idDataSiswa' => $idDataSiswaValue,
                'statusFeedback' => $statusFeedback,
                'idFormPengaduan' => $idFormPengaduan,
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
            return redirect()->to(site_url('arsipPengaduan'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }

    public function generatePDF() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $idUser = $this->dataSiswaModel->getIdByUsername(session('username'));
        $dataPengaduan = $this->formPengaduanModel->getAll($startDate, $endDate, $idUser);
        $pengaduan = $this->formPengaduanModel->getPengaduan($startDate, $endDate, $idUser);
        // var_dump($pengaduan);
        // die;
        $title = "DATA PENGADUAN";
        if (!$dataPengaduan) {
            return view('error/404');
        }    
    
        $pdfData = pdfPengaduan($dataPengaduan, $pengaduan, $title, $startDate, $endDate);
    
        
        $filename = 'Pengaduan - Data Pengaduan' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }
}