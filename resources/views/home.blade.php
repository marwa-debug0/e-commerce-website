@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold uppercase tracking-widest mb-unit-1">Collection</h2>

{{-- Category Filter Bar --}}
<div class="mb-unit-2 flex flex-wrap gap-2 items-center border-b border-bauhaus-black pb-unit-1">
    <span class="text-xs font-black uppercase tracking-widest mr-2">Category:</span>
    <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="px-3 py-1 text-xs font-bold uppercase tracking-wider border {{ !request('category') ? 'bg-bauhaus-black text-bauhaus-white border-bauhaus-black' : 'border-bauhaus-black text-bauhaus-black hover:bg-bauhaus-gray' }}">
        All
    </a>
    @foreach($categories as $category)
        <a href="{{ request()->fullUrlWithQuery(['category' => $category->slug]) }}" class="px-3 py-1 text-xs font-bold uppercase tracking-wider border {{ request('category') === $category->slug ? 'bg-bauhaus-black text-bauhaus-white border-bauhaus-black' : 'border-bauhaus-black text-bauhaus-black hover:bg-bauhaus-gray' }}">
            {{ $category->name }}
        </a>
    @endforeach
</div>

{{-- Bauhaus Unified Search & Filters Panel --}}
<form method="GET" action="{{ route('home') }}" class="mb-unit-2 border border-bauhaus-black p-unit-1 bg-bauhaus-gray bg-opacity-30">
    {{-- Retain category if set --}}
    @if(request('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-unit-1 items-end">
        {{-- Search Input --}}
        <div>
            <label for="search" class="block text-3xs font-black uppercase tracking-widest mb-1">Search Products</label>
            <input 
                type="text" 
                name="search" 
                id="search" 
                value="{{ request('search') }}" 
                placeholder="Title or description..." 
                class="w-full text-xs border border-bauhaus-black px-2 py-1.5 bg-bauhaus-white focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
            >
        </div>

        {{-- Sort By --}}
        <div>
            <label for="sort_by" class="block text-3xs font-black uppercase tracking-widest mb-1">Sort By</label>
            <select 
                name="sort_by" 
                id="sort_by" 
                class="w-full text-xs border border-bauhaus-black px-2 py-1.5 bg-bauhaus-white focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
            >
                <option value="">Latest Arrivals</option>
                <option value="price_asc" {{ request('sort_by') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort_by') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="title_asc" {{ request('sort_by') === 'title_asc' ? 'selected' : '' }}>Name: A-Z</option>
            </select>
        </div>

        {{-- Price Range Min/Max --}}
        <div>
            <label class="block text-3xs font-black uppercase tracking-widest mb-1">Price Range ($)</label>
            <div class="flex items-center gap-1">
                <input 
                    type="number" 
                    name="min_price" 
                    value="{{ request('min_price') }}" 
                    placeholder="Min" 
                    class="w-1/2 text-xs border border-bauhaus-black px-2 py-1.5 bg-bauhaus-white focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
                >
                <span class="text-xs font-bold text-bauhaus-black">—</span>
                <input 
                    type="number" 
                    name="max_price" 
                    value="{{ request('max_price') }}" 
                    placeholder="Max" 
                    class="w-1/2 text-xs border border-bauhaus-black px-2 py-1.5 bg-bauhaus-white focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
                >
            </div>
        </div>

        {{-- Actions: Filter + Reset --}}
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-bauhaus-black text-bauhaus-white py-1.5 text-xs font-black uppercase tracking-wider hover:bg-bauhaus-blue transition-colors">
                Apply
            </button>
            <a href="{{ route('home', ['category' => request('category')]) }}" class="flex-1 border border-bauhaus-black text-bauhaus-black py-1.5 text-xs font-black uppercase tracking-wider text-center hover:bg-bauhaus-gray transition-colors">
                Clear
            </a>
        </div>
    </div>

    {{-- Availability Filter Row --}}
    <div class="mt-2 flex items-center">
        <input 
            type="checkbox" 
            name="in_stock" 
            id="in_stock" 
            value="1" 
            {{ request('in_stock') ? 'checked' : '' }} 
            class="h-3.5 w-3.5 border-bauhaus-black text-bauhaus-black focus:ring-bauhaus-blue"
        >
        <label for="in_stock" class="ml-2 text-3xs font-black uppercase tracking-widest text-bauhaus-black cursor-pointer select-none">
            In Stock Only
        </label>
    </div>
</form>

{{-- Product Catalog Grid --}}
@if($products->isEmpty())
    <div class="border border-bauhaus-black p-unit-2 text-center my-unit-2">
        <p class="text-sm font-bold uppercase tracking-wider text-gray-500">No products match your active search filters.</p>
        <a href="{{ route('home') }}" class="inline-block mt-unit-1 px-4 py-2 bg-bauhaus-black text-bauhaus-white font-bold uppercase text-xs tracking-widest">
            Reset All Filters
        </a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-3 gap-unit-2">
        @foreach($products as $product)
        <div class="border border-bauhaus-black p-unit-2 flex flex-col justify-between relative bg-bauhaus-white">
            
            {{-- Inventory Status Badges --}}
            @if($product->isOutOfStock())
                <div class="absolute top-2 left-2 z-10 bg-bauhaus-red text-bauhaus-white px-2 py-0.5 text-3xs font-black uppercase tracking-widest border border-bauhaus-black">
                    Out of Stock
                </div>
            @elseif($product->isLowStock())
                <div class="absolute top-2 left-2 z-10 bg-bauhaus-yellow text-bauhaus-black px-2 py-0.5 text-3xs font-black uppercase tracking-widest border border-bauhaus-black animate-pulse">
                    Only {{ $product->stock }} Left
                </div>
            @endif

            <div>
                <div class="relative overflow-hidden border border-bauhaus-black bg-bauhaus-gray">
                    <img 
                        src="{{ $product->image_url }}" 
                        alt="{{ $product->title }}" 
                        class="w-full h-48 object-cover filter {{ $product->isOutOfStock() ? 'grayscale opacity-60' : '' }}"
                    >
                </div>
                
                @if($product->category)
                    <span class="text-3xs uppercase font-black tracking-widest text-bauhaus-blue block mt-unit-1 mb-1">
                        {{ $product->category->name }}
                    </span>
                @endif
                
                <h3 class="text-lg font-bold uppercase {{ !$product->category ? 'mt-unit-1' : '' }}">{{ $product->title }}</h3>
                <p class="text-xs text-gray-400 font-mono tracking-tighter mb-1">SKU: {{ $product->sku }}</p>
                <p class="text-sm mt-1 text-gray-600 line-clamp-2">{{ $product->description }}</p>
            </div>
            
            <div class="mt-unit-1">
                {{-- Rating Stars Row --}}
                @if($product->reviews->count() > 0)
                    <div class="flex items-center gap-1 text-xs mb-unit-1">
                        <span class="text-bauhaus-yellow text-sm font-bold">★</span>
                        <span class="font-bold text-bauhaus-black">{{ $product->averageRating() }}</span>
                        <span class="text-gray-400">({{ $product->reviews->count() }})</span>
                    </div>
                @else
                    <div class="text-3xs font-bold uppercase tracking-wider text-gray-400 mb-unit-1">No reviews yet</div>
                @endif

                <div class="flex justify-between items-center border-t border-bauhaus-black pt-unit-1 mt-unit-1">
                    <p class="font-semibold text-lg text-bauhaus-black">${{ number_format($product->price, 2) }}</p>
                    <a 
                        href="{{ route('product.show', $product->id) }}" 
                        class="px-4 py-2 bg-bauhaus-black text-bauhaus-white uppercase font-bold text-xs tracking-wider hover:bg-bauhaus-blue transition-colors border border-bauhaus-black"
                    > View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection