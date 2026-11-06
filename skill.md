# Existing Laravel Website Theme & Admin Transformation Playbook

This playbook defines a reusable operational skill and implementation playbook for an AI coding agent to safely transform the frontend user interface, layout themes, and admin branding of an existing Laravel application. It ensures the preservation of existing business logic, database records, authentication, order histories, payment integrations, and administrative features.

---

## Table of Contents
1. [Skill Identity & Scope](#1-skill-identity--scope)
2. [User Input Contract](#2-user-input-contract)
3. [Core Operating Principles](#3-core-operating-principles)
4. [Pre-Flight Inspection & Discovery Audit](#4-pre-flight-inspection--discovery-audit)
5. [Safety Snapshot & Backup Protocol](#5-safety-snapshot--backup-protocol)
6. [Requirement Mapping Matrix](#6-requirement-mapping-matrix)
7. [Phased Implementation Workflow](#7-phased-implementation-workflow)
8. [Frontend Theme Integration Standards](#8-frontend-theme-integration-standards)
9. [Shared Layout & View Composer Setup](#9-shared-layout--view-composer-setup)
10. [Dynamic Database Content Mapping](#10-dynamic-database-content-mapping)
11. [Preservation of Legacy Database & Code Dependencies](#11-preservation-of-legacy-database--code-dependencies)
12. [Product & Catalog Architecture Inspection](#12-product-&amp;-catalog-architecture-inspection)
13. [Shared Category Table Isolation & Deletion Protection](#13-shared-category-table-isolation--deletion-protection)
14. [Safe Image Upload & Multi-Level File Limit Stack](#14-safe-image-upload--multi-level-file-limit-stack)
15. [Centralized Image URL Resolver Accessor](#15-centralized-image-url-resolver-accessor)
16. [Logo and Device Branding Pipeline](#16-logo-and-device-branding-pipeline)
17. [Admin Panel Rebranding Guidelines](#17-admin-panel-rebranding-guidelines)
18. [Interactive Owner Account Creation Command](#18-interactive-owner-account-creation-command)
19. [Authorization Simplification & Role Consolidation](#19-authorization-simplification--role-consolidation)
20. [Removal of Legacy Branding References](#20-removal-of-legacy-branding-references)
21. [Authentication Redirect Integrity](#21-authentication-redirect-integrity)
22. [Storage Disk & Symlink Management](#22-storage-disk--symlink-management)
23. [Viewport & Responsive UI Validation Layouts](#23-viewport--responsive-ui-validation-layouts)
24. [Automated QA: Playwright Instrumenting](#24-automated-qa-playwright-instrumenting)
25. [Automated Testing Protocols](#25-automated-testing-protocols)
26. [Staging & Commit Git Workflows](#26-staging-&amp;-commit-git-workflows)
27. [Safe Production Deployment Protocol](#27-safe-production-deployment-protocol)
28. [Customer Handover Readiness Checklist](#28-customer-handover-readiness-checklist)
29. [Troubleshooting & Common Failure Patterns](#29-troubleshooting--common-failure-patterns)
30. [Operational Decision Gates](#30-operational-decision-gates)
31. [Reusable Code Patterns Library](#31-reusable-code-patterns-library)
32. [AI Agent Behavioral Code of Conduct](#32-ai-agent-behavioral-code-of-conduct)
33. [Task Execution Report Template](#33-task-execution-report-template)
34. [Acceptance Verification Checklist](#34-acceptance-verification-checklist)

---

## 1. Skill Identity & Scope

* **Skill Name:** Existing Laravel Website Theme & Admin Transformation Playbook
* **Skill Purpose:** Systematically replace a public storefront design theme, rebrand the administrative panel, and simplify role systems in a legacy/live Laravel application while protecting all business data.
* **Supported Project Types:** Laravel e-commerce sites, subscription portals, business directory applications, and content hubs.
* **Supported Laravel Versions:** Laravel 9.x, 10.x, 11.x+
* **Required Inputs:** Reference design files/HTML markup, brand color parameters, name mappings, and local/admin access targets.
* **Expected Outputs:** Fully integrated responsive storefront, rebranded admin interface, updated storage configurations, role test suite, and clean production deploy instructions.
* **Safe Operating Principles:** Zero direct destruction of user profiles, payment mappings, logs, or schema migrations. Never execute fresh database seeds or wipes on live databases.
* **Non-Goals:** Building a database system from scratch; rewrites of payment gateways (e.g. Stripe checkout controllers); restructuring application frameworks.

---

## 2. User Input Contract

Provide the target environment details using this format:

```markdown
Read skill.md and follow it.

Project root:
{{PROJECT_ROOT}}

Current application name:
{{OLD_BRAND_NAME}}

New brand name:
{{NEW_BRAND_NAME}}

Existing admin panel path:
{{ADMIN_PATH}}

Theme/reference description:
{{THEME_DESCRIPTION_OR_PATH}}

Required work:
{{USER_REQUIREMENTS}}
```

---

## 3. Core Operating Principles

> [!IMPORTANT]
> **Rule 1:** Audit before coding. Inspect views, database schemas, and routes before introducing new layouts.
> **Rule 2:** Preserve data. Do not execute destructive migrations (`migrate:fresh`) or seeds on active databases.
> **Rule 3:** Maintain model continuity. If a legacy model like `Course` corresponds to products, preserve the class and table names; only clean up visible labels.
> **Rule 4:** Protect credentials. Never hardcode credentials, local domains, or API keys in repository files.

---

## 4. Pre-Flight Inspection & Discovery Audit

Run the following discovery checks:

```bash
# General Versioning and Environment Details
php -v
composer --version
php artisan --version
php artisan about

# Route and Migration Mapping
php artisan route:list --path={{ADMIN_PATH}}
php artisan migrate:status

# Cleanup configuration and check git status
php artisan optimize:clear
git status --short --untracked-files=all
git diff --stat
```

### Discovery Report Template
```markdown
- **Framework Version:** Laravel [Version] | Node [Version] | NPM [Version]
- **Active Database Connection:** [MySQL / PostgreSQL / SQLite]
- **Current Authentication Implementation:** [Breeze / Jetstream / Custom Guard]
- **Active Route Namespaces:** [admin.*, client.*, polani.*]
- **Observed Legacy Models:** [Course, Student, Registration, etc.]
- **Layout Files Discovered:** [layouts/app.blade.php, admin/layout.blade.php]
```

---

## 5. Safety Snapshot & Backup Protocol

Prior to performing changes:

1. **Git State Capture:** Verify the git status is clean or create a backup patch branch.
2. **Database Dump:** Export active data using native shell dumps:
   ```bash
   mysqldump -u [user] -p[password] [database_name] > backup_before_transformation.sql
   ```
3. **Environment Backup:** Copy `.env` to `.env.backup`.
4. **Storage Archive:** Create a ZIP of `storage/app/public/` to prevent data loss on upload folders.

---

## 6. Requirement Mapping Matrix

Create a requirement mapping matrix:

| Functional Area | Legacy View/Logic | New Layout Component | Source Table | Risk Level | Mitigation Plan |
|---|---|---|---|---|---|
| **Header** | `layouts.navigation` | `{{THEME_SLUG}}.navbar` | `categories` | Low | Use View Composer |
| **Shop Page** | `courses.index` | `{{THEME_SLUG}}.shop` | `courses` | High | Retain `courses` relationship checks |
| **Image Upload**| `ImageProcessor@save` | `banners/` storage folder | `carousel_slides` | Medium | Retain fallback upload rules |

---

## 7. Phased Implementation Workflow

1. **Phase 1 — Read-Only Discovery:** Inspect schemas, active controllers, config values, and asset locations.
2. **Phase 2 — Theme Asset Isolation:** Copy storefront styles, images, and fonts to `public/{{THEME_SLUG}}`.
3. **Phase 3 — Layout Component Integration:** Form the layout structures (`layout.blade.php`) and inject navigation View Composers.
4. **Phase 4 — Homepage Conversion:** Replace static blocks with database queries for banners/featured items.
5. **Phase 5 — Catalog Shop Page:** Map query arguments (filter, search, page parameters) to the new layouts.
6. **Phase 6 — Product View Integration:** Clean product grids and map options from database columns.
7. **Phase 7 — Cart / Checkout Interfaces:** Update views to reference current forms, CSRF tokens, and actions.
8. **Phase 8 — Rebranding of Admin Panels:** Update header, logo, theme colors, and layout titles.
9. **Phase 9 — Authorization Cleanup:** Consolidation of roles and removal of obsolete permissions.
10. **Phase 10 — QA Verification:** Viewport check, test execution, cache validation, and local asset confirmation.

---

## 8. Frontend Theme Integration Standards

* **Asset Directory Structure:** Place files under `public/{{THEME_SLUG}}/` using subdirectories: `css/`, `js/`, `images/`, `fonts/`, and `branding/`.
* **Namespace Styling:** Keep theme layouts separate. Ensure theme styles do not clash with existing admin layout selectors.
* **Asset Loading Helper:** Always use `asset('{{THEME_SLUG}}/css/style.css')` inside Blade files.
* **Separation of JavaScript:** Maintain backend cart updates. Do not use local storage/cookie-based scripts from HTML templates if the backend has database-driven cart tables.

---

## 9. Shared Layout & View Composer Setup

Do not query the database directly inside layout layouts or header views. Use a Laravel View Composer in `AppServiceProvider.php` to inject shared resources (such as active categories) globally.

### Safe Provider Sharing Pattern
```php
view()->composer('{{THEME_SLUG}}.partials.header', function ($view) {
    $view->with('storefrontCategories', \App\Models\Category::where('is_storefront_category', true)->orderBy('order')->get());
});
```

---

## 10. Dynamic Database Content Mapping

Map the dynamic values safely using local fallbacks.

| Data Type | Target Controller / Model Field | Fallback Behavior |
|---|---|---|
| **Products** | `Course::where('is_active', true)` | Render static list if database count is zero |
| **Banners** | `CarouselSlide::where('is_active', true)` | Fallback to theme static asset hero image if none exist |
| **Settings**| `FooterSetting::first()` | Load hardcoded default configs |

---

## 11. Preservation of Legacy Database & Code Dependencies

> [!WARNING]
> **Caution:** Do not delete models (like `Course`, `Student`, `Quiz`) or tables just because you are converting the site terminology to "Products", "Customers", and "Items". Doing so will break checkout, order history, and backend tracking. Only update the labels shown in the views.

---

## 12. Product & Catalog Architecture Inspection

Verify that the product model maps values correctly:

```php
// Check for both legacy course flags and new retail settings
$products = Course::where('is_active', true)
    ->where('product_kind', 'retail') // or legacy course logic
    ->paginate(12);
```

Ensure the admin creation commands or interfaces do not require legacy course variables (e.g. `instructor_id` or `certificate_template`) if creating a standard product.

---

## 13. Shared Category Table Isolation & Deletion Protection

When a single `categories` table is shared between legacy systems (such as courses/internal systems) and the storefront shop, use an explicit `is_storefront_category` boolean flag.

### Unique Category Slug Logic
* Automatically generate slugs.
* Protect existing categories. Prevent deletion if any product links to it:
  ```php
  if ($category->products()->exists()) {
      return back()->with('error', 'Deletion protected: Category contains products.');
  }
  ```

---

## 14. Safe Image Upload & Multi-Level File Limit Stack

Ensure large uploads (up to 100MB) are supported without throwing HTTP 500 errors.

```php
// CarouselController.php size interception
if ($request->isMethod('POST') && empty($request->all()) && $request->server('CONTENT_LENGTH') > $this->getMaxBytes()) {
    return back()->withErrors(['image' => 'The upload exceeded the server request-size limit. Please upload an image no larger than 100MB.']);
}
```

### Verification Stack for Uploads
1. **PHP settings:** `upload_max_filesize = 100M`, `post_max_size = 105M`, `memory_limit = 256M`.
2. **Form enctype:** Ensure `<form enctype="multipart/form-data">` is declared.
3. **GD extension fallback:** If the GD graphics library is missing locally, save original uploads directly rather than calling scaling libraries.

---

## 15. Centralized Image URL Resolver Accessor

Use a single model accessor to resolve image URLs dynamically regardless of where or how the path is stored in the database.

```php
public function getImageUrlAttribute(): ?string
{
    $path = trim((string) $this->image_name);
    if ($path === '') return null;
    
    // 1. External Link
    if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
        return $path;
    }
    
    // Normalize path
    $path = ltrim(str_replace('\\', '/', $path), '/');
    
    // 2. Storage Prefix
    if (\Illuminate\Support\Str::startsWith($path, 'storage/')) {
        $relativePath = \Illuminate\Support\Str::after($path, 'storage/');
        return \Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($relativePath)
            : null;
    }
    
    // 3. Public Asset Path
    if (is_file(public_path($path))) {
        return asset($path);
    }
    
    // 4. Public Disk Path
    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
    }
    
    return null;
}
```

---

## 16. Logo and Device Branding Pipeline

Ensure all logo placements are responsive, have appropriate alt text, and use high-resolution assets:

* **Header wordmark:** `one-stop-mart-logo-header.png`
* **Footer logo:** `one-stop-mart-logo-footer.png`
* **Admin topbar:** `one-stop-mart-logo-admin.png`
* **Favicons:** Store standard variants in the public directory (`favicon-16x16.png`, `favicon-32x32.png`, `apple-touch-icon.png`).

---

## 17. Admin Panel Rebranding Guidelines

Cleanly isolate visual changes from core controller logic:

* Custom CSS variables should be injected to set primary colors (`--admin-primary`, `--admin-bg`, etc.).
* Replace dashboard cards to display relevant analytics (e.g. Sales, Categories, Orders) rather than e-learning variables.
* Sidebar: Update brand text and include emblem next to titles.

---

## 18. Interactive Owner Account Creation Command

Create a custom Artisan command to provision admin accounts securely without hardcoding credentials in seeders.

```php
// app/Console/Commands/CreateAdmin.php
protected function handle()
{
    $email = $this->ask('Enter Administrator Email');
    $password = $this->secret('Enter Administrator Password');
    // Validate and create User, attach Admin role
}
```

---

## 19. Authorization Simplification & Role Consolidation

Ensure access controls are mapped cleanly:

* **Customer Role:** Denied access to any admin route.
* **Admin Role:** Full administrative access (bypassing granular permissions if requested to consolidate roles).
* **Consolidation Seeder:** Converted using a database transaction:
  ```php
  DB::transaction(function () {
      // 1. Assign Admin role to active admins
      // 2. Safely delete obsolete roles (e.g. Super Admin)
  });
  ```

---

## 20. Removal of Legacy Branding References

Perform comprehensive codebase checks to purge legacy wording from active UI:

* **Views:** Scan title sections, meta headers, copyrights, and placeholders.
* **Notifications:** Email subjects, password reset salutations, and SMS signatures.
* **Exemptions:** Technical variables, database columns, migration names, and unit tests are allowed to retain legacy terminology for backward compatibility.

---

## 21. Authentication Redirect Integrity

Update redirects in the following files to prevent users from landing on legacy/non-existent pages:

* `app/Http/Controllers/Auth/LoginController.php`
* `app/Http/Controllers/Auth/GoogleAuthController.php`
* `app/Http/Middleware/RedirectIfAuthenticated.php`

All users should land on `/admin` (for administrators) or `route('home')` (for customers).

---

## 22. Storage Disk & Symlink Management

Understand folder mapping before coding:

```
public/{{THEME_SLUG}}/    --> Static assets (CSS, JS, Static images)
storage/app/public/        --> Uploaded assets (Banners, Products, User files)
public/storage/           --> Symlink pointing to storage/app/public/
```

Verify symlink integrity:
```bash
php artisan storage:link
```

---

## 23. Viewport & Responsive UI Validation Layouts

Test layouts across these device viewports to verify responsiveness:

| Device Type | Viewport Size | Expected Behavior |
|---|---|---|
| **Large Desktop** | 1440x900 | Horizontal grid layout, full sidebar |
| **Standard Laptop**| 1280x800 | Fit grids, wrap navigation cleanly |
| **Tablet** | 768x800 | Collapsed mobile drawer, stacked grids |
| **Mobile Standard**| 375x760 | Centered buttons, hidden sidebar |

---

## 24. Automated QA: Playwright Instrumenting

Capture runtime console logs and response codes alongside screenshots:

```javascript
// playwright_test.js snippet
page.on('console', msg => {
    if (msg.type() === 'error') console.error('Browser error:', msg.text());
});
const response = await page.goto('http://127.0.0.1:8000/admin');
if (response.status() !== 200) throw new Error('Admin page loaded with code ' + response.status());
```

---

## 25. Automated Testing Protocols

* **Database Isolation:** Never execute tests against a live production database. Use a dedicated test database (e.g., SQLite in-memory in `phpunit.xml`).
* **Privilege Escalation checks:** Assert that customers cannot access administrative endpoints.
* **Slug Collisions:** Assert that duplicate categories automatically get assigned incremental unique suffixes.

---

## 26. Staging & Commit Git Workflows

* **Review before staging:** Run `git status` and `git diff` to make sure you only stage files modified during the current phase.
* **Keep configuration values local:** Make sure `.env` is listed in `.gitignore` and is never staged.
* **Descriptive commits:** Group modifications by features and document them clearly.

---

## 27. Safe Production Deployment Protocol

```
1. Back up database, .env, and storage uploads.
2. Build assets locally: npm run build
3. Execute deployment pulls: git pull origin main
4. Run composer: composer install --no-dev --optimize-autoloader
5. Run migrations safely: php artisan migrate --force
6. Rebuild symlink: php artisan storage:link
7. Clear and rebuild caches: php artisan optimize:clear
```

---

## 28. Customer Handover Readiness Checklist

* [ ] Custom Artisan admin account command tested and verified.
* [ ] Admin panel access URL provided to the customer.
* [ ] Zero legacy branding visible in notifications or layouts.
* [ ] Production setup guides documented.
* [ ] Database credentials excluded from commit history.

---

## 29. Troubleshooting & Common Failure Patterns

### Failure 1: Missing `storage` symlink on Windows
* **Symptom:** Banners or product images show as broken-image icons in browser.
* **Diagnosis:** URL path returns 404, but file exists inside `storage/app/public/banners/`.
* **Fix:** Delete the broken symlink inside `public/` and run `php artisan storage:link` inside Administrator Command Prompt.

### Failure 2: Large image uploads return HTTP 500
* **Symptom:** Form submit fails on large images with no validation message.
* **Cause:** Upload size exceeds PHP configuration limit `post_max_size` or `upload_max_filesize`.
* **Fix:** Increase size limits in `php.ini` and add pre-validation content length checks inside the controller.

---

## 30. Operational Decision Gates

* **Shared Category Table:** If a category table contains both internal labels and public storefront tags, **STOP** and add a filter flag (`is_storefront_category`). Do not rely on whitelisting names or IDs.
* **Model Rename Requests:** If asked to change the model/table names from legacy terms (e.g. `Course` to `Product`), verify if relationships are extensively used. If so, **STOP** and change view labels only.

---

## 31. Reusable Code Patterns Library

### Category Deletion Guard
```php
public function destroy(Category $category)
{
    if ($category->courses()->exists()) {
        return back()->with('error', 'Cannot delete category containing products.');
    }
    $category->delete();
    return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
}
```

### Unique Slug Generator
```php
public function generateUniqueSlug(string $name, ?int $ignoreId = null): string
{
    $slug = \Illuminate\Support\Str::slug($name);
    $originalSlug = $slug;
    $count = 1;
    
    while (\App\Models\Category::where('slug', $slug)
        ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
        ->exists()) {
        $slug = "{$originalSlug}-{$count}";
        $count++;
    }
    
    return $slug;
}
```

---

## 32. AI Agent Behavioral Code of Conduct

1. **Safety First:** Never run `migrate:fresh` unless the user explicitly requests to wipe all data.
2. **Review first:** Analyze paths, route definitions, and active models before editing.
3. **Verify:** Do not claim a page displays correctly without checking browser responses (HTTP status 200).

---

## 33. Task Execution Report Template

Use the following template when completing migration phases:

```markdown
### Summary of Changes
- Integrated [file name] to handle [feature]
- Swapped legacy logos with [new logo asset]

### Verification Checklist
- **Admin Dashboard Access:** [Success/Failed]
- **Storefront Display:** [Success/Failed]
- **Image URL Response:** [HTTP 200 OK]
```

---

## 34. Acceptance Verification Checklist

* [ ] Frontend theme styles isolated to public subdirectory.
* [ ] No duplicate image tags or dual source attributes in views.
* [ ] Sidenav navigation routes update active styles correctly.
* [ ] Large uploads (up to 100MB) fail gracefully with error messages rather than HTTP 500.
* [ ] All tests pass without warnings or database configuration failures.
