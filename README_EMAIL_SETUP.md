# Email Setup for SCMS

## Gmail SMTP (Recommended)

1. **Enable 2FA** on your Gmail account
2. **Generate App Password**:
   - Google Account > Security > 2-Step Verification > App passwords
   - Select 'Mail' > 'Other' > name it "SCMS"
3. **Add to scms/.env** (create if missing):
   ```
   email.SMTPHost = smtp.gmail.com
   email.SMTPPort = 587
   email.SMTPUser = yourgmail@gmail.com
   email.SMTPPass = your_app_password
   ```

## Test Email
Submit a clearance request - check Gmail inbox/spam.

## XAMPP/HG (Local)
```
email.SMTPHost = localhost
email.SMTPPort = 25
email.SMTPUser = 
email.SMTPPass = 
```

PHPMailer installed (composer.json). Restart Apache after .env changes.

**Default config in app/Config/Email.php** updated below.
