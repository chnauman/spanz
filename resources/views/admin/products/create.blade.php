@extends('layouts.admin')

@section('title', 'Create Product - SPANZ')

@section('content')
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
                <div class="border border-gray-300 p-3 sm:p-4 lg:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                        <h1 class="text-xl sm:text-2xl font-bold">Create a New Product</h1>
                    </div>
        <form action="{{ route('admin.products.store') }}" method="post" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
            @csrf
            
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                    <strong>Error:</strong> {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif
            
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                        <div class="mt-6 sm:mt-8 lg:mt-10">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Product Title <span class="text-red-500">*</span></label>
                <input id="title" name="title" value="{{ old('title') }}" class="w-full border rounded px-3 py-2" required />
                @error('title')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category_id" class="w-full border rounded px-3 py-2">
                        <option value="">— None —</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (string)old('category_id')===(string)$category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border rounded px-3 py-2">
                        <option value="draft" {{ old('status')==='draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ old('status','active')==='active' ? 'selected' : '' }}>Active</option>
                        <option value="archived" {{ old('status')==='archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                    <input name="price" value="{{ old('price') }}" class="w-full border rounded px-3 py-2" type="number" min="0" step="0.01" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                    <input name="currency" value="{{ old('currency', 'AUD') }}" class="w-full border rounded px-3 py-2" readonly />
                </div>
                <div class="flex items-center mt-6">
                    <input id="featured" type="checkbox" name="featured" value="1" class="mr-2" {{ old('featured') ? 'checked' : '' }} />
                    <label for="featured" class="text-sm text-gray-700">Featured</label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="8" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Primary Image (Main)</label>
                <input type="file" id="image" name="image" accept="image/*" class="w-full border rounded px-3 py-2" onchange="previewImage(this)" />
                @error('image')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                <p class="text-xs text-gray-500 mt-1">Allowed: JPG, PNG, GIF, WEBP. Max size: 5MB</p>
                <div id="image-preview" class="mt-4 hidden">
                    <p class="text-sm font-medium text-gray-700 mb-2">Image Preview:</p>
                    <div class="inline-block border-2 border-blue-300 rounded-lg p-2 bg-gray-50">
                        <img id="preview-img" src="" alt="Preview" class="max-w-full max-h-64 w-auto h-auto border rounded object-cover" />
                    </div>
                    <div class="mt-2">
                        <button type="button" onclick="removePreview()" class="text-sm text-red-600 hover:text-red-800 underline">Remove Image</button>
                    </div>
                </div>
            </div>

            <div>
                <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-2">Gallery Images (Optional)</label>
                <input type="file" id="gallery_images" name="gallery_images[]" accept=".jpg,.jpeg,.png,.gif,.webp,image/*" multiple="multiple"
                       class="w-full border rounded px-3 py-2" onchange="previewGallery(this)" />
                @error('gallery_images')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                @error('gallery_images.*')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                <p class="text-xs text-gray-500 mt-1">You can select multiple images (hold Ctrl/Command to pick many). Max: 12 images, 5MB each.</p>
                <div id="gallery-preview" class="mt-4 hidden">
                    <p class="text-sm font-medium text-gray-700 mb-2">Gallery Preview:</p>
                    <div id="gallery-preview-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3"></div>
                    <div class="mt-2">
                        <button type="button" onclick="removeGalleryPreview()" class="text-sm text-red-600 hover:text-red-800 underline">Remove Gallery Selection</button>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-[#0D6AED] text-white text-sm sm:text-base font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors" type="submit">Save Product</button>
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
        };
        reader.onerror = function() {
            alert('Error reading the file. Please try again.');
            input.value = '';
            preview.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}

function removePreview() {
    const input = document.getElementById('image');
    const preview = document.getElementById('image-preview');
    input.value = '';
    preview.classList.add('hidden');
}

function previewGallery(input) {
    const preview = document.getElementById('gallery-preview');
    const grid = document.getElementById('gallery-preview-grid');
    grid.innerHTML = '';

    if (!input.files || input.files.length === 0) {
        preview.classList.add('hidden');
        return;
    }

    const files = Array.from(input.files).slice(0, 12);
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    for (const file of files) {
        if (!validTypes.includes(file.type)) {
            alert('Gallery: please select valid image files (JPG, PNG, GIF, WEBP)');
            input.value = '';
            preview.classList.add('hidden');
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert('Gallery: each image must be less than 5MB');
            input.value = '';
            preview.classList.add('hidden');
            return;
        }
    }

    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const wrap = document.createElement('div');
            wrap.className = 'border rounded bg-gray-50 p-2';
            wrap.innerHTML = `<img src="${e.target.result}" alt="Gallery preview" class="w-full h-24 object-cover rounded" />`;
            grid.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });

    preview.classList.remove('hidden');
}

function removeGalleryPreview() {
    const input = document.getElementById('gallery_images');
    const preview = document.getElementById('gallery-preview');
    const grid = document.getElementById('gallery-preview-grid');
    input.value = '';
    grid.innerHTML = '';
    preview.classList.add('hidden');
}
</script>
@endpush


