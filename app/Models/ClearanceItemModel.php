<?php
namespace App\Models;
use CodeIgniter\Model;

class ClearanceItemModel extends Model
{
    protected $table         = 'clearance_items';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
    'request_id', 'department_id', 'staff_id',
    'status', 'remarks', 'attachment', 'reviewed_at'
];

    public function getItemsForRequest(int $requestId): array
    {
        return $this->db->table('clearance_items ci')
            ->select('ci.*, d.name AS dept_name, d.code, u.full_name AS reviewed_by')
            ->join('departments d', 'd.id = ci.department_id')
            ->join('users u', 'u.id = ci.staff_id', 'left')
            ->where('ci.request_id', $requestId)
            ->get()->getResultArray();
    }

    public function updateStatus(int $itemId, string $status, string $remarks, int $staffId): bool
    {
        return $this->update($itemId, [
            'status'      => $status,
            'remarks'     => $remarks,
            'staff_id'    => $staffId,
            'reviewed_at' => date('Y-m-d H:i:s'),
        ]);
    }
}