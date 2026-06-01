@extends('layouts.admin')

@section('content')
<div class="mb-unit-2 border-b border-bauhaus-black pb-unit-1">
    <h1 class="text-3xl font-black uppercase tracking-widest text-bauhaus-black">Operational Dashboard</h1>
    <p class="text-xs text-gray-500 uppercase tracking-widest">Aggregate Sales Reports & Live Analytics</p>
</div>

{{-- Metrics Row --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-unit-2 mb-unit-2">
    {{-- Total Revenue Card --}}
    <div class="border border-bauhaus-black p-unit-1 bg-bauhaus-white flex flex-col justify-between">
        <span class="text-3xs font-black uppercase tracking-widest text-gray-400">Total Revenue</span>
        <span class="text-3xl font-black text-bauhaus-black mt-2">${{ number_format($totalRevenue, 2) }}</span>
        <span class="text-4xs font-bold uppercase tracking-widest text-bauhaus-blue mt-2">All completed sales</span>
    </div>

    {{-- Total Orders Card --}}
    <div class="border border-bauhaus-black p-unit-1 bg-bauhaus-white flex flex-col justify-between">
        <span class="text-3xs font-black uppercase tracking-widest text-gray-400">Total Orders</span>
        <span class="text-3xl font-black text-bauhaus-black mt-2">{{ $ordersCount }}</span>
        <span class="text-4xs font-bold uppercase tracking-widest text-bauhaus-blue mt-2">Placed checkouts</span>
    </div>

    {{-- Products Catalog Card --}}
    <div class="border border-bauhaus-black p-unit-1 bg-bauhaus-white flex flex-col justify-between">
        <span class="text-3xs font-black uppercase tracking-widest text-gray-400">Total Catalog</span>
        <span class="text-3xl font-black text-bauhaus-black mt-2">{{ $productsCount }}</span>
        <span class="text-4xs font-bold uppercase tracking-widest text-bauhaus-blue mt-2">Unique designs</span>
    </div>

    {{-- Stock Alerts Card --}}
    <div class="border border-bauhaus-black p-unit-1 bg-bauhaus-white flex flex-col justify-between {{ $alertProducts->count() > 0 ? 'border-bauhaus-red' : '' }}">
        <span class="text-3xs font-black uppercase tracking-widest text-gray-400">Stock Alerts</span>
        <span class="text-3xl font-black mt-2 {{ $alertProducts->count() > 0 ? 'text-bauhaus-red' : 'text-bauhaus-black' }}">{{ $alertProducts->count() }}</span>
        <span class="text-4xs font-bold uppercase tracking-widest mt-2 {{ $alertProducts->count() > 0 ? 'text-bauhaus-red font-black' : 'text-gray-400' }}">
            {{ $alertProducts->count() > 0 ? 'Needs restock attention' : 'Inventory fully optimized' }}
        </span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-unit-2">
    
    {{-- Best Selling Products --}}
    <div class="border border-bauhaus-black p-unit-2 bg-bauhaus-white">
        <h3 class="text-sm font-black uppercase tracking-widest mb-unit-1 border-b border-bauhaus-black pb-2 text-bauhaus-black flex justify-between items-center">
            <span>Best Selling Designs</span>
            <span class="text-3xs bg-bauhaus-black text-bauhaus-white px-2 py-0.5 font-bold">Top 5</span>
        </h3>
        @if($bestSellers->isEmpty())
            <p class="text-xs italic text-gray-400 py-unit-2 text-center">No sales registered yet.</p>
        @else
            <div class="space-y-4 py-2">
                @foreach($bestSellers as $item)
                    @if($item->product)
                        <div class="flex items-center justify-between border-b border-bauhaus-gray pb-2 last:border-b-0">
                            <div>
                                <h4 class="font-bold text-xs uppercase text-bauhaus-black">{{ $item->product->title }}</h4>
                                <p class="text-3xs text-gray-400">SKU: {{ $item->product->sku }} | Category: {{ $item->product->category->name ?? 'None' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-black text-bauhaus-black bg-bauhaus-gray px-3 py-1">{{ $item->total_sold }} Sold</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    {{-- Low Stock / Out of Stock alerts --}}
    <div class="border border-bauhaus-black p-unit-2 bg-bauhaus-white">
        <h3 class="text-sm font-black uppercase tracking-widest mb-unit-1 border-b border-bauhaus-black pb-2 text-bauhaus-black flex justify-between items-center">
            <span>Inventory Alert Console</span>
            <span class="text-3xs bg-bauhaus-red text-bauhaus-white px-2 py-0.5 font-bold">Priority Restock</span>
        </h3>
        @if($alertProducts->isEmpty())
            <div class="text-center py-unit-2">
                <span class="text-xs font-black uppercase text-green-600">✓ All stock counts within safe operating parameters.</span>
            </div>
        @else
            <div class="space-y-4 py-2 overflow-y-auto max-h-64 pr-2">
                @foreach($alertProducts as $prod)
                    <div class="flex items-center justify-between border-l-4 {{ $prod->stock == 0 ? 'border-bauhaus-red bg-bauhaus-red bg-opacity-5' : 'border-bauhaus-yellow bg-bauhaus-yellow bg-opacity-5' }} pl-unit-1 py-1">
                        <div>
                            <h4 class="font-bold text-xs uppercase text-bauhaus-black">{{ $prod->title }}</h4>
                            <p class="text-3xs text-gray-400">SKU: {{ $prod->sku }}</p>
                        </div>
                        <div class="text-right pr-unit-1">
                            @if($prod->stock == 0)
                                <span class="text-3xs font-black uppercase tracking-wider text-bauhaus-red border border-bauhaus-red px-2 py-0.5 bg-bauhaus-white">OUT OF STOCK</span>
                            @else
                                <span class="text-3xs font-black uppercase tracking-wider text-bauhaus-yellow border border-bauhaus-yellow px-2 py-0.5 bg-bauhaus-white">LOW STOCK: {{ $prod->stock }} LEFT</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Recent Orders list --}}
    <div class="border border-bauhaus-black p-unit-2 bg-bauhaus-white lg:col-span-2">
        <h3 class="text-sm font-black uppercase tracking-widest mb-unit-1 border-b border-bauhaus-black pb-2 text-bauhaus-black">
            Recent Placed Checkouts
        </h3>
        @if($recentOrders->isEmpty())
            <p class="text-xs italic text-gray-400 py-unit-2 text-center">No orders created yet in the database.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-bauhaus-black font-black uppercase text-gray-400 tracking-wider">
                            <th class="py-2">Order ID</th>
                            <th class="py-2">Date</th>
                            <th class="py-2">Customer Name</th>
                            <th class="py-2">Country</th>
                            <th class="py-2">Grand Total</th>
                            <th class="py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $ord)
                            <tr class="border-b border-bauhaus-gray last:border-b-0 hover:bg-bauhaus-gray hover:bg-opacity-20">
                                <td class="py-3 font-mono font-bold">#ORD-{{ str_pad($ord->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-3">{{ $ord->created_at->format('M d, Y H:i') }}</td>
                                <td class="py-3 font-bold">{{ $ord->name }} ({{ $ord->email }})</td>
                                <td class="py-3">{{ $ord->country }}</td>
                                <td class="py-3 font-black">${{ number_format($ord->total_price, 2) }}</td>
                                <td class="py-3">
                                    <span class="text-4xs font-black uppercase px-2 py-0.5 border border-bauhaus-black {{ $ord->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-bauhaus-yellow text-bauhaus-black animate-pulse' }}">
                                        {{ $ord->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
