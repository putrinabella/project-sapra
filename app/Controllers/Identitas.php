<?php

namespace App\Controllers;

class Identitas extends BaseController
{
    public function getIdentitasSarana()
    {
        return view('identitas/identitasSarana');
    }
}
