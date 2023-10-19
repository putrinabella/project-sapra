<?php

namespace App\Controllers;
use App\Models\UserModels; 

class Auth extends BaseController
{
    function __construct() {
        $this->userModel = new UserModels();
    }

    public function index() {
        return redirect()->to(site_url('login'));
    }

    public function login() {
        if (session('id_user')) {
            return redirect()->to(site_url('home'));
        }
        return view('auth/login');
    }


    public function loginProcess() {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $dataUser = $this->userModel->find($username);
        
        if ($dataUser == NULL) {
            return redirect()->back()->with('error', 'Username tidak ditemukan');
        } else {
            if (password_verify($password == $dataUser->password)) {
                // Password benar
                $session = session();
                $session_data =  [
                    "username" => $dataUser->username,
                    "role" => $dataUser->role
                ];
                $session->set($session_data);
                return redirect()->to(site_url('home'));
            } else {
                return redirect()->back()->with('error', 'Password salah');
            }
        }
    }


    public function logout() {
        $session = session();
        $session->destroy();
        return redirect()->to(site_url('login'));
    }

}
