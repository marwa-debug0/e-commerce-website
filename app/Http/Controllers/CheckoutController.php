<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;



class CheckoutController extends Controller
{
    /**
     * Buy Now — instantly add to cart and redirect to checkout.
     */
    public function buyNow(\App\Models\Product $product)
    {
        $user = auth()->user();
        $item = $user->carts()->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            $user->carts()->create([
                'product_id' => $product->id,
                'quantity'   => 1,
            ]);
        }

        return redirect()->route('checkout.index');
    }

    /**
     * Show the checkout page.
     */
    public function index()
    {
        $cartItems = auth()->user()->carts()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $coupon   = session('coupon');
        $subtotal = $cartItems->sum(fn ($i) => $i->product->price * $i->quantity);
        
        $discount = 0;
        if ($coupon) {
            if ($subtotal >= $coupon['min_spend']) {
                if ($coupon['type'] === 'percent') {
                    $discount = round($subtotal * ($coupon['value'] / 100), 2);
                } else {
                    $discount = min($coupon['value'], $subtotal);
                }
            } else {
                session()->forget('coupon');
                $coupon = null;
            }
        }
        
        $total    = max(0, $subtotal - $discount);

        // Estimated delivery: 3 business days from now
        $deliveryDate = $this->estimateDelivery();

        return view('checkout.index', compact(
            'cartItems', 'coupon', 'subtotal', 'discount', 'total', 'deliveryDate'
        ));
    }

    /**
     * Process the order.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_name'    => 'required|string|max:100',
            'shipping_address' => 'required|string|max:255',
            'shipping_city'    => 'required|string|max:100',
            'shipping_zip'     => 'required|string|max:20',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
        ]);

        $user      = auth()->user();
        $cartItems = $user->carts()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $coupon   = session('coupon');
        $subtotal = $cartItems->sum(fn ($i) => $i->product->price * $i->quantity);
        
        $discount = 0;
        if ($coupon) {
            if ($subtotal >= $coupon['min_spend']) {
                if ($coupon['type'] === 'percent') {
                    $discount = round($subtotal * ($coupon['value'] / 100), 2);
                } else {
                    $discount = min($coupon['value'], $subtotal);
                }
            } else {
                session()->forget('coupon');
                $coupon = null;
            }
        }
        
        $total    = max(0, $subtotal - $discount);

        // Create the order in a transaction
        $order = DB::transaction(function() use ($user, $cartItems, $validated, $coupon, $discount, $total) {
            $order = Order::create([
                'user_id'          => $user->id,
                'total_price'      => $total,
                'status'           => 'pending',
                'name'             => $validated['shipping_name'],
                'email'            => $user->email,
                'phone'            => $validated['shipping_phone'],
                'address'          => $validated['shipping_address'],
                'city'             => $validated['shipping_city'],
                'country'          => $validated['shipping_country'],
                'postal_code'      => $validated['shipping_zip'],
                'coupon_code'      => $coupon['code'] ?? null,
                'discount_amount'  => $discount,
            ]);

            // Save order items
            foreach ($cartItems as $item) {
                $order->orderItems()->create([
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                ]);
            }

            // Clear cart
            $user->carts()->delete();

            return $order;
        });

        // Clear promo from session
        session()->forget('coupon');

        $deliveryDate = $this->estimateDelivery();

        return view('checkout.success', compact('order', 'deliveryDate'));
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    protected function estimateDelivery(): string
    {
        $date = now();
        $days = 0;

        while ($days < 3) {
            $date->addDay();
            // Skip weekends
            if (! in_array($date->dayOfWeek, [\Carbon\Carbon::SATURDAY, \Carbon\Carbon::SUNDAY])) {
                $days++;
            }
        }

        return $date->format('l, F j');
    }
}
