@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

<h2 class="text-2xl font-bold uppercase tracking-widest mb-unit-2">Checkout</h2>

<form method="POST" action="{{ route('checkout.store') }}">
@csrf

<div class="grid grid-cols-1 lg:grid-cols-3 gap-unit-2 items-start">

    {{-- ── STEPS ───────────────────────────────────────────────────── --}}
    <div class="lg:col-span-2 border border-bauhaus-black divide-y divide-bauhaus-black">

        {{-- ── STEP 1: Shipping Address ──────────────────────────── --}}
        <div id="step-1">
            <button type="button" onclick="toggleStep('step-1')"
                    class="w-full flex items-center justify-between px-unit-1 py-unit-1 hover:bg-gray-50 transition-colors text-left">
                <div class="flex items-center gap-unit-1">
                    <span id="step-1-num" class="w-8 h-8 bg-bauhaus-black text-bauhaus-white flex items-center justify-center text-xs font-black flex-shrink-0">1</span>
                    <span class="text-sm font-black uppercase tracking-widest">Shipping Address</span>
                </div>
                <span id="step-1-arrow" class="text-xs font-black text-gray-400">▼</span>
            </button>

            <div id="step-1-body" class="px-unit-2 pb-unit-2 bg-gray-50 border-t border-bauhaus-gray">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-unit-1 mt-unit-1">

                    <div class="md:col-span-2">
                        <label class="block text-xs font-black uppercase tracking-widest mb-1">Full Name</label>
                        <input type="text" name="shipping_name" value="{{ old('shipping_name') }}"
                               placeholder="Walter Gropius"
                               class="w-full border border-bauhaus-black px-3 py-2 text-sm bg-bauhaus-white focus:outline-none focus:border-bauhaus-blue">
                        @error('shipping_name') <p class="text-xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-black uppercase tracking-widest mb-1">Street Address</label>
                        <input type="text" name="shipping_address" value="{{ old('shipping_address') }}"
                               placeholder="1 Bauhaus-Allee"
                               class="w-full border border-bauhaus-black px-3 py-2 text-sm bg-bauhaus-white focus:outline-none focus:border-bauhaus-blue">
                        @error('shipping_address') <p class="text-xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest mb-1">City</label>
                        <input type="text" name="shipping_city" value="{{ old('shipping_city') }}"
                               placeholder="Dessau"
                               class="w-full border border-bauhaus-black px-3 py-2 text-sm bg-bauhaus-white focus:outline-none focus:border-bauhaus-blue">
                        @error('shipping_city') <p class="text-xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest mb-1">ZIP / Postal Code</label>
                        <input type="text" name="shipping_zip" value="{{ old('shipping_zip') }}"
                               placeholder="06842"
                               class="w-full border border-bauhaus-black px-3 py-2 text-sm bg-bauhaus-white focus:outline-none focus:border-bauhaus-blue">
                        @error('shipping_zip') <p class="text-xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest mb-1">Country</label>
                        <input type="text" name="shipping_country" value="{{ old('shipping_country', 'Germany') }}"
                               placeholder="Germany"
                               class="w-full border border-bauhaus-black px-3 py-2 text-sm bg-bauhaus-white focus:outline-none focus:border-bauhaus-blue">
                        @error('shipping_country') <p class="text-xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-black uppercase tracking-widest mb-1">Phone Number</label>
                        <input type="tel" name="shipping_phone" value="{{ old('shipping_phone') }}"
                               placeholder="+49 340 6508 0"
                               class="w-full border border-bauhaus-black px-3 py-2 text-sm bg-bauhaus-white focus:outline-none focus:border-bauhaus-blue">
                        @error('shipping_phone') <p class="text-xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>
                <button type="button" onclick="confirmStep('step-1','step-2')"
                        class="mt-unit-1 bg-bauhaus-black text-bauhaus-white px-unit-2 py-3 uppercase font-bold text-sm tracking-wider hover:bg-bauhaus-blue transition-colors">
                    Continue to Delivery →
                </button>
            </div>
        </div>

        {{-- ── STEP 2: Review Items & Delivery ───────────────────── --}}
        <div id="step-2">
            <button type="button" onclick="toggleStep('step-2')"
                    class="w-full flex items-center justify-between px-unit-1 py-unit-1 hover:bg-gray-50 transition-colors text-left">
                <div class="flex items-center gap-unit-1">
                    <span id="step-2-num" class="w-8 h-8 bg-bauhaus-black text-bauhaus-white flex items-center justify-center text-xs font-black flex-shrink-0">2</span>
                    <span class="text-sm font-black uppercase tracking-widest">Review Items & Delivery</span>
                </div>
                <span id="step-2-arrow" class="text-xs font-black text-gray-400">▶</span>
            </button>

            <div id="step-2-body" class="hidden px-unit-2 pb-unit-2 bg-gray-50 border-t border-bauhaus-gray">
                <div class="mt-unit-1 border border-bauhaus-black divide-y divide-bauhaus-gray">
                    @foreach($cartItems as $item)
                    <div class="flex items-center gap-unit-1 p-unit-1">
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->title }}"
                             class="w-16 h-16 object-cover border border-bauhaus-black flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="font-black uppercase text-sm truncate">{{ $item->product->title }}</p>
                            <p class="text-xs text-gray-500 font-bold">Qty: {{ $item->quantity }}</p>
                        </div>
                        <p class="font-black text-base flex-shrink-0">${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="mt-unit-1 border-l-4 border-bauhaus-blue bg-gray-100 px-unit-1 py-3">
                    <p class="text-sm font-bold">
                        🚚 <strong>FREE delivery</strong> estimated by <strong>{{ $deliveryDate }}</strong>
                    </p>
                </div>

                <button type="button" onclick="confirmStep('step-2','step-3')"
                        class="mt-unit-1 bg-bauhaus-black text-bauhaus-white px-unit-2 py-3 uppercase font-bold text-sm tracking-wider hover:bg-bauhaus-blue transition-colors">
                    Continue to Review Total →
                </button>
            </div>
        </div>

        {{-- ── STEP 3: Coupon & Total ─────────────────────────────── --}}
        <div id="step-3">
            <button type="button" onclick="toggleStep('step-3')"
                    class="w-full flex items-center justify-between px-unit-1 py-unit-1 hover:bg-gray-50 transition-colors text-left">
                <div class="flex items-center gap-unit-1">
                    <span id="step-3-num" class="w-8 h-8 bg-bauhaus-black text-bauhaus-white flex items-center justify-center text-xs font-black flex-shrink-0">3</span>
                    <span class="text-sm font-black uppercase tracking-widest">Coupon & Total</span>
                </div>
                <span id="step-3-arrow" class="text-xs font-black text-gray-400">▶</span>
            </button>

            <div id="step-3-body" class="hidden px-unit-2 pb-unit-2 bg-gray-50 border-t border-bauhaus-gray mt-unit-1">
                @if($coupon)
                <div class="flex items-center gap-2 bg-bauhaus-blue text-white px-unit-1 py-3 text-xs font-black uppercase tracking-wider mb-unit-1">
                    <span>✓</span>
                    <span>{{ $coupon['code'] }} applied —
                        @if($coupon['type'] === 'percent') {{ $coupon['value'] }}% off
                        @else ${{ number_format($coupon['value'], 2) }} off
                        @endif
                    </span>
                </div>
                @else
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-unit-1">
                    Have a code? <a href="{{ route('cart.index') }}" class="text-bauhaus-blue underline">Apply it in the cart →</a>
                </p>
                @endif

                <div class="border border-bauhaus-black divide-y divide-bauhaus-gray">
                    <div class="flex justify-between px-unit-1 py-3 text-sm font-bold">
                        <span>Subtotal</span><span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between px-unit-1 py-3 text-sm font-bold">
                        <span>Shipping</span><span class="text-bauhaus-blue font-black">FREE</span>
                    </div>
                    @if($discount > 0)
                    <div class="flex justify-between px-unit-1 py-3 text-sm font-bold text-bauhaus-red">
                        <span>Discount ({{ $coupon['code'] }})</span>
                        <span>−${{ number_format($discount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between px-unit-1 py-3 text-xl font-black">
                        <span>Total</span><span>${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── ORDER SUMMARY SIDEBAR ────────────────────────────────────── --}}
    <div class="sticky top-0 flex flex-col gap-unit-2">

        <div class="border border-bauhaus-black">
            <div class="bg-bauhaus-black text-bauhaus-white px-unit-1 py-2 text-xs font-black uppercase tracking-widest">
                Order Summary
            </div>
            <div class="p-unit-1 divide-y divide-bauhaus-gray text-sm">
                @foreach($cartItems as $item)
                <div class="flex justify-between py-2 font-bold text-xs">
                    <span class="truncate pr-2">{{ Str::limit($item->product->title, 22) }} ×{{ $item->quantity }}</span>
                    <span class="flex-shrink-0">${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                </div>
                @endforeach
                <div class="flex justify-between py-2 font-bold">
                    <span>Subtotal</span><span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between py-2 font-bold">
                    <span>Shipping</span><span class="text-bauhaus-blue font-black">FREE</span>
                </div>
                @if($discount > 0)
                <div class="flex justify-between py-2 font-bold text-bauhaus-red">
                    <span>Discount</span><span>−${{ number_format($discount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between py-2 text-xl font-black">
                    <span>Total</span><span>${{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>

        <button type="submit"
                class="w-full bg-bauhaus-black text-bauhaus-white py-3 uppercase font-bold text-sm tracking-wider hover:bg-bauhaus-blue transition-colors">
            Place Order — ${{ number_format($total, 2) }}
        </button>

        <p class="text-center text-xs font-bold uppercase tracking-wider text-gray-400">🔒 Secure Checkout</p>

    </div>

</div>
</form>

<script>
    function toggleStep(id) {
        const body  = document.getElementById(id + '-body');
        const arrow = document.getElementById(id + '-arrow');
        const isOpen = !body.classList.contains('hidden');
        body.classList.toggle('hidden', isOpen);
        arrow.textContent = isOpen ? '▶' : '▼';
    }

    function confirmStep(currentId, nextId) {
        const num = document.getElementById(currentId + '-num');
        num.textContent = '✓';
        num.classList.remove('bg-bauhaus-black');
        num.classList.add('bg-bauhaus-blue');

        document.getElementById(currentId + '-body').classList.add('hidden');
        document.getElementById(currentId + '-arrow').textContent = '▶';

        document.getElementById(nextId + '-body').classList.remove('hidden');
        document.getElementById(nextId + '-arrow').textContent = '▼';

        document.getElementById(nextId).scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
</script>

@endsection