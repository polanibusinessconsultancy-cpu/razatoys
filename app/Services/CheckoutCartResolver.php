<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Shoppingcart;

class CheckoutCartResolver
{
    /**
     * Resolve and verify cart items and totals from server-side storage.
     *
     * @param int|null $userId
     * @param array $sessionCart
     * @return array
     */
    public function resolve($userId = null, array $sessionCart = []): array
    {
        $items = [];
        $total = 0.0;

        if ($userId) {
            $cartRows = Shoppingcart::with(['product'])
                ->where('user_id', $userId)
                ->get();

            foreach ($cartRows as $row) {
                $qty = max(1, (int)$row->quantity);
                $product = $row->product;
                if ($product) {
                    if ($product->is_free) {
                        continue;
                    }
                    $isAvailable = (bool)($product->is_storefront_visible ?? true);
                    $unitPrice = $isAvailable ? (float)($product->weekly_price ?? 0) : 0.0;
                    $lineTotal = $unitPrice * $qty;
                    $items[] = [
                        'cart_row_id' => $row->id,
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'type' => 'Product',
                        'price' => $unitPrice,
                        'unit_price' => $unitPrice,
                        'quantity' => $qty,
                        'line_total' => $lineTotal,
                        'is_available' => $isAvailable,
                    ];
                    $total += $lineTotal;
                }
            }
        } else {
            foreach ($sessionCart as $item) {
                $productId = $item['product_id'] ?? null;
                if ($productId) {
                    $product = Product::find($productId);
                    if ($product) {
                        if ($product->is_free) {
                            continue;
                        }
                        $isAvailable = (bool)($product->is_storefront_visible ?? true);
                        $qty = max(1, (int)($item['quantity'] ?? 1));
                        $unitPrice = $isAvailable ? (float)($product->weekly_price ?? 0) : 0.0;
                        $lineTotal = $unitPrice * $qty;
                        $items[] = [
                            'cart_row_id' => null,
                            'product_id' => $product->id,
                            'name' => $product->name,
                            'type' => 'Product',
                            'price' => $unitPrice,
                            'unit_price' => $unitPrice,
                            'quantity' => $qty,
                            'line_total' => $lineTotal,
                            'is_available' => $isAvailable,
                        ];
                        $total += $lineTotal;
                    }
                }
            }
        }

        return [
            'items' => collect($items),
            'total' => $total,
        ];
    }
}
