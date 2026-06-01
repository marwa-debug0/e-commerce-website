<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(\Illuminate\Http\Request $request) {
        $categories = \App\Models\Category::all();
        $query = Product::with('category');

        if ($request->filled('category')) {
            $category = \App\Models\Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $products = $query->get();

        return view('home', compact('products', 'categories'));
    }

    public function show(Product $product) {
        $product->load('category');
        return view('product.show', compact('product'));
    }
}
