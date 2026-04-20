<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    public function index()
    {
        $userId = session()->get('userId');
        $notifications = $this->notificationModel
            ->where('user_id', $userId)
            ->orWhere('user_id IS NULL') // System notifications
            ->orderBy('created_at', 'DESC')
            ->findAll(20);

        // Mark as read
$this->notificationModel->markAllRead($userId);

        return view('student/notifications', [
            'title' => 'Notifications | SCMS',
            'notifications' => $notifications
        ]);
    }
}

