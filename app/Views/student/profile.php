<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($title ?? 'Profile Settings') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">
      <i class="bi bi-mortarboard-fill me-2"></i>SCMS
    </a>
    <div class="d-flex align-items-center gap-3">
      <span class="text-light small">
        <i class="bi bi-person-circle me-1"></i><?= esc(session()->get('fullName')) ?>
      </span>
      <a href="/logout" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </div>
</nav>

<div class="container py-4" style="max-width: 750px">

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/student/dashboard">Dashboard</a></li>
      <li class="breadcrumb-item active">Profile Settings</li>
    </ol>
  </nav>

  <h4 class="fw-bold mb-4">
    <i class="bi bi-person-circle me-2"></i>Profile Settings
  </h4>

  <!-- Flash Messages -->
  <?php if ($success = session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <i class="bi bi-check-circle-fill me-2"></i><?= esc($success) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <?php if ($error = session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
      <i class="bi bi-exclamation-circle-fill me-2"></i><?= esc($error) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <?php if ($errors = session()->getFlashdata('errors')): ?>
    <div class="alert alert-warning alert-dismissible fade show">
      <ul class="mb-0 small">
        <?php foreach ($errors as $e): ?>
          <li><?= esc($e) ?></li>
        <?php endforeach; ?>
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <form method="POST" action="/student/profile/update">
    <?= csrf_field() ?>

    <!-- Personal Information -->
    <div class="card shadow-sm mb-4">
      <div class="card-header fw-semibold bg-white">
        <i class="bi bi-person-fill me-2 text-primary"></i>Personal Information
      </div>
      <div class="card-body">
        <div class="row g-3">

          <!-- Student ID (read-only) -->
          <div class="col-md-6">
            <label class="form-label">Student ID</label>
            <input type="text"
                   class="form-control bg-light"
                   value="<?= esc($user['student_id'] ?? '') ?>"
                   readonly disabled>
            <div class="form-text">Student ID cannot be changed.</div>
          </div>

          <!-- Full Name -->
          <div class="col-md-6">
            <label class="form-label">
              Full Name <span class="text-danger">*</span>
            </label>
            <input type="text"
                   name="full_name"
                   class="form-control"
                   value="<?= esc(old('full_name', $user['full_name'])) ?>"
                   required>
          </div>

          <!-- Email -->
          <div class="col-md-6">
            <label class="form-label">
              Email Address <span class="text-danger">*</span>
            </label>
            <input type="email"
                   name="email"
                   class="form-control"
                   value="<?= esc(old('email', $user['email'])) ?>"
                   required>
          </div>

          <!-- Role (read-only) -->
          <div class="col-md-6">
            <label class="form-label">Role</label>
            <input type="text"
                   class="form-control bg-light"
                   value="<?= ucfirst(esc($user['role'])) ?>"
                   readonly disabled>
          </div>

          <!-- Department -->
          <div class="col-md-6">
            <label class="form-label">Department</label>
            <input type="text"
                   name="department"
                   class="form-control"
                   value="<?= esc(old('department', $user['department'] ?? '')) ?>"
                   placeholder="e.g. College of Engineering">
          </div>

          <!-- Course -->
          <div class="col-md-6">
            <label class="form-label">Course</label>
            <input type="text"
                   name="course"
                   class="form-control"
                   value="<?= esc(old('course', $user['course'] ?? '')) ?>"
                   placeholder="e.g. BSIT">
          </div>

          <!-- Year Level -->
          <div class="col-md-6">
            <label class="form-label">Year Level</label>
            <select name="year_level" class="form-select">
              <option value="">— Select —</option>
              <option value="1" <?= old('year_level', $user['year_level']) == 1 ? 'selected' : '' ?>>1st Year</option>
              <option value="2" <?= old('year_level', $user['year_level']) == 2 ? 'selected' : '' ?>>2nd Year</option>
              <option value="3" <?= old('year_level', $user['year_level']) == 3 ? 'selected' : '' ?>>3rd Year</option>
              <option value="4" <?= old('year_level', $user['year_level']) == 4 ? 'selected' : '' ?>>4th Year</option>
            </select>
          </div>

        </div>
      </div>
    </div>

    <!-- Change Password -->
    <div class="card shadow-sm mb-4">
      <div class="card-header fw-semibold bg-white">
        <i class="bi bi-shield-lock-fill me-2 text-warning"></i>Change Password
        <span class="text-muted small fw-normal ms-2">(Leave blank to keep current password)</span>
      </div>
      <div class="card-body">
        <div class="row g-3">

          <!-- Current Password -->
          <div class="col-md-4">
            <label class="form-label">Current Password</label>
            <div class="input-group">
              <input type="password"
                     name="current_password"
                     id="current_password"
                     class="form-control"
                     placeholder="Enter current password">
              <button type="button" class="btn btn-outline-secondary"
                      onclick="togglePassword('current_password')">
                <i class="bi bi-eye" id="icon_current_password"></i>
              </button>
            </div>
          </div>

          <!-- New Password -->
          <div class="col-md-4">
            <label class="form-label">New Password</label>
            <div class="input-group">
              <input type="password"
                     name="new_password"
                     id="new_password"
                     class="form-control"
                     placeholder="Min. 8 characters">
              <button type="button" class="btn btn-outline-secondary"
                      onclick="togglePassword('new_password')">
                <i class="bi bi-eye" id="icon_new_password"></i>
              </button>
            </div>
          </div>

          <!-- Confirm Password -->
          <div class="col-md-4">
            <label class="form-label">Confirm New Password</label>
            <div class="input-group">
              <input type="password"
                     name="confirm_password"
                     id="confirm_password"
                     class="form-control"
                     placeholder="Repeat new password">
              <button type="button" class="btn btn-outline-secondary"
                      onclick="togglePassword('confirm_password')">
                <i class="bi bi-eye" id="icon_confirm_password"></i>
              </button>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Submit -->
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-save me-1"></i>Save Changes
      </button>
      <a href="/student/dashboard" class="btn btn-outline-secondary px-4">
        Cancel
      </a>
    </div>

  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword(fieldId) {
  const field = document.getElementById(fieldId);
  const icon  = document.getElementById('icon_' + fieldId);
  if (field.type === 'password') {
    field.type  = 'text';
    icon.className = 'bi bi-eye-slash';
  } else {
    field.type  = 'password';
    icon.className = 'bi bi-eye';
  }
}
</script>
</body>
</html>