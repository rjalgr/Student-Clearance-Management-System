<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        // Admin user
        $userModel->save([
            'student_id' => 'ADMIN001',
            'full_name'  => 'System Administrator',
            'email'      => 'admin@scms.com',
            'password'   => 'admin123',
            'role'       => 'admin',
            'is_active'  => 1,
        ]);

        // Staff user
        $userModel->save([
            'student_id' => 'STAFF001',
            'full_name'  => 'Dean Staff',
            'email'      => 'staff@scms.com',
            'password'   => 'staff123',
            'role'       => 'staff',
            'department' => 'Dean Office',
            'is_active'  => 1,
        ]);

        // Student user
        $userModel->save([
            'student_id' => '2024001',
            'full_name'  => 'John Doe',
            'email'      => 'john@example.com',
            'password'   => 'student123',
            'role'       => 'student',
            'department' => 'IT',
            'course'     => 'BS Computer Science',
            'year_level' => '4',
            'is_active'  => 1,
        ]);
    }
}

