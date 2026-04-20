<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ClearanceRequestModel;
use App\Models\DepartmentModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $userModel    = new UserModel();
        $requestModel = new ClearanceRequestModel();
        $deptModel    = new DepartmentModel();
        $db           = \Config\Database::connect();

        $stats = [
            'totalStudents'   => $userModel->where('role','student')->countAllResults(),
            'totalStaff'      => $userModel->where('role','staff')->countAllResults(),
            'totalRequests'   => $requestModel->countAll(),
            'pendingCount'    => $requestModel->where('overall_status','pending')->countAllResults(),
            'inProgressCount' => $requestModel->where('overall_status','in_progress')->countAllResults(),
            'approvedCount'   => $requestModel->where('overall_status','approved')->countAllResults(),
            'rejectedCount'   => $requestModel->where('overall_status','rejected')->countAllResults(),
        ];

        $chartData = $db->query("
            SELECT DATE_FORMAT(created_at,'%b %Y') AS month, COUNT(*) AS count
            FROM clearance_requests
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at,'%Y-%m')
            ORDER BY created_at
        ")->getResultArray();

        $recentRequests = $db->table('clearance_requests cr')
            ->select('cr.*, u.full_name, u.student_id AS sid')
            ->join('users u','u.id = cr.student_id')
            ->orderBy('cr.created_at','DESC')
            ->limit(10)->get()->getResultArray();

        $allRequests = $db->table('clearance_requests cr')
            ->select('cr.*, u.full_name, u.student_id AS sid')
            ->join('users u','u.id = cr.student_id')
            ->orderBy('cr.created_at','DESC')
            ->get()->getResultArray();

        $users       = $userModel->orderBy('created_at','DESC')->findAll();
        $departments = $deptModel->orderBy('name','ASC')->findAll();

$title = 'Admin Dashboard';

        return view('admin/dashboard', compact(
            'title','stats','chartData','recentRequests',
            'allRequests','users','departments'
        ));
    }
}
