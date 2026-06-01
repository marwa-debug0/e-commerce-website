<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class AdminProductController extends Controller
{
    /**
     * Display a listing of products in the admin panel.
     */
    public function index()
    {
        $products = Product::with('category')->orderBy('title', 'asc')->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'price'        => 'required|numeric|min:0',
            'category_id'  => 'nullable|exists:categories,id',
            'stock'        => 'required|integer|min:0',
            'sku'          => 'required|string|unique:products,sku',
            'image_url'    => 'nullable|url',
            'material'     => 'nullable|string|max:100',
            'weight'       => 'nullable|string|max:50',
            'dimensions'   => 'nullable|string|max:100',
        ]);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'price'        => 'required|numeric|min:0',
            'category_id'  => 'nullable|exists:categories,id',
            'stock'        => 'required|integer|min:0',
            'sku'          => 'required|string|unique:products,sku,' . $product->id,
            'image_url'    => 'nullable|url',
            'material'     => 'nullable|string|max:100',
            'weight'       => 'nullable|string|max:50',
            'dimensions'   => 'nullable|string|max:100',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}
