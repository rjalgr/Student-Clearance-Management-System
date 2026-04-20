<?php
namespace App\Controllers\Student;
use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('userId');
        $user   = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User not found.');
        }

        return view('student/profile', [
            'title' => 'Profile Settings',
            'user'  => $user,
        ]);
    }

    public function update()
    {
        $userId = session()->get('userId');
        $user   = $this->userModel->find($userId);

        // Validation rules
        $rules = [
            'full_name'  => 'required|min_length[3]|max_length[100]',
            'email'      => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'department' => 'permit_empty|max_length[100]',
            'course'     => 'permit_empty|max_length[100]',
            'year_level' => 'permit_empty|in_list[1,2,3,4]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

$passwordChanged = !empty($newPassword);

        // Build update data
        $updateData = [
            'full_name'  => $this->request->getPost('full_name'),
            'email'      => $this->request->getPost('email'),
            'department' => $this->request->getPost('department'),
            'course'     => $this->request->getPost('course'),
            'year_level' => $this->request->getPost('year_level') ?: null,
        ];

        // Handle password change
        $currentPassword = $this->request->getPost('current_password');
        $newPassword     = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (!empty($currentPassword)) {
            // Verify current password
            if (!password_verify($currentPassword, $user['password'])) {
                return redirect()->back()
                                 ->withInput()
                                 ->with('error', 'Current password is incorrect.');
            }

            if (empty($newPassword)) {
                return redirect()->back()
                                 ->withInput()
                                 ->with('error', 'Please enter a new password.');
            }

            if (strlen($newPassword) < 8) {
                return redirect()->back()
                                 ->withInput()
                                 ->with('error', 'New password must be at least 8 characters.');
            }

            if ($newPassword !== $confirmPassword) {
                return redirect()->back()
                                 ->withInput()
                                 ->with('error', 'New password and confirm password do not match.');
            }

            // Hash new password
            $updateData['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        // Temporarily disable model validation to avoid password re-hash conflict
        $this->userModel->skipValidation(true);

        // Perform update
        $updated = $this->userModel->update($userId, $updateData);

        if (!$updated) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Failed to update profile. Please try again.');
        }

        if (isset($passwordChanged) && $passwordChanged) {
            try {
                $notifier = new \App\Libraries\EmailNotifier();
                $notifier->sendPasswordChanged(
                    $updateData['email'],
                    $updateData['full_name']
                );
            } catch (\Exception $e) {
                log_message('error', 'Password change email failed: ' . $e->getMessage());
            }
        }

        // Update session data
        session()->set([
            'fullName' => $updateData['full_name'],
            'email'    => $updateData['email'],
        ]);

        return redirect()->to('/student/profile')
                         ->with('success', 'Profile updated successfully!');
    }
}