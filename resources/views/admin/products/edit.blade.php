@extends('layouts.admin')

@section('content')
<div class="mb-unit-2 border-b border-bauhaus-black pb-unit-1 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-black uppercase tracking-widest text-bauhaus-black">Edit Product</h1>
        <p class="text-xs text-gray-500 uppercase tracking-widest">Update Design Specs: {{ $product->title }}</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="text-xs font-black uppercase tracking-widest border border-bauhaus-black px-3 py-1 hover:bg-bauhaus-gray transition-colors">
        ← Back to Catalog
    </a>
</div>

{{-- CRUD Edit Form --}}
<div class="border border-bauhaus-black bg-bauhaus-white p-unit-2 max-w-3xl">
    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Title --}}
            <div class="md:col-span-2">
                <label for="title" class="block text-3xs font-black uppercase tracking-widest mb-1 text-bauhaus-black">Product Name *</label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title', $product->title) }}" 
                    required 
                    class="w-full text-xs border border-bauhaus-black px-3 py-2 bg-bauhaus-white focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
                >
                @error('title') <p class="text-3xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- SKU --}}
            <div>
                <label for="sku" class="block text-3xs font-black uppercase tracking-widest mb-1 text-bauhaus-black">SKU Identifier *</label>
                <input 
                    type="text" 
                    name="sku" 
                    id="sku" 
                    value="{{ old('sku', $product->sku) }}" 
                    required 
                    class="w-full text-xs border border-bauhaus-black px-3 py-2 bg-bauhaus-white font-mono focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
                >
                @error('sku') <p class="text-3xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Category --}}
            <div>
                <label for="category_id" class="block text-3xs font-black uppercase tracking-widest mb-1 text-bauhaus-black">Design Category</label>
                <select 
                    name="category_id" 
                    id="category_id" 
                    class="w-full text-xs border border-bauhaus-black px-3 py-2 bg-bauhaus-white focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
                >
                    <option value="">Uncategorized</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-3xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Price --}}
            <div>
                <label for="price" class="block text-3xs font-black uppercase tracking-widest mb-1 text-bauhaus-black">Unit Price ($) *</label>
                <input 
                    type="number" 
                    step="0.01" 
                    name="price" 
                    id="price" 
                    value="{{ old('price', $product->price) }}" 
                    required 
                    class="w-full text-xs border border-bauhaus-black px-3 py-2 bg-bauhaus-white focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
                >
                @error('price') <p class="text-3xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Stock --}}
            <div>
                <label for="stock" class="block text-3xs font-black uppercase tracking-widest mb-1 text-bauhaus-black">Current Stock Level *</label>
                <input 
                    type="number" 
                    name="stock" 
                    id="stock" 
                    value="{{ old('stock', $product->stock) }}" 
                    required 
                    class="w-full text-xs border border-bauhaus-black px-3 py-2 bg-bauhaus-white focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
                >
                @error('stock') <p class="text-3xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Image URL --}}
            <div class="md:col-span-2">
                <label for="image_url" class="block text-3xs font-black uppercase tracking-widest mb-1 text-bauhaus-black">Image Asset URL</label>
                <input 
                    type="url" 
                    name="image_url" 
                    id="image_url" 
                    value="{{ old('image_url', $product->image_url) }}" 
                    class="w-full text-xs border border-bauhaus-black px-3 py-2 bg-bauhaus-white focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
                >
                @error('image_url') <p class="text-3xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Material --}}
            <div>
                <label for="material" class="block text-3xs font-black uppercase tracking-widest mb-1 text-bauhaus-black">Material Spec</label>
                <input 
                    type="text" 
                    name="material" 
                    id="material" 
                    value="{{ old('material', $product->material) }}" 
                    class="w-full text-xs border border-bauhaus-black px-3 py-2 bg-bauhaus-white focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
                >
                @error('material') <p class="text-3xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Weight --}}
            <div>
                <label for="weight" class="block text-3xs font-black uppercase tracking-widest mb-1 text-bauhaus-black">Weight Spec</label>
                <input 
                    type="text" 
                    name="weight" 
                    id="weight" 
                    value="{{ old('weight', $product->weight) }}" 
                    class="w-full text-xs border border-bauhaus-black px-3 py-2 bg-bauhaus-white focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
                >
                @error('weight') <p class="text-3xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Dimensions --}}
            <div>
                <label for="dimensions" class="block text-3xs font-black uppercase tracking-widest mb-1 text-bauhaus-black">Dimensions Spec</label>
                <input 
                    type="text" 
                    name="dimensions" 
                    id="dimensions" 
                    value="{{ old('dimensions', $product->dimensions) }}" 
                    class="w-full text-xs border border-bauhaus-black px-3 py-2 bg-bauhaus-white focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
                >
                @error('dimensions') <p class="text-3xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-3xs font-black uppercase tracking-widest mb-1 text-bauhaus-black">Product Description *</label>
            <textarea 
                name="description" 
                id="description" 
                rows="5" 
                required 
                class="w-full text-xs border border-bauhaus-black px-3 py-2 bg-bauhaus-white focus:outline-none focus:ring-1 focus:ring-bauhaus-blue"
            >{{ old('description', $product->description) }}</textarea>
            @error('description') <p class="text-3xs font-bold text-bauhaus-red mt-1">{{ $message }}</p> @enderror
        </div>

        <button 
            type="submit" 
            class="bg-bauhaus-black text-bauhaus-white px-6 py-3 text-xs font-black uppercase tracking-widest hover:bg-bauhaus-blue transition-colors border border-bauhaus-black"
        >
            Update Design Specs
        </button>
    </form>
</div>
@endsection
