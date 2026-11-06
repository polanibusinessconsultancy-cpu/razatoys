# RazaToys — Page-by-Page Transformation Checklist
**Document Version:** 1.0.0  
**Status:** Audit & Target Specification Matrix

---

## 1. Storefront Pages Matrix

### 1.1 Homepage (`/`)
* **Route Name:** `home`
* **Controller Action:** `PolaniController@home`
* **Current Template:** `resources/views/polani/home.blade.php`
* **Current Assets:** `public/one-stop-mart/css/home.min.css`, `public/one-stop-mart/css/page-heroes.min.css`
* **Reusable Components:** `polani.partials.page-hero`, `polani.partials.osm-product-card`
* **Current Bugs & Legacy Remnants:**
  - Category icons reuse generic One Stop Mart badges.
  - Spotlight product hardcoded to look for `qasr-al-oud` or fall back to last ID.
* **RazaToys Redesign Requirements:**
  - Cheerful RazaToys announcement topbar and navigation bar.
  - Dynamic full-bleed toy hero banner with sunshine yellow & pastel overlay.
  - Interactive "Shop by Age" circular badge carousel (0-3 yrs, 4-8 yrs, 9+ yrs).
  - Colorful promo cards: "Trending Toys", "Deal of the Day", "Outdoor & Action".
  - Best-selling toy product slider with discount badges, star ratings, and instant Add-to-Cart.
  - Parent testimonials and toy safety guarantee banner.
* **Verification Status:** [ ] Pending Phase 3

---

### 1.2 Shop Catalog / Collection (`/shop`)
* **Route Name:** `polani.collection`
* **Controller Action:** `PolaniController@collection`
* **Current Template:** `resources/views/polani/collection.blade.php`
* **Current Assets:** `public/one-stop-mart/css/shop.css`
* **Reusable Components:** `polani.partials.osm-product-card`
* **Current Bugs & Legacy Remnants:**
  - Unrelated products display the cooking-pot placeholder image.
  - Sidebar filter lacks age-group and toy-specific facets.
* **RazaToys Redesign Requirements:**
  - Faceted sidebar filter: Categories (Toys, Kitchenware, etc.), Age Groups, Price Range (PKR), Rating.
  - Responsive slide-out filter drawer on mobile with touch-friendly handles.
  - Sort dropdown: Best Selling, Newest, Price: Low to High, Price: High to Low, Customer Rating.
  - Clean pagination and zero-state "No toys found" screen with reset button.
* **Verification Status:** [ ] Pending Phase 4

---

### 1.3 Category Landing Pages (`/shop?category={slug}`)
* **Route Name:** `polani.collection` / `polani.category`
* **Controller Action:** `PolaniController@collection`
* **Current Template:** `resources/views/polani/category.blade.php` / `collection.blade.php`
* **Current Assets:** `public/one-stop-mart/css/shop.css`
* **Reusable Components:** `polani.partials.page-hero`, `polani.partials.osm-product-card`
* **Current Bugs & Legacy Remnants:**
  - Direct routes `/category/{slug}` returned 404 in older iterations; shop query parameter is used.
* **RazaToys Redesign Requirements:**
  - Category header banner with custom pastel illustration and friendly intro description.
  - Specific toy category landing pages: Action Toys, Dolls & Stuffed Animals, Educational Toys, Board Games & Puzzles, Outdoor Play.
  - Continued clean support for `Kitchenware` and other non-toy categories.
* **Verification Status:** [ ] Pending Phase 4

---

### 1.4 Product Details Page (`/product/{slug}`)
* **Route Name:** `polani.product`
* **Controller Action:** `PolaniController@product`
* **Current Template:** `resources/views/polani/product.blade.php`
* **Current Assets:** `public/one-stop-mart/css/product-details.css`
* **Reusable Components:** `polani.partials.osm-product-card` (Related Products)
* **Current Bugs & Legacy Remnants:**
  - Fragrance specs code (`longevity`, `projection`, `top/heart/base notes`) present in template logic.
  - Some items fallback to cooking-pot image.
* **RazaToys Redesign Requirements:**
  - Interactive multi-image product gallery with thumbnail switcher and zoom.
  - Clear pricing: Active PKR price, original price strike-through, discount percentage tag.
  - Toy-specific badges: Age suitability (e.g., "Ages 3+"), Safe & Non-Toxic, Battery Required/Included.
  - Quantity selector stepper (`-` / `1` / `+`) and bouncy "Add to Cart" button.
  - Tabs: Description, Safety & Care Instructions, Shipping Details, Verified Customer Reviews.
  - "Frequently Bought Together" or "Related Toys" slider.
* **Verification Status:** [ ] Pending Phase 4

---

