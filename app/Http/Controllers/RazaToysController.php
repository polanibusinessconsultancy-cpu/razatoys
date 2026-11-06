<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\CarouselSlide;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Controller for RazaToys Storefront Redesign.
 * Handles isolated preview routes and homepage rendering.
 */
class RazaToysController extends Controller
{
    /**
     * Display the complete RazaToys homepage.
     */
    public function home(Request $request)
    {
        // 1. Categories
        $categories = Category::query()
            ->where('is_storefront_category', true)
            ->withCount(['products' => function ($q) {
                $q->where('is_storefront_visible', true);
            }])
            ->get();

        // 2. Real Toy Products
        $allToys = Product::query()
            ->where('category_id', 7) // Toys
            ->where('is_storefront_visible', true)
            ->with('category')
            ->get();

        // If toys category is empty or has fewer than 4 products, include visible products
        if ($allToys->isEmpty()) {
            $allToys = Product::query()
                ->where('is_storefront_visible', true)
                ->with('category')
                ->take(8)
                ->get();
        }

        // 3. Trending Toys (Tab 1)
        $trendingToys = $allToys->take(6);

        // 4. Best Sellers (Tab 2)
        $bestSellers = Product::query()
            ->where('is_storefront_visible', true)
            ->with('category')
            ->orderBy('id', 'asc')
            ->take(6)
            ->get();

        // 5. New Arrivals (Tab 3)
        $newArrivals = Product::query()
            ->where('is_storefront_visible', true)
            ->with('category')
            ->latest('id')
            ->take(6)
            ->get();

        // 6. Educational Toys Spotlight
        $educationalToys = Product::query()
            ->where('category_id', 7)
            ->where('is_storefront_visible', true)
            ->where(function ($q) {
                $q->where('name', 'like', '%Learning%')
                  ->orWhere('name', 'like', '%Blocks%')
                  ->orWhere('name', 'like', '%Doctor%')
                  ->orWhere('name', 'like', '%Creative%');
            })
            ->with('category')
            ->get();

        if ($educationalToys->isEmpty()) {
            $educationalToys = $allToys->take(4);
        }

        // 7. RC & Action Toys Spotlight
        $rcActionToys = Product::query()
            ->where('category_id', 7)
            ->where('is_storefront_visible', true)
            ->where(function ($q) {
                $q->where('name', 'like', '%Car%')
                  ->orWhere('name', 'like', '%Monster%')
                  ->orWhere('name', 'like', '%Stunt%');
            })
            ->with('category')
            ->get();

        if ($rcActionToys->isEmpty()) {
            $rcActionToys = $allToys->take(4);
        }

        // 8. Kitchenware Collection (Visually Secondary)
        $kitchenware = Product::query()
            ->where('category_id', 8) // Kitchenware
            ->where('is_storefront_visible', true)
            ->with('category')
            ->take(6)
            ->get();

        // 9. Dynamic Banners
        $banners = CarouselSlide::query()
            ->where('is_active', true)
            ->where('page_key', 'home')
            ->orderBy('order')
            ->get();

        // 10. Blogs / Playtime Guides
        $blogs = Blog::query()
            ->where('is_storefront_visible', true)
            ->latest('id')
            ->take(3)
            ->get();

        // 11. Cart count
        $cartCount = count(session('razatoys_cart', session('polani_cart', [])));

        return view('razatoys.home', [
            'categories' => $categories,
            'trendingToys' => $trendingToys,
            'allToys' => $allToys,
            'bestSellers' => $bestSellers,
            'newArrivals' => $newArrivals,
            'educationalToys' => $educationalToys,
            'rcActionToys' => $rcActionToys,
            'kitchenware' => $kitchenware,
            'banners' => $banners,
            'blogs' => $blogs,
            'cartCount' => $cartCount,
        ]);
    }
}
