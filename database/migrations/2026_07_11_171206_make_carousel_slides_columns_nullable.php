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
        Schema::table('carousel_slides', function (Blueprint $table) {
            $table->string('title', 100)->nullable()->change();
            $table->text('subtitle')->nullable()->change();
            $table->string('cta_text', 50)->nullable()->change();
            $table->string('cta_url', 255)->nullable()->change();
            $table->string('image_name', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carousel_slides', function (Blueprint $table) {
            $table->string('title', 100)->nullable(false)->change();
            $table->text('subtitle')->nullable(false)->change();
            $table->string('cta_text', 50)->nullable(false)->change();
            $table->string('cta_url', 255)->nullable(false)->change();
            $table->string('image_name', 100)->nullable(false)->change();
        });
    }
};
