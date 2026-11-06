<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'cart_items',
        'total',
        'status',
        'billing_address',
        'payment_method',
        'discount',
        'final_total',
        'coupon_code',
        'rejection_reason',
        'checkout_token',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'gateway_amount',
        'gateway_currency',
        'payment_status',
        'payment_finalized_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'gateway_amount' => 'integer',
        'payment_finalized_at' => 'datetime',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the accounting transaction for this order.
     */
    public function accountTransaction()
    {
        return $this->hasOne(\App\Models\AccountTransaction::class);
    }
}
