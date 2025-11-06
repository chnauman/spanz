@extends('layouts.admin')
@section('title', 'Edit Category - SPANZ')
@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">Edit Category</h1>
            </div>

            <!-- Back Button -->
            <div class="mt-4 sm:mt-6">
                <a href="{{ route('admin.categories.index', ['page' => $page ?? request('page', 1)]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    ← Back to Categories
                </a>
            </div>

            <!-- Form Container -->
            <div class="mt-6">
            
            <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="page" value="{{ $page ?? request('page', 1) }}">
                
                <!-- Category Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $category->name) }}"
                           placeholder="Enter category name"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              placeholder="Enter category description (optional)"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Parent Category -->
                <div class="mb-6">
                    <label for="parent_category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Parent Category
                    </label>
                    <select id="parent_category_id" 
                            name="parent_category_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('parent_category_id') border-red-500 @enderror">
                        <option value="">Select parent category (optional)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" 
                                    {{ (old('parent_category_id', $category->parent_category_id) == $parent->id) ? 'selected' : '' }}
                                    {{ $parent->id == $category->id ? 'disabled' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Leave empty to create a main category. Cannot select self as parent.</p>
                    @error('parent_category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Status</label>
                    <div class="flex space-x-6">
                        <div class="flex items-center">
                            <input type="radio" 
                                   id="active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', $category->is_active) == '1' ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <label for="active" class="ml-2 text-sm text-gray-700">Active</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" 
                                   id="inactive" 
                                   name="is_active" 
                                   value="0" 
                                   {{ old('is_active', $category->is_active) == '0' ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <label for="inactive" class="ml-2 text-sm text-gray-700">Inactive</label>
                        </div>
                    </div>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                    <button type="button" 
                            onclick="window.location.href='{{ route('admin.categories.index', ['page' => $page ?? request('page', 1)]) }}'"
                            class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Cancel
                    </button>
                    <button type="submit" 
                            class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Update Category
                    </button>
                </div>
            </form>
        </div>

      
    </div>
</div>
@endsection
