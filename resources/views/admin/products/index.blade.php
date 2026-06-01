@extends('layouts.admin')

@section('content')
<div class="mb-unit-2 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-bauhaus-black pb-unit-1 gap-2">
    <div>
        <h1 class="text-3xl font-black uppercase tracking-widest text-bauhaus-black">Products Catalog</h1>
        <p class="text-xs text-gray-500 uppercase tracking-widest">Inventory Management Console</p>
    </div>
    <a 
        href="{{ route('admin.products.create') }}" 
        class="bg-bauhaus-black text-bauhaus-white px-4 py-2.5 text-xs font-black uppercase tracking-widest hover:bg-bauhaus-blue transition-colors border border-bauhaus-black"
    >
        + Add New Design Product
    </a>
</div>

{{-- Product List Table --}}
<div class="border border-bauhaus-black bg-bauhaus-white p-unit-2">
    @if($products->isEmpty())
        <div class="text-center py-unit-2">
            <p class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-unit-1">No products found in the catalog.</p>
            <a href="{{ route('admin.products.create') }}" class="inline-block px-4 py-2 bg-bauhaus-black text-bauhaus-white uppercase text-xs tracking-widest">
                Create First Product
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-bauhaus-black font-black uppercase text-gray-400 tracking-wider">
                        <th class="py-2">Image</th>
                        <th class="py-2">SKU</th>
                        <th class="py-2">Product Name</th>
                        <th class="py-2">Category</th>
                        <th class="py-2">Unit Price</th>
                        <th class="py-2">Stock Level</th>
                        <th class="py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $prod)
                        <tr class="border-b border-bauhaus-gray last:border-b-0 hover:bg-bauhaus-gray hover:bg-opacity-25">
                            <td class="py-3">
                                <img 
                                    src="{{ $prod->image_url }}" 
                                    alt="{{ $prod->title }}" 
                                    class="w-12 h-12 object-cover border border-bauhaus-black bg-bauhaus-gray"
                                >
                            </td>
                            <td class="py-3 font-mono font-bold">{{ $prod->sku }}</td>
                            <td class="py-3 font-black text-sm uppercase text-bauhaus-black">
                                <a href="{{ route('product.show', $prod->id) }}" target="_blank" class="hover:text-bauhaus-blue underline">
                                    {{ $prod->title }}
                                </a>
                            </td>
                            <td class="py-3 font-bold text-bauhaus-blue">{{ $prod->category->name ?? 'Uncategorized' }}</td>
                            <td class="py-3 font-black text-sm">${{ number_format($prod->price, 2) }}</td>
                            <td class="py-3">
                                @if($prod->isOutOfStock())
                                    <span class="text-3xs font-black uppercase bg-bauhaus-red text-bauhaus-white px-2 py-0.5 border border-bauhaus-black">
                                        Sold Out (0)
                                    </span>
                                @elseif($prod->isLowStock())
                                    <span class="text-3xs font-black uppercase bg-bauhaus-yellow text-bauhaus-black px-2 py-0.5 border border-bauhaus-black animate-pulse">
                                        Low: Only {{ $prod->stock }} Left
                                    </span>
                                @else
                                    <span class="text-3xs font-black uppercase bg-green-100 text-green-700 px-2 py-0.5 border border-green-300">
                                        {{ $prod->stock }} items
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 text-right">
                                <div class="flex gap-2 justify-end">
                                    <a 
                                        href="{{ route('admin.products.edit', $prod->id) }}" 
                                        class="px-3 py-1.5 border border-bauhaus-black text-bauhaus-black font-black uppercase text-4xs tracking-widest hover:bg-bauhaus-gray transition-colors"
                                    >
                                        Edit
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.products.destroy', $prod->id) }}" onsubmit="return confirm('Are you absolutely certain you want to purge this product design?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="px-3 py-1.5 bg-bauhaus-red text-bauhaus-white border border-bauhaus-black font-black uppercase text-4xs tracking-widest hover:bg-bauhaus-black transition-colors"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
