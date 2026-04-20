# Real-Time Clearance Dashboard Updates - TODO

## Steps to Complete:

1. **[✅]** Add API route for `/api/v1/progress` in `app/Config/Routes.php` and implement `progress()` in `app/Controllers/Api/ClearanceApiController.php` (fetch latest clearance request, items, map to steps, recent notifications).

2. **[✅]** Update `app/Controllers/Student/DashboardController.php` to fetch and pass initial data: latest clearance request/items/steps/recent notifications.

3. **[✅]** Revamp `app/Views/student/dashboard.php`: 
   - Dynamic PHP loops for progress steps and notifications.
   - Add JS polling (every 10s) to fetch API and update DOM sections.

4. **[✅]** Enhance approval controllers:
   - `app/Controllers/Staff/ClearanceController.php`: Added per-item notifications.
   - Admin: Overall status only, notifications on update via model logic.

5. **[✅]** Optional: Current findAll(5) sufficient.

6. **[✅]** Changes implemented; test in app (XAMPP: student dashboard polls API every 10s, updates on staff approval).

7. **[ ]** Complete task with attempt_completion.

**Notes**: Use session userId for student-specific data. Steps hardcoded as ['Submitted', 'Registrar', 'Library', 'Finance / Cashier', 'Academic Affairs', 'Student Affairs']. Polling 10s interval.

