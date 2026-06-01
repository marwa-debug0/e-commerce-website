@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold uppercase tracking-widest mb-unit-2">Your Cart</h2>

@if($cartItems->isEmpty())
    <p class="text-gray-500">Your cart is empty. <a href="{{ route('home') }}" class="underline font-bold">Shop now</a>.</p>
@else
    <div class="border border-bauhaus-black">
        @foreach($cartItems as $item)
            <div class="flex items-center justify-between p-unit-2 border-b border-bauhaus-gray">

                {{-- Product Info --}}
                <div class="flex items-center gap-unit-2">
                    <img src="{{ $item->product->image_url }}" class="w-16 h-16 object-cover border border-bauhaus-black">
                    <div>
                        <p class="font-bold uppercase">{{ $item->product->title }}</p>
                        <p class="text-sm text-gray-600">${{ $item->product->price }}</p>
                    </div>
                </div>

                {{-- Quantity Update --}}
                <form method="POST" action="{{ route('cart.update', $item->id) }}" class="flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <input 
                        type="number" 
                        name="quantity" 
                        value="{{ $item->quantity }}" 
                        min="1"
                        class="w-16 border border-bauhaus-black text-center py-1"
                    >
                    <button type="submit" class="bg-bauhaus-black text-bauhaus-white px-3 py-1 text-sm uppercase font-bold">
                        Update
                    </button>
                </form>

                {{-- Remove --}}
                <form method="POST" action="{{ route('cart.destroy', $item->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm uppercase font-bold underline">
                        Remove
                    </button>
                </form>

            </div>
        @endforeach
    </div>

    {{-- Coupon & Total --}}
    <div class="mt-unit-2 grid grid-cols-1 md:grid-cols-2 gap-unit-2">
        {{-- Coupon Form --}}
        <div class="border border-bauhaus-black p-unit-2 flex flex-col justify-between">
            @if(session()->has('coupon'))
                <div>
                    <p class="text-sm font-bold uppercase tracking-wider mb-2">Coupon Applied</p>
                    <div class="flex items-center justify-between bg-bauhaus-gray p-2 border border-bauhaus-black">
                        <div>
                            <span class="font-black text-sm uppercase">{{ session('coupon.code') }}</span>
                            <span class="text-xs font-bold text-gray-600 ml-2">
                                ({{ session('coupon.type') === 'percent' ? session('coupon.value').'% off' : '$'.session('coupon.value').' off' }})
                            </span>
                        </div>
                        <form method="POST" action="{{ route('coupon.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-black uppercase text-bauhaus-red underline">Remove</button>
                        </form>
                    </div>
                </div>
            @else
                <form method="POST" action="{{ route('coupon.apply') }}">
                    @csrf
                    <label class="block text-xs font-black uppercase tracking-widest mb-2">Have a coupon code?</label>
                    <div class="flex border border-bauhaus-black">
                        <input 
                            type="text" 
                            name="code" 
                            placeholder="BAUHAUS10" 
                            class="flex-1 px-3 py-2 text-sm bg-bauhaus-white focus:outline-none"
                            required
                        >
                        <button type="submit" class="bg-bauhaus-black text-bauhaus-white px-unit-2 py-2 text-xs font-bold uppercase tracking-wider hover:bg-bauhaus-blue transition-colors">
                            Apply
                        </button>
                    </div>
                </form>
            @endif
        </div>

        {{-- Order Summary --}}
        <div class="border border-bauhaus-black p-unit-2 divide-y divide-bauhaus-gray">
            @php
                $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
                $coupon = session('coupon');
                $discount = 0;
                if ($coupon && $subtotal >= $coupon['min_spend']) {
                    if ($coupon['type'] === 'percent') {
                        $discount = round($subtotal * ($coupon['value'] / 100), 2);
                    } else {
                        $discount = min($coupon['value'], $subtotal);
                    }
                }
                $total = max(0, $subtotal - $discount);
            @endphp
            <div class="flex justify-between py-2 text-sm font-bold uppercase">
                <span>Subtotal</span>
                <span>${{ number_format($subtotal, 2) }}</span>
            </div>
            @if($discount > 0)
                <div class="flex justify-between py-2 text-sm font-bold uppercase text-bauhaus-red">
                    <span>Discount ({{ session('coupon.code') }})</span>
                    <span>-${{ number_format($discount, 2) }}</span>
                </div>
            @endif
            <div class="flex justify-between py-2 text-xl font-black uppercase">
                <span>Total</span>
                <span>${{ number_format($total, 2) }}</span>
            </div>
            <div class="pt-4">
                <a href="{{ route('checkout.index') }}" class="block text-center bg-bauhaus-black text-bauhaus-white py-3 uppercase font-bold tracking-wider hover:bg-bauhaus-blue transition-colors">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    </div>
@endif

@endsection