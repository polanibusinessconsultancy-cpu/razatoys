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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('stripe_session_id')->nullable()->unique();
            $table->string('stripe_payment_intent_id')->nullable()->index();
            $table->unsignedBigInteger('gateway_amount')->nullable();
            $table->string('gateway_currency', 3)->nullable();
            $table->string('payment_status')->nullable();
            $table->timestamp('payment_finalized_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique('orders_stripe_session_id_unique');
            $table->dropIndex('orders_stripe_payment_intent_id_index');
            $table->dropColumn([
                'stripe_session_id',
                'stripe_payment_intent_id',
                'gateway_amount',
                'gateway_currency',
                'payment_status',
                'payment_finalized_at',
            ]);
        });
    }
};
