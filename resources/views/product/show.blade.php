@extends('layouts.app')

@section('content')
{{-- Main Product Card Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-unit-2">
    {{-- Product Image --}}
    <div class="border border-bauhaus-black relative bg-bauhaus-gray">
        @if($product->isOutOfStock())
            <div class="absolute top-4 left-4 z-10 bg-bauhaus-red text-bauhaus-white px-3 py-1 text-xs font-black uppercase tracking-widest border border-bauhaus-black">
                Out of Stock
            </div>
        @elseif($product->isLowStock())
            <div class="absolute top-4 left-4 z-10 bg-bauhaus-yellow text-bauhaus-black px-3 py-1 text-xs font-black uppercase tracking-widest border border-bauhaus-black animate-pulse">
                Low Stock: Only {{ $product->stock }} Left!
            </div>
        @endif
        <img 
            src="{{ $product->image_url }}"
            alt="{{ $product->title }}"
            class="w-full h-96 md:h-full object-cover filter {{ $product->isOutOfStock() ? 'grayscale opacity-65' : '' }}"
        >
    </div>

    {{-- Product Details --}}
    <div class="flex flex-col justify-between p-unit-2 border border-bauhaus-black bg-white">
        <div>
            @if($product->category)
                <span class="text-xs uppercase font-black tracking-widest text-bauhaus-blue block mb-1">
                    {{ $product->category->name }}
                </span>
            @endif
            <h1 class="text-3xl font-black uppercase tracking-widest text-bauhaus-black">{{ $product->title }}</h1>
            <p class="text-xs text-gray-500 font-mono tracking-tighter mb-unit-2">SKU: {{ $product->sku }}</p>
            
            <div class="border-t border-b border-bauhaus-black py-unit-1 my-unit-1 flex justify-between items-center">
                <span class="text-3xs font-black uppercase tracking-widest text-gray-400">Price</span>
                <span class="text-3xl font-black text-bauhaus-black">${{ number_format($product->price, 2) }}</span>
            </div>

            <div class="my-unit-2">
                <h3 class="text-xs font-black uppercase tracking-widest text-bauhaus-black mb-1">Description</h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $product->description }}</p>
            </div>

            {{-- Tech Specs block --}}
            @if($product->material || $product->weight || $product->dimensions)
                <div class="my-unit-2 border border-bauhaus-black p-unit-1 bg-bauhaus-gray bg-opacity-20 text-xs">
                    <h3 class="text-xs font-black uppercase tracking-widest text-bauhaus-black mb-2 border-b border-bauhaus-black pb-1">Specifications</h3>
                    @if($product->material)
                        <div class="flex justify-between py-0.5"><span class="font-bold uppercase tracking-wider text-gray-400">Material</span><span class="font-bold">{{ $product->material }}</span></div>
                    @endif
                    @if($product->weight)
                        <div class="flex justify-between py-0.5"><span class="font-bold uppercase tracking-wider text-gray-400">Weight</span><span class="font-bold">{{ $product->weight }}</span></div>
                    @endif
                    @if($product->dimensions)
                        <div class="flex justify-between py-0.5"><span class="font-bold uppercase tracking-wider text-gray-400">Dimensions</span><span class="font-bold">{{ $product->dimensions }}</span></div>
                    @endif
                </div>
            @endif

            {{-- Stock Availability Indicator --}}
            <div class="my-unit-1 flex items-center gap-2">
                <span class="text-3xs font-black uppercase tracking-widest text-gray-400">Availability:</span>
                @if($product->isOutOfStock())
                    <span class="text-xs font-black uppercase text-bauhaus-red">Sold Out</span>
                @elseif($product->isLowStock())
                    <span class="text-xs font-black uppercase text-bauhaus-yellow animate-pulse">Low Stock ({{ $product->stock }} left)</span>
                @else
                    <span class="text-xs font-black uppercase text-green-600">{{ $product->stock }} Items In Stock</span>
                @endif
            </div>
        </div>

        {{-- Add to cart --}}
        <div class="mt-unit-2">
            @if($product->isOutOfStock())
                <div class="w-full bg-bauhaus-gray border border-bauhaus-black text-gray-400 py-3 uppercase font-black text-center text-sm tracking-wider cursor-not-allowed select-none">
                    Out of Stock
                </div>
            @else
                @auth
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <form method="POST" action="{{ route('cart.store') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="w-full bg-bauhaus-white text-bauhaus-black border border-bauhaus-black py-3 uppercase font-black tracking-wider text-sm hover:bg-bauhaus-gray transition-colors">
                                Add to cart
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('checkout.buyNow', $product->id) }}">
                            @csrf
                            <button type="submit" class="w-full bg-bauhaus-black text-bauhaus-white border border-bauhaus-black py-3 uppercase font-black tracking-wider text-sm hover:bg-bauhaus-blue transition-colors">
                                Buy it now
                            </button>
                        </form>
                    </div>
                @else 
                    <a href="{{ route('login') }}" class="block text-center bg-bauhaus-black text-white py-3 uppercase font-bold tracking-wider text-sm hover:bg-bauhaus-blue transition-colors">
                        Login to purchase
                    </a>
                @endauth
            @endif
        </div>
    </div>
