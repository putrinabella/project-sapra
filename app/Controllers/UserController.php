<?php

namespace App\Controllers;

class UserController extends BaseController
{
    public function getManajemenUser()
    {
        return view('user/manajemenUserView');
    }
}
