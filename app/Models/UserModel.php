<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'student_id','full_name','email','password',
        'role','department','course','year_level','is_active'
    ];

    // Validation rules
    protected $validationRules = [
        'full_name'  => 'required|min_length[3]|max_length[100]',
        'email'      => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password'   => 'required|min_length[8]',
        'role'       => 'required|in_list[admin,staff,student]',
    ];

    protected $validationMessages = [
        'email' => ['is_unique' => 'This email is already registered.'],
    ];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

protected function hashPassword(array $data)
{
    if (!empty($data['data']['password'])) {
        $password = $data['data']['password'];
        // Skip re-hashing if already BCRYPT hash (60 chars, starts with $2)
        if (strlen($password) === 60 && str_starts_with($password, '$2')) {
            return $data;
        }
        $data['data']['password'] = password_hash($password, PASSWORD_BCRYPT);
    }
    return $data;
}

    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->where('is_active', 1)->first();
    }
}