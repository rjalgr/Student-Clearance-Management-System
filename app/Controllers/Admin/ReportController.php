<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ClearanceRequestModel;

class ReportController extends BaseController
{
    // ── Reports page ─────────────────────────────────────────────
    public function index()
    {
        return redirect()->to('/admin/dashboard#section-reports');
    }

    // ── Export CSV ───────────────────────────────────────────────
    public function export()
    {
        $db = \Config\Database::connect();

        // Fetch all clearance requests with student info
        $requests = $db->table('clearance_requests cr')
            ->select('
                cr.id,
                u.student_id AS student_no,
                u.full_name,
                u.email,
                u.course,
                u.department AS student_dept,
                u.year_level,
                cr.academic_year,
                cr.semester,
                cr.purpose,
                cr.overall_status,
                cr.created_at AS date_submitted,
                cr.updated_at AS date_updated
            ')
            ->join('users u', 'u.id = cr.student_id')
            ->orderBy('cr.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Fetch department clearance items summary per request
        $itemsRaw = $db->table('clearance_items ci')
            ->select('ci.request_id, d.name AS dept_name, ci.status, ci.remarks, ci.reviewed_at')
            ->join('departments d', 'd.id = ci.department_id')
            ->get()
            ->getResultArray();

        // Group items by request_id
        $itemsByRequest = [];
        foreach ($itemsRaw as $item) {
            $itemsByRequest[$item['request_id']][] = $item;
        }

        // Generate filename with timestamp
        $filename = 'SCMS_Clearance_Report_' . date('Y-m-d_His') . '.csv';

        // Set CSV headers
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open output stream
        $output = fopen('php://output', 'w');

        // UTF-8 BOM for Excel compatibility
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // ── Main Header Row ──────────────────────────────────────
        fputcsv($output, [
            'STUDENT CLEARANCE MANAGEMENT SYSTEM',
            'Generated: ' . date('F d, Y h:i A'),
        ]);

        fputcsv($output, []); // empty row

        // ── Column Headers ───────────────────────────────────────
        fputcsv($output, [
            '#',
            'Student ID',
            'Full Name',
            'Email',
            'Course',
            'Department',
            'Year Level',
            'Academic Year',
            'Semester',
            'Purpose',
            'Overall Status',
            'Date Submitted',
            'Last Updated',
            // Department columns
            'Registrar',
            'Library',
            'Finance / Cashier',
            'Academic Affairs',
            'Student Affairs',
        ]);

        // ── Data Rows ────────────────────────────────────────────
        foreach ($requests as $i => $row) {
            $reqId = $row['id'];
            $items = $itemsByRequest[$reqId] ?? [];

            // Map department statuses
            $deptStatus = [];
            foreach ($items as $item) {
                $deptStatus[$item['dept_name']] = ucfirst($item['status'])
                    . ($item['remarks'] ? ' - ' . $item['remarks'] : '');
            }

            fputcsv($output, [
                $i + 1,
                $row['student_no']   ?? 'N/A',
                $row['full_name']    ?? 'N/A',
                $row['email']        ?? 'N/A',
                $row['course']       ?? 'N/A',
                $row['student_dept'] ?? 'N/A',
                $row['year_level']   ? $row['year_level'] . getOrdinalSuffix((int)$row['year_level']) . ' Year' : 'N/A',
                $row['academic_year'] ?? 'N/A',
                $row['semester']      ?? 'N/A',
                $row['purpose']       ?? 'N/A',
                ucfirst(str_replace('_', ' ', $row['overall_status'] ?? 'N/A')),
                $row['date_submitted'] ? date('M d, Y h:i A', strtotime($row['date_submitted'])) : 'N/A',
                $row['date_updated']   ? date('M d, Y h:i A', strtotime($row['date_updated']))   : 'N/A',
                // Department statuses
                $deptStatus['Registrar']         ?? 'Pending',
                $deptStatus['Library']           ?? 'Pending',
                $deptStatus['Finance / Cashier'] ?? 'Pending',
                $deptStatus['Academic Affairs']  ?? 'Pending',
                $deptStatus['Student Affairs']   ?? 'Pending',
            ]);
        }

        fputcsv($output, []); // empty row

        // ── Summary Row ──────────────────────────────────────────
        $userModel    = new UserModel();
        $requestModel = new ClearanceRequestModel();

        fputcsv($output, ['--- SUMMARY ---']);
        fputcsv($output, ['Total Students',   $userModel->where('role', 'student')->countAllResults()]);
        fputcsv($output, ['Total Staff',      $userModel->where('role', 'staff')->countAllResults()]);
        fputcsv($output, ['Total Requests',   $requestModel->countAll()]);
        fputcsv($output, ['Pending',          $requestModel->where('overall_status', 'pending')->countAllResults()]);
        fputcsv($output, ['In Progress',      $requestModel->where('overall_status', 'in_progress')->countAllResults()]);
        fputcsv($output, ['Approved',         $requestModel->where('overall_status', 'approved')->countAllResults()]);
        fputcsv($output, ['Rejected',         $requestModel->where('overall_status', 'rejected')->countAllResults()]);

        fclose($output);
        exit();
    }
}

// ── Helper: ordinal suffix ────────────────────────────────────────
if (!function_exists('getOrdinalSuffix')) {
    function getOrdinalSuffix(int $n): string
    {
        return match($n) {
            1       => 'st',
            2       => 'nd',
            3       => 'rd',
            default => 'th',
        };
    }
}