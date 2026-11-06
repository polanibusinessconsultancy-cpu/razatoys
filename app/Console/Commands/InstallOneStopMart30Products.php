<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InstallOneStopMart30Products extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catalog:install-one-stop-mart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean legacy fragrance categories/products from storefront and install 30 fresh One Stop Mart products.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Phase 19 One Stop Mart Catalog Installation...');

        DB::beginTransaction();

        try {
            // 1. Deactivate legacy fragrance categories on storefront
            $legacyCategorySlugs = [
                'men', 'women', 'attars', 'oud', 'signature', 'scented-candles', 'unisex', 'perfumes'
            ];
            Category::whereIn('slug', $legacyCategorySlugs)->update(['is_storefront_category' => false]);
            $this->info('Legacy fragrance categories set to is_storefront_category = false.');

            // 2. Hide all legacy Polani Fragrance products from storefront
            Course::query()
                ->whereNull('catalog_source')
                ->orWhere('catalog_source', '!=', 'one_stop_mart_30')
                ->update(['is_storefront_visible' => false]);
            $this->info('Legacy Polani products hidden from storefront (is_storefront_visible = false).');

            // 3. Ensure target 6 storefront categories exist and are active
            $targetCategories = [
                'Toys' => 'toys',
                'Kitchenware' => 'kitchenware',
                'Electronics' => 'electronics',
                'Fashion' => 'fashion',
                'Home & Living' => 'home-living',
                'Sports' => 'sports',
            ];

            $categoryMap = [];
            foreach ($targetCategories as $name => $slug) {
                $cat = Category::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $name, 'is_storefront_category' => true]
                );
                $cat->update(['is_storefront_category' => true, 'name' => $name]);
                $categoryMap[$slug] = $cat->id;
            }
            $this->info('6 storefront categories verified and activated.');

            // 4. Define the 30 fresh One Stop Mart products
            $productsData = [
                // TOYS (1-5)
                [
                    'sku' => 'OSM-30-001',
                    'name' => 'RC Off-Road Monster Car',
                    'category' => 'toys',
                    'price' => 12600,
                    'short' => 'A rugged remote-control monster car built for exciting indoor and outdoor play.',
                    'long' => 'The RC Off-Road Monster Car features oversized wheels, responsive remote controls, and a durable body designed for adventurous play. Ideal for kids who enjoy racing, obstacle courses, and outdoor action. Its strong grip and bold design make it a fun addition to any toy collection.',
                    'image' => 'one-stop-mart/images/product-car.png',
                ],
                [
                    'sku' => 'OSM-30-002',
                    'name' => 'Musical Learning Activity Cube',
                    'category' => 'toys',
                    'price' => 4250,
                    'short' => 'An interactive activity cube with lights, sounds, shapes, and early-learning activities.',
                    'long' => 'Designed for curious young minds, the Musical Learning Activity Cube combines colorful buttons, shape sorting, music, and sensory play. It supports hand-eye coordination, early recognition skills, and imaginative play in a compact child-friendly design.',
                    'image' => 'one-stop-mart/images/cat-toys.webp',
                ],
                [
                    'sku' => 'OSM-30-003',
                    'name' => 'Building Blocks Creative Set',
                    'category' => 'toys',
                    'price' => 3850,
                    'short' => 'A colorful building-block set for creative construction and imaginative play.',
                    'long' => 'The Building Blocks Creative Set gives children the freedom to design houses, vehicles, towers, and original creations. Easy-to-handle pieces help develop creativity, spatial awareness, and fine motor skills while keeping playtime engaging.',
                    'image' => 'one-stop-mart/images/cat-toys.webp',
                ],
                [
                    'sku' => 'OSM-30-004',
                    'name' => 'Kids Doctor Play Set',
                    'category' => 'toys',
                    'price' => 2950,
                    'short' => 'A pretend medical kit designed for imaginative roleplay and learning.',
                    'long' => 'This Kids Doctor Play Set includes child-friendly pretend medical accessories for fun roleplay sessions. Children can practice caring, communication, and imaginative storytelling while learning about common medical tools in a playful environment.',
                    'image' => 'one-stop-mart/images/cat-toys.webp',
                ],
                [
                    'sku' => 'OSM-30-005',
                    'name' => 'Rechargeable Stunt Car',
                    'category' => 'toys',
                    'price' => 7500,
                    'short' => 'A rechargeable stunt car with spinning, flipping, and multi-directional movement.',
                    'long' => 'Built for action-packed play, the Rechargeable Stunt Car can perform spins, flips, and quick directional movements. Its eye-catching design, responsive controller, and rechargeable setup make it a great choice for energetic indoor and outdoor entertainment.',
                    'image' => 'one-stop-mart/images/product-car.png',
                ],

                // KITCHENWARE (6-10)
                [
                    'sku' => 'OSM-30-006',
                    'name' => '7 Pcs Non-Stick Cookware Set with Glass Lids',
                    'category' => 'kitchenware',
                    'price' => 16800,
                    'short' => 'A practical seven-piece non-stick cookware set for everyday home cooking.',
                    'long' => 'This versatile cookware set is designed for daily meal preparation with easy-clean non-stick surfaces and convenient glass lids. The coordinated pieces help cover common cooking needs while offering a clean, modern appearance for contemporary kitchens.',
                    'image' => 'one-stop-mart/images/product-cookware.png',
                ],
                [
                    'sku' => 'OSM-30-007',
                    'name' => 'Premium Bamboo Cutting Board Set',
                    'category' => 'kitchenware',
                    'price' => 3500,
                    'short' => 'A durable bamboo cutting-board set for chopping, slicing, and serving.',
                    'long' => 'Made for everyday kitchen preparation, the Premium Bamboo Cutting Board Set provides reliable surfaces for vegetables, fruit, bread, and meat preparation. The natural bamboo finish adds warmth to the kitchen while remaining practical and easy to maintain.',
                    'image' => 'one-stop-mart/images/cat-kitchenware.webp',
                ],
                [
                    'sku' => 'OSM-30-008',
                    'name' => 'Stainless Steel Mixing Bowl Set',
                    'category' => 'kitchenware',
                    'price' => 4900,
                    'short' => 'A nesting stainless-steel bowl set for mixing, serving, and food preparation.',
                    'long' => 'This Mixing Bowl Set is ideal for baking, meal preparation, salads, marinades, and serving. The stainless-steel construction offers dependable everyday use while the nesting design helps save cabinet space.',
                    'image' => 'one-stop-mart/images/cat-kitchenware.webp',
                ],
                [
                    'sku' => 'OSM-30-009',
                    'name' => 'Multi-Purpose Vegetable Chopper',
                    'category' => 'kitchenware',
                    'price' => 2750,
                    'short' => 'A compact manual chopper for quick vegetable and food preparation.',
                    'long' => 'Reduce preparation time with this easy-to-use Multi-Purpose Vegetable Chopper. Designed for onions, vegetables, herbs, and other everyday ingredients, it helps make kitchen prep faster and more convenient.',
                    'image' => 'one-stop-mart/images/cat-kitchenware.webp',
                ],
                [
                    'sku' => 'OSM-30-010',
                    'name' => 'Insulated Food Storage Container Set',
                    'category' => 'kitchenware',
                    'price' => 5250,
                    'short' => 'A practical storage-container set for organized meal preparation and food storage.',
                    'long' => 'Keep meals and ingredients organized with a coordinated set of food storage containers. Suitable for everyday kitchen use, meal preparation, leftovers, and pantry organization, these containers are designed for convenience and space-efficient storage.',
                    'image' => 'one-stop-mart/images/product-bottle.png',
                ],

                // ELECTRONICS (11-15)
                [
                    'sku' => 'OSM-30-011',
                    'name' => 'Wireless Bluetooth Headphones',
                    'category' => 'electronics',
                    'price' => 6900,
                    'short' => 'Comfortable wireless headphones for music, calls, entertainment, and everyday use.',
                    'long' => 'Enjoy convenient wireless listening with Bluetooth connectivity, comfortable ear cushions, and simple onboard controls. These headphones are suitable for music, online meetings, casual gaming, video streaming, and daily commuting.',
                    'image' => 'one-stop-mart/images/cat-electronics.webp',
                ],
                [
                    'sku' => 'OSM-30-012',
                    'name' => 'Portable Bluetooth Speaker',
                    'category' => 'electronics',
                    'price' => 4750,
                    'short' => 'A compact wireless speaker for music at home, work, or on the go.',
                    'long' => 'The Portable Bluetooth Speaker offers an easy way to enjoy wireless audio in a compact form. Pair it with compatible smartphones, tablets, and laptops for convenient music playback during gatherings, travel, or everyday use.',
                    'image' => 'one-stop-mart/images/cat-electronics.webp',
                ],
                [
                    'sku' => 'OSM-30-013',
                    'name' => '20W Fast Charging Power Bank',
                    'category' => 'electronics',
                    'price' => 5900,
                    'short' => 'A portable high-capacity power bank with fast charging support.',
                    'long' => 'Stay powered throughout the day with a convenient fast-charging power bank designed for smartphones and compatible devices. Its portable form factor makes it ideal for travel, work, university, and daily commuting.',
                    'image' => 'one-stop-mart/images/cat-electronics.webp',
                ],
                [
                    'sku' => 'OSM-30-014',
                    'name' => 'Smart Fitness Watch',
                    'category' => 'electronics',
                    'price' => 8500,
                    'short' => 'A modern smart fitness watch for activity tracking and everyday notifications.',
                    'long' => 'Track daily activity, workouts, and common fitness metrics while keeping important smartphone notifications accessible from your wrist. The Smart Fitness Watch combines practical everyday features with a clean and modern wearable design.',
                    'image' => 'one-stop-mart/images/cat-electronics.webp',
                ],
                [
                    'sku' => 'OSM-30-015',
                    'name' => 'Adjustable LED Desk Lamp',
                    'category' => 'electronics',
                    'price' => 3650,
                    'short' => 'A modern adjustable LED desk lamp for study, work, and reading.',
                    'long' => 'Create a comfortable workspace with an adjustable LED lamp designed for desks, bedside tables, and study areas. Its flexible positioning and clean appearance make it suitable for students, professionals, and home-office users.',
                    'image' => 'one-stop-mart/images/cat-electronics.webp',
                ],

                // FASHION (16-20)
                [
                    'sku' => 'OSM-30-016',
                    'name' => 'Classic Everyday Hoodie',
                    'category' => 'fashion',
                    'price' => 4250,
                    'short' => 'A comfortable everyday hoodie with a clean, versatile casual design.',
                    'long' => 'Designed for relaxed daily wear, the Classic Everyday Hoodie combines a comfortable fit with simple styling that pairs easily with jeans, joggers, and casual footwear. Suitable for cool evenings, travel, and everyday layering.',
                    'image' => 'one-stop-mart/images/cat-fashion.webp',
                ],
                [
                    'sku' => 'OSM-30-017',
                    'name' => 'Premium Cotton Polo Shirt',
                    'category' => 'fashion',
                    'price' => 2950,
                    'short' => 'A versatile cotton polo shirt for smart-casual everyday dressing.',
                    'long' => 'The Premium Cotton Polo Shirt provides a neat and comfortable option for everyday wear. Its simple collar design works well for casual outings, office-casual settings, travel, and relaxed social occasions.',
                    'image' => 'one-stop-mart/images/cat-fashion.webp',
                ],
                [
                    'sku' => 'OSM-30-018',
                    'name' => 'Casual Crossbody Bag',
                    'category' => 'fashion',
                    'price' => 3750,
                    'short' => 'A compact crossbody bag for carrying everyday essentials securely.',
                    'long' => 'Keep daily essentials organized with a practical crossbody design featuring useful storage space and an adjustable strap. Ideal for shopping, commuting, travel, and casual everyday use.',
                    'image' => 'one-stop-mart/images/cat-fashion.webp',
                ],
                [
                    'sku' => 'OSM-30-019',
                    'name' => 'Minimal Everyday Backpack',
                    'category' => 'fashion',
                    'price' => 5500,
                    'short' => 'A clean, practical backpack for work, university, travel, and everyday use.',
                    'long' => 'The Minimal Everyday Backpack combines convenient storage with a modern understated design. Multiple compartments help organize common essentials, making it suitable for students, commuters, professionals, and short trips.',
                    'image' => 'one-stop-mart/images/cat-fashion.webp',
                ],
                [
                    'sku' => 'OSM-30-020',
                    'name' => 'Lightweight Sports Cap',
                    'category' => 'fashion',
                    'price' => 1850,
                    'short' => 'A lightweight adjustable cap for casual outfits and outdoor activities.',
                    'long' => 'Designed for simple everyday comfort, the Lightweight Sports Cap offers an adjustable fit and versatile styling. Wear it for casual outings, walking, travel, outdoor activities, or sporty everyday looks.',
                    'image' => 'one-stop-mart/images/cat-fashion.webp',
                ],

                // HOME & LIVING (21-25)
                [
                    'sku' => 'OSM-30-021',
                    'name' => 'Titanium Insulated Sports Bottle',
                    'category' => 'home-living',
                    'price' => 7800,
                    'short' => 'A premium insulated sports bottle designed to keep drinks convenient throughout the day.',
                    'long' => 'The Titanium Insulated Sports Bottle combines a sleek modern look with practical everyday hydration. Suitable for work, workouts, travel, school, and outdoor activities, its reusable design makes it a useful daily companion.',
                    'image' => 'one-stop-mart/images/product-bottle.png',
                ],
                [
                    'sku' => 'OSM-30-022',
                    'name' => 'Soft Microfiber Bedsheet Set',
                    'category' => 'home-living',
                    'price' => 5900,
                    'short' => 'A comfortable microfiber bedsheet set designed for a clean, cozy bedroom.',
                    'long' => 'Refresh the bedroom with a soft microfiber bedsheet set designed for everyday comfort and easy maintenance. Its versatile styling works with a variety of bedroom interiors while providing a neat finished look.',
                    'image' => 'one-stop-mart/images/cat-home.webp',
                ],
                [
                    'sku' => 'OSM-30-023',
                    'name' => 'Decorative Storage Basket Set',
                    'category' => 'home-living',
                    'price' => 3950,
                    'short' => 'A coordinated basket set for organizing everyday household items.',
                    'long' => 'Organize shelves, closets, bedrooms, and living areas with these decorative storage baskets. Their practical design helps keep everyday items contained while adding a clean and organized appearance to the home.',
                    'image' => 'one-stop-mart/images/cat-home.webp',
                ],
                [
                    'sku' => 'OSM-30-024',
                    'name' => 'Aroma Diffuser with Ambient Light',
                    'category' => 'home-living',
                    'price' => 4450,
                    'short' => 'A compact aroma diffuser with soft ambient lighting for relaxing spaces.',
                    'long' => 'Create a calm atmosphere in bedrooms, living rooms, or workspaces with a compact aroma diffuser featuring gentle ambient lighting. Its modern design makes it an attractive addition to home and office interiors.',
                    'image' => 'one-stop-mart/images/cat-home.webp',
                ],
                [
                    'sku' => 'OSM-30-025',
                    'name' => 'Vacuum Insulated Travel Mug',
                    'category' => 'home-living',
                    'price' => 3250,
                    'short' => 'A reusable insulated travel mug for coffee, tea, and everyday beverages.',
                    'long' => 'Designed for commuting and daily routines, the Vacuum Insulated Travel Mug offers a practical reusable option for carrying hot or cold drinks. Its compact shape fits comfortably into work, travel, and everyday lifestyles.',
                    'image' => 'one-stop-mart/images/product-bottle.png',
                ],

                // SPORTS (26-30)
                [
                    'sku' => 'OSM-30-026',
                    'name' => 'Adjustable Resistance Band Set',
                    'category' => 'sports',
                    'price' => 3900,
                    'short' => 'A versatile resistance-band set for home workouts, stretching, and strength training.',
                    'long' => 'Add variety to home or travel workouts with adjustable resistance bands suited to different exercises. Ideal for strength training, mobility work, warmups, and general fitness routines.',
                    'image' => 'one-stop-mart/images/cat-sports.webp',
                ],
                [
                    'sku' => 'OSM-30-027',
                    'name' => 'Non-Slip Yoga Mat',
                    'category' => 'sports',
                    'price' => 3250,
                    'short' => 'A comfortable non-slip exercise mat for yoga, stretching, and home workouts.',
                    'long' => 'The Non-Slip Yoga Mat provides a supportive exercise surface for yoga sessions, stretching, bodyweight workouts, and general fitness. Its lightweight format makes storage and transport convenient.',
                    'image' => 'one-stop-mart/images/cat-sports.webp',
                ],
                [
                    'sku' => 'OSM-30-028',
                    'name' => 'Adjustable Hand Grip Strengthener',
                    'category' => 'sports',
                    'price' => 1650,
                    'short' => 'A compact adjustable hand-grip trainer for grip and forearm exercises.',
                    'long' => 'Improve grip-training routines with an adjustable resistance hand strengthener. Its compact design makes it easy to use at home, at work, or while travelling.',
                    'image' => 'one-stop-mart/images/cat-sports.webp',
                ],
                [
                    'sku' => 'OSM-30-029',
                    'name' => 'Sports Gym Duffel Bag',
                    'category' => 'sports',
                    'price' => 4850,
                    'short' => 'A spacious gym bag for workout gear, clothing, shoes, and daily essentials.',
                    'long' => 'Carry training essentials in an organized duffel designed for gym sessions, sports activities, and short trips. The practical storage layout gives users room for clothing, accessories, and everyday gear.',
                    'image' => 'one-stop-mart/images/cat-sports.webp',
                ],
                [
                    'sku' => 'OSM-30-030',
                    'name' => 'Digital Jump Rope Counter',
                    'category' => 'sports',
                    'price' => 2350,
                    'short' => 'A fitness jump rope with a digital counter for convenient workout tracking.',
                    'long' => 'Add cardio training to home or outdoor workouts with a jump rope featuring a simple digital counter. Suitable for warmups, fitness routines, and regular cardio sessions.',
                    'image' => 'one-stop-mart/images/cat-sports.webp',
                ],
            ];

            $createdCount = 0;
            $updatedCount = 0;

            foreach ($productsData as $p) {
                $slug = Str::slug($p['name']);
                $catId = $categoryMap[$p['category']];

                $existing = Course::where('catalog_source', 'one_stop_mart_30')
                    ->where('source_key', $p['sku'])
                    ->first();

                if ($existing) {
                    $existing->update([
                        'name' => $p['name'],
                        'slug' => $slug,
                        'description' => $p['short'],
                        'long_description' => $p['long'],
                        'weekly_price' => $p['price'],
                        'monthly_price' => $p['price'],
                        'image_path' => $p['image'],
                        'category_id' => $catId,
                        'is_free' => false,
                        'purchase_model' => 'flexible',
                        'product_kind' => 'retail',
                        'is_storefront_visible' => true,
                    ]);
                    $updatedCount++;
                } else {
                    // Check duplicate slugs
                    if (Course::where('slug', $slug)->exists()) {
                        $slug = $slug . '-' . strtolower($p['sku']);
                    }

                    Course::create([
                        'name' => $p['name'],
                        'slug' => $slug,
                        'description' => $p['short'],
                        'long_description' => $p['long'],
                        'weekly_price' => $p['price'],
                        'monthly_price' => $p['price'],
                        'image_path' => $p['image'],
                        'category_id' => $catId,
                        'is_free' => false,
                        'purchase_model' => 'flexible',
                        'product_kind' => 'retail',
                        'is_storefront_visible' => true,
                        'catalog_source' => 'one_stop_mart_30',
                        'source_key' => $p['sku'],
                    ]);
                    $createdCount++;
                }
            }

            DB::commit();
            $this->info("Successfully installed 30 products ({$createdCount} created, {$updatedCount} updated).");
            return 0;

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error("Installation failed: " . $e->getMessage());
            return 1;
        }
    }
}