</div>

{{-- Ratings & Reviews Section --}}
<div class="mt-unit-2 border border-bauhaus-black p-unit-2 bg-bauhaus-white">
    <h2 class="text-xl font-black uppercase tracking-widest border-b border-bauhaus-black pb-unit-1 mb-unit-2">Customer Reviews</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-unit-2">
        {{-- Ratings Breakdown --}}
        <div class="md:col-span-1 border-r border-bauhaus-black pr-unit-2 flex flex-col justify-center">
            @if($product->reviews->count() > 0)
                <div class="text-center">
                    <p class="text-5xl font-black text-bauhaus-black">{{ $product->averageRating() }}</p>
                    <p class="text-bauhaus-yellow text-xl tracking-widest my-1">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= round($product->averageRating()) ? '★' : '☆' }}
                        @endfor
                    </p>
                    <p class="text-3xs font-black uppercase tracking-widest text-gray-400">Based on {{ $product->reviews->count() }} reviews</p>
                </div>
                
                {{-- Horizontal Rating Breakdown Bars --}}
                <div class="mt-unit-2 space-y-1 text-xs">
                    @for($stars = 5; $stars >= 1; $stars--)
                        <div class="flex items-center gap-2">
                            <span class="w-8 font-bold">{{ $stars }} Star</span>
                            <div class="flex-1 h-3 bg-bauhaus-gray border border-bauhaus-black relative">
                                <div class="h-full bg-bauhaus-yellow" style="width: {{ $product->starRating($stars) }}%"></div>
                            </div>
                            <span class="w-8 text-right font-bold text-gray-500">{{ $product->ratingCount($stars) }}</span>
                        </div>
                    @endfor
                </div>
            @else
                <div class="text-center py-unit-2">
                    <p class="text-sm font-bold uppercase tracking-wider text-gray-400">No ratings yet for this product.</p>
                </div>
            @endif
        </div>

        {{-- Reviews List --}}
        <div class="md:col-span-2 space-y-unit-2 overflow-y-auto max-h-96 pr-2">
            @if($product->reviews->count() > 0)
                @foreach($product->reviews as $review)
                    <div class="border-l-4 border-bauhaus-black pl-unit-1 py-1">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-black text-xs uppercase tracking-wider text-bauhaus-black">{{ $review->user->name }}</span>
                            <span class="text-3xs font-mono text-gray-400">{{ $review->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="text-bauhaus-yellow text-xs mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $review->rating ? '★' : '☆' }}
                            @endfor
                        </div>
                        @if($review->comment)
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                        @else
                            <p class="text-xs italic text-gray-400">Rating left without a comment.</p>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="h-full flex items-center justify-center text-center py-unit-2">
                    <p class="text-xs uppercase font-black tracking-widest text-gray-400">Be the first to share your thoughts.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Write a Review Form --}}
    @auth
        <div class="border-t border-bauhaus-black mt-unit-2 pt-unit-2">
            <h3 class="text-sm font-black uppercase tracking-widest mb-unit-1">Share Your Opinion</h3>
            <form method="POST" action="{{ route('reviews.store', $product->id) }}" class="max-w-xl space-y-4">
                @csrf
                
                {{-- Rating input --}}
                <div>
                    <label for="rating" class="block text-3xs font-black uppercase tracking-widest mb-1">Rating</label>
                    <select 
                        name="rating" 
                        id="rating" 
                        required 
                        class="w-full sm:w-1/3 text-xs border border-bauhaus-black px-2 py-1.5 bg-bauhaus-white focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
                    >
                        <option value="5">★★★★★ (5 - Excellent)</option>
                        <option value="4">★★★★☆ (4 - Very Good)</option>
                        <option value="3">★★★☆☆ (3 - Good)</option>
                        <option value="2">★★☆☆☆ (2 - Fair)</option>
                        <option value="1">★☆☆☆☆ (1 - Poor)</option>
                    </select>
                </div>

                {{-- Comment input --}}
                <div>
                    <label for="comment" class="block text-3xs font-black uppercase tracking-widest mb-1">Review Comments</label>
                    <textarea 
                        name="comment" 
                        id="comment" 
                        rows="3" 
                        placeholder="Detail your experience with this design product..." 
                        class="w-full text-xs border border-bauhaus-black px-2 py-1.5 bg-bauhaus-white focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
                    ></textarea>
                </div>

                <button type="submit" class="bg-bauhaus-black text-bauhaus-white px-6 py-2 text-xs font-black uppercase tracking-widest hover:bg-bauhaus-blue transition-colors border border-bauhaus-black">
                    Submit Review
                </button>
            </form>
        </div>
    @else
        <div class="border-t border-bauhaus-black mt-unit-2 pt-unit-2 text-center bg-bauhaus-gray bg-opacity-20 p-unit-1">
            <p class="text-xs uppercase font-black tracking-widest text-gray-400">
                You must <a href="{{ route('login') }}" class="text-bauhaus-blue underline">login</a> to write a review.
            </p>
        </div>
    @endauth
