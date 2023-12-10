<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
{
    if (!session('id_user')) {
        return redirect()->to(site_url('login'));
    }

    // Check if the user has the required role to access the route
    $requiredRoles = $arguments['roles'] ?? [];
    
    // If no roles are specified for the route, allow access
    if (empty($requiredRoles)) {
        return;
    }

    $userRole = session('role');

    // Check if the user has one of the required roles
    if (!in_array($userRole, $requiredRoles)) {
        // Redirect the user to a page or show an error
        return redirect()->to(site_url('access-denied'));
    }
}

    // public function before(RequestInterface $request, $arguments = null)
    // {
    //     if(!session('id_user')) {
    //         return redirect()->to(site_url('login'));
    //     } 

    //     // Check if the user has the required role to access the route
    //     $requiredRole = $arguments['role'] ?? null;

    //     if (!$requiredRole) {
    //         // If no role is specified for the route, allow access
    //         return;
    //     }

    //     $userRole = session('role');

    //     // Check if the user has the required role
    //     if ($userRole !== $requiredRole) {
    //         // Redirect the user to a page or show an error
    //         return redirect()->to(site_url('access-denied'));
    //     }
    // }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here after the response is sent
    }
}
