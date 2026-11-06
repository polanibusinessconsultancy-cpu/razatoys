<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Category;
use App\Models\Blog;
use App\Models\FooterSetting;
use App\Models\CarouselSlide;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OneStopMartCatalogImporter
{
    /**
     * Get the static 26 products data array from cart-manager.js
     */
    public function getProductsData(): array
    {
        return [
            // Main Shop Products (IDs 1-16)
            [
                'id' => '1',
                'name' => 'Cuddly Teddy Bear',
                'price' => 19.99,
                'oldPrice' => 24.99,
                'category' => 'Toys',
                'image' => 'one-stop-mart/images/product-teddy.png',
                'shortDescription' => 'Ultra-soft plush teddy bear, perfect for kids and gifts.',
                'description' => 'Give the gift of cuddles with this premium Cuddly Teddy Bear. Crafted from high-density, child-safe plush materials, this bear features reinforced stitching and a charming hand-stitched nose. Perfect for nurseries, birthdays, or special celebrations.',
                'sku' => 'OSM-TY-001',
            ],
            [
                'id' => '2',
                'name' => 'RC Off-Road Monster Car',
                'price' => 45.00,
                'oldPrice' => 59.99,
                'category' => 'Toys',
                'image' => 'one-stop-mart/images/product-car.png',
                'shortDescription' => 'High-speed remote control monster truck with shock absorbers.',
                'description' => 'Conquer any terrain with the RC Off-Road Monster Car. Equipped with 4WD, independent shock absorbers, and rubber tires, this car delivers speeds up to 20 km/h. Features a 2.4GHz interference-free transmitter for long-range multiplayer control.',
                'sku' => 'OSM-TY-002',
            ],
            [
                'id' => '3',
                'name' => '7 Pcs Non-Stick Cookware Set with Glass Lids',
                'price' => 59.99,
                'oldPrice' => 74.99,
                'category' => 'Kitchenware',
                'image' => 'one-stop-mart/images/product-cookware.png',
                'shortDescription' => 'Cook healthy and delicious meals with this premium non-stick cookware set.',
                'description' => 'This 7-piece non-stick cookware set is designed to make your cooking experience easier, healthier, and more enjoyable. The set includes essential pots and pans with tempered glass lids, allowing you to monitor your cooking without lifting the lid. The non-stick coating ensures easy food release and quick cleaning.',
                'sku' => 'OSM-KW-003',
            ],
            [
                'id' => '4',
                'name' => 'Power Blend High-Speed Mixer',
                'price' => 89.99,
                'oldPrice' => 109.99,
                'category' => 'Kitchenware',
                'image' => 'one-stop-mart/images/product-blender.png',
                'shortDescription' => '1200W high-speed professional blender with travel cups.',
                'description' => 'Blend shakes, smoothies, and purées effortlessly with the Power Blend High-Speed Mixer. Powered by a 1200W pure copper motor and 6 pro-stainless steel blades, it crushes ice and breaks down seeds instantly.',
                'sku' => 'OSM-KW-004',
            ],
            [
                'id' => '5',
                'name' => 'Chérie Belle Premium Perfume',
                'price' => 79.99,
                'oldPrice' => 99.99,
                'category' => 'Perfumes',
                'image' => 'one-stop-mart/images/product-perfume.png',
                'shortDescription' => 'Elegant floral fragrance for women with long-lasting scent notes.',
                'description' => 'Unveil your grace with Chérie Belle Premium Perfume. Featuring top notes of fresh citrus and pear, a heart of jasmine and rose petals, and a warm base of vanilla, patchouli, and musk.',
                'sku' => 'OSM-PF-005',
            ],
            [
                'id' => '6',
                'name' => 'Vacuum Insulated Water Bottle',
                'price' => 24.99,
                'oldPrice' => 29.99,
                'category' => 'Kitchenware',
                'image' => 'one-stop-mart/images/product-bottle.png',
                'shortDescription' => 'Double-wall insulated bottle keeping beverages cold for 24h or hot for 12h.',
                'description' => 'Stay hydrated with the Vacuum Insulated Water Bottle. Crafted from premium 18/8 food-grade stainless steel, this double-walled sweat-proof bottle features a leakproof sports cap.',
                'sku' => 'OSM-KW-006',
            ],
            [
                'id' => '7',
                'name' => 'SoundMax Wireless Headphones',
                'price' => 149.99,
                'oldPrice' => null,
                'category' => 'Electronics',
                'image' => 'one-stop-mart/images/cat-electronics.png',
                'shortDescription' => 'Active Noise Cancelling (ANC) Bluetooth over-ear headphones.',
                'description' => 'Immerse yourself in high-fidelity audio with SoundMax Wireless Headphones. Featuring custom 40mm drivers, active noise cancellation to mute external environment noise, and soft memory-foam ear cushions.',
                'sku' => 'OSM-EL-007',
            ],
            [
                'id' => '8',
                'name' => 'StyleHub Leather Handbag',
                'price' => 199.99,
                'oldPrice' => null,
                'category' => 'Fashion',
                'image' => 'one-stop-mart/images/cat-fashion.png',
                'shortDescription' => 'Elegant genuine saffiano leather handbag for modern women.',
                'description' => 'Elevate your accessory style with the StyleHub Leather Handbag. Made from scratch-resistant saffiano leather, this bag offers organized internal compartments and premium gold-toned zippers.',
                'sku' => 'OSM-FS-008',
            ],
            [
                'id' => '9',
                'name' => 'Minimalist Potted Plant',
                'price' => 15.00,
                'oldPrice' => null,
                'category' => 'Home & Living',
                'image' => 'one-stop-mart/images/cat-home.png',
                'shortDescription' => 'Low-maintenance live snake plant in a ceramic geometric pot.',
                'description' => 'Add a touch of nature to your workspace or home with the Minimalist Potted Plant. Features a hardy, low-maintenance Snake Plant that thrives in low light, paired with a white geometric ceramic pot.',
                'sku' => 'OSM-HL-009',
            ],
            [
                'id' => '10',
                'name' => 'Pro Leather Soccer Football',
                'price' => 29.99,
                'oldPrice' => null,
                'category' => 'Sports',
                'image' => 'one-stop-mart/images/cat-sports.png',
                'shortDescription' => 'Official size 5 professional training and match football.',
                'description' => 'Play like a pro with the Pro Leather Soccer Football. Stitched with high-grade synthetic leather panels, it features a butyl bladder for optimal air retention and structural shape integrity.',
                'sku' => 'OSM-SP-010',
            ],
            [
                'id' => '11',
                'name' => "Men's Cushioned Running Shoes",
                'price' => 89.99,
                'oldPrice' => null,
                'category' => 'Fashion',
                'image' => 'one-stop-mart/images/cat-fashion.png',
                'shortDescription' => 'Lightweight running shoes with breathable mesh and responsive foam.',
                'description' => "Go the distance with Men's Cushioned Running Shoes. Designed with a lightweight engineered mesh upper for airflow and a dual-density foam midsole for impact cushioning.",
                'sku' => 'OSM-FS-011',
            ],
            [
                'id' => '12',
                'name' => 'Chérie Noir Luxury Cologne',
                'price' => 110.00,
                'oldPrice' => null,
                'category' => 'Perfumes',
                'image' => 'one-stop-mart/images/cat-perfumes.png',
                'shortDescription' => 'Sophisticated woody-spicy cologne for modern men.',
                'description' => 'Project confidence with Chérie Noir Luxury Cologne. This masculine scent starts with refreshing bergamot and pink pepper, moves to a heart of vetiver, and sets into a rich base of leather.',
                'sku' => 'OSM-PF-012',
            ],
            [
                'id' => '13',
                'name' => 'SoundMax Bluetooth Smartwatch',
                'price' => 249.99,
                'oldPrice' => null,
                'category' => 'Electronics',
                'image' => 'one-stop-mart/images/cat-electronics.png',
                'shortDescription' => 'AMOLED screen smartwatch with heart rate, SPO2 and activity tracking.',
                'description' => 'Monitor your health seamlessly with the SoundMax Bluetooth Smartwatch. Features a brilliant 1.43" AMOLED display, heart rate monitor, SPO2 sensor, sleep tracker, and over 100 sports modes.',
                'sku' => 'OSM-EL-013',
            ],
            [
                'id' => '14',
                'name' => 'SoundMax Cinema Soundbar',
                'price' => 179.99,
                'oldPrice' => null,
                'category' => 'Electronics',
                'image' => 'one-stop-mart/images/cat-electronics.png',
                'shortDescription' => '2.1 Channel home theater soundbar with built-in subwoofer.',
                'description' => 'Transform your home entertainment with the SoundMax Cinema Soundbar. Delivering 120W of crystal-clear dialogue and deep bass via dual mid-range drivers and a built-in subwoofer.',
                'sku' => 'OSM-EL-014',
            ],
            [
                'id' => '15',
                'name' => 'Chef Pro Professional Knife Set',
                'price' => 59.99,
                'oldPrice' => null,
                'category' => 'Kitchenware',
                'image' => 'one-stop-mart/images/cat-kitchenware.png',
                'shortDescription' => '6-Piece high-carbon stainless steel knife set with acrylic stand.',
                'description' => 'Slice with absolute precision using the Chef Pro Professional Knife Set. Includes 5 chef-grade knives forged from high-carbon German steel, featuring ergonomic non-slip handles and a contemporary rotating stand.',
                'sku' => 'OSM-KW-015',
            ],
            [
                'id' => '16',
                'name' => 'Wooden Educational Block Set',
                'price' => 34.99,
                'oldPrice' => null,
                'category' => 'Toys',
                'image' => 'one-stop-mart/images/cat-toys.png',
                'shortDescription' => '50-Piece solid wood building blocks with alphabet and shapes.',
                'description' => 'Spark imagination and motor skills with the Wooden Educational Block Set. Carefully shaped from sustainable premium pine wood, each of the 50 blocks is finished with water-based non-toxic paint.',
                'sku' => 'OSM-TY-016',
            ],
            // New Arrivals Products (IDs 101-110)
            [
                'id' => '101',
                'name' => 'Soft Plush Teddy Bear',
                'price' => 21.99,
                'oldPrice' => null,
                'category' => 'Toys',
                'image' => 'one-stop-mart/images/product-teddy.png',
                'shortDescription' => 'Premium soft plush teddy bear, great gift item.',
                'description' => 'Make someone\'s day special with the Soft Plush Teddy Bear. Made with ultra-soft fluffy fleece material and polyester fill, it\'s perfect for hugs and children\'s room decoration.',
                'sku' => 'OSM-TY-101',
            ],
            [
                'id' => '102',
                'name' => 'High-Speed RC Drift Racer',
                'price' => 49.99,
                'oldPrice' => null,
                'category' => 'Toys',
                'image' => 'one-stop-mart/images/product-car.png',
                'shortDescription' => 'Remote control drift racing sports car with LED headlamps.',
                'description' => 'Fulfill your drift racing passion with the High-Speed RC Drift Racer. Operates on 2.4GHz remote frequency and includes specific slick drift tires and rubber tread tires for quick wheel swaps.',
                'sku' => 'OSM-TY-102',
            ],
            [
                'id' => '103',
                'name' => 'Elite 12-Piece Nonstick Cookware Set',
                'price' => 139.99,
                'oldPrice' => null,
                'category' => 'Kitchenware',
                'image' => 'one-stop-mart/images/product-cookware.png',
                'shortDescription' => 'Ultra-durable 12-piece hard-anodized kitchen cookware set.',
                'description' => 'Set up your dream kitchen with the Elite 12-Piece Nonstick Cookware Set. Crafted from hard-anodized aluminum that heats rapidly and evenly, it features a titanium-reinforced interior triple layer for superior non-stick execution.',
                'sku' => 'OSM-KW-103',
            ],
            [
                'id' => '104',
                'name' => 'Super Blend Smart Food Mixer',
                'price' => 95.00,
                'oldPrice' => null,
                'category' => 'Kitchenware',
                'image' => 'one-stop-mart/images/product-blender.png',
                'shortDescription' => 'Smart kitchen stand mixer with 5L stainless bowl and 3 attachments.',
                'description' => 'Bake effortlessly with the Super Blend Smart Food Mixer. Equipped with a 6-speed motor, a 5-liter polished stainless steel bowl, and includes a wire whip, dough hook, and flat beater attachments.',
                'sku' => 'OSM-KW-104',
            ],
            [
                'id' => '105',
                'name' => 'Aura Royale Luxury Scent',
                'price' => 85.00,
                'oldPrice' => null,
                'category' => 'Perfumes',
                'image' => 'one-stop-mart/images/product-perfume.png',
                'shortDescription' => 'Opulent oriental perfume with notes of saffron, cedarwood, and amber.',
                'description' => 'Indulge in pure luxury with Aura Royale Luxury Scent. An alluring unisex perfume opening with aromatic saffron and jasmine, blooming into amberwood, and leaving a trace of cedarwood resin and fir balm.',
                'sku' => 'OSM-PF-105',
            ],
            [
                'id' => '106',
                'name' => 'Titanium Insulated Sports Bottle',
                'price' => 27.99,
                'oldPrice' => null,
                'category' => 'Kitchenware',
                'image' => 'one-stop-mart/images/product-bottle.png',
                'shortDescription' => 'Ultra-light weight insulated titanium water bottle for hiking.',
                'description' => 'Experience high-performance outdoor gear with the Titanium Insulated Sports Bottle. Exceptionally light, corrosion-resistant, and features a vacuum double-wall to keep liquids perfectly cold or hot.',
                'sku' => 'OSM-KW-106',
            ],
            [
                'id' => '107',
                'name' => 'SoundMax Wireless Pro Headphones',
                'price' => 149.99,
                'oldPrice' => null,
                'category' => 'Electronics',
                'image' => 'one-stop-mart/images/cat-electronics.png',
                'shortDescription' => 'Professional studio Bluetooth headphones with active noise cancellation.',
                'description' => 'Elevate your audio standard with SoundMax Wireless Pro Headphones. Featuring advanced ANC, high-res audio drivers, and foldable travel design.',
                'sku' => 'OSM-EL-107',
            ],
            [
                'id' => '108',
                'name' => 'Kids Building Blocks Set',
                'price' => 29.99,
                'oldPrice' => null,
                'category' => 'Toys',
                'image' => 'one-stop-mart/images/cat-toys.png',
                'shortDescription' => '80-Piece jumbo colorful plastic building blocks bag.',
                'description' => 'Let creativity run wild with the Kids Building Blocks Set. Contains 80 bright, chunky plastic blocks in classic primary colors, packed in a zippered eco-friendly storage bag.',
                'sku' => 'OSM-TY-108',
            ],
            [
                'id' => '109',
                'name' => 'Elegance Rose Eau de Parfum',
                'price' => 65.00,
                'oldPrice' => null,
                'category' => 'Perfumes',
                'image' => 'one-stop-mart/images/cat-perfumes.png',
                'shortDescription' => 'Fresh delicate rose and cedarwood perfume for daily wear.',
                'description' => 'Breathe in the morning garden air with Elegance Rose Eau de Parfum. A delicate, romantic fragrance highlighting turkish damask rose, peony, fresh citrus, and soft white musk.',
                'sku' => 'OSM-PF-109',
            ],
            [
                'id' => '110',
                'name' => 'Premium Bamboo Cutting Boards',
                'price' => 32.50,
                'oldPrice' => null,
                'category' => 'Kitchenware',
                'image' => 'one-stop-mart/images/cat-kitchenware.png',
                'shortDescription' => '3-Piece organic bamboo wood cutting board set with drip grooves.',
                'description' => 'Prep food safely with the Premium Bamboo Cutting Boards. Features three knife-friendly boards carved from organic bamboo wood, featuring deep juice drip grooves and side handle grips.',
                'sku' => 'OSM-KW-110',
            ],
        ];
    }

    /**
     * Get categories list mapping names to slugs
     */
    public function getCategoriesData(): array
    {
        return [
            'Toys' => 'toys',
            'Kitchenware' => 'kitchenware',
            'Perfumes' => 'perfumes',
            'Electronics' => 'electronics',
            'Fashion' => 'fashion',
            'Home & Living' => 'home-living',
            'Sports' => 'sports',
        ];
    }

    /**
     * Main import loop
     */
    public function import(float $rate = 280.0, bool $dryRun = false): array
    {
        $log = [];
        $rollbackManifest = [
            'inserted_categories' => [],
            'inserted_products' => [],
            'updated_products' => [],
            'hidden_products' => [],
            'hidden_blogs' => [],
            'footer_settings' => [],
            'carousel_slides' => [],
            'copied_assets' => [],
            'rate' => $rate,
            'timestamp' => now()->toIso8601String(),
        ];

        $counts = [
            'categories_created' => 0,
            'categories_reused' => 0,
            'products_inserted' => 0,
            'products_updated' => 0,
            'products_skipped' => 0,
            'products_failed' => 0,
            'old_products_hidden' => 0,
            'old_blogs_hidden' => 0,
        ];

        $categoriesMap = [];

        DB::beginTransaction();

        try {
            // 1. Process categories
            foreach ($this->getCategoriesData() as $name => $slug) {
                // Check if category slug already exists
                $existing = Category::where('slug', $slug)->first();
                if ($existing) {
                    $categoriesMap[$name] = $existing->id;
                    $counts['categories_reused']++;
                    $log[] = "Category '{$name}' reused with slug '{$slug}' (ID: {$existing->id})";
                } else {
                    if (!$dryRun) {
                        $cat = Category::create([
                            'name' => $name,
                            'slug' => $slug,
                        ]);
                        $categoriesMap[$name] = $cat->id;
                        $rollbackManifest['inserted_categories'][] = $cat->id;
                    } else {
                        $categoriesMap[$name] = 999 + count($categoriesMap); // Mock ID
                    }
                    $counts['categories_created']++;
                    $log[] = "Category '{$name}' created with slug '{$slug}'";
                }
            }

            // 2. Process products
            foreach ($this->getProductsData() as $p) {
                // Validate required fields
                if (empty($p['name']) || empty($p['sku']) || empty($p['category'])) {
                    $counts['products_failed']++;
                    $log[] = "Failed product validation for item SKU: " . ($p['sku'] ?? 'unknown');
                    continue;
                }

                // Check image file existence in public path
                $fullPublicImagePath = public_path($p['image']);
                if (!file_exists($fullPublicImagePath)) {
                    $counts['products_failed']++;
                    $log[] = "Product SKU '{$p['sku']}' failed: asset image file '{$p['image']}' does not exist in public path.";
                    continue;
                }

                // Map category
                $catId = $categoriesMap[$p['category']] ?? null;
                if (!$catId) {
                    $counts['products_failed']++;
                    $log[] = "Product SKU '{$p['sku']}' failed: category '{$p['category']}' not found.";
                    continue;
                }

                // Price conversion
                $rawPrice = (float)$p['price'];
                $convertedPrice = round(($rawPrice * $rate) / 100) * 100;

                $convertedOldPrice = $convertedPrice;
                if (!empty($p['oldPrice'])) {
                    $convertedOldPrice = round(((float)$p['oldPrice'] * $rate) / 100) * 100;
                }


                // Determine stable slug
                $slug = Str::slug($p['name']);

                // Query existing by unique importer key (catalog_source + source_key)
                $existingProduct = Course::where('catalog_source', 'one_stop_mart')
                    ->where('source_key', $p['sku'])
                    ->first();

                if ($existingProduct) {
                    // Update only records where catalog_source = one_stop_mart
                    if (!$dryRun) {
                        $existingProduct->update([
                            'name' => $p['name'],
                            'slug' => $slug,
                            'description' => $p['shortDescription'],
                            'long_description' => $p['description'],
                            'weekly_price' => $convertedPrice,
                            'monthly_price' => $convertedOldPrice, // Map oldPrice to monthly_price display
                            'image_path' => $p['image'],
                            'category_id' => $catId,
                            'is_free' => false,
                            'purchase_model' => 'flexible',
                            'product_kind' => 'retail',
                            'is_storefront_visible' => true,
                        ]);
                        $rollbackManifest['updated_products'][] = $existingProduct->id;
                    }
                    $counts['products_updated']++;
                    $log[] = "Product SKU '{$p['sku']}' updated.";
                } else {
                    // Check duplicate slugs for safety
                    if (Course::where('slug', $slug)->exists()) {
                        $slug = $slug . '-' . strtolower($p['sku']);
                    }

                    if (!$dryRun) {
                        $newProduct = Course::create([
                            'name' => $p['name'],
                            'slug' => $slug,
                            'description' => $p['shortDescription'],
                            'long_description' => $p['description'],
                            'weekly_price' => $convertedPrice,
                            'monthly_price' => $convertedOldPrice,
                            'image_path' => $p['image'],
                            'category_id' => $catId,
                            'is_free' => false,
                            'purchase_model' => 'flexible',
                            'product_kind' => 'retail',
                            'is_storefront_visible' => true,
                            'catalog_source' => 'one_stop_mart',
                            'source_key' => $p['sku'],
                        ]);
                        $rollbackManifest['inserted_products'][] = $newProduct->id;
                    }
                    $counts['products_inserted']++;
                    $log[] = "Product SKU '{$p['sku']}' inserted.";
                }
            }

            // 3. Hide old Polani products (IDs 1 through 12)
            $oldProductIds = range(1, 12);
            foreach ($oldProductIds as $oldId) {
                $oldProduct = Course::find($oldId);
                if ($oldProduct) {
                    if ($oldProduct->is_storefront_visible !== false) {
                        if (!$dryRun) {
                            $oldProduct->update(['is_storefront_visible' => false]);
                            $rollbackManifest['hidden_products'][] = $oldId;
                        }
                        $counts['old_products_hidden']++;
                        $log[] = "Old product ID {$oldId} ('{$oldProduct->name}') hidden.";
                    }
                }
            }

            // 4. Hide old Polani blog (ID 1)
            $oldBlog = Blog::find(1);
            if ($oldBlog) {
                if ($oldBlog->is_storefront_visible !== false) {
                    if (!$dryRun) {
                        $oldBlog->update(['is_storefront_visible' => false]);
                        $rollbackManifest['hidden_blogs'][] = 1;
                    }
                    $counts['old_blogs_hidden']++;
                    $log[] = "Old Blog ID 1 ('{$oldBlog->title}') hidden.";
                }
            }

            // 5. Update FooterSettings singleton branding fields
            $footer = FooterSetting::getSettings();
            if ($footer) {
                $rollbackManifest['footer_settings'] = [
                    'brand_name' => $footer->brand_name,
                    'brand_tagline' => $footer->brand_tagline,
                    'brand_description' => $footer->brand_description,
                    'copyright_name' => $footer->copyright_name,
                    'copyright_url' => $footer->copyright_url,
                ];

                if (!$dryRun) {
                    $footer->update([
                        'brand_name' => 'One Stop Mart',
                        'brand_tagline' => 'Everyday Essentials',
                        'brand_description' => 'Your ultimate online general store for quality toys, kitchenware, electronics, fashion, home essentials, and more.',
                        'copyright_name' => 'One Stop Mart',
                        'copyright_url' => url('/'),
                        'phone' => '',
                        'email' => '',
                        'address' => '',
                    ]);


                }
                $log[] = "Footer settings updated with One Stop Mart branding (contact values cleared).";
            }

            // 6. Update CarouselSlide
            $slide = CarouselSlide::where('page_key', 'home')->first();
            if ($slide) {
                $rollbackManifest['carousel_slides'][] = [
                    'id' => $slide->id,
                    'title' => $slide->title,
                    'subtitle' => $slide->subtitle,
                    'cta_text' => $slide->cta_text,
                    'cta_url' => $slide->cta_url,
                    'image_name' => $slide->image_name,
                ];

                if (!$dryRun) {
                    $slide->update([
                        'title' => 'Everything You Need',
                        'subtitle' => 'All in One Place',
                        'cta_text' => 'Shop Now',
                        'cta_url' => route('polani.collection'),
                        'image_name' => 'one-stop-mart/images/hero-banner.png',
                    ]);
                }
                $log[] = "Home carousel slide updated with One Stop Mart content.";
            }

            if ($dryRun) {
                DB::rollBack();
                $log[] = "[DRY RUN] Transaction rolled back successfully.";
            } else {
                DB::commit();
                // Write rollback manifest
                $manifestPath = storage_path('app/backups/import_rollback_manifest.json');
                file_put_contents($manifestPath, json_encode($rollbackManifest, JSON_PRETTY_PRINT));
                $log[] = "Rollback manifest written to {$manifestPath}.";
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("One Stop Mart import failed: " . $e->getMessage());
            throw $e;
        }

        return [
            'counts' => $counts,
            'log' => $log,
            'manifest' => $rollbackManifest,
        ];
    }
}
