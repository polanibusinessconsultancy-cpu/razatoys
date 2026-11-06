# RazaToys — Comprehensive 21-Day Implementation Plan
**Document Version:** 1.0.0  
**Project:** RazaToys E-Commerce Transformation  
**Estimated Timeline:** 21 Working Days  
**Methodology:** Phased, Non-Destructive, Verification-Driven Delivery

---

## Roadmap Overview

```
Week 1: Days 1–5   │ Phase 1: Audit & Preparation  ──►  Phase 2: Design System & Shared Layout
Week 2: Days 6–10  │ Phase 3: RazaToys Homepage    ──►  Phase 4: Shop, Catalog & Product Detail
Week 3: Days 11–15 │ Phase 4 (Wishlist)           ──►  Phase 5: Cart, Checkout & Order Tracking
Week 4: Days 16–21 │ Phase 6: Accounts & Content  ──►  Phase 7: Responsive QA  ──►  Phase 8: Launch
```

---

## Phase 1: Project Audit, Protection & Implementation Preparation
**Timeline:** Days 1–2 (Completed in Phase 1 Mandate)  
**Effort:** 16 Developer Hours  
**Dependencies:** None

### Objectives
* Inspect the full codebase, database structure, assets, and routes.
* Protect existing data, customer records, and order history from accidental modification.
* Isolate the root cause of the "cooking-pot placeholder" product image bug.
* Author the six foundational planning and architectural documents in `docs/razatoys/`.

### Detailed Tasks
1. Audit full technology stack (PHP 8.2, Laravel 11, Livewire 3, MySQL, Vite, Tailwind).
2. Inspect Git repository, verify clean working tree on `main`, and formulate branch strategy.
3. Inventory all 26 database tables and 298 registered routes.
4. Investigate product image resolution in `Product.php`, `PolaniController.php`, and `InstallOneStopMart30Products.php`.
5. Create `PROJECT_AUDIT.md`, `ARCHITECTURE.md`, `DESIGN_SYSTEM.md`, `IMPLEMENTATION_PLAN.md`, `PAGE_CHECKLIST.md`, and `TESTING_STRATEGY.md`.

### Acceptance Criteria
* [x] Six planning documents authored and verified in repository.
* [x] Zero application files modified or deleted.
* [x] Zero database records changed, wiped, or truncated.
* [x] Product image placeholder root cause documented with code evidence.

### Risks & Mitigations
* *Risk:* Database corruption or unintentional schema alteration.  
  *Mitigation:* Strict read-only mode during Phase 1; complete SQL backup verified.

---

## Phase 2: Design System, Brand Theme, Shared Header, Navigation & Footer
**Timeline:** Days 3–5  
**Effort:** 24 Developer Hours  
**Dependencies:** Phase 1 Approval

### Objectives
* Establish the RazaToys visual brand identity across CSS tokens, layout templates, header, and footer.
* Integrate the approved RazaToys teddy bear logo and favicon suite.
* Build the colorful, responsive header with live search, category mega-menu, and cart badge.

### Detailed Tasks
1. Create `public/razatoys/css/` directory structure with modular CSS files (`tokens.css`, `header.css`, `footer.css`, `buttons.css`).
2. Integrate the approved RazaToys logo in header, mobile navigation, and footer.
3. Build the announcement topbar with free delivery, returns, and WhatsApp link.
4. Construct the primary navigation with "Shop by Age" and "Toy Categories" dropdown menus.
5. Create the responsive mobile drawer with smooth slide-in animation and category accordions.
6. Build the playful footer with newsletter subscription, category shortcuts, trust badges, and payment icons.
7. Integrate the floating WhatsApp chat widget with customized greeting message.

### Acceptance Criteria
* Header renders flawlessly across 1440px, 1024px, 768px, and 390px viewports.
* RazaToys logo displays sharply with correct aspect ratio and no distortion.
* Category dropdown and mobile navigation open and close smoothly without JavaScript errors.
* Footer links and contact details function properly.

### Risks & Mitigations
* *Risk:* CSS regressions affecting checkout or admin pages.  
  *Mitigation:* Strict namespace scoping using `.razatoys-storefront` wrapper class.

---

## Phase 3: Complete RazaToys Homepage Implementation
**Timeline:** Days 6–8  
**Effort:** 24 Developer Hours  
**Dependencies:** Phase 2

