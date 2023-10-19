<?php

namespace App\Controllers;
use App\Models\UserLoginLogModel;

class Auth extends BaseController
{
    function __construct() {
        $this->userLoginModel = new UserLoginLogModel();
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
                $this->logLogin($user->idUser, $_SERVER['REMOTE_ADDR']);

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
        $loginLogModel = new UserLoginLogModel();

        $data = [
            'user_id'    => $userId,
            'login_time' => date('Y-m-d H:i:s'),
            'ip_address' => $ipAddress,
        ];

        $loginLogModel->insert($data);
    }
    
    public function logout() {
        $userId = session('id_user');
        $this->logLogout($userId);

        session()->remove('id_user');
        return redirect()->to(site_url('login'));
    }

    protected function logLogout($userId) {
        $logoutLogModel = new UserLoginLogModel();

        $data = [
            'user_id'    => $userId,
            'login_time' => date('Y-m-d H:i:s'), 
            'ip_address' => $ipAddress,
        ];

        $logoutLogModel->insert($data);
    }

    public function viewLogs()
    {
        $data['dataUserLog'] = $this->userLoginModel->getAll();
        return view('auth/viewLogs', $data);
    }
}