### 1.5 Shopping Cart (`/shopping-cart` / `/cart`)
* **Route Name:** `shopping-cart` / `polani.cart`
* **Controller Action:** `PolaniController@cart`
* **Current Template:** `resources/views/polani/cart.blade.php`
* **Current Assets:** `public/one-stop-mart/css/cart.css`
* **Reusable Components:** `<livewire:shoppingcart />`
* **Current Bugs & Legacy Remnants:**
  - Legacy `FreeCourseService::enrollInFreeCourse` hook inside Livewire component.
* **RazaToys Redesign Requirements:**
  - Free delivery progress bar (`Add Rs. 650 more to unlock FREE Delivery!`).
  - Clear line items with high-res product thumbnails, title, price, quantity stepper, and remove icon.
  - Order summary sidebar with subtotal, estimated shipping, coupon input, and checkout CTA.
  - Empty cart state with cute RazaToys teddy illustration and "Start Exploring Toys" button.
* **Verification Status:** [ ] Pending Phase 5

---

### 1.6 Checkout Page (`/checkout`)
* **Route Name:** `polani.checkout`
* **Controller Action:** `PolaniController@checkout`
* **Current Template:** `resources/views/polani/checkout.blade.php`
* **Current Assets:** `public/one-stop-mart/css/checkout.css`
* **Reusable Components:** `<livewire:checkout />`
* **Current Bugs & Legacy Remnants:**
  - Course access and lecture imports in `Checkout.php`.
* **RazaToys Redesign Requirements:**
  - Streamlined 2-column checkout layout:
    - Left column: Customer information (Name, Email, Mobile with 03XX formatting, Full Delivery Address, City dropdown).
    - Right column: Order item summary, coupon code voucher box, and payment method options (Cash on Delivery, Direct Bank Transfer, Online Card).
  - Clear security trust badges: 100% Secure Checkout, SSL Encrypted.
  - Smooth loading state during order placement.
* **Verification Status:** [ ] Pending Phase 5

---

### 1.7 Order Confirmation / Receipt (`/order-status/{orderNumber}`)
* **Route Name:** `polani.order-status`
* **Controller Action:** `PolaniController@orderStatus`
* **Current Template:** `resources/views/polani/order-status.blade.php`
* **Current Assets:** `public/one-stop-mart/css/order-status.css`
* **RazaToys Redesign Requirements:**
  - Celebration banner: "Thank you for shopping at RazaToys! Your little one's toys are on their way!"
  - Full order summary card with order number, estimated delivery date, shipping address, and payment method.
  - "Print Invoice" and "Track Order in Real-Time" action buttons.
* **Verification Status:** [ ] Pending Phase 5

---

### 1.8 Order Tracking (`/track-order`)
* **Route Name:** `polani.track-order`
* **Controller Action:** `PolaniController@trackOrder`
* **Current Template:** `resources/views/polani/track-order.blade.php`
* **Current Assets:** `public/one-stop-mart/css/customer-account.css`
* **Current Bugs & Legacy Remnants:**
  - Hardcoded strings: "Preparing Access", "Your course or lecture access is being configured", link to `route('courses')`.
* **RazaToys Redesign Requirements:**
  - Visual 5-step toy delivery timeline:
    1. Order Confirmed
    2. Toys Packed at Warehouse
    3. Handed over to Courier (with tracking link)
    4. Out for Delivery
    5. Delivered with Smiles
  - Instant lookup form by Order ID + Phone/Email.
  - Direct WhatsApp link for instant customer service assistance.
* **Verification Status:** [ ] Pending Phase 5

---

### 1.9 Informational & Policy Pages
1. **About Us (`/about`):** The RazaToys mission, commitment to quality toys, non-toxic materials, and happy childhood memories.
2. **Contact Us (`/contact`):** Customer service hours, telephone, WhatsApp chat link, email inquiry form with validation.
3. **FAQ (`/faq`):** Interactive accordion covering delivery times, returns, payment security, and toy safety.
4. **Shipping & Delivery (`/shipping-delivery`):** Free delivery thresholds, courier partners (TCS, Leopards, Trax), delivery timeframes across Pakistan.
5. **Returns & Refunds (`/returns-refunds`):** Clear 7-day hassle-free replacement policy for defective or damaged items.
6. **Terms & Conditions (`/terms-conditions`) & Privacy Policy (`/privacy-policy`):** Updated with RazaToys corporate branding.
* **Verification Status:** [ ] Pending Phase 6

---

## 2. Customer & Administrative Portals

### 2.1 Customer Account & Profile (`/account/profile`, `/sign-in`, `/sign-up`)
* **RazaToys Redesign Requirements:**
  - Brand-consistent authentication cards with RazaToys teddy bear emblem.
  - Customer dashboard displaying recent orders, saved delivery addresses, and wishlist.
* **Verification Status:** [ ] Pending Phase 6

### 2.2 Admin Dashboard (`/admin`)
* **RazaToys Redesign Requirements:**
  - Clean RazaToys logo in admin sidebar.
  - Seamless management of products, categories, orders, banners, and reviews without impacting legacy databases.
* **Verification Status:** [ ] Pending Phase 8
