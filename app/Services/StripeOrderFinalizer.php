<?php

namespace App\Services;

use App\Models\Order;
use App\Models\UserCourse;
use App\Models\Lecture;
use App\Models\Shoppingcart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StripeOrderFinalizer
{
    /**
     * Finalize order status and grant access atomically.
     *
     * @param Order $order
     * @param \Stripe\Checkout\Session $session
     * @return bool
     * @throws \Exception
     */
    public function finalize(Order $order, \Stripe\Checkout\Session $session): bool
    {
        return DB::transaction(function () use ($order, $session) {
            // Reload and lock the order
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            // Safe double finalization check
            if ($order->payment_finalized_at !== null) {
                Log::info("Order {$order->id} is already finalized.");
                return true;
            }

            // Confirm payment method and stripe references
            if ($order->payment_method !== 'card') {
                throw new \Exception("Order {$order->id} payment method is not card.");
            }

            if ($order->stripe_session_id !== $session->id) {
                throw new \Exception("Order stripe session ID mismatch.");
            }

            // Confirm Stripe transaction states
            if ($session->payment_status !== 'paid') {
                throw new \Exception("Stripe checkout session payment status is not paid.");
            }

            if ($session->status !== 'complete') {
                throw new \Exception("Stripe checkout session status is not complete.");
            }

            // Verify expected amounts and currencies
            if ((int)$session->amount_total !== (int)$order->gateway_amount) {
                throw new \Exception("Stripe amount total {$session->amount_total} does not match order gateway amount {$order->gateway_amount}.");
            }

            if (strtolower($session->currency) !== strtolower($order->gateway_currency)) {
                throw new \Exception("Stripe currency {$session->currency} does not match order gateway currency {$order->gateway_currency}.");
            }

            // Verify metadata association
            $metadataOrderId = $session->metadata->order_id ?? null;
            $metadataCheckoutToken = $session->metadata->checkout_token ?? null;
            $clientRefId = $session->client_reference_id ?? null;

            if ((string)$metadataOrderId !== (string)$order->id) {
                throw new \Exception("Stripe metadata order ID mismatch.");
            }

            if ((string)$metadataCheckoutToken !== (string)$order->checkout_token) {
                throw new \Exception("Stripe metadata checkout token mismatch.");
            }

            if ((string)$clientRefId !== (string)$order->id) {
                throw new \Exception("Stripe client reference ID mismatch.");
            }

            // Allowed order statuses for finalization: pending or failed (if retry is allowed/recovered)
            if (!in_array($order->status, ['pending', 'failed'])) {
                throw new \Exception("Order {$order->id} is not in a pending or failed state.");
            }

            // Set finalization fields
            $order->update([
                'stripe_payment_intent_id' => $session->payment_intent,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_finalized_at' => now(),
            ]);

            // Increment coupon uses count if present
            if ($order->coupon_code) {
                $coupon = \App\Models\Coupon::where('code', $order->coupon_code)->first();
                if ($coupon) {
                    $coupon->incrementUses();
                }
            }

            // Decode snapshot items
            $cartItems = json_decode($order->cart_items ?? '[]', true);
            if (!is_array($cartItems)) {
                $cartItems = [];
            }

            // Grant Course Access
            foreach ($cartItems as $item) {
                if (isset($item['lecture_id']) && $item['lecture_id'] !== null) {
                    // Lecture specific access
                    $lecture = Lecture::find($item['lecture_id']);
                    if ($lecture) {
                        UserCourse::firstOrCreate([
                            'user_id' => $order->user_id,
                            'course_id' => $lecture->course_id,
                            'lecture_id' => $item['lecture_id'],
                            'status' => 'active'
                        ], [
                            'order_id' => $order->id
                        ]);
                    }
                } else {
                    $productId = $item['product_id'] ?? null;
                    if ($productId !== null) {
                        $course = \App\Models\Course::find($productId);
                        if ($course && $course->product_kind === 'course') {
                            UserCourse::firstOrCreate([
                                'user_id' => $order->user_id,
                                'course_id' => $productId,
                                'lecture_id' => null,
                                'status' => 'active'
                            ], [
                                'order_id' => $order->id
                            ]);
                        }
                    }
                }

            }

            // Clear represented cart rows
            $cartRowIds = collect($cartItems)->pluck('cart_row_id')->filter()->toArray();
            if (!empty($cartRowIds)) {
                Shoppingcart::where('user_id', $order->user_id)
                    ->whereIn('id', $cartRowIds)
                    ->delete();
            }

            return true;
        });
    }
}
