<?php
namespace App\Controllers\Staff;
use App\Controllers\BaseController;
use App\Models\ClearanceItemModel;
use App\Models\DepartmentModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $itemModel = new ClearanceItemModel();
        $deptModel = new DepartmentModel();
        $userModel = new UserModel();

        $staffUser = $userModel->find(session()->get('userId'));
        $dept      = $deptModel->where('name', $staffUser['department'])->first();

        $pendingCount  = 0;
        $approvedCount = 0;
        $rejectedCount = 0;

        if ($dept) {
            $pendingCount  = $itemModel->where('department_id', $dept['id'])->where('status', 'pending')->countAllResults();
            $approvedCount = $itemModel->where('department_id', $dept['id'])->where('status', 'approved')->countAllResults();
            $rejectedCount = $itemModel->where('department_id', $dept['id'])->where('status', 'rejected')->countAllResults();
        }

        return view('staff/dashboard', [
            'title'         => 'Staff Dashboard',
            'pendingCount'  => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
        ]);
    }
}