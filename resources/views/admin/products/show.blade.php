@extends('layouts.admin')

@section('content')
<div class="p-4 max-w-4xl">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">{{ $product->title }}</h1>
        <a href="{{ route('admin.products.edit', $product) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm text-gray-500">Slug</dt>
                <dd class="text-gray-900">{{ $product->slug }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Category</dt>
                <dd class="text-gray-900">{{ optional($product->category)->name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Status</dt>
                <dd class="text-gray-900">{{ $product->status }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Price</dt>
                <dd class="text-gray-900">{{ $product->price ? number_format($product->price, 2) . ' ' . $product->currency : '-' }}</dd>
            </div>
        </dl>

        <div class="mt-6">
            <h2 class="text-lg font-medium mb-2">Description</h2>
            <div class="prose max-w-none">{!! nl2br(e($product->description)) !!}</div>
        </div>
    </div>

    <div class="mt-4">
        <a class="px-4 py-2 border rounded" href="{{ route('admin.products.index') }}">Back to list</a>
    </div>
</div>
@endsection


