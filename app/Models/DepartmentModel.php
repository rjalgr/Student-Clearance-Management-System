<?php
namespace App\Models;
use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table         = 'departments';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'name','code','head_name','description','is_active'
    ];

    public function getActive(): array
    {
        return $this->where('is_active', 1)->findAll();
    }
}