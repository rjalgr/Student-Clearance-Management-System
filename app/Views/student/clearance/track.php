<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-3 mb-3">
  <a href="/student/clearance" class="btn btn-outline-secondary btn-sm">← Back</a>
  <h5 class="mb-0">Clearance #<?= $request['id'] ?></h5>
  <?php
  $badge = match($request['overall_status']) {
      'approved'    => 'success',
      'rejected'    => 'danger',
      'in_progress' => 'info',
      default       => 'warning',
  };
  ?>
  <span class="badge bg-<?= $badge ?> fs-6"><?= ucfirst($request['overall_status']) ?></span>
  <?php if ($request['overall_status'] === 'approved'): ?>
    <a href="/student/clearance/download/<?= $request['id'] ?>"
       class="btn btn-success btn-sm ms-auto">
      <i class="bi bi-download me-1"></i>Download Clearance
    </a>
  <?php endif; ?>
</div>

<div class="card shadow-sm mb-3">
  <div class="card-body row g-2">
    <div class="col-md-4"><strong>Student:</strong> <?= esc($request['full_name']) ?></div>
    <div class="col-md-4"><strong>Student ID:</strong> <?= esc($request['sid']) ?></div>
    <div class="col-md-4"><strong>Semester:</strong> <?= esc($request['semester']) ?> – <?= esc($request['academic_year']) ?></div>
    <div class="col-12"><strong>Purpose:</strong> <?= esc($request['purpose']) ?></div>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-header fw-semibold">Department Clearance Status</div>
  <div class="card-body p-0">
    <table class="table mb-0 table-hover">
      <thead class="table-light">
        <tr><th>Department</th><th>Status</th><th>Reviewed By</th><th>Remarks</th><th>Date</th></tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
          <td><?= esc($item['dept_name']) ?></td>
          <td>
            <?php $b = match($item['status']) {
              'approved' => 'success', 'rejected' => 'danger', default => 'warning'
            }; ?>
            <span class="badge bg-<?= $b ?>"><?= ucfirst($item['status']) ?></span>
          </td>
          <td><?= esc($item['reviewed_by'] ?? '—') ?></td>
          <td><?= esc($item['remarks'] ?? '—') ?></td>
          <td><?= $item['reviewed_at'] ? date('M d, Y', strtotime($item['reviewed_at'])) : '—' ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>