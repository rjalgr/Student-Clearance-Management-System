<?php
namespace App\Controllers\Api;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ClearanceRequestModel;
use App\Models\ClearanceItemModel;

class ClearanceApiController extends ResourceController
{
    protected $format = 'json';

    public function progress()
    {
        $userId = session()->get('userId');
        if (!$userId) {
            return $this->failUnauthorized('User not authenticated.');
        }

        $requestModel = new \App\Models\ClearanceRequestModel();
        $itemModel = new \App\Models\ClearanceItemModel();
        $deptModel = new \App\Models\DepartmentModel();
        $notifModel = new \App\Models\NotificationModel();
        $userModel = new \App\Models\UserModel();

        // Fetch latest active clearance request for student
        $latestRequest = $requestModel->where('student_id', $userId)
            ->where('overall_status !=', 'approved')
            ->orderBy('created_at', 'DESC')
            ->first();

        if (!$latestRequest) {
            return $this->respond([
                'status' => 200,
                'hasRequest' => false,
                'message' => 'No active clearance request found.',
                'steps' => [],
                'notifications' => [],
                'overallStatus' => 'none'
            ]);
        }

        $requestId = $latestRequest['id'];
        $student = $userModel->find($userId);
        $items = $itemModel->getItemsForRequest($requestId);
        $depts = array_column($deptModel->getActive(), null, 'id');
        $recentNotifs = $notifModel->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')->findAll(5);

        // Hardcoded step order matching dashboard
        $stepOrder = [
            'Submitted' => 'done', // Always done if request exists
            'Registrar' => 'pending',
            'Library' => 'pending',
            'Finance / Cashier' => 'pending',
            'Academic Affairs' => 'pending',
            'Student Affairs' => 'pending'
        ];

        // Map item statuses
        foreach ($items as $item) {
            $deptName = $depts[$item['department_id']]['name'] ?? $item['dept_name'];
            $statusClass = $item['status'] === 'approved' ? 'done' : ($item['status'] === 'pending' ? 'pending' : 'warn');
            
            // Match dashboard labels
            $displayName = match(true) {
                str_contains($deptName, 'Registrar') => 'Registrar',
                str_contains($deptName, 'Library') => 'Library',
                str_contains($deptName, 'Finance') || str_contains($deptName, 'Cashier') => 'Finance / Cashier',
                str_contains($deptName, 'Academic') || str_contains($deptName, 'Dean') => 'Academic Affairs',
                str_contains($deptName, 'Student') => 'Student Affairs',
                default => $deptName
            };
            
            if (isset($stepOrder[$displayName])) {
                $stepOrder[$displayName] = $statusClass;
            }
        }

        // Determine active step and overall
        $stepKeys = array_keys($stepOrder);
        $activeIndex = 0;
        $overallStatus = 'in_progress';
        for ($i = 0; $i < count($stepKeys); $i++) {
            if ($stepOrder[$stepKeys[$i]] === 'pending') {
                $activeIndex = $i;
                break;
            }
        }

        if (array_search('rejected', array_column($items, 'status')) !== false) {
            $overallStatus = 'rejected';
        } elseif (count(array_filter(array_values($stepOrder), fn($s) => $s === 'done')) === count($stepOrder)) {
            $overallStatus = 'approved';
        }

        $stepsData = [
            'order' => $stepOrder,
            'activeIndex' => $activeIndex,
            'overallStatus' => $overallStatus
        ];

        return $this->respond([
            'status' => 200,
            'hasRequest' => true,
            'request' => $latestRequest,
            'student' => $student,
            'steps' => $stepsData,
            'items' => $items,
            'notifications' => $recentNotifs
        ]);
    }

    public function show($id = null)
    {
        $requestModel = new ClearanceRequestModel();
        $itemModel    = new ClearanceItemModel();

        $request = $requestModel->getWithStudent($id);
        if (!$request) {
            return $this->failNotFound('Clearance request not found.');
        }
        $items = $itemModel->getItemsForRequest($id);

        return $this->respond([
            'status'  => 200,
            'request' => $request,
            'items'   => $items,
        ]);
    }

    public function stats()
    {
        $requestModel = new ClearanceRequestModel();
        $db           = \Config\Database::connect();

        $data = [
            'total'       => $requestModel->countAll(),
            'pending'     => $requestModel->where('overall_status','pending')->countAllResults(),
            'in_progress' => $requestModel->where('overall_status','in_progress')->countAllResults(),
            'approved'    => $requestModel->where('overall_status','approved')->countAllResults(),
            'rejected'    => $requestModel->where('overall_status','rejected')->countAllResults(),
        ];

        return $this->respond(['status' => 200, 'stats' => $data]);
    }
}