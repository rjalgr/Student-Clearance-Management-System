<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($title ?? 'Admin Dashboard') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
<style>
  body { background: #f4f6fb; }
  .sidebar {
    min-height: 100vh;
    background: #1a237e;
    color: #fff;
    width: 230px;
    position: fixed;
    top: 0; left: 0;
    z-index: 100;
    transition: all 0.3s;
  }
  .sidebar .brand {
    padding: 24px 20px 16px;
    font-size: 1.2rem;
    font-weight: 700;
    border-bottom: 1px solid rgba(255,255,255,0.1);
  }
  .sidebar .nav-link {
    color: rgba(255,255,255,0.75);
    padding: 10px 20px;
    border-radius: 8px;
    margin: 2px 8px;
    font-size: 0.92rem;
    transition: all 0.2s;
  }
  .sidebar .nav-link:hover,
  .sidebar .nav-link.active {
    background: rgba(255,255,255,0.15);
    color: #fff;
  }
  .sidebar .nav-link i { width: 22px; }
  .main-content {
    margin-left: 230px;
    padding: 24px;
    min-height: 100vh;
  }
  .topbar {
    background: #fff;
    border-radius: 12px;
    padding: 12px 20px;
    margin-bottom: 24px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.07);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .stat-card {
    border-radius: 14px;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.2s;
  }
  .stat-card:hover { transform: translateY(-3px); }
  .stat-icon {
    width: 50px; height: 50px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
  }
  .card { border-radius: 14px; border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
  .table th { font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6c757d; }
  .badge { font-size: 0.78rem; }
  .btn-action { padding: 4px 10px; font-size: 0.8rem; }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <div class="brand">
    <i class="bi bi-mortarboard-fill me-2"></i>SCMS Admin
  </div>
  <nav class="nav flex-column mt-3">
    <a href="#section-dashboard" class="nav-link active" onclick="showSection('dashboard')">
      <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a href="#section-users" class="nav-link" onclick="showSection('users')">
      <i class="bi bi-people me-2"></i>Users
    </a>
    <a href="#section-departments" class="nav-link" onclick="showSection('departments')">
      <i class="bi bi-building me-2"></i>Departments
    </a>
    <a href="#section-clearances" class="nav-link" onclick="showSection('clearances')">
      <i class="bi bi-card-checklist me-2"></i>Clearances
    </a>
    <a href="#section-reports" class="nav-link" onclick="showSection('reports')">
      <i class="bi bi-bar-chart me-2"></i>Reports
    </a>
    <hr style="border-color:rgba(255,255,255,0.1);margin:8px 16px">
    <a href="/logout" class="nav-link text-danger">
      <i class="bi bi-box-arrow-right me-2"></i>Logout
    </a>
  </nav>
</div>

<!-- Main Content -->
<div class="main-content">

  <!-- Topbar -->
  <div class="topbar">
    <h6 class="mb-0 fw-semibold" id="page-title">Dashboard Overview</h6>
    <div class="d-flex align-items-center gap-3">
      <span class="text-muted small"><i class="bi bi-person-circle me-1"></i><?= esc(session()->get('fullName')) ?></span>
      <span class="badge bg-danger">Admin</span>
    </div>
  </div>

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
      <ul class="mb-0 small"><?php foreach($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- ===================== DASHBOARD SECTION ===================== -->
  <div id="section-dashboard">

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="card stat-card p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
              <i class="bi bi-people-fill"></i>
            </div>
            <div>
              <div class="fw-bold fs-4"><?= $stats['totalStudents'] ?></div>
              <div class="text-muted small">Total Students</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card stat-card p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
              <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
              <div class="fw-bold fs-4"><?= $stats['pendingCount'] ?></div>
              <div class="text-muted small">Pending</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card stat-card p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
              <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
              <div class="fw-bold fs-4"><?= $stats['approvedCount'] ?></div>
              <div class="text-muted small">Approved</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card stat-card p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-danger bg-opacity-10 text-danger">
              <i class="bi bi-x-circle-fill"></i>
            </div>
            <div>
              <div class="fw-bold fs-4"><?= $stats['rejectedCount'] ?></div>
              <div class="text-muted small">Rejected</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Chart + Recent -->
    <div class="row g-3">
      <div class="col-md-7">
        <div class="card p-3">
          <div class="fw-semibold mb-3">Clearance Requests — Last 6 Months</div>
          <canvas id="requestChart" height="120"></canvas>
        </div>
      </div>
      <div class="col-md-5">
        <div class="card p-0">
          <div class="p-3 fw-semibold border-bottom">Recent Requests</div>
          <div class="table-responsive">
            <table class="table table-hover mb-0 small">
              <thead class="table-light">
                <tr><th>Student</th><th>Semester</th><th>Status</th></tr>
              </thead>
              <tbody>
                <?php foreach (array_slice($recentRequests, 0, 8) as $r): ?>
                <?php $b = match($r['overall_status']) {
                  'approved'=>'success','rejected'=>'danger','in_progress'=>'info',default=>'warning'
                }; ?>
                <tr>
                  <td><?= esc($r['full_name']) ?><br><span class="text-muted"><?= esc($r['sid']) ?></span></td>
                  <td><?= esc($r['semester']) ?></td>
                  <td><span class="badge bg-<?= $b ?>"><?= ucfirst($r['overall_status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================== USERS SECTION ===================== -->
  <div id="section-users" style="display:none">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
        <span class="fw-semibold"><i class="bi bi-people me-2"></i>Manage Users</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddUser">
          <i class="bi bi-plus-lg me-1"></i>Add User
        </button>
      </div>
      <!-- Search -->
      <div class="px-3 pt-3">
        <input type="text" id="userSearch" class="form-control form-control-sm" placeholder="Search users..." onkeyup="filterTable('userTable', this.value)">
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="userTable">
            <thead class="table-light">
              <tr>
                <th>#</th><th>Student ID</th><th>Full Name</th>
                <th>Email</th><th>Role</th><th>Department</th>
                <th>Status</th><th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($users)): ?>
                <?php foreach ($users as $i => $u): ?>
                <tr>
                  <td class="text-muted small"><?= $i + 1 ?></td>
                  <td><?= esc($u['student_id'] ?? '—') ?></td>
                  <td class="fw-medium"><?= esc($u['full_name']) ?></td>
                  <td class="small"><?= esc($u['email']) ?></td>
                  <td>
                    <?php $rb = match($u['role']) {
                      'admin'=>'danger','staff'=>'info',default=>'primary'
                    }; ?>
                    <span class="badge bg-<?= $rb ?>"><?= ucfirst($u['role']) ?></span>
                  </td>
                  <td class="small"><?= esc($u['department'] ?? '—') ?></td>
                  <td>
                    <span class="badge bg-<?= $u['is_active'] ? 'success' : 'secondary' ?>">
                      <?= $u['is_active'] ? 'Active' : 'Inactive' ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <button class="btn btn-outline-primary btn-action me-1"
                      onclick="openEditUser(<?= htmlspecialchars(json_encode($u)) ?>)">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-action"
                      onclick="confirmDelete('/admin/users/delete/<?= $u['id'] ?>', 'Delete user <?= esc($u['full_name']) ?>?')">
                      <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="8" class="text-center text-muted py-4">No users found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================== DEPARTMENTS SECTION ===================== -->
  <div id="section-departments" style="display:none">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
        <span class="fw-semibold"><i class="bi bi-building me-2"></i>Manage Departments</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddDept">
          <i class="bi bi-plus-lg me-1"></i>Add Department
        </button>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr><th>#</th><th>Name</th><th>Code</th><th>Head</th><th>Status</th><th class="text-center">Actions</th></tr>
            </thead>
            <tbody>
              <?php if (!empty($departments)): ?>
                <?php foreach ($departments as $i => $d): ?>
                <tr>
                  <td class="text-muted small"><?= $i + 1 ?></td>
                  <td class="fw-medium"><?= esc($d['name']) ?></td>
                  <td><span class="badge bg-secondary"><?= esc($d['code']) ?></span></td>
                  <td><?= esc($d['head_name'] ?? '—') ?></td>
                  <td>
                    <span class="badge bg-<?= $d['is_active'] ? 'success' : 'secondary' ?>">
                      <?= $d['is_active'] ? 'Active' : 'Inactive' ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <button class="btn btn-outline-primary btn-action me-1"
                      onclick="openEditDept(<?= htmlspecialchars(json_encode($d)) ?>)">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-action"
                      onclick="confirmDelete('/admin/departments/delete/<?= $d['id'] ?>', 'Delete department <?= esc($d['name']) ?>?')">
                      <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No departments found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================== CLEARANCES SECTION ===================== -->
  <div id="section-clearances" style="display:none">
    <div class="card">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-card-checklist me-2"></i>All Clearance Requests</span>
        <div class="d-flex gap-2">
          <input type="text" id="clearSearch" class="form-control form-control-sm" placeholder="Search student..." onkeyup="filterTable('clearTable', this.value)" style="width:200px">
          <select class="form-select form-select-sm" onchange="filterByStatus(this.value)" style="width:140px">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="clearTable">
            <thead class="table-light">
              <tr>
                <th>#</th><th>Student</th><th>Student ID</th>
                <th>Academic Year</th><th>Semester</th>
                <th>Status</th><th>Date Filed</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($allRequests)): ?>
                <?php foreach ($allRequests as $i => $req): ?>
                <?php $b = match($req['overall_status']) {
                  'approved'=>'success','rejected'=>'danger','in_progress'=>'info',default=>'warning'
                }; ?>
                <tr data-status="<?= $req['overall_status'] ?>">
                  <td class="small text-muted"><?= $i + 1 ?></td>
                  <td class="fw-medium"><?= esc($req['full_name']) ?></td>
                  <td class="small"><?= esc($req['sid']) ?></td>
                  <td><?= esc($req['academic_year']) ?></td>
                  <td><?= esc($req['semester']) ?></td>
                  <td><span class="badge bg-<?= $b ?>"><?= ucfirst(str_replace('_',' ',$req['overall_status'])) ?></span></td>
                  <td class="small text-muted"><?= date('M d, Y', strtotime($req['created_at'] ?? $req['submitted_at'] ?? now())) ?></td>
                  <td class="text-center">
                    <button class="btn btn-outline-info btn-action me-1"
                      onclick="viewClearance(<?= $req['id'] ?>)">
                      <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-outline-warning btn-action me-1"
                      onclick="openEditClearance(<?= htmlspecialchars(json_encode($req)) ?>)">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-action"
                      onclick="confirmDelete('/admin/clearance/delete/<?= $req['id'] ?>', 'Delete this clearance request?')">
                      <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="8" class="text-center text-muted py-4">No clearance requests found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================== REPORTS SECTION ===================== -->
  <div id="section-reports" style="display:none">
    <div class="row g-3">
      <div class="col-md-6">
        <div class="card p-3">
          <div class="fw-semibold mb-3">Status Breakdown</div>
          <canvas id="pieChart" height="200"></canvas>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card p-3">
          <div class="fw-semibold mb-3">Summary Report</div>
          <table class="table table-sm">
            <tr><td>Total Students</td><td class="fw-bold"><?= $stats['totalStudents'] ?></td></tr>
            <tr><td>Total Staff</td><td class="fw-bold"><?= $stats['totalStaff'] ?></td></tr>
            <tr><td>Total Requests</td><td class="fw-bold"><?= $stats['totalRequests'] ?></td></tr>
            <tr><td>Pending</td><td><span class="badge bg-warning"><?= $stats['pendingCount'] ?></span></td></tr>
            <tr><td>In Progress</td><td><span class="badge bg-info"><?= $stats['inProgressCount'] ?? 0 ?></span></td></tr>
            <tr><td>Approved</td><td><span class="badge bg-success"><?= $stats['approvedCount'] ?></span></td></tr>
            <tr><td>Rejected</td><td><span class="badge bg-danger"><?= $stats['rejectedCount'] ?></span></td></tr>
          </table>
            <a href="/admin/reports/export"
            class="btn btn-success w-100 mt-2"
            target="_self">
            <i class="bi bi-download me-1"></i> Export CSV
          </a>
        </div>
      </div>
    </div>
  </div>

</div><!-- end main-content -->

<!-- ===================== MODALS ===================== -->

<!-- Add User Modal -->
<div class="modal fade" id="modalAddUser" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="/admin/users/store">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Add New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label class="form-label">Student ID</label>
            <input type="text" name="student_id" class="form-control" placeholder="2024-00123">
          </div>
          <div class="col-md-6">
            <label class="form-label">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="full_name" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control" placeholder="Min 8 chars" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Role <span class="text-danger">*</span></label>
            <select name="role" class="form-select" required>
              <option value="student">Student</option>
              <option value="staff">Staff</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Department</label>
            <select name="department" class="form-select">
              <option value="">-- Select --</option>
              <?php foreach ($departments as $d): ?>
                <option value="<?= esc($d['name']) ?>"><?= esc($d['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Course</label>
            <input type="text" name="course" class="form-control" placeholder="e.g. BSIT">
          </div>
          <div class="col-md-6">
            <label class="form-label">Year Level</label>
            <select name="year_level" class="form-select">
              <option value="">--</option>
              <option value="1">1st Year</option>
              <option value="2">2nd Year</option>
              <option value="3">3rd Year</option>
              <option value="4">4th Year</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save User</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="modalEditUser" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="editUserForm">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label class="form-label">Student ID</label>
            <input type="text" name="student_id" id="edit_student_id" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="full_name" id="edit_full_name" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="edit_email" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Role</label>
            <select name="role" id="edit_role" class="form-select">
              <option value="student">Student</option>
              <option value="staff">Staff</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Department</label>
            <select name="department" id="edit_department" class="form-select">
              <option value="">-- Select --</option>
              <?php foreach ($departments as $d): ?>
                <option value="<?= esc($d['name']) ?>"><?= esc($d['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Course</label>
            <input type="text" name="course" id="edit_course" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="is_active" id="edit_is_active" class="form-select">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update User</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Department Modal -->
<div class="modal fade" id="modalAddDept" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="/admin/departments/store">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-building-add me-2"></i>Add Department</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
          <div class="col-md-8">
            <label class="form-label">Department Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Code <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control" placeholder="e.g. REG" required>
          </div>
          <div class="col-12">
            <label class="form-label">Department Head</label>
            <input type="text" name="head_name" class="form-control">
          </div>
          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Department</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Department Modal -->
<div class="modal fade" id="modalEditDept" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="editDeptForm">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Department</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
          <div class="col-md-8">
            <label class="form-label">Department Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="edit_dept_name" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Code <span class="text-danger">*</span></label>
            <input type="text" name="code" id="edit_dept_code" class="form-control" required>
          </div>
          <div class="col-md-8">
            <label class="form-label">Department Head</label>
            <input type="text" name="head_name" id="edit_dept_head" class="form-control">
          </div>
          <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="is_active" id="edit_dept_active" class="form-select">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" id="edit_dept_desc" class="form-control" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update Department</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Clearance Status Modal -->
<div class="modal fade" id="modalEditClearance" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="editClearanceForm">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Update Clearance Status</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted small mb-3" id="clearance_info"></p>
          <div class="mb-3">
            <label class="form-label">Overall Status</label>
            <select name="overall_status" id="edit_clearance_status" class="form-select">
              <option value="pending">Pending</option>
              <option value="in_progress">In Progress</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update Status</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Confirm Modal -->
<div class="modal fade" id="modalDelete" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="delete_message" class="text-muted small"></p>
      </div>
      <div class="modal-footer border-0">
        <form method="POST" id="deleteForm">
          <?= csrf_field() ?>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash me-1"></i>Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ---- Section switcher ----
const sections = ['dashboard','users','departments','clearances','reports'];
function showSection(name) {
  sections.forEach(s => {
    document.getElementById('section-' + s).style.display = s === name ? '' : 'none';
  });
  document.getElementById('page-title').textContent = {
    dashboard:   'Dashboard Overview',
    users:       'Manage Users',
    departments: 'Manage Departments',
    clearances:  'All Clearance Requests',
    reports:     'Reports & Analytics',
  }[name];
  document.querySelectorAll('.sidebar .nav-link').forEach(l => l.classList.remove('active'));
  event.currentTarget.classList.add('active');
  if (name === 'reports') initPieChart();
}

// ---- Charts ----
const chartData = <?= json_encode($chartData) ?>;
new Chart(document.getElementById('requestChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: chartData.map(d => d.month),
    datasets: [{
      label: 'Requests',
      data: chartData.map(d => d.count),
      backgroundColor: 'rgba(26,35,126,0.7)',
      borderRadius: 6,
    }]
  },
  options: { responsive: true, plugins: { legend: { display: false } } }
});

let pieInited = false;
function initPieChart() {
  if (pieInited) return; pieInited = true;
  new Chart(document.getElementById('pieChart').getContext('2d'), {
    type: 'doughnut',
    data: {
      labels: ['Pending','In Progress','Approved','Rejected'],
      datasets: [{
        data: [
          <?= $stats['pendingCount'] ?>,
          <?= $stats['inProgressCount'] ?? 0 ?>,
          <?= $stats['approvedCount'] ?>,
          <?= $stats['rejectedCount'] ?>
        ],
        backgroundColor: ['#ffc107','#0dcaf0','#198754','#dc3545'],
        borderWidth: 2,
      }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
  });
}

// ---- Table search filter ----
function filterTable(tableId, query) {
  const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
  query = query.toLowerCase();
  rows.forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
  });
}

// ---- Status filter for clearances ----
function filterByStatus(status) {
  document.querySelectorAll('#clearTable tbody tr').forEach(row => {
    row.style.display = (!status || row.dataset.status === status) ? '' : 'none';
  });
}

// ---- Edit User ----
function openEditUser(u) {
  document.getElementById('editUserForm').action = '/admin/users/update/' + u.id;
  document.getElementById('edit_student_id').value = u.student_id ?? '';
  document.getElementById('edit_full_name').value  = u.full_name;
  document.getElementById('edit_email').value      = u.email;
  document.getElementById('edit_role').value       = u.role;
  document.getElementById('edit_department').value = u.department ?? '';
  document.getElementById('edit_course').value     = u.course ?? '';
  document.getElementById('edit_is_active').value  = u.is_active;
  new bootstrap.Modal(document.getElementById('modalEditUser')).show();
}

// ---- Edit Department ----
function openEditDept(d) {
  document.getElementById('editDeptForm').action   = '/admin/departments/update/' + d.id;
  document.getElementById('edit_dept_name').value  = d.name;
  document.getElementById('edit_dept_code').value  = d.code;
  document.getElementById('edit_dept_head').value  = d.head_name ?? '';
  document.getElementById('edit_dept_desc').value  = d.description ?? '';
  document.getElementById('edit_dept_active').value = d.is_active;
  new bootstrap.Modal(document.getElementById('modalEditDept')).show();
}

// ---- Edit Clearance ----
function openEditClearance(req) {
  document.getElementById('editClearanceForm').action = '/admin/clearance/update/' + req.id;
  document.getElementById('edit_clearance_status').value = req.overall_status;
  document.getElementById('clearance_info').textContent =
    'Student: ' + req.full_name + ' | ' + req.semester + ' ' + req.academic_year;
  new bootstrap.Modal(document.getElementById('modalEditClearance')).show();
}

// ---- View Clearance ----
function viewClearance(id) {
  window.open('/admin/clearance/' + id, '_blank');
}

// ---- Delete Confirm ----
function confirmDelete(url, message) {
  document.getElementById('delete_message').textContent = message;
  document.getElementById('deleteForm').action = url;
  new bootstrap.Modal(document.getElementById('modalDelete')).show();
}

// Show correct section on load if hash present
const hash = window.location.hash.replace('#section-','');
if (sections.includes(hash)) showSection(hash);
</script>
</body>
</html>