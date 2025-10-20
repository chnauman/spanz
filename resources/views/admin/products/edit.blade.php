@extends('layouts.admin')

@section('title', 'Edit Product - SPANZ')

@section('content')
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
                <div class="border border-gray-300 p-3 sm:p-4 lg:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                        <h1 class="text-xl sm:text-2xl font-bold">Edit Product</h1>
                        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-white bg-transparent hover:bg-white hover:text-[#092C48] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">Back to list</a>
                    </div>

        @if(session('success'))
            <div class="mt-4 sm:mt-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mt-4 sm:mt-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.products.update', $product) }}" class="space-y-4 sm:space-y-6">
            @csrf
            @method('PUT')

            <div class="mt-6 sm:mt-8 lg:mt-10">
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Title <span class="text-red-500">*</span></label>
                <input name="title" value="{{ old('title', $product->title) }}" class="w-full border rounded px-3 py-2" required />
                @error('title')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category_id" class="w-full border rounded px-3 py-2">
                        <option value="">— None —</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (string)old('category_id', $product->category_id)===(string)$category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border rounded px-3 py-2">
                        <option value="draft" {{ old('status', $product->status)==='draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ old('status', $product->status)==='active' ? 'selected' : '' }}>Active</option>
                        <option value="archived" {{ old('status', $product->status)==='archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                    <input name="price" value="{{ old('price', $product->price) }}" class="w-full border rounded px-3 py-2" type="number" min="0" step="0.01" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                    <input name="currency" value="{{ old('currency', $product->currency) }}" class="w-full border rounded px-3 py-2" />
                </div>
                <div class="flex items-center mt-6">
                    <input id="featured" type="checkbox" name="featured" value="1" class="mr-2" {{ old('featured', $product->featured) ? 'checked' : '' }} />
                    <label for="featured" class="text-sm text-gray-700">Featured</label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="8" class="w-full border rounded px-3 py-2">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="flex gap-3">
                <button class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-[#0D6AED] text-white text-sm sm:text-base font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors" type="submit">Save Changes</button>
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border rounded">Cancel</a>
            </div>
        </form>
                </div>
            </div>
@endsection


