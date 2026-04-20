<?php

namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailNotifier
{
    protected PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer                 = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host           = env('email.SMTPHost', 'smtp.gmail.com');
        $this->mailer->SMTPAuth       = true;
        $this->mailer->Username       = env('email.SMTPUser');
        $this->mailer->Password       = env('email.SMTPPass');
        $this->mailer->SMTPSecure     = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port           = (int) env('email.SMTPPort', 587);
        $this->mailer->SMTPDebug      = 0; 
        $this->mailer->CharSet        = 'UTF-8';
        $this->mailer->isHTML(true);
        $this->mailer->setFrom(
            env('email.fromEmail', env('email.SMTPUser')),
            env('email.fromName', 'SCMS-System')
    );
    }

    // ── Clearance Submitted ──────────────────────────────
    public function sendClearanceSubmitted(string $to, string $name): void
    {
        $this->mailer->clearAddresses();
        $this->mailer->addAddress($to, $name);
        $this->mailer->Subject = 'Clearance Request Submitted — SCMS';
        $this->mailer->Body    = $this->template(
            'Your clearance request submitted successfully!',
            $name,
            'Your clearance request has been <strong>successfully submitted</strong>.',
            'Our departments will review your request. You will receive an email once your clearance is processed.',
            '#1a237e'
        );
        $this->mailer->AltBody = "Hello {$name}, your clearance request has been submitted successfully.";
        $this->mailer->send();
    }

    // ── Status Update ────────────────────────────────────
    public function sendStatusUpdate(string $to, string $name, string $status): void
    {
        $isApproved = $status === 'approved';
        $emoji      = $isApproved ? '✅' : '❌';
        $color      = $isApproved ? '#198754' : '#dc3545';
        $label      = strtoupper($status);

        $this->mailer->clearAddresses();
        $this->mailer->addAddress($to, $name);
        $this->mailer->Subject = "{$emoji} Clearance {$label} — SCMS";
        $this->mailer->Body    = $this->template(
            "{$emoji} Clearance {$label}",
            $name,
            "Your clearance request has been <strong style='color:{$color}'>{$label}</strong>.",
            $isApproved
                ? 'You may now log in to SCMS and download your clearance slip.'
                : 'Please log in to SCMS to check which department rejected your request and the reason.',
            $color
        );
        $this->mailer->AltBody = "Hello {$name}, your clearance has been {$status}.";
        $this->mailer->send();
    }

    // ── Welcome / Registration ───────────────────────────
    public function sendWelcome(string $to, string $name): void
    {
        $this->mailer->clearAddresses();
        $this->mailer->addAddress($to, $name);
        $this->mailer->Subject = 'Welcome to SCMS!';
        $this->mailer->Body    = $this->template(
            '🎓 Welcome to SCMS!',
            $name,
            'Your account has been <strong>successfully created</strong>.',
            'You can now log in to the Student Clearance Management System and submit your clearance requests.',
            '#1a237e'
        );
        $this->mailer->AltBody = "Hello {$name}, welcome to SCMS! Your account has been created.";
        $this->mailer->send();
    }

    // ── Password Changed ─────────────────────────────────
    public function sendPasswordChanged(string $to, string $name): void
    {
        $this->mailer->clearAddresses();
        $this->mailer->addAddress($to, $name);
        $this->mailer->Subject = 'Password Changed — SCMS';
        $this->mailer->Body    = $this->template(
            'Your password changed',
            $name,
            'Your SCMS account password has been <strong>successfully changed</strong>.',
            'If you did not make this change, please contact the administrator immediately.',
            '#fd7e14'
        );
        $this->mailer->AltBody = "Hello {$name}, your SCMS password has been changed.";
        $this->mailer->send();
    }

    // ── HTML Email Template ──────────────────────────────
    private function template(
        string $heading,
        string $name,
        string $mainMessage,
        string $subMessage,
        string $headerColor = '#1a237e'
    ): string {
        $year    = date('Y');
        $baseUrl = env('app.baseURL', 'http://localhost:8080');

        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body style="margin:0;padding:0;background:#f4f6fb;font-family:Arial,sans-serif;">
          <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6fb;padding:30px 0;">
            <tr>
              <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                       style="background:#ffffff;border-radius:12px;overflow:hidden;
                              box-shadow:0 4px 20px rgba(0,0,0,0.08);max-width:600px;width:100%;">

                  <!-- Header -->
                  <tr>
                    <td style="background:{$headerColor};padding:30px 40px;text-align:center;">
                      <h1 style="color:#ffffff;margin:0;font-size:22px;font-weight:700;">
                        SCMS
                      </h1>
                      <p style="color:rgba(255,255,255,0.85);margin:6px 0 0;font-size:13px;">
                        Student Clearance Management System
                      </p>
                    </td>
                  </tr>

                  <!-- Body -->
                  <tr>
                    <td style="padding:36px 40px;">
                      <h2 style="color:#1a237e;margin:0 0 16px;font-size:20px;">{$heading}</h2>
                      <p style="color:#444;font-size:15px;margin:0 0 12px;">
                        Hello, <strong>{$name}</strong>!
                      </p>
                      <p style="color:#444;font-size:15px;margin:0 0 12px;">
                        {$mainMessage}
                      </p>
                      <p style="color:#666;font-size:14px;margin:0 0 24px;">
                        {$subMessage}
                      </p>

                      <!-- CTA Button -->
                      <div style="text-align:center;margin:24px 0;">
                        <a href="{$baseUrl}/login"
                           style="background:{$headerColor};color:#ffffff;padding:12px 32px;
                                  border-radius:8px;text-decoration:none;font-size:15px;
                                  font-weight:600;display:inline-block;">
                          Login to SCMS
                        </a>
                      </div>

                      <hr style="border:none;border-top:1px solid #eee;margin:24px 0;">
                      <p style="color:#999;font-size:12px;text-align:center;margin:0;">
                        This is an automated email from SCMS. Please do not reply.
                      </p>
                    </td>
                  </tr>

                  <!-- Footer -->
                  <tr>
                    <td style="background:#f8f9fa;padding:16px 40px;text-align:center;
                               border-top:1px solid #eee;">
                      <p style="color:#aaa;font-size:12px;margin:0;">
                        &copy; {$year} Student Clearance Management System. All rights reserved.
                      </p>
                    </td>
                  </tr>

                </table>
              </td>
            </tr>
          </table>
        </body>
        </html>
        HTML;
    }
}