### Objectives
* Build a vibrant, high-converting toy-centric homepage.
* Implement the dynamic full-bleed hero banner with left-aligned headline and cheerful CTA.
* Create the circular toy category showcase, promotional deal banners, and product slider.

### Detailed Tasks
1. Implement `resources/views/razatoys/home.blade.php`.
2. Configure dynamic banner carousel reading from `carousel_slides` table with fallback to RazaToys hero art.
3. Build the "Shop by Age" and "Top Toy Categories" interactive circular card grid.
4. Build promotional campaign cards: "Deal of the Day", "New Arrivals", "Trending Toys".
5. Construct the "Best Selling Toys" product card slider with touch-swipe support.
6. Build the "Parent & Child Favorites" spotlight featured product section.
7. Create customer testimonials / parent reviews carousel.
8. Add the trust strip: 100% Non-Toxic & Child-Safe, Fast Nationwide Delivery, 7-Day Replacement.

### Acceptance Criteria
* Homepage loads in under 2.0s with preloaded LCP hero banner.
* Carousel slides cycle smoothly with pause-on-hover.
* Product slider navigates effortlessly on touch and click.
* All links correctly route to filtered catalog views.

### Risks & Mitigations
* *Risk:* Heavy banner graphics slowing down mobile page speed.  
  *Mitigation:* Modern WebP compression, lazy loading for below-fold images, explicit width/height attributes.

---

## Phase 4: Shop Catalog, Toy Categories, Product Details & Wishlist
**Timeline:** Days 9–12  
**Effort:** 32 Developer Hours  
**Dependencies:** Phase 2, Phase 3

### Objectives
* Redesign the main shop and category catalog pages with real-time filtering.
* Eliminate the "cooking-pot placeholder" by provisioning genuine toy product photography and clean fallbacks.
* Create a detailed, conversion-focused product page with photo gallery, specifications, and reviews.
* Implement customer wishlist functionality.

### Detailed Tasks
1. Build `resources/views/razatoys/shop.blade.php` and `category.blade.php`.
2. Create the faceted filter sidebar (Category, Age Group, Price Range, In-Stock Only, Sorting).
3. Build mobile slide-out filter drawer with touch-friendly checkboxes and sliders.
4. Resolve product imagery: map each toy product to unique, colorful toy photography; assign kitchenware products to dedicated kitchen assets; set default fallback to the friendly RazaToys teddy bear.
5. Redesign `resources/views/razatoys/product.blade.php`:
   - Interactive multi-image gallery with zoom.
   - Price calculation, stock badge, and age recommendation tag.
   - Child-safety indicators and material specifications (BPA-free, non-toxic).
   - Star rating distribution and customer review submission form.
   - "Related Toys You'll Love" recommendation grid.
6. Implement Wishlist: add to wishlist button on all cards and product details; persist to session/database; wishlist counter badge in header.

### Acceptance Criteria
* Catalog filtering by category, price, and sorting updates results cleanly.
* Cooking-pot placeholder is 100% eliminated from unrelated products.
* Product detail page displays full specifications, clear price, and functional Add-to-Cart.
* Wishlist items save reliably and can be reviewed in customer profile.

### Risks & Mitigations
* *Risk:* Large number of product assets causing slow queries or missing images.  
  *Mitigation:* Rigorous media audit script ensuring zero broken image paths.

---

## Phase 5: Cart, Checkout, Payment & Order Tracking
**Timeline:** Days 13–15  
**Effort:** 24 Developer Hours  
**Dependencies:** Phase 4

### Objectives
* Upgrade the cart and checkout experience with colorful, trust-building RazaToys styling.
* Remove legacy LMS course hooks and fragrance references from cart, checkout, and tracking views.
* Deliver an intuitive order tracking timeline.

### Detailed Tasks
1. Restyle Livewire `Shoppingcart` component with free delivery progress bar (`Add Rs. 550 more for Free Delivery!`), quantity steppers, and clear line-item totals.
2. Remove legacy LMS enrollment hook (`FreeCourseService`) from cart workflow.
3. Restyle Livewire `Checkout` component:
   - Clean, frictionless customer contact and shipping address form.
   - Payment method selector: Cash on Delivery (COD), Direct Bank Transfer, Card via Stripe.
   - Order summary box with coupon code input and instant discount calculation.
