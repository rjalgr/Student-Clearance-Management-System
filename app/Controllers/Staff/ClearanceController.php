<?php
namespace App\Controllers\Staff;
use App\Controllers\BaseController;
use App\Models\ClearanceRequestModel;
use App\Models\ClearanceItemModel;
use App\Models\DepartmentModel;
use App\Models\NotificationModel;
use App\Models\UserModel;
use App\Libraries\EmailNotifier;

class ClearanceController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $deptModel = new DepartmentModel();
        $itemModel = new ClearanceItemModel();

        $staffUser = $userModel->find(session()->get('userId'));
        $dept      = $deptModel->where('name', $staffUser['department'])->first();

        if (!$dept) {
            return redirect()->back()->with('error', 'Your account is not linked to any department. Please contact admin.');
        }

        $search  = $this->request->getGet('search');
        $perPage = 15;
        $page    = (int)($this->request->getGet('page') ?? 1);

        $builder = \Config\Database::connect()
            ->table('clearance_items ci')
            ->select('ci.*, cr.academic_year, cr.semester, cr.overall_status,
                      u.full_name, u.student_id AS sid')
            ->join('clearance_requests cr', 'cr.id = ci.request_id')
            ->join('users u', 'u.id = cr.student_id')
            ->where('ci.department_id', $dept['id'])
            ->orderBy('cr.created_at', 'DESC');

        if ($search) {
            $builder->groupStart()
                ->like('u.full_name', $search)
                ->orLike('u.student_id', $search)
                ->groupEnd();
        }

        $total = $builder->countAllResults(false);
        $items = $builder->limit($perPage, ($page - 1) * $perPage)
                         ->get()->getResultArray();

        return view('staff/clearance/index', [
            'title'    => 'Clearance Requests',
            'items'    => $items,
            'deptName' => $dept['name'],
            'search'   => $search,
            'total'    => $total,
            'page'     => $page,
            'perPage'  => $perPage,
        ]);
    }

    public function review(int $itemId)
    {
        $rules = [
            'status'  => 'required|in_list[approved,rejected]',
            'remarks' => 'permit_empty|max_length[500]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $itemModel    = new ClearanceItemModel();
        $requestModel = new ClearanceRequestModel();
        $deptModel    = new DepartmentModel();
        $notifModel   = new NotificationModel();
        $userModel    = new UserModel();

        $item    = $itemModel->find($itemId);
        $status  = $this->request->getPost('status');
        $remarks = $this->request->getPost('remarks');

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        // Handle optional file upload
        $attachment = $item['attachment'];
        $file       = $this->request->getFile('attachment');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowed = ['pdf', 'png', 'jpg', 'jpeg'];
            if (!in_array(strtolower($file->getExtension()), $allowed)) {
                return redirect()->back()->with('error', 'Invalid file type.');
            }
            if ($file->getSizeByUnit('mb') > 5) {
                return redirect()->back()->with('error', 'File too large. Max 5MB.');
            }
            $newName    = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/clearance', $newName);
            $attachment = $newName;
        }

        // Update the item
        $updatedItem = $itemModel->update($itemId, [
            'status'      => $status,
            'remarks'     => $remarks,
            'staff_id'    => session()->get('userId'),
            'reviewed_at' => date('Y-m-d H:i:s'),
            'attachment'  => $attachment,
        ]);

        if ($updatedItem) {
            // Send per-item notification
            $dept = $deptModel->find($item['department_id']);
            $request = $requestModel->find($item['request_id']);
            $notifTitle = ucwords($status) . ' - ' . ($dept['name'] ?? 'Department');
            $notifMsg = "Your clearance for " . ($dept['name'] ?? 'department') . " has been {$status}.";
            if ($remarks) $notifMsg .= " Remarks: " . $remarks;
            
            $notifModel->sendTo($request['student_id'], $notifTitle, $notifMsg, $status);
        }

        // Check if ALL items in the request are reviewed
        $this->updateOverallStatus(
            $item['request_id'],
            $requestModel,
            $itemModel,
            $notifModel,
            $userModel
        );

        return redirect()->to('/staff/clearance')
                         ->with('success', 'Clearance item marked as ' . strtoupper($status) . '.');
    }

    private function updateOverallStatus(
        int $requestId,
        ClearanceRequestModel $requestModel,
        ClearanceItemModel $itemModel,
        NotificationModel $notifModel,
        UserModel $userModel
    ): void {
        $allItems = $itemModel->where('request_id', $requestId)->findAll();
        $statuses = array_column($allItems, 'status');

        $hasRejected = in_array('rejected', $statuses);
        $hasPending  = in_array('pending', $statuses);

        if ($hasRejected) {
            $overall = 'rejected';
        } elseif (!$hasPending) {
            $overall = 'approved';
        } else {
            $overall = 'in_progress';
        }

        $requestModel->update($requestId, ['overall_status' => $overall]);

        // Notify student only when final decision is made
        if (in_array($overall, ['approved', 'rejected'])) {
            $request = $requestModel->find($requestId);
            $student = $userModel->find($request['student_id']);

            $msg = $overall === 'approved'
                ? 'Congratulations! Your clearance has been fully APPROVED. You may now download your clearance slip.'
                : 'Your clearance request was REJECTED by one or more departments. Please check the remarks.';

            $notifModel->sendTo(
                $request['student_id'],
                'Clearance ' . ucfirst($overall),
                $msg,
                $overall === 'approved' ? 'success' : 'danger'
            );

            // Send email
            try {
                $notifier = new EmailNotifier();
                $notifier->sendStatusUpdate(
                    $student['email'],
                    $student['full_name'],
                    $overall
                );
            } catch (\Exception $e) {
                log_message('error', 'Status update email failed: ' . $e->getMessage());
            }
        }
    }
}