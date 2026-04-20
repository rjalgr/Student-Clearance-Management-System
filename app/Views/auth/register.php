<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register | SCMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width: 600px">
  <div class="card shadow-sm">
    <div class="card-body p-4">

      <h4 class="text-center mb-1 text-primary fw-bold">
        <i class="bi bi-mortarboard-fill"></i> SCMS
      </h4>
      <p class="text-center text-muted small mb-4">Create a Student Account</p>

      <!-- Error messages -->
      <?php if ($errors = session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
          <ul class="mb-0 small">
            <?php foreach ($errors as $e): ?>
              <li><?= esc($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ($error = session()->getFlashdata('error')): ?>
        <div class="alert alert-danger small"><?= esc($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="/register">
        <?= csrf_field() ?>

        <div class="row g-3">

          <!-- Student ID -->
          <div class="col-md-6">
            <label class="form-label">Student ID <span class="text-danger">*</span></label>
            <input type="text"
                   name="student_id"
                   class="form-control <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['student_id'])) ? 'is-invalid' : '' ?>"
                   value="<?= old('student_id') ?>"
                   placeholder="e.g. 2024-00123"
                   required>
          </div>

          <!-- Full Name -->
          <div class="col-md-6">
            <label class="form-label">Full Name <span class="text-danger">*</span></label>
            <input type="text"
                   name="full_name"
                   class="form-control"
                   value="<?= old('full_name') ?>"
                   placeholder="Juan dela Cruz"
                   required>
          </div>

          <!-- Email -->
          <div class="col-md-6">
            <label class="form-label">Email Address <span class="text-danger">*</span></label>
            <input type="email"
                   name="email"
                   class="form-control"
                   value="<?= old('email') ?>"
                   placeholder="juan@email.com"
                   required>
          </div>

          <!-- Password -->
          <div class="col-md-6">
            <label class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password"
                   name="password"
                   class="form-control"
                   placeholder="Min. 8 characters"
                   required>
          </div>

          <!-- Department -->
          <div class="col-md-6">
            <label class="form-label">Department <span class="text-danger">*</span></label>
            <select name="department" class="form-select" required>
              <option value="">-- Select Department --</option>
              <?php if (!empty($departments)): ?>
                <?php foreach ($departments as $dept): ?>
                  <option value="<?= esc($dept['name']) ?>"
                    <?= old('department') === $dept['name'] ? 'selected' : '' ?>>
                    <?= esc($dept['name']) ?>
                  </option>
                <?php endforeach; ?>
              <?php else: ?>
                <option value="BSIT">BSIT</option>
                <option value="BSCS">BSCS</option>
                <option value="BSED">BSED</option>
                <option value="BSBA">BSBA</option>
                <option value="BSN">BSN</option>
              <?php endif; ?>
            </select>
          </div>

          <!-- Course -->
          <div class="col-md-6">
            <label class="form-label">Course <span class="text-danger">*</span></label>
            <input type="text"
                   name="course"
                   class="form-control"
                   value="<?= old('course') ?>"
                   placeholder="e.g. BSIT"
                   required>
          </div>

          <!-- Year Level -->
          <div class="col-md-6">
            <label class="form-label">Year Level</label>
            <select name="year_level" class="form-select">
              <option value="">-- Select Year --</option>
              <option value="1" <?= old('year_level') == '1' ? 'selected' : '' ?>>1st Year</option>
              <option value="2" <?= old('year_level') == '2' ? 'selected' : '' ?>>2nd Year</option>
              <option value="3" <?= old('year_level') == '3' ? 'selected' : '' ?>>3rd Year</option>
              <option value="4" <?= old('year_level') == '4' ? 'selected' : '' ?>>4th Year</option>
            </select>
          </div>

          <!-- Submit -->
          <div class="col-12 d-grid mt-2">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-person-plus-fill me-1"></i> Create Account
            </button>
          </div>

        </div><!-- end row -->
      </form>

      <hr>
      <p class="text-center small mb-0">
        Already have an account? <a href="/login">Login here</a>
      </p>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>