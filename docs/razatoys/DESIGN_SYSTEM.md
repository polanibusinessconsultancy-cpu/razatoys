# RazaToys — Design System & Visual Specification
**Document Version:** 1.0.0  
**Brand:** RazaToys  
**Tagline:** Joy in Every Box  
**Core Aesthetic:** Modern, Premium, Colorful, Warm, Child-Safe, Family-Friendly

---

## 1. Brand Philosophy & Visual Identity

RazaToys is dedicated to bringing smiles, wonder, and quality play into every home. The design system rejects cold, generic corporate e-commerce aesthetics in favor of a warm, delightful, tactile visual language that balances playful childhood energy with modern, trustworthy e-commerce ergonomics.

The visual direction features:
* **Curved, Soft Geometry:** Friendly rounded edges (`16px`, `24px`, and full pill `9999px`) that communicate warmth and safety.
* **Vibrant Pastel Harmonization:** A distinctive, balanced color palette built around Sunshine Yellow, Bubblegum Pink, Sky Blue, Grass Green, and Warm Vanilla Cream.
* **Tactile Micro-Interactions:** Subtle lift effects, soft button bounces, and cheerful badge pops that reward user interaction.
* **Approved Mascot Integration:** The charming RazaToys Teddy Bear logo and emblem featured prominently across the header, badges, empty states, and favicons.

---

## 2. Color Palette & Tokens

```css
:root {
    /* --- PRIMARY BRAND ACCENTS --- */
    --raza-yellow: #FFC107;
    --raza-yellow-light: #FFF8E1;
    --raza-yellow-dark: #FFA000;
    --raza-yellow-glow: rgba(255, 193, 7, 0.35);

    --raza-pink: #FF4081;
    --raza-pink-light: #FCE4EC;
    --raza-pink-dark: #C2185B;
    --raza-pink-glow: rgba(255, 64, 129, 0.28);

    --raza-blue: #0288D1;
    --raza-blue-light: #E1F5FE;
    --raza-blue-dark: #01579B;
    --raza-blue-glow: rgba(2, 136, 209, 0.25);

    --raza-green: #4CAF50;
    --raza-green-light: #E8F5E9;
    --raza-green-dark: #2E7D32;
    --raza-green-glow: rgba(76, 175, 80, 0.25);

    /* --- WARM SURFACES & BACKGROUNDS --- */
    --raza-bg-cream: #FFFDF7;
    --raza-bg-soft: #FFF9EB;
    --raza-surface: #FFFFFF;
    --raza-card-border: #F3EBD8;
    --raza-card-border-hover: #FFD54F;

    /* --- TYPOGRAPHY & NEUTRALS --- */
    --raza-text-main: #1E293B;      /* Deep Slate for high legibility */
    --raza-text-muted: #64748B;     /* Subtitles and supporting text */
    --raza-text-light: #94A3B8;     /* Inactive icons and borders */
    --raza-white: #FFFFFF;

    /* --- FUNCTIONAL STATES --- */
    --raza-danger: #EF4444;
    --raza-warning: #F59E0B;
    --raza-success: #10B981;
    --raza-info: #3B82F6;

    /* --- RADIUS & SHADOWS --- */
    --raza-radius-sm: 8px;
    --raza-radius-md: 14px;
    --raza-radius-lg: 20px;
    --raza-radius-xl: 28px;
    --raza-radius-full: 9999px;

    --raza-shadow-sm: 0 2px 8px rgba(30, 41, 59, 0.04);
    --raza-shadow-md: 0 8px 24px rgba(30, 41, 59, 0.08);
    --raza-shadow-lg: 0 16px 36px rgba(30, 41, 59, 0.12);
    --raza-shadow-hover: 0 12px 28px rgba(255, 193, 7, 0.22);
}
```

---

## 3. Typography System

* **Primary Heading Font:** `Plus Jakarta Sans` or `Fredoka` with fallback to `Inter`, `-apple-system`, `sans-serif`.
  - Cheerful, approachable, highly legible with slightly rounded character terminals.
* **Body & UI Font:** `Inter`, `-apple-system`, `BlinkMacSystemFont`, `sans-serif`.
  - Clean, crisp metrics ensuring effortless reading of product specifications, prices, and forms.

