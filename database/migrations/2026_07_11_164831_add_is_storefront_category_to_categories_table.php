<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('categories', 'is_storefront_category')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->boolean('is_storefront_category')->default(false);
            });
        }

        // Backfill existing One Stop Mart storefront categories
        $storefrontSlugs = [
            'toys',
            'kitchenware',
            'perfumes',
            'electronics',
            'fashion',
            'home-living',
            'sports'
        ];

        \Illuminate\Support\Facades\DB::table('categories')
            ->whereIn('slug', $storefrontSlugs)
            ->update(['is_storefront_category' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'is_storefront_category')) {
                $table->dropColumn('is_storefront_category');
            }
        });
    }
};
