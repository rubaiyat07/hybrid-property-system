# Task: After approval of tenant application, redirect landlord to create lease page

## Steps to Complete:

1. [x] Search for relevant files using search_code for "tenant approval" to identify controllers handling approvals (e.g., TenantInquiryController, TenantController).
2. [x] Read routes/web.php to understand routing for approvals and lease creation.
3. [x] Read TenantController.php to analyze approval methods (approveInquiry, approveLead, approveInquiryFromIndex).
4. [x] Search for LeaseController using search_files to locate it.
5. [x] Read LeaseController.php to check create method and ensure it supports prefilling.
6. [x] Read resources/views/landlord/leases/create.blade.php to verify form supports old() input for preselection.
7. [x] Read app/Models/Unit.php to confirm unit status handling for availability.
8. [x] Update TenantController: Add withInput() to redirects in approveInquiry and approveLead for prefilling tenant_id/unit_id.
9. [x] Update approveInquiryFromIndex: After creating tenant, redirect to lease create with tenant_id and unit_id (from inquiry), using withInput().
10. [x] In LeaseController::create, if unit_id provided, filter units to only that unit if available; set default start_date to today if not set.
11. [] Test: No command needed yet; use browser_action if verification required later.
12. [] Confirm completion with user.

## Dependencies:
- None; changes are isolated to controllers.

## Follow-up:
- After edits, verify by simulating approval flow (manual or via browser_action).
- If screening approval exists (TenantScreening model), extend flow, but current task focuses on inquiry/lead approval.
