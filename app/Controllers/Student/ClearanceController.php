<?php
namespace App\Controllers\Student;
use App\Controllers\BaseController;
use App\Models\ClearanceRequestModel;
use App\Models\ClearanceItemModel;
use App\Models\DepartmentModel;
use App\Models\NotificationModel;
use App\Libraries\EmailNotifier;

class ClearanceController extends BaseController
{
    protected ClearanceRequestModel $requestModel;
    protected ClearanceItemModel    $itemModel;
    protected DepartmentModel       $deptModel;
    protected NotificationModel     $notifModel;

    public function __construct()
    {
        $this->requestModel = new ClearanceRequestModel();
        $this->itemModel    = new ClearanceItemModel();
        $this->deptModel    = new DepartmentModel();
        $this->notifModel   = new NotificationModel();
    }

    // ── List all clearance requests of the student ──────────────
    public function index()
    {
        $userId = session()->get('userId');

        $requests = $this->requestModel
            ->where('student_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('student/clearance/index', [
            'title'    => 'My Clearance Requests',
            'requests' => $requests,
            'pager'    => $this->requestModel->pager,
        ]);
    }

    // ── Track a specific clearance request ──────────────────────
    public function track($id = null)
    {
        $userId = session()->get('userId');

        if (!$id) {
            return redirect()->to('/student/clearance');
        }

        $request = $this->requestModel->getWithStudent((int)$id);

        if (!$request || $request['student_id'] != $userId) {
            return redirect()->to('/student/clearance')
                             ->with('error', 'Clearance request not found.');
        }

        $items = $this->itemModel->getItemsForRequest((int)$id);

        return view('student/clearance/track', [
            'title'   => 'Track Clearance #' . $id,
            'request' => $request,
            'items'   => $items,
        ]);
    }

    // ── Download / View clearance slip ──────────────────────────
    public function download(int $id)
    {
        $request = $this->requestModel->getWithStudent($id);

        // Security: must belong to logged-in student
        if (!$request || $request['student_id'] != session()->get('userId')) {
            return redirect()->to('/student/clearance')
                             ->with('error', 'Clearance request not found.');
        }

        // Only allow if fully approved
        if ($request['overall_status'] !== 'approved') {
            return redirect()->to('/student/clearance')
                             ->with('error', 'Your clearance is not yet fully approved.');
        }

        $items = $this->itemModel->getItemsForRequest($id);

        // Render directly in browser — student uses Ctrl+P → Save as PDF
        return view('student/clearance/pdf_template', [
            'request' => $request,
            'items'   => $items,
        ]);
    }

    // ── Submit a new clearance request ──────────────────────────
    public function submit()
    {
        $rules = [
            'academic_year' => 'required|max_length[20]',
            'semester'      => 'required|in_list[1st,2nd,Summer]',
            'purpose'       => 'required|min_length[5]|max_length[200]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $studentId = session()->get('userId');

        // Check for existing active/pending request
        $existing = $this->requestModel
            ->where('student_id', $studentId)
            ->whereIn('overall_status', ['pending', 'in_progress'])
            ->first();

        if ($existing) {
            return redirect()->back()
                             ->with('error', 'You already have an active clearance request. Please wait for it to be completed before submitting a new one.');
        }

        // Insert the clearance request
        $this->requestModel->insert([
            'student_id'     => $studentId,
            'academic_year'  => $this->request->getPost('academic_year'),
            'semester'       => $this->request->getPost('semester'),
            'purpose'        => $this->request->getPost('purpose'),
            'overall_status' => 'pending',
        ]);

        $requestId = $this->requestModel->getInsertID();

        if (!$requestId) {
            return redirect()->back()
                             ->with('error', 'Submission failed. Could not create request. Please try again.');
        }

        // Get all active departments
        $departments = $this->deptModel->getActive();

        if (empty($departments)) {
            $this->requestModel->delete($requestId);
            return redirect()->back()
                             ->with('error', 'Submission failed. No departments are configured yet. Please contact the administrator.');
        }

        // Create one clearance item per active department
        foreach ($departments as $dept) {
            $this->itemModel->insert([
                'request_id'    => $requestId,
                'department_id' => $dept['id'],
                'status'        => 'pending',
            ]);
        }

        // Send email notification
        try {
            $notifier = new EmailNotifier();
            $notifier->sendClearanceSubmitted(
                session()->get('email'),
                session()->get('fullName')
            );
        } catch (\Exception $e) {
            log_message('error', 'Clearance submission email failed: ' . $e->getMessage());
        }

        // In-app notification
        $this->notifModel->sendTo(
            $studentId,
            'Clearance Submitted',
            'Your clearance request has been submitted successfully. Please wait for each department to review it.',
            'success'
        );

        return redirect()->to('/student/clearance')
                         ->with('success', 'Clearance request submitted successfully! You will be notified once departments review your request.');
    }
}