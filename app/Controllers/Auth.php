<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function index() {
        return redirect()->to(site_url('login'));
    }

    public function login() {
        return view('auth/login');
    }

    public function loginProcess() {
        $post = $this->request->getPost();
        $query = $this->db->table('tblUser')->getWhere(['username' => $post['username']]);
        $user = $query->getRow();
        if($user) {
            if(password_verify($post['password'], $user->password)) {
                $params = ['id_user' => $user->idUser];
                session()->set($params);
                return redirect()->to(site_url('/'));
            } else {
                return redirect()->back()->with('error', 'Password salah');
            }
        } else {
            return redirect()->back()->with('error', 'Username tidak ditemukan');
        }
    }
}
