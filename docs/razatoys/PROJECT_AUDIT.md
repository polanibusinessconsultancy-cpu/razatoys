# RazaToys — Comprehensive Project Audit
**Document Version:** 1.0.0  
**Audit Date:** November 4, 2026  
**Auditor:** Lead Software Engineer  
**Target Application:** RazaToys E-Commerce Platform (Transformed from One Stop Mart / Polani Core)

---

## 1. Executive Summary & Historical Evolution

The application codebase at `C:/laraworld/ubaid_web` represents a layered, multi-iteration enterprise Laravel application that has undergone several significant domain evolutions:
1. **LMS Foundation (Origins):** Originally constructed as an Islamic Economics & LMS platform featuring courses, video lecture streaming via secure tokens, quizzes, student assignments, certificates, and instructor profiles.
2. **Polani Fragrances Era:** Adapted into a luxury perfume e-commerce storefront (`polani` namespace), integrating fragrance attributes (top/heart/base notes, longevity, sillage, projection, seasonal recommendations), sample cards, and fragrance categories (`Men`, `Women`, `Attars`, `Oud`, `Signature`, `Scented Candles`).
3. **One Stop Mart Phase:** Transformed into a general multi-category retail marketplace ("One Stop Mart") with 30 fresh products across 6 active categories (`Toys`, `Kitchenware`, `Electronics`, `Fashion`, `Home & Living`, `Sports`), full-bleed hero banner layout, and compact responsive components.
4. **RazaToys Mandate (Current Transformation):** Converting the storefront into **RazaToys** — a joyful, premium, colorful, toy-focused e-commerce destination with warm pastel brand accents (yellow, pink, blue, green, cream), playful modern UI, robust product discovery, customer wishlist, seamless cart and checkout, while maintaining continued backward compatibility and support for secondary retail lines (such as kitchenware).

---

## 2. Technology Stack Verification

| Layer | Verified Technology | Version / Configuration | Notes & Status |
|---|---|---|---|
| **Backend Framework** | Laravel Framework | `11.45.0` | Active, optimized autoloader |
| **PHP Runtime** | PHP CLI / CGI | `8.2.12 (ZTS Visual C++ 2019 x64)` | Meets Laravel 11 requirements |
| **Reactive Component Layer** | Livewire & Volt | `livewire/livewire ^3.5`, `livewire/volt ^1.0` | Powers Cart, Checkout, Dynamic UI |
| **Database Engine** | MySQL / MariaDB (XAMPP) | Port `3306`, Database: `ubaid_web` | 26 active tables, relational constraints |
| **Asset Bundling & Build** | Vite + Tailwind CSS | `vite ^4.5.5`, `tailwindcss ^3.1.0` | Custom CSS builds + PostCSS + PurgeCSS |
| **Frontend Styling** | Scoped BEM Vanilla CSS + Tailwind | `public/one-stop-mart/css/`, `public/polani/css/` | Separate minified stylesheets per page |
| **Image Processing** | Sharp + Native WebP | `sharp ^0.34.5` (Node), WebP pipeline | Supports high-performance WebP fallbacks |
| **Payment Gateway** | Stripe SDK + Custom COD / Bank | `stripe/stripe-php ^16.3` | Multi-provider checkout integration |
| **PDF Generation** | Laravel DomPDF | `barryvdh/laravel-dompdf ^3.1` | Used for order receipts / invoices |
| **Sanitization & Security** | HTMLPurifier + Phone Validator | `mews/purifier ^3.4`, `propaganistas/laravel-phone ^5.3` | Clean customer input handling |
| **Browser Testing** | Playwright + Headless Chrome | `@playwright/test ^1.61.1`, Chrome 120+ | Multi-viewport automated verification |

---

## 3. Git Repository Status & Application Protection Baseline

### Current Git Status
* **Active Branch:** `main`
* **Upstream Synchronization:** Up to date with `origin/main`
* **Working Tree:** Clean (`nothing to commit, working tree clean`)
* **Recent Commits:**
  - `0361027`: Update One Stop Mart storefront, catalog and UI
  - `d3ca5ce`: Phase 13: Achieve genuine 98+ Lighthouse performance & 100 accessibility scores with WebP images
  - `de80a2c`: Rename README to One Stop Mart
  - `290e8bd`: Branding Cleanup - Remove legacy IEC and Islamic Economics branding from active UI and templates

