<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $userId = session()->get('userId');
        
        $requestModel = model(\App\Models\ClearanceRequestModel::class);
        $itemModel = model(\App\Models\ClearanceItemModel::class);
        $deptModel = model(\App\Models\DepartmentModel::class);
        $notifModel = model(\App\Models\NotificationModel::class);
        $userModel = model(\App\Models\UserModel::class);

        // Same logic as API progress()
        $latestRequest = $requestModel->where('student_id', $userId)
            ->where('overall_status !=', 'approved')
            ->orderBy('created_at', 'DESC')
            ->first();

        $clearanceData = ['hasRequest' => false];
        $recentNotifications = [];
        
        if ($latestRequest) {
            $requestId = $latestRequest['id'];
            $student = $userModel->find($userId);
            $items = $itemModel->getItemsForRequest($requestId);
            $depts = array_column($deptModel->getActive(), null, 'id');
            $recentNotifs = $notifModel->where('user_id', $userId)
                ->orderBy('created_at', 'DESC')->findAll(5);

            // Step order
            $stepOrder = [
                'Submitted' => 'done',
                'Registrar' => 'pending',
                'Library' => 'pending',
                'Finance / Cashier' => 'pending',
                'Academic Affairs' => 'pending',
                'Student Affairs' => 'pending'
            ];

            foreach ($items as $item) {
                $deptName = $depts[$item['department_id']]['name'] ?? $item['dept_name'];
                $statusClass = $item['status'] === 'approved' ? 'done' : ($item['status'] === 'pending' ? 'pending' : 'warn');
                
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

            $stepKeys = array_keys($stepOrder);
            $activeIndex = 0;
            $overallStatus = 'in_progress';
            for ($i = 0; $i < count($stepKeys); $i++) {
                if ($stepOrder[$stepKeys[$i]] === 'pending') {
                    $activeIndex = $i;
                    break;
                }
            }

            // Overall logic
            $hasRejected = false;
            foreach ($items as $item) {
                if ($item['status'] === 'rejected') {
                    $hasRejected = true;
                    break;
                }
            }
            if ($hasRejected) {
                $overallStatus = 'rejected';
            } elseif (count(array_filter($stepOrder, fn($s) => $s === 'done')) === count($stepOrder)) {
                $overallStatus = 'approved';
            }

            $clearanceData = [
                'hasRequest' => true,
                'request' => $latestRequest,
                'stepOrder' => $stepOrder,
                'activeIndex' => $activeIndex,
                'overallStatus' => $overallStatus,
                'items' => $items
            ];
            $recentNotifications = $recentNotifs;
        }

        $data = [
            'title' => 'Student Dashboard | SCMS',
            'clearanceData' => $clearanceData,
            'recentNotifications' => $recentNotifications
        ];
        return view('student/dashboard', $data);
    }
}

