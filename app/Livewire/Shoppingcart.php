<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Shoppingcart as Cart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Shoppingcart extends Component
{
    public $cartitems = [];
    public $totalAmount = 0;
    public $isGuest = false;

    public function mount()
    {
        $this->isGuest = !auth()->check();
        $this->loadCartItems();
    }

    private function sessionCart(): array
    {
        return session()->get('polani_cart', []);
    }

    private function saveSessionCart(array $items): void
    {
        session()->put('polani_cart', array_values($items));
    }

    public function loadCartItems()
    {
        if (auth()->check()) {
            $this->cartitems = Cart::with('product')
                ->where('user_id', auth()->id())
                ->get();

            $userId = auth()->id();
            foreach ($this->cartitems as $item) {
                $shouldRemove = false;

                $product = $item->product;
                if ($product && $product->is_free) {
                    \App\Services\FreeCourseService::enrollInFreeCourse($userId, $product->id);
                    $shouldRemove = true;
                }

                if ($shouldRemove) {
                    $item->delete();
                }
            }

            $this->cartitems = Cart::with('product')
                ->where('user_id', auth()->id())
                ->get();

            foreach ($this->cartitems as $item) {
                $product = $item->product;
                if ($product) {
                    $isAvailable = (bool)($product->is_storefront_visible ?? true);
                    $item->is_available = $isAvailable;
                    if (!$isAvailable) {
                        $item->price = 0.0;
                    }
                } else {
                    $item->is_available = true;
                }
            }
        } else {
            $this->cartitems = collect($this->sessionCart())->map(function ($item) {
                $productId = $item['product_id'] ?? null;
                $product = $productId ? Product::find($productId) : null;
                if (!$product) {
                    return null;
                }

                $isAvailable = (bool)($product->is_storefront_visible ?? true);
                $cartItem = new \stdClass();
                $cartItem->id = $product->id;
                $cartItem->product_id = $product->id;
                $cartItem->price = $isAvailable ? (float) ($item['price'] ?? $product->weekly_price ?? 0) : 0.0;
                $cartItem->quantity = (int) ($item['quantity'] ?? 1);
                $cartItem->discount_amount = 0;
                $cartItem->discount_reason = null;
                $cartItem->product = $product;
                $cartItem->is_available = $isAvailable;
                return $cartItem;
            })->filter()->values();
        }

        $this->calculateTotalAmount();
    }

    public function calculateTotalAmount()
    {
        $this->totalAmount = collect($this->cartitems)->sum(function ($item) {
            $price = (float) ($item->price ?? 0);
            $quantity = (int) ($item->quantity ?? 1);

            return $price * $quantity;
        });
    }

    public function incrementQuantity($itemId)
    {
        if (auth()->check()) {
            $cartItem = Cart::where('id', $itemId)
                ->where('user_id', auth()->id())
                ->first();

            if ($cartItem) {
                $cartItem->quantity = max(1, (int) $cartItem->quantity + 1);
                $cartItem->save();
            }
        } else {
            $cart = $this->sessionCart();

            foreach ($cart as &$item) {
                if ((int) ($item['product_id'] ?? 0) === (int) $itemId) {
                    $item['quantity'] = max(1, (int) ($item['quantity'] ?? 1) + 1);
                    break;
                }
            }
            unset($item);

            $this->saveSessionCart($cart);
        }

        $this->loadCartItems();
        $this->dispatch('cartUpdated');
    }

    public function decrementQuantity($itemId)
    {
        if (auth()->check()) {
            $cartItem = Cart::where('id', $itemId)
                ->where('user_id', auth()->id())
                ->first();

            if ($cartItem && (int) $cartItem->quantity > 1) {
                $cartItem->quantity = (int) $cartItem->quantity - 1;
                $cartItem->save();
            }
        } else {
            $cart = $this->sessionCart();

            foreach ($cart as &$item) {
                if ((int) ($item['product_id'] ?? 0) === (int) $itemId) {
                    $currentQuantity = (int) ($item['quantity'] ?? 1);
                    $item['quantity'] = max(1, $currentQuantity - 1);
                    break;
                }
            }
            unset($item);

            $this->saveSessionCart($cart);
        }

        $this->loadCartItems();
        $this->dispatch('cartUpdated');
    }

    public function removeFromCart($itemId)
    {
        if (auth()->check()) {
            Cart::where('id', $itemId)
                ->where('user_id', auth()->id())
                ->delete();
        } else {
            $cart = $this->sessionCart();
            $cart = array_values(array_filter($cart, fn ($item) => (int) ($item['product_id'] ?? 0) !== (int) $itemId));
            $this->saveSessionCart($cart);
        }

        $this->loadCartItems();
        $this->dispatch('cartUpdated');
    }

    public function checkout()
    {
        if ($this->cartitems->isEmpty()) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }

        foreach ($this->cartitems as $item) {
            if (isset($item->is_available) && !$item->is_available) {
                session()->flash('error', 'Please remove unavailable items before proceeding to checkout.');
                return;
            }
        }

        if (!auth()->check()) {
            session()->put('url.intended', route('checkout'));
            return redirect()->route('sign-in')
                ->with('error', 'Please sign in to continue with checkout for COD or online payment.');
        }

        return redirect()->route('checkout');
    }

    public function render()
    {
        return view('livewire.shoppingcart', [
            'cartitems' => $this->cartitems,
            'totalAmount' => $this->totalAmount,
        ])->layout('layouts.app');
    }
}
