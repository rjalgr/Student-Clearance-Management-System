<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\DepartmentModel;
use App\Libraries\EmailNotifier;

class AuthController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // ── Show login page 
    public function login()
    {
        // Redirect if already logged in
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('auth/login', [
            'title' => 'Login | SCMS',
        ]);
    }

    // ── Handle login form submission 
    public function loginPost()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user     = $this->userModel->findByEmail($email);

        // Check credentials
        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Invalid email or password.');
        }

        // Check if account is active
        if (!$user['is_active']) {
            return redirect()->back()
                             ->with('error', 'Your account is inactive. Please contact the administrator.');
        }

        // Set session data
        session()->set([
            'isLoggedIn' => true,
            'userId'     => $user['id'],
            'fullName'   => $user['full_name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'studentId'  => $user['student_id'],
            'department' => $user['department'],
        ]);

        log_message('info', "User {$user['email']} (role: {$user['role']}) logged in successfully.");

        return $this->redirectByRole($user['role']);
    }

    // ── Show registration page 
    public function register()
    {
        $deptModel   = new DepartmentModel();
        $departments = $deptModel->getActive();

        return view('auth/register', [
            'title'       => 'Register | SCMS',
            'departments' => $departments,
        ]);
    }

    // ── Handle registration form submission 
    public function registerPost()
    {
        $rules = [
            'student_id' => 'required|is_unique[users.student_id]',
            'full_name'  => 'required|min_length[3]|max_length[100]',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'password'   => 'required|min_length[8]',
            'department' => 'required',
            'course'     => 'required',
            'year_level' => 'permit_empty|in_list[1,2,3,4]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        // Insert new student account
        $this->userModel->insert([
            'student_id' => $this->request->getPost('student_id'),
            'full_name'  => $this->request->getPost('full_name'),
            'email'      => $this->request->getPost('email'),
            'password'   => $this->request->getPost('password'),
            'role'       => 'student',
            'department' => $this->request->getPost('department'),
            'course'     => $this->request->getPost('course'),
            'year_level' => $this->request->getPost('year_level') ?: null,
            'is_active'  => 1,
        ]);

        // Send welcome email notification
        try {
            $notifier = new EmailNotifier();
            $notifier->sendWelcome(
                $this->request->getPost('email'),
                $this->request->getPost('full_name')
            );
        } catch (\Exception $e) {
            log_message('error', 'Welcome email failed: ' . $e->getMessage());
        }

        return redirect()->to('/login')
                         ->with('success', 'Account created successfully! Please check your email. You may now log in.');
    }

    // ── Handle logout
    public function logout()
    {
        $fullName = session()->get('fullName');
        session()->destroy();

        log_message('info', "User {$fullName} logged out.");

        return redirect()->to('/login')
                         ->with('success', 'You have been logged out successfully.');
    }

    // ── Redirect user based on role
    private function redirectByRole(string $role): \CodeIgniter\HTTP\RedirectResponse
    {
        return match($role) {
            'admin' => redirect()->to('/admin/dashboard'),
            'staff' => redirect()->to('/staff/dashboard'),
            default => redirect()->to('/student/dashboard'),
        };
    }
}