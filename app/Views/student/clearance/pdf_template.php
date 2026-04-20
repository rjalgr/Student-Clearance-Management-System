<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clearance Slip — <?= esc($request['full_name']) ?></title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: Arial, sans-serif;
    font-size: 13px;
    color: #222;
    background: #fff;
    padding: 40px;
  }

  /* Header */
  .header {
    text-align: center;
    border-bottom: 3px solid #1a237e;
    padding-bottom: 16px;
    margin-bottom: 24px;
  }
  .header .school-name {
    font-size: 20px;
    font-weight: bold;
    color: #1a237e;
    text-transform: uppercase;
    letter-spacing: 1px;
  }
  .header .doc-title {
    font-size: 16px;
    font-weight: bold;
    margin-top: 6px;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 2px;
  }
  .header .doc-subtitle {
    font-size: 12px;
    color: #666;
    margin-top: 4px;
  }

  /* Status Badge */
  .status-approved {
    display: inline-block;
    background: #198754;
    color: #fff;
    padding: 4px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 8px;
  }

  /* Student Info */
  .info-box {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 16px 20px;
    margin-bottom: 24px;
  }
  .info-box .info-title {
    font-weight: bold;
    color: #1a237e;
    font-size: 13px;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 8px;
  }
  .info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px 24px;
  }
  .info-item label {
    font-size: 11px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: block;
  }
  .info-item span {
    font-weight: bold;
    font-size: 13px;
    color: #222;
  }

  /* Departments Table */
  .dept-title {
    font-weight: bold;
    color: #1a237e;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 24px;
  }
  table thead tr {
    background: #1a237e;
    color: #fff;
  }
  table thead th {
    padding: 10px 14px;
    text-align: left;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  table tbody tr {
    border-bottom: 1px solid #dee2e6;
  }
  table tbody tr:nth-child(even) {
    background: #f8f9fa;
  }
  table tbody td {
    padding: 10px 14px;
    font-size: 13px;
  }
  .badge-approved {
    background: #d1e7dd;
    color: #0a3622;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: bold;
  }

  /* Signature Section */
  .signature-section {
    margin-top: 40px;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 24px;
    text-align: center;
  }
  .signature-box {
    border-top: 1px solid #333;
    padding-top: 8px;
  }
  .signature-box .sig-name {
    font-weight: bold;
    font-size: 13px;
  }
  .signature-box .sig-role {
    font-size: 11px;
    color: #666;
  }

  /* Footer */
  .footer {
    margin-top: 40px;
    text-align: center;
    border-top: 1px solid #dee2e6;
    padding-top: 12px;
    font-size: 11px;
    color: #999;
  }

  /* Watermark */
  .watermark {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-45deg);
    font-size: 80px;
    color: rgba(26, 35, 126, 0.05);
    font-weight: bold;
    pointer-events: none;
    white-space: nowrap;
    z-index: 0;
  }

  /* Print button */
  .print-btn {
    text-align: center;
    margin-bottom: 24px;
  }
  .print-btn button {
    background: #1a237e;
    color: #fff;
    border: none;
    padding: 10px 28px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    margin-right: 8px;
  }
  .print-btn button.secondary {
    background: #6c757d;
  }
  @media print {
    .print-btn { display: none; }
    .watermark { display: block; }
    body { padding: 20px; }
  }
</style>
</head>
<body>

<!-- Watermark -->
<div class="watermark">APPROVED</div>

<!-- Print Button (hidden on print) -->
<div class="print-btn">
  <button onclick="window.print()">🖨️ Print Clearance Slip</button>
  <a href="/student/clearance" class="btn btn-outline-secondary px-4">
    <button onclick="window.close()">✕ Close</button>
</a>
</div>

<!-- Header -->
<div class="header">
  <div class="school-name">🎓 Student Clearance Management System</div>
  <div class="doc-title">Official Clearance Slip</div>
  <div class="doc-subtitle">
    Academic Year: <?= esc($request['academic_year']) ?> |
    <?= esc($request['semester']) ?> Semester
  </div>
  <div>
    <span class="status-approved">✓ Cleared</span>
  </div>
</div>

<!-- Student Information -->
<div class="info-box">
  <div class="info-title">Student Information</div>
  <div class="info-grid">
    <div class="info-item">
      <label>Student Name</label>
      <span><?= esc($request['full_name']) ?></span>
    </div>
    <div class="info-item">
      <label>Student ID</label>
      <span><?= esc($request['sid'] ?? $request['student_id']) ?></span>
    </div>
    <div class="info-item">
      <label>Course / Program</label>
      <span><?= esc($request['course'] ?? 'N/A') ?></span>
    </div>
    <div class="info-item">
      <label>Department</label>
      <span><?= esc($request['department'] ?? 'N/A') ?></span>
    </div>
    <div class="info-item">
      <label>Purpose</label>
      <span><?= esc($request['purpose']) ?></span>
    </div>
    <div class="info-item">
      <label>Date Issued</label>
      <span><?= date('F d, Y') ?></span>
    </div>
    <div class="info-item">
      <label>Clearance Reference No.</label>
      <span>SCMS-<?= str_pad($request['id'], 6, '0', STR_PAD_LEFT) ?></span>
    </div>
    <div class="info-item">
      <label>Status</label>
      <span style="color:#198754;font-weight:bold;">APPROVED</span>
    </div>
  </div>
</div>

<!-- Department Clearances -->
<div class="dept-title">Department Clearance Details</div>
<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Department</th>
      <th>Status</th>
      <th>Reviewed By</th>
      <th>Remarks</th>
      <th>Date Approved</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($items as $i => $item): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><strong><?= esc($item['dept_name']) ?></strong></td>
      <td><span class="badge-approved">✓ Approved</span></td>
      <td><?= esc($item['reviewed_by'] ?? 'N/A') ?></td>
      <td><?= esc($item['remarks'] ?? '—') ?></td>
      <td>
        <?= $item['reviewed_at']
            ? date('M d, Y', strtotime($item['reviewed_at']))
            : date('M d, Y') ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Signature Section -->
<div class="signature-section">
  <div class="signature-box">
    <div style="height: 40px;"></div>
    <div class="sig-name">Registrar</div>
    <div class="sig-role">University Registrar</div>
  </div>
  <div class="signature-box">
    <div style="height: 40px;"></div>
    <div class="sig-name"><?= esc($request['full_name']) ?></div>
    <div class="sig-role">Student Signature</div>
  </div>
  <div class="signature-box">
    <div style="height: 40px;"></div>
    <div class="sig-name">Dean / Director</div>
    <div class="sig-role">Authorized Signatory</div>
  </div>
</div>

<!-- Footer -->
<div class="footer">
  <p>This clearance slip was generated on <?= date('F d, Y \a\t h:i A') ?> via SCMS.</p>
  <p>Reference No: SCMS-<?= str_pad($request['id'], 6, '0', STR_PAD_LEFT) ?> | This document is valid only with authorized signatures.</p>
</div>

</body>
</html>