<?php

namespace App\Controllers;
use App\Models\UserLogModels;

class Auth extends BaseController
{
    function __construct() {
        $this->userLogModel = new UserLogModels();
    }

    public function index() {
        return redirect()->to(site_url('login'));
    }

    public function error() {
        return view('error/404');
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
        $mode = 'light'; 
        if ($user) {
            if (password_verify($post['password'], $user->password)) {
                $this->logEvent($user->idUser, 'Login', $_SERVER['REMOTE_ADDR']);
                
                $session = session();
                $session_data = [
                    'id_user'  => $user->idUser,
                    'username' => $user->username,
                    'role'     => $user->role,
                    'nama'     => $user->nama,
                    'password' => $user->password,
                    'mode'     => $mode,
                ];
                $session->set($session_data);
                return redirect()->to(site_url('home'));
            } else {
                return redirect()->to(site_url('login'))->with('error', 'Password salah');
            }
        } else {
            return redirect()->to(site_url('login'))->with('error', 'Username tidak ditemukan');
        }
    }

    public function logout() {
        $userId = session('id_user');
        $this->logEvent($userId, 'Logout', $this->request->getIPAddress());
    
        session()->remove('id_user');
        return redirect()->to(site_url('login'));
    }

    protected function logEvent($userId, $actionType, $ipAddress) {

        $data = [
            'user_id'     => $userId,
            'loginTime'  => date('Y-m-d H:i:s'),
            'ipAddress'  => $ipAddress,
            'actionType' => $actionType,
        ];

        $this->userLogModel->insert($data);
    }

    public function updateTheme() {
        $request = service('request');
        $mode = $request->getJSON('mode');
    
        if ($mode === 'light' || $mode === 'dark') {
            session()->set('mode', $mode); 
            return $this->response->setJSON(['success' => true, 'mode' => $mode]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid mode value']);
        }
    }

    public function updateSessionMode() {
        $mode = $this->request->getPost('mode');
        $session = session();
    
        if ($mode === 'dark' || $mode === 'light') {
            $session->set('mode', $mode);
        }
        return $this->response->setJSON(['mode' => $mode]);
    }
    
    public function checkOldPassword() {
        $request = service('request');
        $session = session();
        
        $oldPassword = $request->getPost('oldPassword');
        $userPassword = $session->get('password');
    
        if (password_verify($oldPassword, $userPassword)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Incorrect old password']);
        }
    }
    
    
}
