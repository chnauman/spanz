@extends('layouts.dashlayout')
@section('title', 'Create Category - Spanz')
@section('content')
<div class="block lg:grid lg:grid-cols-12 w-full h-screen">
    <div class="hidden lg:block lg:col-span-2 bg-gradient-to-l from-[#092C48] to-[#1b3963]">
        @include('admin.partials.sidebar')
    </div>
    
    <div class="lg:col-span-10 bg-gray-50 p-6 h-screen overflow-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Create New Category</h1>
            <p class="text-gray-600 mt-3 text-lg">Add a new product category to your system</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Category Name -->
                        <div>
                            <label for="name" class="block text-lg font-medium text-gray-700 mb-3">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0D6AED] focus:border-transparent @error('name') border-red-500 @enderror"
                                   placeholder="Enter category name"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Category -->
                        <div>
                            <label for="parent_category_id" class="block text-lg font-medium text-gray-700 mb-3">
                                Parent Category
                            </label>
                            <select id="parent_category_id" 
                                    name="parent_category_id"
                                    class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0D6AED] focus:border-transparent @error('parent_category_id') border-red-500 @enderror">
                                <option value="">Select a parent category (optional)</option>
                                @foreach($parentCategories as $parentCategory)
                                    <option value="{{ $parentCategory->id }}" {{ old('parent_category_id') == $parentCategory->id ? 'selected' : '' }}>
                                        {{ $parentCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_category_id')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-lg font-medium text-gray-700 mb-3">
                                Description
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="8"
                                      class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0D6AED] focus:border-transparent @error('description') border-red-500 @enderror"
                                      placeholder="Enter category description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-6 mt-12 pt-8 border-t border-gray-200">
                    <a href="{{ route('admin.categories.index') }}" 
                       class="px-8 py-4 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 text-lg font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-4 bg-[#0D6AED] text-white rounded-lg hover:bg-[#0B5AC7] transition-colors duration-200 text-lg font-medium">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
