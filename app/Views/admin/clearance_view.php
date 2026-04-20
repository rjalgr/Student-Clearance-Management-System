<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">
                    <i class="bi bi-card-checklist me-2"></i>
                    Clearance Request #<?= $request['id'] ?>
                </h2>
                <a href="<?= site_url('admin/dashboard#section-clearances') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Request Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Student:</label>
                            <p class="mb-0"><?= esc($request['full_name']) ?></p>
                            <small class="text-muted"><?= esc($request['student_id']) ?></small>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Date Filed:</label>
                            <p class="mb-0"><?= date('M d, Y', strtotime($request['created_at'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Academic Year:</label>
                            <p class="mb-0"><?= esc($request['academic_year']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Semester:</label>
                            <p class="mb-0"><?= esc($request['semester']) ?></p>
                        </div>
                        <div class="col-12">
                            <label class="fw-bold">Overall Status:</label>
                            <?php $badge = match($request['overall_status'] ?? 'pending') {
                                'approved' => 'success',
                                'rejected' => 'danger',
                                'in_progress' => 'warning',
                                default => 'secondary'
                            }; ?>
                            <span class="badge bg-<?= $badge ?> fs-6 px-3 py-2">
                                <?= ucwords(str_replace('_', ' ', $request['overall_status'] ?? 'pending')) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0"><i class="bi bi-list-check me-1"></i>Clearance Items</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Staff Remarks</th>
                                    <th>Action Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($items)): ?>
                                    <?php foreach ($items as $item): ?>
                                        <?php $statusBadge = match($item['status']) {
                                            'cleared' => 'success',
                                            'pending' => 'warning',
                                            'rejected' => 'danger',
                                            default => 'secondary'
                                        }; ?>
                                        <tr>
                                            <td class="fw-medium"><?= esc($item['dept_name'] ?? '—') ?></td>
                                            <td>
                                                <span class="badge bg-<?= $statusBadge ?>">
                                                    <?= ucfirst($item['status']) ?>
                                                </span>
                                            </td>
                                            <td><?= esc($item['remarks'] ?? '—') ?></td>
                                            <td>
                                                <?= $item['reviewed_at'] ? date('M d', strtotime($item['reviewed_at'])) : '—' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            No clearance items found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Status Update -->
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h6 class="card-title mb-0"><i class="bi bi-gear me-2"></i>Update Status</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= site_url('admin/clearance/update/' . $request['id']) ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Overall Status</label>
                            <select name="overall_status" class="form-select" required>
                                <option value="pending" <?= ($request['overall_status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="in_progress" <?= ($request['overall_status'] ?? '') === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                <option value="approved" <?= ($request['overall_status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= ($request['overall_status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-lg me-2"></i>Update Status
                        </button>
                    </form>
                    <hr>
                    <form method="POST" action="<?= site_url('admin/clearance/delete/' . $request['id']) ?>" class="mt-2" onsubmit="return confirm('Delete this clearance request?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger w-100 btn-sm">
                            <i class="bi bi-trash me-2"></i>Delete Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

