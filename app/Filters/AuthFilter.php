<?php
namespace App\Filters;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Not logged in at all
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please log in first.');
        }

        // Role check if argument provided
        if (!empty($arguments)) {
            $allowedRole = $arguments[0];
            $userRole    = $session->get('role');

            // Admin can access everything
            if ($userRole === 'admin') {
                return;
            }

            // Staff trying to access staff routes — allow
            if ($allowedRole === 'staff' && $userRole === 'staff') {
                return;
            }

            // Student trying to access student routes — allow
            if ($allowedRole === 'student' && $userRole === 'student') {
                return;
            }

            // Wrong role — redirect to their own dashboard
            return redirect()->to('/' . $userRole . '/dashboard')
                             ->with('error', 'Access denied. You do not have permission.');
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {}
}