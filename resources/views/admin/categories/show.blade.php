@extends('layouts.dashlayout')
@section('title', 'View Category - Spanz')
@section('content')
<div class="block lg:grid lg:grid-cols-12 w-full h-screen">
    <div class="hidden lg:block lg:col-span-2 bg-gradient-to-l from-[#092C48] to-[#1b3963]">
        @include('admin.partials.sidebar')
    </div>
    
    <div class="lg:col-span-10 bg-gray-50 p-6 h-screen overflow-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Category Details</h1>
                <p class="text-gray-600 mt-3 text-lg">View category information</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('admin.categories.edit', $category) }}" 
                   class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center gap-2 text-lg font-medium">
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.categories.index') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 flex items-center gap-2 text-lg font-medium">
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Back to Categories
                </a>
            </div>
        </div>

        <!-- Category Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6">Basic Information</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-lg font-medium text-gray-500 mb-2">Category Name</label>
                            <p class="text-xl text-gray-900 font-medium">{{ $category->name }}</p>
                        </div>
                        <div>
                            <label class="block text-lg font-medium text-gray-500 mb-2">Description</label>
                            <p class="text-lg text-gray-900">{{ $category->description ?: 'No description provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-lg font-medium text-gray-500 mb-2">Parent Category</label>
                            <p class="text-lg text-gray-900">
                                {{ $category->parent ? $category->parent->name : 'Root Category' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6">Additional Information</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-lg font-medium text-gray-500 mb-2">Created At</label>
                            <p class="text-lg text-gray-900">{{ $category->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <div>
                            <label class="block text-lg font-medium text-gray-500 mb-2">Last Updated</label>
                            <p class="text-lg text-gray-900">{{ $category->updated_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <div>
                            <label class="block text-lg font-medium text-gray-500 mb-2">Category ID</label>
                            <p class="text-lg text-gray-900 font-mono">#{{ $category->id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subcategories (if any) -->
            @if($category->children->count() > 0)
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6">Subcategories</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($category->children as $child)
                            <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors duration-200">
                                <h4 class="text-lg font-medium text-gray-900 mb-2">{{ $child->name }}</h4>
                                <p class="text-gray-600 mb-4">{{ Str::limit($child->description, 80) }}</p>
                                <div>
                                    <a href="{{ route('admin.categories.show', $child) }}" 
                                       class="text-[#0D6AED] hover:text-[#0B5AC7] font-medium transition-colors duration-200">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-lg shadow-sm border border-red-200 p-8">
            <h3 class="text-2xl font-semibold text-red-900 mb-4">Danger Zone</h3>
            <p class="text-lg text-gray-600 mb-6">
                Once you delete a category, there is no going back. Please be certain.
            </p>
            <form action="{{ route('admin.categories.destroy', $category) }}" 
                  method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 text-lg font-medium">
                    Delete Category
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
