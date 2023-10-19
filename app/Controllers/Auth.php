<?php

namespace App\Controllers;

class Auth extends BaseController
{
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
        $post = $this->request->getPost();
        $query = $this->db->table('tblUser')->getWhere(['username' => $post['username']]);
        $user = $query->getRow();
        
        if ($user) {
            if (password_verify($post['password'], $user->password)) {
                $params = ['id_user' => $user->idUser];
                $session = session();
                $session_data = [
                    'id_user' => $user->idUser,
                    'username' => $user->username,
                    'role' => $user->role,
                ];
                $session->set($session_data);
                return redirect()->to(site_url('home'));
            } else {
                return redirect()->back()->with('error', 'Password salah');
            }
        } else {
            return redirect()->back()->with('error', 'Username tidak ditemukan');
        }
    }


    public function logout() {
        session()->remove('id_user');
        return redirect()->to(site_url('login'));
    }
}
