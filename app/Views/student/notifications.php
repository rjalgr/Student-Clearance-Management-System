<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <!--  -->
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/student/dashboard">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Notification</li>
    </ol>
  </nav>
  
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center mb-4">
                <h1 class="h3 mb-0"><i class="bi bi-bell me-2"></i>Notifications</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Notifications</h5>
                    <span class="badge bg-info"><?= count($notifications) ?> total</span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($notifications)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-bell-slash display-4 opacity-25 mb-3"></i>
                            <h5>No notifications yet</h5>
                            <p>You'll receive notifications for clearance updates and important announcements.</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($notifications as $notif): ?>
                                <div class="list-group-item d-flex align-items-start p-3 border-end-0 border-start-0">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-envelope <?= $notif['is_read'] ? 'text-muted' : 'text-primary' ?>" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <h6 class="mb-1 <?= $notif['is_read'] ? '' : 'fw-bold' ?>"><?= esc($notif['title']) ?></h6>
                                            <small title="<?= date('M d, Y H:i', strtotime($notif['created_at'])) ?>"><?= date('M d', strtotime($notif['created_at'])) ?></small>
                                        </div>
                                        <p class="mb-1 text-muted small"><?= esc($notif['message']) ?></p>
<?php if (isset($notif['url']) && $notif['url']): ?>
    <a href="<?= esc($notif['url']) ?>" class="btn btn-sm btn-outline-primary">View Details</a>
<?php endif; ?>

                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer text-center text-muted small py-2">
                    Showing recent 20 notifications
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

