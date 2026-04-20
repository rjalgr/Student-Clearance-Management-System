<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff Dashboard | SCMS</title>
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
      <span class="text-light small">
        <i class="bi bi-person-circle me-1"></i><?= esc(session()->get('fullName')) ?>
      </span>
      <span class="badge bg-warning text-dark">Staff</span>
      <a href="/logout" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <h5 class="fw-bold mb-4">
    <i class="bi bi-speedometer2 me-2"></i>Staff Dashboard
  </h5>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card border-0 shadow-sm text-center p-3">
        <div class="fs-2 fw-bold text-warning"><?= $pendingCount ?></div>
        <div class="text-muted small">Pending Reviews</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm text-center p-3">
        <div class="fs-2 fw-bold text-success"><?= $approvedCount ?></div>
        <div class="text-muted small">Approved</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm text-center p-3">
        <div class="fs-2 fw-bold text-danger"><?= $rejectedCount ?></div>
        <div class="text-muted small">Rejected</div>
      </div>
    </div>
  </div>

  <a href="/staff/clearance" class="btn btn-primary">
    <i class="bi bi-card-checklist me-1"></i>View Clearance Requests
  </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>