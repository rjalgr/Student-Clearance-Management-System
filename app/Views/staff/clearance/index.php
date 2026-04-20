<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clearance Requests | Staff</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold">
      <i class="bi bi-mortarboard-fill me-2"></i>SCMS — Staff
    </a>
    <div class="d-flex align-items-center gap-3">
      <span class="text-light small"><?= esc(session()->get('fullName')) ?></span>
      <a href="/logout" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4">

  <!-- Flash Messages -->
  <?php if ($success = session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <i class="bi bi-check-circle-fill me-2"></i><?= esc($success) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  <?php if ($error = session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
      <?= esc($error) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">
      <i class="bi bi-card-checklist me-2"></i>
      Clearance Requests — <?= esc($deptName) ?>
    </h5>
    <!-- Search -->
    <form method="GET" class="d-flex gap-2">
      <input type="text" name="search" class="form-control form-control-sm"
             placeholder="Search student..." value="<?= esc($search ?? '') ?>">
      <button type="submit" class="btn btn-primary btn-sm">Search</button>
      <?php if (!empty($search)): ?>
        <a href="/staff/clearance" class="btn btn-secondary btn-sm">Clear</a>
      <?php endif; ?>
    </form>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Student Name</th>
              <th>Student ID</th>
              <th>Semester</th>
              <th>Academic Year</th>
              <th>Overall Status</th>
              <th>My Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($items)): ?>
              <tr>
                <td colspan="8" class="text-center text-muted py-5">
                  <i class="bi bi-inbox display-6 d-block mb-2"></i>
                  No clearance requests found.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($items as $i => $item): ?>
                <?php
                  $overallBadge = match($item['overall_status']) {
                    'approved'    => 'success',
                    'rejected'    => 'danger',
                    'in_progress' => 'info',
                    default       => 'warning',
                  };
                  $myBadge = match($item['status']) {
                    'approved' => 'success',
                    'rejected' => 'danger',
                    default    => 'warning',
                  };
                ?>
                <tr>
                  <td class="text-muted small"><?= $i + 1 ?></td>
                  <td class="fw-medium"><?= esc($item['full_name']) ?></td>
                  <td class="small"><?= esc($item['sid']) ?></td>
                  <td><?= esc($item['semester']) ?></td>
                  <td><?= esc($item['academic_year']) ?></td>
                  <td>
                    <span class="badge bg-<?= $overallBadge ?>">
                      <?= ucfirst(str_replace('_', ' ', $item['overall_status'])) ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge bg-<?= $myBadge ?>">
                      <?= ucfirst($item['status']) ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <?php if ($item['status'] === 'pending'): ?>
                      <button class="btn btn-sm btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#reviewModal"
                        data-item-id="<?= $item['id'] ?>"
                        data-student="<?= esc($item['full_name']) ?>"
                        data-sid="<?= esc($item['sid']) ?>">
                        <i class="bi bi-pencil-square me-1"></i>Review
                      </button>
                    <?php else: ?>
                      <button class="btn btn-sm btn-outline-secondary"
                        data-bs-toggle="modal"
                        data-bs-target="#reviewModal"
                        data-item-id="<?= $item['id'] ?>"
                        data-student="<?= esc($item['full_name']) ?>"
                        data-sid="<?= esc($item['sid']) ?>">
                        <i class="bi bi-arrow-repeat me-1"></i>Update
                      </button>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if ($total > $perPage): ?>
        <div class="d-flex justify-content-center py-3">
          <?php
            $totalPages = ceil($total / $perPage);
            for ($p = 1; $p <= $totalPages; $p++):
          ?>
            <a href="?page=<?= $p ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
               class="btn btn-sm <?= $p == $page ? 'btn-primary' : 'btn-outline-secondary' ?> mx-1">
              <?= $p ?>
            </a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="reviewForm" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="bi bi-pencil-square me-2"></i>Review Clearance
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info small mb-3">
            <strong>Student:</strong> <span id="modal-student"></span><br>
            <strong>Student ID:</strong> <span id="modal-sid"></span>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">
              Decision <span class="text-danger">*</span>
            </label>
            <div class="d-flex gap-3">
              <div class="form-check">
                <input class="form-check-input" type="radio"
                       name="status" id="statusApprove" value="approved" required>
                <label class="form-check-label text-success fw-bold" for="statusApprove">
                  <i class="bi bi-check-circle-fill me-1"></i>Approve
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio"
                       name="status" id="statusReject" value="rejected">
                <label class="form-check-label text-danger fw-bold" for="statusReject">
                  <i class="bi bi-x-circle-fill me-1"></i>Reject
                </label>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Remarks</label>
            <textarea name="remarks" class="form-control" rows="3"
                      placeholder="Enter remarks or reason (optional for approval, required for rejection)..."></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">
              Attachment <span class="text-muted small">(optional, max 5MB)</span>
            </label>
            <input type="file" name="attachment" class="form-control"
                   accept=".pdf,.jpg,.jpeg,.png">
            <div class="form-text">Allowed: PDF, JPG, PNG</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>Submit Review
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const reviewModal = document.getElementById('reviewModal');
reviewModal.addEventListener('show.bs.modal', function(e) {
  const btn     = e.relatedTarget;
  const itemId  = btn.getAttribute('data-item-id');
  const student = btn.getAttribute('data-student');
  const sid     = btn.getAttribute('data-sid');

  document.getElementById('modal-student').textContent = student;
  document.getElementById('modal-sid').textContent     = sid;
  document.getElementById('reviewForm').action = '/staff/clearance/review/' + itemId;

  // Reset form
  document.getElementById('reviewForm').reset();
});
</script>
</body>
</html>