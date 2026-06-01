@extends('layouts.app')

@section('content')

<div class="text-center py-16 border border-bauhaus-black">
    <h2 class="text-4xl font-bold uppercase tracking-widest">Order Confirmed</h2>
    <p class="mt-unit-2 text-gray-600 uppercase tracking-wider">Thank you for your purchase.</p>
    <a 
        href="{{ route('home') }}" 
        class="inline-block mt-unit-2 bg-bauhaus-black text-bauhaus-white px-unit-2 py-3 uppercase font-bold tracking-wider"
    >
        Continue Shopping
    </a>
</div>

@endsection