<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('shoppingcarts')) {
            if (!Schema::hasColumn('shoppingcarts', 'product_id')) {
                // Step A & B: Add nullable product_id and index
                Schema::table('shoppingcarts', function (Blueprint $table) {
                    $table->unsignedBigInteger('product_id')->nullable()->after('quantity');
                    $table->index('product_id', 'shoppingcarts_product_id_index');
                });
            }

            // Step C & D: Backfill product_id from verified course_id inside transaction
            DB::transaction(function () {
                DB::statement("
                    UPDATE shoppingcarts 
                    SET product_id = course_id 
                    WHERE product_id IS NULL 
                      AND course_id IS NOT NULL 
                      AND course_id IN (SELECT id FROM products)
                ");
            });

            // Step E: Add foreign key constraint to products.id
            try {
                Schema::table('shoppingcarts', function (Blueprint $table) {
                    $table->foreign('product_id', 'shoppingcarts_product_id_foreign')
                          ->references('id')
                          ->on('products')
                          ->onDelete('cascade');
                });
            } catch (\Throwable $e) {
                // Foreign key constraint might already exist
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('shoppingcarts') && Schema::hasColumn('shoppingcarts', 'product_id')) {
            Schema::table('shoppingcarts', function (Blueprint $table) {
                try {
                    $table->dropForeign('shoppingcarts_product_id_foreign');
                } catch (\Throwable $e) {}
                try {
                    $table->dropIndex('shoppingcarts_product_id_index');
                } catch (\Throwable $tableException) {}
                $table->dropColumn('product_id');
            });
        }
    }
};