</div>

{{-- Rule-Based Recommendations Grid --}}
<div class="mt-unit-2">
    <h2 class="text-xl font-black uppercase tracking-widest mb-unit-1 text-bauhaus-black border-b border-bauhaus-black pb-2">You May Also Like</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-unit-2">
        @foreach($recommendations as $rec)
            <div class="border border-bauhaus-black p-unit-1 flex flex-col justify-between bg-bauhaus-white relative">
                @if($rec->isOutOfStock())
                    <span class="absolute top-2 left-2 z-10 bg-bauhaus-red text-bauhaus-white px-2 py-0.5 text-4xs font-black uppercase tracking-widest border border-bauhaus-black">Out of Stock</span>
                @endif
                <div>
                    <div class="relative border border-bauhaus-black bg-bauhaus-gray">
                        <img 
                            src="{{ $rec->image_url }}" 
                            alt="{{ $rec->title }}" 
                            class="w-full h-32 object-cover filter {{ $rec->isOutOfStock() ? 'grayscale opacity-60' : '' }}"
                        >
                    </div>
                    @if($rec->category)
                        <span class="text-4xs uppercase font-black tracking-widest text-bauhaus-blue block mt-2 mb-0.5">
                            {{ $rec->category->name }}
                        </span>
                    @endif
                    <h4 class="font-bold text-sm uppercase leading-tight mt-1">{{ $rec->title }}</h4>
                    <p class="text-3xs text-gray-400 font-mono tracking-tighter">SKU: {{ $rec->sku }}</p>
                </div>
                <div class="mt-2 border-t border-bauhaus-black pt-2 flex justify-between items-center">
                    <span class="font-bold text-sm text-bauhaus-black">${{ number_format($rec->price, 2) }}</span>
                    <a 
                        href="{{ route('product.show', $rec->id) }}" 
                        class="px-2.5 py-1 bg-bauhaus-black text-bauhaus-white uppercase font-black text-4xs tracking-widest hover:bg-bauhaus-blue transition-colors border border-bauhaus-black"
                    > View
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
            