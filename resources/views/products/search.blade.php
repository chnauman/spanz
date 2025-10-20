@extends('layouts.app')
@section('title', 'Search Products - Spanz')
@section('content')

<div class="block lg:grid lg:grid-cols-12 w-full bg-slate-50">
    <div class="hidden lg:block lg:col-span-2 p-4 lg:pl-10">
        <div class="flex gap-2 items-center mb-4">
            <img src="{{ asset('spanz-img/filter.svg') }}" alt="Filter" class="w-5 h-5">
            <span class="text-sm font-medium">Filter</span>
        </div>
        <hr class="my-3 border-t border-gray-400 w-[70%]" />
        <div class="mt-2">
            <div class="filter-section">
                <div class="filter-header cursor-pointer flex items-center justify-between">
                    <h1 class="text-sm sm:text-md font-semibold text-[#092C48]">Related Categories</h1>
                    <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div class="filter-content">
                    <ul class="space-y-1 mt-2" id="desktop-categories-list">
                        @foreach($categories as $category)
                        <li>
                            <a href="{{ route('products.search', array_merge(request()->query(), ['category' => $category->id])) }}"
                               class="text-sm sm:text-md hover:underline block py-1 {{ request('category') == $category->id ? 'font-semibold text-blue-600' : '' }}">
                                {{ $category->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full lg:col-span-9 p-4 lg:p-6">
        <h1 class="text-2xl font-semibold text-[#092C48] mb-4">Search Results</h1>
        <div class="mb-4">
            <form id="products-search-form" method="GET" action="{{ route('products.search') }}" class="flex flex-col sm:flex-row items-center gap-2 sm:gap-0 w-full max-w-2xl" onsubmit="return handleProductsSearch(event)">
                <div class="w-full sm:w-auto">
                    <select id="products-search-type" class="w-full sm:w-40 px-3 py-3 sm:py-2 bg-gray-100 border border-gray-300 text-gray-700 text-sm">
                        <option value="products" selected>Products</option>
                        <option value="tenders">Tenders</option>
                    </select>
                </div>
                <input id="products-search-input" type="search" name="q" value="{{ request('q') }}" placeholder="Search..."
                    class="w-full px-3 py-3 sm:py-2 border border-gray-300 text-gray-700 focus:outline-none text-sm" />
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 sm:py-2 bg-[#0D6AED] text-white text-sm font-medium">Search</button>
                </div>
            </form>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($products as $product)
                <a href="{{ route('products.show', $product) }}" class="block bg-white border border-gray-200 rounded-sm p-4 hover:shadow">
                    <div class="flex items-center justify-between">
                        <div class="text-[#092C48] font-semibold text-lg">{{ $product->title }}</div>
                        @if($product->featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Featured</span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-600">{{ optional($product->category)->name ?? 'Uncategorized' }}</div>
                    <div class="mt-2 text-gray-800 line-clamp-3">{{ Str::limit(strip_tags($product->description), 160, '...') }}</div>
                </a>
            @empty
                <div>No products found.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $products->links() }}</div>
    </div>
</div>
<script>
    function handleProductsSearch(e) {
        e.preventDefault();
        const type = document.getElementById('products-search-type')?.value || 'products';
        const input = document.getElementById('products-search-input');
        const query = input ? input.value : '';
        if (type === 'tenders') {
            const url = new URL("{{ route('tenders.search') }}", window.location.origin);
            if (query.trim()) url.searchParams.set('search', query);
            window.location.href = url.toString();
            return false;
        }
        // default: submit product form
        const url = new URL("{{ route('products.index') }}", window.location.origin);
        if (query.trim()) url.searchParams.set('q', query);
        window.location.href = url.toString();
        return false;
    }
    // Sidebar filter collapse/expand
    document.querySelectorAll('.filter-header').forEach((header) => {
        header.addEventListener('click', () => {
            const content = header.nextElementSibling;
            const arrow = header.querySelector('svg');
            if (!content) return;
            const isHidden = content.style.display === 'none';
            content.style.display = isHidden ? 'block' : 'none';
            if (arrow) {
                arrow.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(-90deg)';
            }
        });
    });
    // Initialize: make content visible and arrow default rotation
    document.querySelectorAll('.filter-content').forEach((c) => { c.style.display = 'block'; });
</script>
@endsection


