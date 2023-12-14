<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PertanyaanFeedbackModels; 

class PertanyaanFeedback extends ResourceController
{
    
    function __construct() {
        $this->pertanyaanFeedbackModel = new PertanyaanFeedbackModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataPertanyaanFeedback'] = $this->pertanyaanFeedbackModel->findAll();
        return view('master/pertanyaanFeedbackView/index', $data);
    }

    public function new() {
        $data['dataPertanyaanFeedback'] = $this->pertanyaanFeedbackModel->findAll();
        return view('master/pertanyaanFeedbackView/new', $data);        
    }
    
    public function create() {
        $data = $this->request->getPost(); 
        if (!empty($data['pertanyaanFeedback'])) {
            $this->pertanyaanFeedbackModel->insert($data);
            return redirect()->to(site_url('pertanyaanFeedback'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('pertanyaanFeedback'))->with('error', 'Semua field harus terisi');
        }
    }

    public function edit($id = null) {
        if ($id != null) {
            $dataPertanyaanFeedback = $this->pertanyaanFeedbackModel->find($id);
            if (is_object($dataPertanyaanFeedback)) {
                $data = [
                    'dataPertanyaanFeedback' => $dataPertanyaanFeedback,
                ];
                return view('master/pertanyaanFeedbackView/edit', $data);
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
            if (!empty($data['pertanyaanFeedback'])) {
                $this->pertanyaanFeedbackModel->update($id, $data);
                return redirect()->to(site_url('pertanyaanFeedback'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to(site_url('pertanyaanFeedback'))->with('error', 'Semua data harus diisi');
            }
        } else {
            return view('error/404');
        }
    }
}