<?php

namespace App\Controllers;
use App\Models\userLogModels;

class Auth extends BaseController
{
    function __construct() {
        $this->userLogModel = new userLogModels();
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
        $post = $this->request->getPost();
        $query = $this->db->table('tblUser')->getWhere(['username' => $post['username']]);
        $user = $query->getRow();
        
        if ($user) {
            if (password_verify($post['password'], $user->password)) {
                $this->logEvent($user->idUser, 'Login', $_SERVER['REMOTE_ADDR']);
                $session = session();
                $session_data = [
                    'id_user'  => $user->idUser,
                    'username' => $user->username,
                    'role'     => $user->role,
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

    protected function logLogin($userId, $ipAddress)
    {
        // $loginLogModel = new UserLoginLogModel();

        $data = [
            'user_id'    => $userId,
            'loginTime' => date('Y-m-d H:i:s'),
            'ipAddress' => $ipAddress,
        ];

        $this->userLogModel->insert($data);
    }
    
    public function logout() {
        $userId = session('id_user');
        $this->logEvent($userId, 'Logout', $this->request->getIPAddress());
    
        session()->remove('id_user');
        return redirect()->to(site_url('login'));
    }

    protected function logEvent($userId, $actionType, $ipAddress) {
        // $userLoginLogModel = new UserLoginLogModel();

        $data = [
            'user_id'     => $userId,
            'loginTime'  => date('Y-m-d H:i:s'),
            'ipAddress'  => $ipAddress,
            'actionType' => $actionType,
        ];

        $this->userLogModel->insert($data);
    }
}
