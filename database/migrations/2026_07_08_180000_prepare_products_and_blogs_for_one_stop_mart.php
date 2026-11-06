<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'is_storefront_visible')) {
                    $table->boolean('is_storefront_visible')->default(true)->index();
                }
                if (!Schema::hasColumn('products', 'catalog_source')) {
                    $table->string('catalog_source')->nullable()->index();
                }
                if (!Schema::hasColumn('products', 'source_key')) {
                    $table->string('source_key')->nullable();
                }
                if (!Schema::hasColumn('products', 'product_kind')) {
                    $table->string('product_kind')->default('course')->index();
                }

                // Add unique constraint for catalog_source + source_key
                $table->unique(['catalog_source', 'source_key'], 'products_catalog_source_source_key_unique');
            });
        }

        if (Schema::hasTable('blogs')) {
            Schema::table('blogs', function (Blueprint $table) {
                if (!Schema::hasColumn('blogs', 'is_storefront_visible')) {
                    $table->boolean('is_storefront_visible')->default(true)->index();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropUnique('products_catalog_source_source_key_unique');
                
                $columns = [];
                if (Schema::hasColumn('products', 'is_storefront_visible')) {
                    $columns[] = 'is_storefront_visible';
                }
                if (Schema::hasColumn('products', 'catalog_source')) {
                    $columns[] = 'catalog_source';
                }
                if (Schema::hasColumn('products', 'source_key')) {
                    $columns[] = 'source_key';
                }
                if (Schema::hasColumn('products', 'product_kind')) {
                    $columns[] = 'product_kind';
                }

                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('blogs')) {
            Schema::table('blogs', function (Blueprint $table) {
                if (Schema::hasColumn('blogs', 'is_storefront_visible')) {
                    $table->dropColumn('is_storefront_visible');
                }
            });
        }
    }
};
