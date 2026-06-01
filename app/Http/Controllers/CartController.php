<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Cart;


class CartController extends Controller
{
    public function index() {
        $cartItems = Auth::user()->carts()->with('product')->get();
        return view('cart.index', compact('cartItems'));
    }

    public function store(Request $request) {
        $user = Auth::user();
        $existing = Cart::where('user_id', $user->id)->where('product_id', $request->product_id)->first();

        if($existing) {
            $existing->increment('quantity');
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'quantity' => 1
            ]);
        }

        return redirect()->route('cart.index');
    }

    public function update(Request $request, Cart $cart) {
        $cart->update(['quantity' => $request->quantity]);
        return back();
    }

    public function destroy(Cart $cart) {
        $cart->delete();
        return back();
    }
}
