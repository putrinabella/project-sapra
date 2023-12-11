<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LaboranFilter implements FilterInterface
{  
    public function before(RequestInterface $request, $arguments = null) {
        if (!session('id_user')) {
            return redirect()->to(site_url('login'));
        }
        
        $allowedRoles = ['Super Admin', 'Laboran'];
        if ( !in_array(session('role'), $allowedRoles)) {
            return redirect()->to(site_url('404'));
        }
    }


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here after the response is sent
    }
}
