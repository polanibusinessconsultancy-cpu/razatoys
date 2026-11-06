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
        if (!Schema::hasTable('contact_messages')) {
            Schema::create('contact_messages', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->string('email', 255);
                $table->string('subject', 200);
                $table->text('message');
                $table->enum('status', ['new', 'read', 'replied', 'closed'])->default('new');
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                $table->index('status');
                $table->index('email');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
