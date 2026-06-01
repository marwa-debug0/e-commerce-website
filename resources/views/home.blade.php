@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold uppercase tracking-widest mb-unit-1">Collection</h2>

{{-- Category Filter Bar --}}
<div class="mb-unit-2 flex flex-wrap gap-2 items-center border-b border-bauhaus-black pb-unit-1">
    <span class="text-xs font-black uppercase tracking-widest mr-2">Filter:</span>
    <a href="{{ route('home') }}" class="px-3 py-1 text-xs font-bold uppercase tracking-wider border {{ !request('category') ? 'bg-bauhaus-black text-bauhaus-white border-bauhaus-black' : 'border-bauhaus-black text-bauhaus-black hover:bg-bauhaus-gray' }}">
        All
    </a>
    @foreach($categories as $category)
        <a href="{{ route('home', ['category' => $category->slug]) }}" class="px-3 py-1 text-xs font-bold uppercase tracking-wider border {{ request('category') === $category->slug ? 'bg-bauhaus-black text-bauhaus-white border-bauhaus-black' : 'border-bauhaus-black text-bauhaus-black hover:bg-bauhaus-gray' }}">
            {{ $category->name }}
        </a>
    @endforeach
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-unit-2">
    @foreach($products as $product)
    <div class="border border-bauhaus-black p-unit-2 flex flex-col justify-between">
        <div>
            <img 
                src="{{ $product->image_url }}" 
                alt="{{ $product->title }}" 
                class="w-full h-48 object-cover border border-bauhaus-black"
            >
            @if($product->category)
                <span class="text-3xs uppercase font-black tracking-widest text-bauhaus-blue block mt-unit-1 mb-1">
                    {{ $product->category->name }}
                </span>
            @endif
            <h3 class="text-lg font-bold uppercase {{ !$product->category ? 'mt-unit-1' : '' }}">{{ $product->title }}</h3>
            <p class="text-sm mt-1 text-gray-600">{{ $product->description }}</p>
        </div>
        <div>
            <p class="font-semibold mt-unit-1">${{ number_format($product->price, 2) }}</p>
            <a 
                href="{{ route('product.show', $product->id) }}" 
                class="block mt-unit-2 text-center bg-bauhaus-black text-bauhaus-white py-2 uppercase font-bold text-sm tracking-wider hover:bg-bauhaus-blue transition-colors"
            > View Product
            </a>
        </div>
    </div>
    @endforeach
</div>
@endsection