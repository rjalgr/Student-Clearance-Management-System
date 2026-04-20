<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($title ?? 'My Clearance') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">
      <i class="bi bi-mortarboard-fill me-2"></i>SCMS
    </a>
    <div class="d-flex align-items-center gap-3">
      <span class="text-light small">
        <i class="bi bi-person-circle me-1"></i>
        <?= esc(session()->get('fullName')) ?>
      </span>
      <a href="/logout" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </div>
</nav>

<div class="container py-4">

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/student/dashboard">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">My Clearance Requests</li>
    </ol>
  </nav>

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

  <div class="row g-4">

    <!-- LEFT: Submit New Request -->
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-primary text-white fw-semibold">
          <i class="bi bi-plus-circle me-2"></i>New Clearance Request
        </div>
        <div class="card-body">
          <form method="POST" action="/student/clearance/submit">
            <?= csrf_field() ?>

            <div class="mb-3">
              <label class="form-label">Academic Year <span class="text-danger">*</span></label>
              <input type="text"
                     name="academic_year"
                     class="form-control"
                     value="<?= old('academic_year', date('Y') . '-' . (date('Y') + 1)) ?>"
                     placeholder="e.g. 2024-2025"
                     required>
            </div>

            <div class="mb-3">
              <label class="form-label">Semester <span class="text-danger">*</span></label>
              <select name="semester" class="form-select" required>
                <option value="">-- Select Semester --</option>
                <option value="1st"    <?= old('semester') === '1st'    ? 'selected' : '' ?>>1st Semester</option>
                <option value="2nd"    <?= old('semester') === '2nd'    ? 'selected' : '' ?>>2nd Semester</option>
                <option value="Summer" <?= old('semester') === 'Summer' ? 'selected' : '' ?>>Summer</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Purpose <span class="text-danger">*</span></label>
              <textarea name="purpose"
                        class="form-control"
                        rows="3"
                        placeholder="e.g. For graduation, transfer of records, etc."
                        required><?= old('purpose') ?></textarea>
              <div class="form-text">Min. 5 characters, max 200.</div>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-send-fill me-1"></i> Submit Request
              </button>
            </div>
          </form>
        </div>

        <!-- Info box -->
        <div class="card-footer bg-light small text-muted">
          <i class="bi bi-info-circle me-1"></i>
          A clearance request will be sent to all departments for approval.
          You will be notified via email once processed.
        </div>
      </div>
    </div>

    <!-- RIGHT: My Requests Table -->
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
          <span><i class="bi bi-list-check me-2"></i>My Clearance Requests</span>
          <span class="badge bg-secondary"><?= count($requests) ?> record(s)</span>
        </div>
        <div class="card-body p-0">

          <?php if (empty($requests)): ?>
            <div class="text-center py-5 text-muted">
              <i class="bi bi-inbox display-4 d-block mb-2"></i>
              No clearance requests yet.<br>
              <small>Submit your first request using the form on the left.</small>
            </div>

          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>#</th>
                    <th>Academic Year</th>
                    <th>Semester</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Date Filed</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($requests as $i => $req): ?>
                    <?php
                      $badgeClass = match($req['overall_status']) {
                          'approved'    => 'success',
                          'rejected'    => 'danger',
                          'in_progress' => 'info',
                          default       => 'warning',
                      };
                      $badgeIcon = match($req['overall_status']) {
                          'approved'    => 'bi-check-circle-fill',
                          'rejected'    => 'bi-x-circle-fill',
                          'in_progress' => 'bi-arrow-repeat',
                          default       => 'bi-hourglass-split',
                      };
                    ?>
                    <tr>
                      <td class="text-muted small"><?= $i + 1 ?></td>
                      <td><?= esc($req['academic_year']) ?></td>
                      <td><?= esc($req['semester']) ?></td>
                      <td>
                        <span title="<?= esc($req['purpose']) ?>">
                          <?= esc(strlen($req['purpose']) > 30
                              ? substr($req['purpose'], 0, 30) . '...'
                              : $req['purpose']) ?>
                        </span>
                      </td>
                      <td>
                        <span class="badge bg-<?= $badgeClass ?>">
                          <i class="bi <?= $badgeIcon ?> me-1"></i>
                          <?= ucfirst(str_replace('_', ' ', $req['overall_status'])) ?>
                        </span>
                      </td>
                      <td class="small text-muted">
                        <?= date('M d, Y', strtotime($req['created_at'])) ?>
                      </td>
                      <td class="text-center">
                        <a href="/student/clearance/track/<?= $req['id'] ?>"
                           class="btn btn-sm btn-outline-primary"
                           title="Track this request">
                          <i class="bi bi-eye"></i> Track
                        </a>
                        <?php if ($req['overall_status'] === 'approved'): ?>
                          <a href="/student/clearance/download/<?= $req['id'] ?>"
                             class="btn btn-sm btn-success ms-1"
                             title="Download clearance slip">
                            <i class="bi bi-download"></i>
                          </a>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <?php if (isset($pager)): ?>
              <div class="d-flex justify-content-center py-3">
                <?= $pager->links() ?>
              </div>
            <?php endif; ?>

          <?php endif; ?>
        </div>
      </div>
    </div>

  </div><!-- end row -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>