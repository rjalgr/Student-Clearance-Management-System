<?php
namespace App\Models;
use CodeIgniter\Model;

class ClearanceRequestModel extends Model
{
    protected $table         = 'clearance_requests';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'student_id','academic_year','semester','purpose','overall_status'
    ];

    // Get request with student info joined
    public function getWithStudent(int $id)
    {
        return $this->db->table('clearance_requests cr')
            ->select('cr.*, u.full_name, u.student_id AS sid, u.course, u.department')
            ->join('users u', 'u.id = cr.student_id')
            ->where('cr.id', $id)
            ->get()->getRowArray();
    }

    // Paginated list for admin
    public function listAll(array $filters = [], int $perPage = 15)
    {
        $builder = $this->db->table('clearance_requests cr')
            ->select('cr.*, u.full_name, u.student_id AS sid')
            ->join('users u', 'u.id = cr.student_id');

        if (!empty($filters['status'])) {
            $builder->where('cr.overall_status', $filters['status']);
        }
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('u.full_name', $filters['search'])
                ->orLike('u.student_id', $filters['search'])
                ->groupEnd();
        }
        return $builder->orderBy('cr.submitted_at','DESC');
    }
}