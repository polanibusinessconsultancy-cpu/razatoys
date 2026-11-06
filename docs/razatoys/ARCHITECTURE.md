# RazaToys — Architecture Specification
**Document Version:** 1.0.0  
**Status:** Approved for Implementation  
**Target Platform:** Laravel 11 + Livewire 3 + MySQL + Modern Responsive Frontend

---

## 1. System Overview & Architectural Vision

The RazaToys e-commerce application provides a fast, vibrant, joyful, and reliable toy-shopping experience for families, parents, and gift-shoppers. The architecture emphasizes high reliability, low latency, robust data preservation, and a clear separation of concerns between core retail business logic, reactive components, and aesthetic presentation.

```
┌────────────────────────────────────────────────────────────────────────┐
│                        CLIENT VIEWPORTS & BROWSERS                     │
│      Mobile (360-430px)  │  Tablet (768-1024px)  │  Desktop (1440px+)  │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │ HTTPS / HTTP/2
                                    ▼
┌────────────────────────────────────────────────────────────────────────┐
│                        LARAVEL 11 WEB KERNEL                           │
│  Security Headers (CSP, Nonces) │ CSRF Protection │ Session Manager   │
├───────────────────────────────────┼────────────────────────────────────┤
│           STOREFRONT ROUTING LAYER & CONTROLLER DISPATCH               │
│               RazaToysController / PolaniController Alias              │
├───────────────────────────────────┼────────────────────────────────────┤
│           BLADE PRESENTATION LAYER│      LIVEWIRE 3 REACTIVE LAYER     │
│   • Header, Nav & Mega-Menu       │   • ShoppingCart Component         │
│   • Hero Banners & Promos         │   • Multi-Step Checkout Component  │
│   • Toy Category Grids & Badges   │   • CartIcon Header Badge Sync     │
│   • Product Cards & Sliders       │   • Dynamic Filter & Search Drawer │
│   • Content & Account Views       │   • QuickView Modal                │
├───────────────────────────────────┴────────────────────────────────────┤
│                           BUSINESS SERVICE LAYER                       │
│    • ProductService (Catalog, Pricing, Visibility, Stock)             │
│    • CartService (Session/DB Hybrid Resolution)                        │
│    • OrderService (Validation, Order Snapshot, Inventory Hold)         │
│    • TrackingService (Real-Time Order Fulfillment Timeline)            │
│    • MediaService (WebP Conversion, Image Optimization, Fallbacks)     │
├───────────────────────────────────┬────────────────────────────────────┤
│            ELOQUENT ORM           │         EXTERNAL INTEGRATIONS      │
│  • Product & Category             │   • Stripe Payment Gateway         │
│  • Order & OrderItem              │   • WhatsApp Direct API Link       │
│  • User, Role & Wishlist          │   • DomPDF Invoice Generator       │
│  • Rating & CarouselSlide         │   • Twilio SMS OTP Gateway         │
└───────────────────┬───────────────┴────────────────────────────────────┘
                    │ PDO Connection
                    ▼
┌────────────────────────────────────────────────────────────────────────┐
│                        MYSQL DATABASE ENGINE                           │
│    `products` │ `categories` │ `orders` │ `shoppingcarts` │ `users`    │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 2. Storefront Routing & Controller Strategy

To preserve existing URL structures, search engine indices, customer bookmarks, and legacy admin links while delivering the clean RazaToys experience, the system utilizes a non-breaking dual-binding routing approach:

1. **Canonical Storefront Controller:** A new, clean `RazaToysController` is established in `app/Http/Controllers/RazaToys/` or as an extended wrapper over existing actions.
2. **Backward Compatible Aliasing:** Routes in `routes/web.php` route directly to RazaToys actions while retaining named route aliases (`home`, `polani.collection`, `polani.product`, `polani.cart.add`, `polani.checkout`, `polani.track-order`).
3. **Graceful Redirection:** Any legacy perfume category URLs (`/men`, `/women`, `/attars`, `/oud`, `/perfumes`) are safely redirected with HTTP 301 to `/shop` or appropriate toy categories, ensuring zero 404 errors for legacy visitors.

---

## 3. Blade Presentation & Component Hierarchy

RazaToys implements a modular, DRY Blade view architecture residing in `resources/views/razatoys/` (inheriting from the base layout `razatoys.layout`):

```
resources/views/razatoys/
├── layout.blade.php                 # Master HTML5 skeleton, meta, fonts, asset includes
├── home.blade.php                   # Complete vibrant homepage
├── shop.blade.php                   # Catalog grid with sidebar & mobile filter drawer
├── category.blade.php               # Category-specific landing pages
├── product.blade.php                # High-conversion product details & image gallery
├── cart.blade.php                   # Cart shell wrapping <livewire:shoppingcart />
├── checkout.blade.php               # Checkout shell wrapping <livewire:checkout />
├── order-status.blade.php           # Post-checkout celebration & invoice print view
├── track-order.blade.php            # Cheerful timeline order tracking
├── deals.blade.php                  # Special offers & toy bundles
├── new-arrivals.blade.php           # Latest toy releases
├── informational/
│   ├── about.blade.php              # RazaToys story, quality commitment, child safety
│   ├── contact.blade.php            # Customer support, phone, email, WhatsApp
│   ├── faq.blade.php                # Interactive accordion FAQ
│   ├── shipping.blade.php           # Delivery timelines, rates, and cash-on-delivery
│   ├── returns.blade.php            # 7-day hassle-free replacement policy
│   ├── terms.blade.php              # Terms of service
│   └── privacy.blade.php            # Privacy & data protection statement
└── partials/
    ├── header.blade.php             # Announcement bar, logo, search, icons
    ├── navigation.blade.php         # Primary menu, toy category dropdown, mobile drawer
    ├── footer.blade.php             # Playful footer, category columns, trust badges
    ├── product-card.blade.php       # RazaToys standard toy product card
    ├── hero-banner.blade.php        # Dynamic full-bleed promotional banner
    ├── promo-banner.blade.php       # Secondary promo banners & deal cards
    └── trust-strip.blade.php        # Free delivery, secure payment, genuine toys
