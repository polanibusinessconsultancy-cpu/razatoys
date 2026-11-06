# RazaToys — Quality Assurance & Testing Strategy
**Document Version:** 1.0.0  
**Target:** 100% Verified Functionality, Zero Regression, High Performance

---

## 1. Testing Philosophy & Pyramid

The RazaToys testing framework enforces strict verification at every layer of the application to ensure that the brand transformation introduces zero functional regressions, preserves all historical data, and delivers an accessible, high-performance customer experience.

```
                  ┌────────────────────────┐
                  │   MANUAL & EXPLORATORY │  (Edge cases, visual feel)
                  ├────────────────────────┤
                  │  RESPONSIVE REGRESSION │  (Playwright Screenshots: 6 Viewports)
                  ├────────────────────────┤
                  │  END-TO-END AUTOMATION │  (Complete Purchase & Tracking Flows)
                  ├────────────────────────┤
                  │  FEATURE & HTTP TESTS  │  (Route Status, Form Validation, Auth)
                  ├────────────────────────┤
                  │  UNIT & SERVICE TESTS  │  (Pricing, Cart, Coupons, Stock)
                  └────────────────────────┘
```

---

## 2. Test Execution Layers

### 2.1 Unit & Business Service Tests
* **Cart Calculations:** Test line item totals, quantity increments/decrements, zero-quantity deletions, and subtotal math in PKR.
* **Coupon & Discount Engine:** Verify percentage discounts, fixed PKR discounts, minimum order threshold rules, and expired coupon rejections.
* **Pricing & Discount Display:** Ensure correct calculation of regular price (`monthly_price`), selling price (`weekly_price`), and discount percentage badge formatting.
* **Image URL Resolution:** Test that `Product::getImageUrlAttribute` correctly returns valid WebP images, resolves existing PNGs, and cleanly falls back to `raza-teddy-placeholder.webp` when no image is assigned.

### 2.2 Feature & HTTP Endpoint Tests
* **Route Availability (200 OK):**
  - Homepage (`/`)
  - Shop Catalog (`/shop`, `/shop?category=toys`, `/shop?category=kitchenware`)
  - Product Details (`/product/{slug}`)
  - Shopping Cart (`/cart`)
  - Checkout (`/checkout`)
  - Informational Pages (`/about`, `/contact`, `/faq`, `/terms-conditions`, `/privacy-policy`, `/shipping-delivery`, `/returns-refunds`)
  - Customer Order Tracking (`/track-order`)
* **Legacy URL Handling:** Verify that legacy perfume routes (`/men`, `/women`, `/attars`, `/oud`, `/perfumes`) redirect cleanly without throwing 404 or 500 errors.
* **Cart AJAX API:** Test `POST /cart/add/{slug}`, `POST /cart/update/{slug}`, `POST /cart/remove/{slug}`, and `GET /cart/count`.
* **Checkout Submission:** Test order generation in `orders` table under Cash on Delivery, Bank Transfer, and Stripe scenarios.

### 2.3 End-to-End & Playwright Automation
Automated test scripts powered by `@playwright/test` and Google Chrome headless validate critical customer journeys:
1. **User Journey 1 — Product Discovery to Cart:**
   - Visitor lands on Homepage.
   - Clicks "Toys" category badge.
   - Filters by price or age group.
   - Clicks product card to view details.
   - Adds product to cart; verifies header badge updates instantly.
2. **User Journey 2 — Cart to Completed Checkout:**
   - Navigates to `/cart`.
   - Modifies quantity; verifies real-time total update.
   - Proceeds to `/checkout`.
   - Fills shipping information and selects Cash on Delivery.
   - Submits order; verifies redirection to `/order-status/{orderNumber}`.
3. **User Journey 3 — Order Tracking:**
   - Enters newly created `order_number` and phone number on `/track-order`.
   - Verifies 5-stage fulfillment timeline displays accurately.

---

## 3. Responsive Visual Regression Suite

To ensure flawless presentation across all devices, automated full-page screenshots are captured and visually audited across six distinct device viewports:

| Device Target | Viewport Dimensions | Key Validation Points |
|---|---|---|
| **Large Desktop** | `1440 x 900` | Full header layout, 4-5 product cards per row, no whitespace gaps |
| **Laptop** | `1024 x 768` | 4 cards per row, compact search bar, sticky navigation |
| **Tablet Portrait** | `768 x 1024` | 3 cards per row, touch-friendly buttons, sidebar toggling to drawer |
| **Mobile Pro** | `430 x 932` | 2 cards per row, bottom action bar, zero horizontal scroll |
| **Mobile Standard** | `390 x 844` | Header mobile hamburger, readable prices, stacked cart items |
| **Mobile Compact** | `360 x 800` | Accessible touch targets (44px+), no clipped text or images |

---

## 4. Performance & Core Web Vitals Benchmarks

Every major customer-facing page must satisfy strict performance criteria:
* **Largest Contentful Paint (LCP):** `< 2.2s` (Hero banner preloaded with high fetch priority).
* **Cumulative Layout Shift (CLS):** `< 0.05` (Explicit `width` and `height` on all images).
* **First Input Delay (FID) / Interaction to Next Paint (INP):** `< 150ms`.
* **Google Lighthouse Thresholds:**
  - Performance: `>= 90`
  - Accessibility: `>= 95`
  - Best Practices: `>= 95`
  - SEO: `>= 95`

---

## 5. Security & Data Integrity Assurance

1. **Non-Destructive Database Guarantee:**
   - Under no circumstances will migration rollback, fresh migration, or table drops be executed.
   - All tests run against verified seeded test fixtures or transactional database rollbacks.
2. **Customer Data Isolation:**
   - Order tracking requires matching order number + email/phone to prevent unauthorized order inspection.
   - Customer account profile strictly enforces authentication checks.
3. **Input Sanitization & CSRF:**
   - Contact form, newsletter subscription, and reviews sanitized via HTMLPurifier.
   - All forms and AJAX calls protected by CSRF tokens and Content Security Policy nonces.