| Style Role | Font Weight | Desktop Size | Mobile Size | Line Height | Letter Spacing |
|---|---|---|---|---|---|
| **Hero Display** | 800 (ExtraBold) | `48px – 56px` | `32px – 38px` | `1.15` | `-0.02em` |
| **Section Title (H2)** | 800 (ExtraBold) | `32px – 36px` | `24px – 28px` | `1.20` | `-0.015em` |
| **Subsection (H3)** | 700 (Bold) | `22px – 24px` | `18px – 20px` | `1.30` | `0em` |
| **Product Card Title** | 700 (Bold) | `16px – 17px` | `15px` | `1.35` | `0em` |
| **Body Standard** | 400 / 500 | `15px – 16px` | `14px` | `1.55` | `0em` |
| **Price Main** | 800 (ExtraBold) | `22px – 26px` | `18px – 20px` | `1.10` | `-0.01em` |
| **Badge / Eyebrow** | 700 (Bold) | `11px – 12px` | `10px – 11px` | `1.00` | `+0.06em (Uppercase)` |

---

## 4. Core UI Components

### 4.1 Header & Navigation
* **Top Announcement Bar:** Sunshine yellow background (`#FFF9EB`), displaying "Free Delivery over Rs. 3,000", "7-Day Easy Returns", WhatsApp support shortcut, and order tracking link.
* **Main Header:**
  - **Logo:** Official RazaToys Teddy Bear logo (`height: 60px`), centered or aligned left on desktop.
  - **Live Search Bar:** Rounded pill search bar with category selector and instant drop-down results.
  - **Action Hub:** Wishlist counter pill with heart icon, Cart pill with quantity bubble in bright pink/yellow, and My Account shortcut.
* **Primary Navigation Bar:**
  - "Shop by Age" and "Toy Categories" dropdown menus with colorful pastel category icons.
  - Quick links: `Home`, `Toys & Games`, `Age 0-3`, `Age 4-8`, `Age 9+`, `Kitchenware`, `Deals`, `Track Order`.

### 4.2 Toy Category Cards
* **Visual Form:** Circular or pill-shaped cards with soft pastel tinted background discs (e.g. Baby Toys in Sky Blue, Action Toys in Sunshine Yellow, Educational in Grass Green, Dolls & Plush in Rose Pink).
* **Hover Interaction:** Subtle vertical float (`translateY(-4px)`), soft color border glow, and image scale.

### 4.3 Toy Product Card (`raza-product-card`)
* **Card Container:** White background, `border: 1.5px solid var(--raza-card-border)`, `border-radius: 20px`, overflow hidden.
* **Media Container:** Fixed aspect ratio (`1:1` or `4:3`), warm cream background (`#FFFDF7`), crisp WebP toy image with smooth zoom on hover.
* **Badges:**
  - Discount Ribbon: Floating top-left pill (`25% OFF`) in bright pink.
  - Age Recommendation: Floating bottom-left tag (`3+ Years`) in soft blue.
  - Wishlist Heart: Top-right floating circular button with micro-pop animation.
* **Content:**
  - Category breadcrumb in muted slate.
  - Product name (max 2 lines with clean ellipsis).
  - Star ratings with numeric count `(4.9 ★ - 18 reviews)`.
  - Price: Prominent `Rs. 2,450` in slate, with strike-through `Rs. 3,200` in soft red.
* **Action:**
  - Vibrant "Add to Cart" button with shopping bag/cart icon. Turns green with checkmark upon instant addition.

### 4.4 Buttons & Badges
* **Primary Button:** Warm Sunshine Yellow background, deep charcoal text, `border-radius: 14px`, bold typography. Hover shifts to deeper gold with soft drop shadow.
* **Secondary / Accent Button:** Cheerful Rose Pink or Sky Blue for promotional triggers ("Deal of the Day", "Explore Toys").
* **Outline / Ghost Button:** Transparent background with `2px` pastel border and charcoal text.

---

## 5. Responsive Design Standards & Breakpoints

RazaToys strictly targets seamless responsive execution across all standard viewports:
* **Large Desktop (`1440px+`):** 4 to 5 products per row, wide container (`max-width: 1360px`), comfortable breathing room.
* **Laptop (`1024px – 1439px`):** 4 products per row, compact header, optimized font scaling.
* **Tablet (`768px – 1023px`):** 3 products per row, horizontal swipeable category carousel, collapsible filter drawer.
* **Mobile Large (`430px – 767px`):** 2 products per row, sticky bottom navigation with cart bubble, full-width touch targets.
* **Mobile Compact (`360px – 429px`):** 2 compact products per row with readable price badges and touch-friendly buttons.
