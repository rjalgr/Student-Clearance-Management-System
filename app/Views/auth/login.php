<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Login | SCMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<script>
function togglePassword(fieldId) {
  const field = document.getElementById(fieldId);
  const icon  = document.getElementById('icon_' + fieldId);
  if (field.type === 'password') {
    field.type     = 'text';
    icon.className = 'bi bi-eye-slash';
  } else {
    field.type     = 'password';
    icon.className = 'bi bi-eye';
  }
}
</script>
<body class="bg-light d-flex align-items-center" style="min-height:100vh">
<div class="container" style="max-width:420px">
  <div class="card shadow-sm">
    <div class="card-body p-4">
      <h4 class="text-center mb-1 text-primary fw-bold">
        <i class="bi bi-mortarboard-fill"></i> SCMS
      </h4>
      <p class="text-center text-muted small mb-4">Student Clearance Management System</p>

      <?php if ($error = session()->getFlashdata('error')): ?>
        <div class="alert alert-danger small"><?= esc($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="/login">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">Email address</label>
          <input type="email" name="email" class="form-control"
                 value="<?= set_value('email') ?>" required autofocus>
        </div>
       <div class="mb-3">
  <label class="form-label">Password</label>
  <div class="input-group">
    <input type="password" 
           name="password" 
           id="password" 
           class="form-control" 
           placeholder="*******"
           required>
    <button type="button" 
            class="btn btn-outline-secondary"
            onclick="togglePassword('password')">
      <i class="bi bi-eye" id="icon_password"></i>
    </button>
  </div>
</div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Login</button>
        </div>
      </form>
      <hr>
      <p class="text-center small">No account? <a href="/register">Register here</a></p>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>