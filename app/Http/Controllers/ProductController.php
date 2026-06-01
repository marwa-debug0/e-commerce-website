<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(\Illuminate\Http\Request $request) {
        $categories = \App\Models\Category::all();
        $query = Product::with('category');

        // 1. Search Query (Title + Description)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 2. Category Filter (Slug)
        if ($request->filled('category')) {
            $category = \App\Models\Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // 3. Price Range Filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 4. Availability Filter (In Stock Only)
        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        // 5. Sorting Options
        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'title_asc':
                    $query->orderBy('title', 'asc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->get();

        return view('home', compact('products', 'categories'));
    }

    public function show(Product $product) {
        // Load reviews with user who authored them
        $product->load(['category', 'reviews.user']);
        
        // Rule-based Recommendations ("You may also like"):
        // A. Same category (excluding current product)
        $recommendations = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // B. If fewer than 4 items, backfill with similar price range (+/- 30%)
        if ($recommendations->count() < 4) {
            $needed = 4 - $recommendations->count();
            $excludeIds = $recommendations->pluck('id')->push($product->id)->toArray();
            
            $minPrice = $product->price * 0.7;
            $maxPrice = $product->price * 1.3;

            $extra = Product::whereBetween('price', [$minPrice, $maxPrice])
                ->whereNotIn('id', $excludeIds)
                ->orderByRaw('ABS(price - ?)', [$product->price])
                ->take($needed)
                ->get();

            $recommendations = $recommendations->concat($extra);
        }

        return view('product.show', compact('product', 'recommendations'));
    }
}
