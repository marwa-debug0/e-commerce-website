@extends('layouts.app')


@section('content')


<div class="grid grid-cols-1 md:grid-cols-2 gap-unit-2">
    {{-- Product Image--}}
    <div class="border border-bauhaus-black">
        <img 
            src="{{ $product->image_url }}"
            alt="{{ $product->title }}"
            class="w-full h-full object-cover"
        >
    </div>

    {{-- Product Details --}}
    <div class="flex flex-col justify-between p-unit-2 border border-bauhaus-black">
        <div>
            <h1 class="text-3xl font-bold uppercase tracking-widest">{{$product->title}}</h1>
            <p class="mt-unit-1 text-gray-600">{{ $product->description }}</p>
            <p class="text-2xl font-bold mt-unit-2">${{ $product->price }}</p>
        </div>

        {{-- Add to cart --}}
        @auth
            <form method="POST" action="{{ route('cart.store') }}" class="mt-unit-2">
                @csrf
                <input type="hidden" name="product_id" value="{{$product->id}}">
                <button type="submit" class="w-full bg-bauhaus-black text-white py-3 uppercase font-bold tracking-wider">
                    Add to cart
                </button>
            </form>
        @else 
            <a href="{{ route('login') }}" class="block mt-unit-2 text-center bg-bauhaus-black text-white py-3 uppercase font-bold tracking-wider">
                Login to add to cart
            </a>
        @endauth
    </div>

</div>

@endsection
            