### Application Protection Principles for Phase 1
1. **Zero Data Destruction:** No database records (`products`, `categories`, `orders`, `users`, `ratings`, `carousel_slides`) will be wiped, dropped, or truncated.
2. **Migration Safety:** No destructive migrations (`migrate:fresh`, `db:wipe`, `migrate:reset`) are permitted.
3. **Preservation of Snapshots:** Historical customer orders, order line items (JSON), addresses, and transaction histories must remain intact for audit and accounting purposes.
4. **Isolated Branch Strategy:** All subsequent implementation work will occur in dedicated, reviewable feature branches (e.g., `feature/razatoys-phase2-design-system`).
5. **Storefront Non-Interference:** The active storefront remains fully operational while planning and baseline assets are prepared.

---

## 4. Comprehensive Architecture & Route Inventory

The application currently registers **298 routes**. The primary customer-facing and administrative touchpoints include:

### Storefront Routes (`PolaniController`)
* `/` (`home`): Homepage with dynamic carousel, featured categories, promo banners, best-selling product slider, and spotlight product.
* `/shop` (`polani.collection`): Full product catalog with real-time category filtering, min/max price sliders, keyword search, and multi-option sorting.
* `/product/{slug}` (`polani.product`): Detailed product showcase with image viewer, discount calculations, stock status, ratings/reviews, and session/AJAX Add-to-Cart.
* `/deals` (`polani.deals`): Special promotional deals and discounted product collections.
* `/new-arrivals` (`polani.new-arrivals`): Curated new release products.
* `/cart` / `/shopping-cart` (`shopping-cart`): Cart management interface powered by `<livewire:shoppingcart />`.
* `/checkout` (`polani.checkout`): Multi-step checkout powered by `<livewire:checkout />` supporting COD, Bank Transfer, and Card payments.
* `/order-status/{orderNumber}` (`polani.order-status`): Post-checkout order confirmation and invoice receipt.
* `/track-order` (`polani.track-order`): Customer order timeline tracker (Lookup by Order Number + Email/Phone).
* **Informational Pages:** `/about`, `/contact`, `/faq`, `/terms-conditions`, `/privacy-policy`, `/shipping-delivery`, `/returns-refunds`.

### Authentication & Account Routes
* `/sign-in` (`sign-in`): Customer and staff authentication.
* `/sign-up` (`sign-up`): Customer account registration.
* `/account/profile` (`users.profile.view`): User profile management and order history.
* `/auth/google`: OAuth login integration.

### Admin Dashboard Routes (`Admin\AdminController` & Modules)
* `/admin` (`admin.dashboard`): Central metric dashboard (product counts, category counts, pending orders, revenue, user accounts).
* `/admin/products`: Product catalog CRUD, stock management, pricing, category assignment, and storefront visibility toggling.
* `/admin/categories`: Category taxonomy management.
* `/admin/orders`: Order fulfillment, invoice printing, tracking assignment, status updates.
* `/admin/carousels`: Banner slides, promotional image uploads, CTA linking, display ordering.
* `/admin/reviews`: Product review moderation and approval.

---

## 5. Database Schema & Catalog Architecture

### Database Table Inventory (26 Total Tables)
1. `products`: Core product catalog. Contains 42 records. Columns: `id`, `name`, `slug`, `description`, `long_description`, `instructor` (legacy), `weekly_price` (active selling price), `monthly_price` (regular/old price), `image_path`, `intro_video_url`, `category_id`, `is_free`, `purchase_model`, `is_storefront_visible`, `catalog_source`, `product_kind`.
2. `categories`: Category hierarchy. Columns: `id`, `name`, `slug`, `description`, `image`, `is_storefront_category`.
3. `orders`: Order header records with snapshot customer and shipping data.
4. `shoppingcarts`: Authenticated user cart line items with product relations.
5. `carousel_slides`: Dynamic storefront banners with title, subtitle, CTA text/url, image url, page key (`home`, `deals`, `new-arrivals`), active flag.
6. `ratings`: Customer reviews and star ratings linked polymorphically to rateable entities.
7. `footer_settings`: Editable footer contact numbers, WhatsApp numbers, social links, and copyright text.
8. `users`, `roles`, `role_user`: Multi-role user accounts (Admin, Customer, etc.).
9. `payment_methods`: Configured payment gateways (COD, Card, Bank).
10. `coupons`: Discount coupon engine.
11. `blogs`, `contact_messages`, `cities`, `countries`, `states`, `user_devices`, `traffic_logs`, etc.

