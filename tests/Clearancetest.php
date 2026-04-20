<?php
namespace Tests;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class ClearanceTest extends CIUnitTestCase
{
    use DatabaseTestTrait, FeatureTestTrait;

    protected $refresh = true;

    // Test Case 1: Login with valid credentials
    public function testValidLoginRedirectsDashboard()
    {
        // Seed a test user
        $userModel = new \App\Models\UserModel();
        $userModel->insert([
            'student_id' => 'TEST001',
            'full_name'  => 'Test Student',
            'email'      => 'test@student.edu',
            'password'   => 'password123',
            'role'       => 'student',
        ]);

        $result = $this->post('/login', [
            'email'    => 'test@student.edu',
            'password' => 'password123',
        ]);
        $result->assertRedirectTo('/student/dashboard');
    }

    // Test Case 2: Login with invalid credentials
    public function testInvalidLoginShowsError()
    {
        $result = $this->post('/login', [
            'email'    => 'wrong@email.com',
            'password' => 'wrongpass',
        ]);
        $result->assertSessionHas('error');
    }

    // Test Case 3: Unauthenticated access to student dashboard is blocked
    public function testUnauthenticatedAccessRedirectsToLogin()
    {
        $result = $this->get('/student/dashboard');
        $result->assertRedirectTo('/login');
    }

    // Test Case 4: Clearance submission validates required fields
    public function testClearanceSubmissionValidation()
    {
        // Login first
        session()->set(['isLoggedIn' => true, 'userId' => 1, 'role' => 'student']);

        $result = $this->post('/student/clearance/submit', [
            // Missing required fields
        ]);
        $result->assertSessionHas('errors');
    }

    // Test Case 5: REST API returns 401 without token
    public function testApiRequiresToken()
    {
        $result = $this->get('/api/v1/stats');
        $result->assertStatus(401);
    }
}