4. Redesign order confirmation receipt (`order-status.blade.php`) with printable invoice option.
5. Overhaul `track-order.blade.php`:
   - Replace legacy "course access" wording with retail fulfillment stages: Order Placed -> Verified -> Packed at Warehouse -> Out for Delivery -> Delivered.
   - Display carrier tracking number and customer support WhatsApp button.

### Acceptance Criteria
* Adding products to cart, updating quantities, and proceeding to checkout works flawlessly.
* Cash on Delivery and Stripe checkouts successfully create orders in `orders` table.
* Order tracking displays correct status for both authenticated users and guest lookups.
* Zero mentions of courses, lectures, or perfumes in the order lifecycle.

### Risks & Mitigations
* *Risk:* Payment webhook failures or duplicate order submissions.  
  *Mitigation:* Atomic DB transactions and unique checkout attempt tokens.

---

## Phase 6: Customer Accounts & Informational Pages
**Timeline:** Days 16–17  
**Effort:** 16 Developer Hours  
**Dependencies:** Phase 5

### Objectives
* Refresh customer account dashboards, login, and registration screens.
* Redesign all informational and policy pages with friendly, engaging RazaToys copywriting.

### Detailed Tasks
1. Restyle customer authentication views (`sign-in.blade.php`, `sign-up.blade.php`, `forgot-password.blade.php`) with RazaToys branding.
2. Build customer account dashboard (`/account/profile`) showing past toy orders, delivery statuses, and saved addresses.
3. Implement redesigned informational pages:
   - `About Us`: The story of RazaToys, mission to inspire creativity through safe play, quality standards.
   - `Contact Us`: Customer service hours, telephone, email form, and WhatsApp link.
   - `FAQ`: Interactive accordion covering ordering, shipping, returns, and toy safety.
   - `Shipping & Delivery`: Shipping zones across Pakistan, COD guidelines, delivery estimates.
   - `Returns & Refunds`: Transparent 7-day return and replacement policy.
   - `Terms & Conditions` and `Privacy Policy`.

### Acceptance Criteria
* Customer login, registration, and profile updates execute seamlessly.
* All informational pages feature clean, professional RazaToys typography and brand graphics.
* Contact form successfully logs messages or sends admin notification.

### Risks & Mitigations
* *Risk:* Broken internal navigation links across policy pages.  
  *Mitigation:* Comprehensive route link verification in automated tests.

---

## Phase 7: Responsive Design, Cross-Browser Testing & Performance
**Timeline:** Days 18–19  
**Effort:** 16 Developer Hours  
**Dependencies:** Phase 6

### Objectives
* Perform rigorous responsive audits across mobile, tablet, and desktop viewports.
* Optimize asset delivery to achieve Lighthouse scores of 90+ across Performance, Accessibility, and SEO.

### Detailed Tasks
1. Execute Playwright automated visual regression tests at 1440px, 1024px, 768px, 430px, 390px, and 360px.
2. Audit color contrast ratios for all text elements against WCAG AA accessibility standards.
3. Audit touch targets (minimum 44x44px for buttons, links, and inputs).
4. Run Google Lighthouse audit on Home, Shop, Product, Cart, and Checkout pages.
5. Minify all production CSS and JavaScript assets.
6. Verify smooth operation on Chrome, Firefox, Safari (iOS), and Edge.

### Acceptance Criteria
* Zero horizontal scroll or layout overflow across all tested viewports.
* Lighthouse Performance >= 90, Accessibility >= 95, SEO >= 95.
* Automated responsive screenshot suite passes with 100% visual integrity.

---

## Phase 8: Production Readiness, Final Verification & Deployment
**Timeline:** Days 20–21  
**Effort:** 16 Developer Hours  
**Dependencies:** Phase 7

### Objectives
* Final end-to-end user journey validation.
* Database integrity verification (all historical records intact).
* Prepare deployment playbook, documentation, and handoff.

### Detailed Tasks
1. Test complete purchase cycle from homepage to delivered order status.
2. Verify admin dashboard functions (product creation, inventory update, order fulfillment, banner uploads).
3. Clear all development scratch files and flush application caches (`optimize:clear`, `view:cache`, `config:cache`, `route:cache`).
4. Generate final deployment checklist and rollback instructions.
5. Present completed project for stakeholder review.

### Acceptance Criteria
* 100% of acceptance criteria across Phases 1–8 verified.
* All historical data, orders, and customer accounts intact.
* Application ready for immediate, risk-free production deployment.