### Catalog State Audit
* **Active Visible Storefront Products:** Exactly 30 products (`is_storefront_visible = 1`, `catalog_source = 'one_stop_mart_30'`).
* **Hidden Legacy Products:** 12 Polani Fragrance products preserved in database (`is_storefront_visible = 0`).
* **Active Storefront Categories (6 Total):**
  1. `Toys` (Slug: `toys`, ID: 7) — 5 products
  2. `Kitchenware` (Slug: `kitchenware`, ID: 8) — 5 products
  3. `Electronics` (Slug: `electronics`, ID: 10) — 5 products
  4. `Fashion` (Slug: `fashion`, ID: 11) — 5 products
  5. `Home & Living` (Slug: `home-living`, ID: 12) — 5 products
  6. `Sports` (Slug: `sports`, ID: 13) — 5 products
* **Deactivated Legacy Categories (7 Total):** `Men`, `Women`, `Attars`, `Oud`, `Signature`, `Scented Candles`, `Perfumes`.

---

## 6. Investigation of the "Cooking-Pot Placeholder" Product Image Issue

### The Observed Problem
When navigating the storefront, several products (including items unrelated to cooking, such as bamboo cutting boards, vegetable choppers, or mixing bowls) display an identical image of cooking pots and pans.

### Technical Root Cause Determination (Verified Without Data Modification)
1. **Seeder Image Reuse:** In `app/Console/Commands/InstallOneStopMart30Products.php` (lines 125–167) and `app/Services/OneStopMartCatalogImporter.php` (lines 180–310):
   - Only a single real cookware photograph existed (`product-cookware.png`).
   - The category icon for Kitchenware (`cat-kitchenware.webp`) is an illustration depicting a saucepan, pot, and frying pan.
   - During catalog installation, because unique product photography was unavailable for all 5 kitchenware products, `cat-kitchenware.webp` was directly assigned as the `image_path` for:
     - `Premium Bamboo Cutting Board Set` (Product ID 19)
     - `Stainless Steel Mixing Bowl Set` (Product ID 20)
     - `Multi-Purpose Vegetable Chopper` (Product ID 21)
2. **Fallback Model Resolution:** In `app/Models/Product.php` (`getImageUrlAttribute`), when a product lacks a custom uploaded image, it attempts to resolve the assigned `image_path`. Because `image_path` explicitly points to `one-stop-mart/images/cat-kitchenware.webp`, the cooking-pot artwork is rendered as the primary product image.
3. **View-Level Hardcoding:** In `resources/views/polani/partials/osm-product-card.blade.php`, fallback handling checks for image existence, but since `cat-kitchenware.webp` physically exists on disk, the system correctly serves it—causing the visual mismatch.

### Remediation Strategy for RazaToys
* For RazaToys, every product will be supplied with genuine, distinctive product imagery organized in `public/razatoys/images/products/`.
* Category artwork will be strictly isolated to category cards (`public/razatoys/images/categories/`) and never populated as a product's primary picture.
* A friendly, neutral toy placeholder (the approved RazaToys teddy mascot) will serve as the global default fallback when an image is missing or loading.

---

## 7. Technical Debt & Residue Inventory

1. **Legacy LMS Residue:**
   - Active database tables and models (`Course.php`, `Lecture.php`, `Quiz.php`, `Certificate.php`).
   - `Shoppingcart.php` still contains an automatic enrollment hook (`\App\Services\FreeCourseService::enrollInFreeCourse`).
   - Order tracking page contains strings referring to "course or lecture access" and links to `route('courses')`.
2. **Legacy Fragrance Residue:**
   - Storefront controller is named `PolaniController.php`.
   - Views reside in `resources/views/polani/`.
   - Product detail view still contains conditional checks for perfume notes, longevity, and projection.
3. **Wishlist Implementation:**
   - The header and product cards feature wishlist buttons, but they operate exclusively via client-side JavaScript / localStorage without persistent database persistence or synchronization across devices.
4. **Development Tooling Inconsistency:**
   - PHPUnit is declared in `require-dev` in `composer.json`, but `vendor/phpunit` is not currently installed in the workspace (indicating a production `--no-dev` build).
   - Playwright is installed in `node_modules/@playwright/test`, and Chrome is installed at `C:\Program Files\Google\Chrome\Application\chrome.exe`.
