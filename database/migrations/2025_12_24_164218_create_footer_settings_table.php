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
        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();

            // Brand Section
            $table->string('brand_name')->default('One Stop Mart');
            $table->string('brand_tagline')->default('Everyday Essentials');
            $table->text('brand_description')->default('Your ultimate online general store for quality toys, kitchenware, electronics, fashion, home essentials, and more.');

            // Social Media Links
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();

            // Contact Information
            $table->string('address')->default('Dawat-e-Islami, Global Headquarters, Karachi, Pakistan');
            $table->string('email')->default('support@onestopmart.com');
            $table->string('phone')->default('+92 (21) 111-25-26-27');

            // Copyright & Footer Text
            $table->string('copyright_name')->default('One Stop Mart');
            $table->string('copyright_url')->default('https://polanifragrance.com');
            $table->text('footer_text')->default('Made with passion for Sharia education.');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_settings');
    }
};
