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

        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
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

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                
                @if($product->image)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                        <div class="relative inline-block">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Current product image" class="max-w-xs max-h-48 border rounded object-cover" id="current-image" />
                            <button type="button" onclick="removeCurrentImage()" class="absolute top-0 right-0 bg-red-600 text-white rounded-full p-1 hover:bg-red-700" title="Remove image">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <input type="hidden" name="remove_image" id="remove-image-flag" value="0" />
                    </div>
                @else
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">No image uploaded</p>
                        <img src="{{ asset('spanz-img/default-product.png') }}" alt="Default product image" class="max-w-xs max-h-48 border rounded object-cover opacity-50" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23ddd%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%23999%22 font-family=%22sans-serif%22 font-size=%2214%22 dy=%2210.5%22 font-weight=%22bold%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22%3ENo Image%3C/text%3E%3C/svg%3E';" />
                    </div>
                @endif

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">{{ $product->image ? 'Replace Image' : 'Upload Image' }}</label>
                    <input type="file" id="image" name="image" accept="image/*" class="w-full border rounded px-3 py-2" onchange="previewImage(this)" />
                    @error('image')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    <p class="text-xs text-gray-500 mt-1">Allowed: JPG, PNG, GIF, WEBP. Max size: 5MB</p>
                </div>

                <div id="image-preview" class="mt-4 hidden">
                    <p class="text-sm font-medium text-gray-700 mb-2">New Image Preview:</p>
                    <div class="inline-block border-2 border-blue-300 rounded-lg p-2 bg-gray-50">
                        <img id="preview-img" src="" alt="Preview" class="max-w-full max-h-64 w-auto h-auto border rounded object-cover" />
                    </div>
                    <div class="mt-2">
                        <button type="button" onclick="removePreview()" class="text-sm text-red-600 hover:text-red-800 underline">Cancel Upload</button>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-[#0D6AED] text-white text-sm sm:text-base font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors" type="submit">Save Changes</button>
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border rounded">Cancel</a>
            </div>
        </form>
                </div>
            </div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const currentImage = document.getElementById('current-image');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (JPG, PNG, GIF, or WEBP)');
            input.value = '';
            preview.classList.add('hidden');
            return;
        }
        
        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Image size must be less than 5MB');
            input.value = '';
            preview.classList.add('hidden');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
            // Scroll to preview smoothly
            preview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            if (currentImage) {
                currentImage.style.opacity = '0.5';
            }
        };
        reader.onerror = function() {
            alert('Error reading the file. Please try again.');
            input.value = '';
            preview.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
        if (currentImage) {
            currentImage.style.opacity = '1';
        }
    }
}

function removePreview() {
    const input = document.getElementById('image');
    const preview = document.getElementById('image-preview');
    const currentImage = document.getElementById('current-image');
    input.value = '';
    preview.classList.add('hidden');
    if (currentImage) {
        currentImage.style.opacity = '1';
    }
}

function removeCurrentImage() {
    if (confirm('Are you sure you want to remove the current image?')) {
        document.getElementById('remove-image-flag').value = '1';
        const currentImageDiv = document.getElementById('current-image').closest('.relative');
        if (currentImageDiv) {
            currentImageDiv.style.display = 'none';
        }
    }
}
</script>
@endpush


