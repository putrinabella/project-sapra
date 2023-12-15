<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ManajemenUserModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class ManajemenUser extends ResourceController
{
    
    function __construct() {
        $this->manajemenUserModel = new ManajemenUserModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataManajemenUser'] = $this->manajemenUserModel->findAll();
        return view('master/manajemenUserView/index', $data);
    }

    public function new() {
        $data['dataManajemenUser'] = $this->manajemenUserModel->findAll();
        
        return view('master/manajemenUserView/new', $data);        
    }

    public function create() {
        $data = $this->request->getPost();
        $username = $data['username'];
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        if ($this->manajemenUserModel->isDuplicate($username)) {
            return redirect()->to(site_url('manajemenUser'))->with('error', 'Ditemukan duplikat data! Masukkan data yang berbeda.');
        } else {
            $data = [
                'username' => $data['username'],
                'nama' => $data['nama'],
                'role' => $data['role'],
                'password' => $hashedPassword,
            ];

            $this->manajemenUserModel->insert($data);
            return redirect()->to(site_url('manajemenUser'))->with('success', 'Data berhasil disimpan');
        }
    }

    public function edit($id = null) {
        if ($id != null) {
            $dataManajemenUser = $this->manajemenUserModel->find($id);
    
            if (is_object($dataManajemenUser)) {
                $data = [
                    'dataManajemenUser' => $dataManajemenUser,
                ];
                return view('master/manajemenUserView/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function editPassword($id = null) {
        if ($id != null) {
            $dataManajemenUser = $this->manajemenUserModel->find($id);
    
            if (is_object($dataManajemenUser)) {
                $data = [
                    'dataManajemenUser' => $dataManajemenUser,
                ];
                return view('master/manajemenUserView/editPassword', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function update($id = null) {
        if ($id !== null) {
            $data = $this->request->getPost();
            $username = $data['username'];

            $existingData = $this->manajemenUserModel->find($id);
            if ($existingData->username != $username ) {
                if ($this->manajemenUserModel->isDuplicate($username)) {
                    return redirect()->to(site_url('manajemenUser'))->with('error', 'Gagal update karena ditemukan duplikat data!');
                }
            } 
            
            if (isset($data['username']) && isset($data['nama']) && isset($data['role'])) {
                $updateData = [
                    'username' => $data['username'],
                    'nama' => $data['nama'],
                    'role' => $data['role'],
                ];
                $this->manajemenUserModel->update($id, $updateData);
                return redirect()->to(site_url('manajemenUser'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to(site_url('manajemenUser'))->with('error', 'Silahkan isi semua kolom!');
            }
        } else {
            return view('error/404');
        }
    }
    
    public function delete($id = null) {
        $this->manajemenUserModel->delete($id);
        return redirect()->to(site_url('manajemenUser'));
    }

    public function updatePassword($id = null) {
        if ($id !== null) {
            $data = $this->request->getPost();

            if (isset($data['password'])) {
                $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
                $data = [
                    'password' => $hashedPassword,
                ];

                $this->manajemenUserModel->update($id, $data);
                return redirect()->to(site_url('manajemenUser'))->with('success', 'Password berhasil diubah!');
            } else {
                return redirect()->to(site_url('manajemenUser'))->with('error', 'Silahkan isi semua kolom!');
            }
        } else {
            return view('error/404');
        }
    }

    public function updateUser($id = null) {
        if ($id !== null) {
            $data = $this->request->getPost();

            if (isset($data['oldPassword']) && isset($data['password'])) {

                $currentUser = $this->manajemenUserModel->find($id);
                $currentPasswordHash = $currentUser->password; 
                if (password_verify($data['oldPassword'], $currentPasswordHash)) {
                    $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
                    $newPassword = [
                        'password' => $hashedPassword,
                    ];
                    $this->manajemenUserModel->update($id, $newPassword);
                    
                    return redirect()->to(site_url('profileUser'))->with('success', 'Password berhasil diperbarui');
                } else {
                    return redirect()->to(site_url('profileUser'))->with('error', 'Password lama tidak sesuai');
                }
            } else {
                return redirect()->to(site_url('profileUser'))->with('error', 'Silahkan isi semua kolom!');
            }
        } else {
            return view('error/404');
        }
    }
    
}