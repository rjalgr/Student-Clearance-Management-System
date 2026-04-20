<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Libraries\EmailNotifier;

class TestEmailController extends BaseController
{
    public function send()
    {
        try {
            $notifier = new EmailNotifier();
            $notifier->sendClearanceSubmitted('your-test@gmail.com', 'Test User');
            echo "Email sent successfully!";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

