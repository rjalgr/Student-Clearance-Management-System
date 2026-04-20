<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($title ?? 'SCMS') ?></title>
<!-- CSRF meta tag for AJAX -->
<meta name="csrf-token" content="<?= csrf_token() ?>">
<meta name="csrf-hash"  content="<?= csrf_hash() ?>">
<link rel="icon" type="image/png" href="<?= base_url('public/grad_hat.png')?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
<link href="/public/css/app.css" rel="stylesheet">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">
      <i class="bi bi-mortarboard-fill me-2"></i>SCMS
    </a>
    <div class="d-flex align-items-center gap-3">
      <!-- Notification Bell -->
      <div class="dropdown">
        <button class="btn btn-outline-light btn-sm position-relative" data-bs-toggle="dropdown">
          <i class="bi bi-bell"></i>
          <span id="notif-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none"></span>
        </button>
        <div class="dropdown-menu dropdown-menu-end" id="notif-list" style="min-width:320px">
          <h6 class="dropdown-header">Notifications</h6>
          <div id="notif-items"><p class="dropdown-item text-muted small">Loading...</p></div>
        </div>
      </div>
      <span class="text-light small"><?= esc(session()->get('fullName')) ?></span>
      <a href="/logout" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </div>
</nav>

<div class="container-fluid mt-3">
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <?= session()->getFlashdata('success') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
      <?= session()->getFlashdata('error') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  <?php if ($errors = session()->getFlashdata('errors')): ?>
    <div class="alert alert-warning">
      <ul class="mb-0">
        <?php foreach ($errors as $e): ?>
          <li><?= esc($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?= $this->renderSection('content') ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Fetch notifications via fetch API
async function loadNotifications() {
  const res  = await fetch('/api/v1/notifications', {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  });
  // Simple demo — in production call an internal endpoint
}
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>