<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if the user already reviewed this product to avoid duplicates (optional but good practice)
        $existing = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->update([
                'rating'  => $validated['rating'],
                'comment' => $validated['comment'],
            ]);
            $msg = 'Your review has been updated!';
        } else {
            Review::create([
                'user_id'    => Auth::id(),
                'product_id' => $product->id,
                'rating'     => $validated['rating'],
                'comment'    => $validated['comment'],
            ]);
            $msg = 'Thank you for your review!';
        }

        return redirect()->route('product.show', $product->id)->with('success', $msg);
    }
}