```

---

## 4. Livewire Reactive Component Architecture

Livewire 3 provides server-side state management without the architectural complexity of a decoupled SPA:

1. **`CartIcon` Component:**
   - Mounts in the main site header.
   - Listens for `'cart-updated'` events emitted from any Add-to-Cart trigger.
   - Dynamically re-renders the cart quantity badge with playful micro-animation.
2. **`Shoppingcart` Component:**
   - Supports both logged-in users (synced to `shoppingcarts` table) and guests (session storage `razatoys_cart`).
   - Real-time quantity adjustment (`+` / `-`), line-item deletion, subtotal calculations, and free delivery progress bar.
   - Zero full-page refreshes.
3. **`Checkout` Component:**
   - Multi-step address entry with inline phone formatting and postal code autocomplete.
   - Payment method toggle: Cash on Delivery (COD), Direct Bank Transfer, or Stripe Credit/Debit Card.
   - Real-time coupon code validation with instant discount deduction.
   - Order creation and atomic cart purge in a single database transaction.

---

## 5. Database Schema & Data Models

### Canonical Models & Non-Destructive Enhancements

1. **`Product` Model (`products` table):**
   - Active retail products are flagged with `product_kind = 'retail'`, `is_storefront_visible = true`.
   - Pricing architecture:
     - `weekly_price`: Selling price in PKR (displayed as `Rs. X,XXX`).
     - `monthly_price`: Original MSRP / Regular price for discount strike-through calculation.
   - Image resolution: Enhanced `getImageUrlAttribute` prioritizing high-resolution WebP toy images and falling back to the official RazaToys teddy bear placeholder.
2. **`Category` Model (`categories` table):**
   - Main toy categories flagged with `is_storefront_category = true`.
   - Columns include `slug`, `name`, `description`, `image`, and display `order`.
   - Secondary retail categories (e.g. `Kitchenware`) remain fully supported.
3. **`Order` & `OrderItem` Architecture:**
   - Order snapshots store an immutable JSON payload of product names, unit prices, quantities, and images at the moment of purchase.
   - Preserves historical records regardless of future price or catalog changes.
4. **Wishlist Architecture (Proposed Non-Destructive Extension):**
   - Addition of a lightweight `wishlists` table (`id`, `user_id`, `product_id`, `created_at`) with graceful fallback to localStorage for guests.

---

## 6. Performance, Caching & Security Architecture

### Performance Optimization
* **WebP Pipeline:** All image assets are authored in WebP with fallback PNG/JPG formats.
* **LCP Optimization:** Hero banner images for desktop and mobile are preloaded via `<link rel="preload" fetchpriority="high">`.
* **CSS & Asset Minification:** Production stylesheets are bundled and minified to minimize parse and render-blocking times.
* **Lazy Loading:** All off-screen catalog and product card images utilize `loading="lazy"` and `decoding="async"`.

### Security Architecture
* **Strict Nonces & CSP:** Content Security Policy nonces are dynamically injected into inline scripts and Livewire requests.
* **HTML Sanitization:** User reviews, inquiry messages, and contact forms pass through HTMLPurifier.
* **SQL Injection & Mass Assignment Prevention:** Strict Eloquent `$fillable` whitelisting and parameterized queries throughout.
* **CSRF & Rate Limiting:** All state-modifying requests require verified CSRF tokens, with throttled login and checkout endpoints.
