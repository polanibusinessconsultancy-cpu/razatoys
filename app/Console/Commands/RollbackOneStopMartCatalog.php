<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use App\Models\Category;
use App\Models\Blog;
use App\Models\FooterSetting;
use App\Models\CarouselSlide;
use Illuminate\Support\Facades\DB;

class RollbackOneStopMartCatalog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'one-stop-mart:rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback One Stop Mart catalog import using the generated manifest';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $manifestPath = storage_path('app/backups/import_rollback_manifest.json');

        if (!file_exists($manifestPath)) {
            $this->error("Rollback failed: Manifest file '{$manifestPath}' not found.");
            return 1;
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);
        if (!is_array($manifest)) {
            $this->error("Rollback failed: Invalid manifest structure.");
            return 1;
        }

        $this->info("Starting rollback using manifest created at: " . ($manifest['timestamp'] ?? 'unknown'));

        DB::beginTransaction();

        try {
            // 1. Delete inserted products
            $insertedProducts = $manifest['inserted_products'] ?? [];
            if (!empty($insertedProducts)) {
                $deletedCount = Course::whereIn('id', $insertedProducts)->delete();
                $this->info("Deleted {$deletedCount} inserted retail products.");
            }

            // 2. Delete inserted categories
            $insertedCategories = $manifest['inserted_categories'] ?? [];
            if (!empty($insertedCategories)) {
                $deletedCount = Category::whereIn('id', $insertedCategories)->delete();
                $this->info("Deleted {$deletedCount} inserted categories.");
            }

            // 3. Restore hidden old products
            $hiddenProducts = $manifest['hidden_products'] ?? [];
            if (!empty($hiddenProducts)) {
                $restoredCount = Course::whereIn('id', $hiddenProducts)->update(['is_storefront_visible' => true]);
                $this->info("Restored visibility for {$restoredCount} Polani products.");
            }

            // 4. Restore hidden old blogs
            $hiddenBlogs = $manifest['hidden_blogs'] ?? [];
            if (!empty($hiddenBlogs)) {
                $restoredCount = Blog::whereIn('id', $hiddenBlogs)->update(['is_storefront_visible' => true]);
                $this->info("Restored visibility for {$restoredCount} Polani blogs.");
            }

            // 5. Restore footer settings
            $footerData = $manifest['footer_settings'] ?? [];
            if (!empty($footerData)) {
                $footer = FooterSetting::getSettings();
                if ($footer) {
                    $footer->update([
                        'brand_name' => $footerData['brand_name'] ?? $footer->brand_name,
                        'brand_tagline' => $footerData['brand_tagline'] ?? $footer->brand_tagline,
                        'brand_description' => $footerData['brand_description'] ?? $footer->brand_description,
                        'copyright_name' => $footerData['copyright_name'] ?? $footer->copyright_name,
                        'copyright_url' => $footerData['copyright_url'] ?? $footer->copyright_url,
                        // Restore Polani contact defaults
                        'phone' => '+1 (555) 123-4567',
                        'email' => 'support@polanifragrance.com',
                        'address' => '123 Perfume Lane, scent city',
                    ]);
                    $this->info("Restored footer settings branding and contact details.");
                }
            }

            // 6. Restore carousel slides
            $carouselSlides = $manifest['carousel_slides'] ?? [];
            foreach ($carouselSlides as $slideData) {
                $slide = CarouselSlide::find($slideData['id']);
                if ($slide) {
                    $slide->update([
                        'title' => $slideData['title'],
                        'subtitle' => $slideData['subtitle'],
                        'cta_text' => $slideData['cta_text'],
                        'cta_url' => $slideData['cta_url'],
                        'image_name' => $slideData['image_name'],
                    ]);
                    $this->info("Restored carousel slide ID {$slideData['id']}.");
                }
            }

            DB::commit();

            // Delete manifest
            unlink($manifestPath);
            $this->info("Rollback manifest deleted.");
            $this->info("Rollback completed successfully.");
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Rollback process failed: " . $e->getMessage());
            return 1;
        }
    }
}
