@extends('layouts.admin')

@section('title', 'Products - SPANZ')

@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">Products</h1>
                <a href="{{ route('admin.products.create') }}" 
                   class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-[#0D6AED] text-white text-sm sm:text-base font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Create Product
                </a>
            </div>

            @if(session('success'))
                <div class="mt-4 sm:mt-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mt-4 sm:mt-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $product)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">{{ $product->title }}</h3>
                                <div class="flex items-center gap-2">
                                    @if($product->featured)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Featured</span>
                                    @endif
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </div>
                            </div>

                            <p class="text-sm text-gray-500 mb-3">
                                Category: {{ optional($product->category)->name ?? '-' }} • {{ $product->created_at->diffForHumans() }}
                            </p>

                            <p class="text-gray-700 text-sm mb-4 line-clamp-3">{{ Str::limit($product->description, 150, '...') }}</p>

                            <div class="space-y-2 mb-4">
                                @if($product->price)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Price:</span>
                                    <span class="font-medium">{{ $product->currency }} {{ number_format($product->price, 2) }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                <a href="{{ route('admin.products.show', $product) }}" 
                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                    View
                                </a>
                                <div class="space-x-2">
                                    <a class="text-blue-600" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                                    <button type="button" class="text-red-600" data-product-id="{{ $product->id }}" data-product-title="{{ e($product->title) }}" data-delete-url="{{ route('admin.products.destroy', $product) }}" onclick="confirmProductDelete(this)">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No products yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Create your first product to showcase on the site.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.products.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Create Product
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>

                <div class="mt-6 flex justify-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Hidden form for deletion -->
<form id="deleteProductForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmProductDelete(buttonEl) {
    const productId = buttonEl.dataset.productId;
    const productTitle = buttonEl.dataset.productTitle;
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete product "${productTitle}". This action cannot be undone!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait while we delete the product.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const form = document.getElementById('deleteProductForm');
            const deleteUrl = buttonEl.dataset.deleteUrl;
            form.action = deleteUrl;
            form.submit();
        }
    });
}

@if(session('success'))
Swal.fire({
    title: 'Success!',
    text: '{{ session('success') }}',
    icon: 'success',
    confirmButtonText: 'OK'
});
@endif

@if(session('error'))
Swal.fire({
    title: 'Error!',
    text: '{{ session('error') }}',
    icon: 'error',
    confirmButtonText: 'OK'
});
@endif
</script>
@endpush


