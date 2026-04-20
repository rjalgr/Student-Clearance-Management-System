<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['name' => 'Dean Office', 'code' => 'DEAN', 'is_active' => 1],
            ['name' => 'Registrar', 'code' => 'REG', 'is_active' => 1],
            ['name' => 'Library', 'code' => 'LIB', 'is_active' => 1],
            ['name' => 'IT Department', 'code' => 'IT', 'is_active' => 1],
            ['name' => 'Finance Office', 'code' => 'FIN', 'is_active' => 1],
            ['name' => 'Cashier', 'code' => 'CASH', 'is_active' => 1],
            ['name' => 'Student Affairs', 'code' => 'SA', 'is_active' => 1],
            ['name' => 'Property Office', 'code' => 'PROP', 'is_active' => 1],
        ];

        model('App\Models\DepartmentModel')->insertBatch($departments);
    }
}

