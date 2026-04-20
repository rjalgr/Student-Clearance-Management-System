<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected UserModel $userModel;
    public function __construct() { $this->userModel = new UserModel(); }

    public function store()
    {
        $rules = [
            'full_name' => 'required|min_length[3]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[8]',
            'role'      => 'required|in_list[admin,staff,student]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->to('/admin/dashboard#section-users')
                             ->with('errors', $this->validator->getErrors());
        }
        $data = $this->request->getPost(['student_id','full_name','email','password','role','department','course','year_level']);
        $this->userModel->insert($data);
        return redirect()->to('/admin/dashboard#section-users')->with('success','User added successfully.');
    }

    public function update(int $id)
    {
        $data = $this->request->getPost(['student_id','full_name','email','role','department','course','is_active']);
        $password = $this->request->getPost('password');
        if (!empty($password)) $data['password'] = $password;
        $this->userModel->update($id, $data);
        return redirect()->to('/admin/dashboard#section-users')->with('success','User updated successfully.');
    }

    public function delete(int $id)
    {
        if ($id == session()->get('userId')) {
            return redirect()->to('/admin/dashboard#section-users')->with('error','You cannot delete your own account.');
        }
        $this->userModel->delete($id);
        return redirect()->to('/admin/dashboard#section-users')->with('success','User deleted.');
    }
}