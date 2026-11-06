<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use App\Models\StripeWebhookEvent;
use App\Models\Order;
use App\Services\StripeOrderFinalizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    /**
     * Handle incoming Stripe webhook events.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        if (empty($signature)) {
            Log::error('Stripe webhook error: Missing signature header.');
            return response()->json(['error' => 'Missing signature'], 400);
        }

        if (empty($secret)) {
            Log::error('Stripe webhook error: Webhook secret is not configured.');
            return response()->json(['error' => 'Webhook secret not configured'], 400);
        }

        try {
            $event = Webhook::constructEvent($payload, $signature, $secret);
        } catch (UnexpectedValueException $e) {
            Log::error('Stripe webhook error: Invalid payload JSON: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook error: Signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Exact Stripe Events whitelist
        $allowedEvents = [
            'checkout.session.completed',
            'checkout.session.expired',
        ];

        if (!in_array($event->type, $allowedEvents)) {
            Log::info("Stripe webhook: Event type {$event->type} is ignored.");
            
            // Record as ignored event
            StripeWebhookEvent::firstOrCreate(
                ['stripe_event_id' => $event->id],
                [
                    'event_type' => $event->type,
                    'stripe_object_id' => $event->data->object->id ?? null,
                    'status' => 'ignored',
                    'processed_at' => now(),
                ]
            );

            return response()->json(['status' => 'ignored'], 200);
        }

        // 1. Create or load the event log
        $eventLog = StripeWebhookEvent::firstOrCreate(
            ['stripe_event_id' => $event->id],
            [
                'event_type' => $event->type,
                'stripe_object_id' => $event->data->object->id ?? null,
                'status' => 'received',
            ]
        );

        try {
            // 2. Lock event row
            $result = DB::transaction(function () use ($eventLog, $event) {
                $lockedLog = StripeWebhookEvent::whereKey($eventLog->id)->lockForUpdate()->firstOrFail();

                // 3. Processed or ignored
                if (in_array($lockedLog->status, ['processed', 'ignored'])) {
                    return response()->json(['status' => $lockedLog->status], 200);
                }

                // 4. Processing lockout
                if ($lockedLog->status === 'processing') {
                    return response()->json(['error' => 'Event is currently processing'], 409);
                }

                // 5. recibido/fallido transition
                $lockedLog->increment('attempts');
                $lockedLog->update(['status' => 'processing']);

                // Process specific events
                $session = $event->data->object;
                $orderId = $session->metadata->order_id ?? null;

                if (!$orderId) {
                    throw new \Exception("Stripe event metadata is missing order_id.");
                }

                $order = Order::find($orderId);
                if (!$order) {
                    throw new \Exception("Order {$orderId} not found in local database.");
                }

                if ($event->type === 'checkout.session.completed') {
                    // Initialize finalizer
                    $finalizer = app(StripeOrderFinalizer::class);
                    $finalizer->finalize($order, $session);
                } elseif ($event->type === 'checkout.session.expired') {
                    // Lock the pending order row
                    $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

                    if ($lockedOrder->status === 'pending') {
                        $lockedOrder->update([
                            'status' => 'failed',
                            'payment_status' => 'unpaid',
                        ]);
                        Log::info("Order {$order->id} marked failed due to Stripe checkout session expiration.");
                    }
                }

                // Save processed state
                $lockedLog->update([
                    'status' => 'processed',
                    'processed_at' => now(),
                    'last_error' => null,
                ]);

                return response()->json(['status' => 'processed'], 200);
            });

            return $result;

        } catch (\Throwable $e) {
            Log::error("Stripe webhook processing exception: " . $e->getMessage(), ['exception' => $e]);

            // Save fail state in DB
            $eventLog->update([
                'status' => 'failed',
                'last_error' => substr($e->getMessage(), 0, 500),
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }
}
