<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PertanyaanPengaduanModels; 

class PertanyaanPengaduan extends ResourceController
{
    
     function __construct() {
        $this->pertanyaanPengaduanModel = new PertanyaanPengaduanModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataPertanyaanPengaduan'] = $this->pertanyaanPengaduanModel->findAll();
        return view('master/pertanyaanPengaduanView/index', $data);
    }

    public function new() {
        $data['dataPertanyaanPengaduan'] = $this->pertanyaanPengaduanModel->findAll();
        return view('master/pertanyaanPengaduanView/new', $data);        
    }
    
    public function create() {
        $data = $this->request->getPost(); 
        if (!empty($data['pertanyaanPengaduan'])) {
            $this->pertanyaanPengaduanModel->insert($data);
            return redirect()->to(site_url('pertanyaanPengaduan'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('pertanyaanPengaduan'))->with('error', 'Semua field harus terisi');
        }
    }

    public function edit($id = null) {
        if ($id != null) {
            $dataPertanyaanPengaduan = $this->pertanyaanPengaduanModel->find($id);
    
            if (is_object($dataPertanyaanPengaduan)) {
                $data = [
                    'dataPertanyaanPengaduan' => $dataPertanyaanPengaduan,
                ];
                return view('master/pertanyaanPengaduanView/edit', $data);
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
            if (!empty($data['pertanyaanPengaduan'])) {
                $this->pertanyaanPengaduanModel->update($id, $data);
                return redirect()->to(site_url('pertanyaanPengaduan'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to(site_url('pertanyaanPengaduan'))->with('error', 'Semua data harus diisi');
            }
        } else {
            return view('error/404');
        }
    }
}