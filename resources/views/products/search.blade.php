<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Search Products - Spanz</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
    <style>
        .filter-content {
            transition: all 0.3s ease;
        }
        .filter-header svg {
            transition: transform 0.3s ease;
        }
        .filter-section {
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="bg-[#092C48] py-5">
        <!-- Navbar -->
        <nav>
            <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-[#0D6AED]">Spanz</a>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex space-x-6">
                        <!-- For Buyers Dropdown -->
                        <div class="relative group">
                            <button class="text-white hover:text-blue-400 flex items-center">
                                For Buyers ▾
                            </button>
                            <div class="absolute left-0 mt-2 w-auto bg-white rounded-md shadow-lg opacity-0 invisible pointer-events-none group-hover:opacity-100 group-hover:visible group-hover:pointer-events-auto transition-all duration-200 z-50" style="min-width: 16rem;">
                                <div class="py-1 whitespace-nowrap">
                                    @auth
                                        <a href="{{ route('tenders.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Post a Tender</a>
                                        <a href="{{ route('tenders.my-tenders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">My Tenders</a>
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.create')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Post a Tender</a>
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.my-tenders')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">My Tenders</a>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <!-- For Suppliers Dropdown -->
                        <div class="relative group">
                            <button class="text-white hover:text-blue-400 flex items-center">
                                For Suppliers ▾
                            </button>
                            <div class="absolute left-0 mt-2 w-auto bg-white rounded-md shadow-lg opacity-0 invisible pointer-events-none group-hover:opacity-100 group-hover:visible group-hover:pointer-events-auto transition-all duration-200 z-50" style="min-width: 16rem;">
                                <div class="py-1 whitespace-nowrap">
                                    @auth
                                        @if(auth()->user()->isSupplier() || auth()->user()->isSubSupplier())
                                            <a href="{{ route('tenders.saved') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Saved Tenders</a>
                                            <a href="{{ route('user.interests') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">My Interests</a>
                                            <a href="{{ route('tenders.viewed') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Viewed Tenders</a>
                                            <a href="{{ route('invite.sub-suppliers') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Invite Sub Supplier</a>
                                            <a href="#" onclick="openSubscriptionModal(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Subscription Plans</a>
                                        @else
                                            <a href="#" onclick="openSubscriptionModal(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Become a Supplier</a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Become a Supplier</a>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <a href="#" class="text-white hover:text-blue-400">About</a>
                    </div>

                    <!-- Right Actions -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('tenders.search') }}" class="text-white hover:text-blue-400">Tenders</a>
                        <a href="{{ route('products.search') }}" class="text-white hover:text-blue-400">Products</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="border border-white text-white px-3 py-1 rounded hover:bg-white hover:text-black">
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="border border-white text-white px-3 py-1 rounded hover:bg-white hover:text-black">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800">
                                Register
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile Burger -->
                    <div class="md:hidden">
                        <button id="menu-btn" class="text-white focus:outline-none">
                            <!-- Icon -->
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden bg-[#092c47] text-white px-4 py-4 space-y-3">
                <a href="#" class="block hover:text-blue-300">For Buyers ▾</a>
                <a href="#" class="block hover:text-blue-300">For Suppliers ▾</a>
                <a href="#" class="block hover:text-blue-300">About</a>
                <a href="{{ route('tenders.search') }}" class="block hover:text-blue-300">Tenders</a>
                <a href="{{ route('products.search') }}" class="block hover:text-blue-300">Products</a>
                <a href="{{ route('company.register') }}" class="block hover:text-blue-300">Claim Your Company</a>
                <a href="#" class="block hover:text-blue-300">Start Advertising</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full border border-white text-white px-3 py-2 rounded hover:bg-white hover:text-black text-center block">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full bg-blue-700 text-white px-3 py-2 rounded hover:bg-blue-800 text-center block">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="w-full border border-white text-white px-3 py-2 rounded hover:bg-white hover:text-black text-center block">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="w-full bg-blue-700 text-white px-3 py-2 rounded hover:bg-blue-800 text-center block">
                        Register
                    </a>
                    @endauth
            </div>
        </nav>
        <!-- Hero main content (centered) -->
        <div class="flex flex-col items-center justify-center">
            <!-- Search row: centered -->
            <div class="w-full max-w-4xl mx-auto mt-5 px-4 sm:px-8">
                <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-0">
                    <!-- search type selector -->
                    <div class="w-full sm:w-auto">
                        <select id="products-search-type" class="w-full sm:w-40 px-3 py-3 sm:py-2 bg-gray-100 border border-gray-300 text-gray-700 text-sm">
                            <option value="products" selected>Products</option>
                            <option value="tenders">Tenders</option>
                        </select>
                    </div>

                    <!-- input -->
                    <form id="products-search-form" method="GET" action="{{ route('products.search') }}" class="flex flex-col sm:flex-row items-center gap-2 sm:gap-0 w-full max-w-2xl">
                        <input id="products-search-input" type="search" name="q" value="{{ request('q') }}" placeholder="By Category, Company or Brand..."
                            class="w-full px-3 py-3 sm:py-2 border border-gray-300 text-gray-700 focus:outline-none text-sm" />

                        <!-- Hidden inputs to preserve current filters -->
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif

                        <!-- search button -->
                        <div class="w-full sm:w-auto">
                            <button type="submit" class="w-full sm:w-auto px-6 py-3 sm:py-2 bg-[#0D6AED] text-white text-sm font-medium">Search</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div
        class="flex flex-col md:flex-row bg-slate-50 justify-between items-start md:items-center p-4 sm:p-5 gap-4 md:gap-0">
        <div class="flex flex-wrap items-center text-sm flex-1">
            <span><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-300">Home</a></span>
            <span class="mx-1"><a href="#" class="text-blue-600 hover:text-blue-300">/</a></span>
            <span><a href="#" class="text-blue-600 hover:text-blue-300">Products</a></span>
        </div>
        <div class="flex space-x-3 items-center flex-shrink-0">
            <img src="{{ asset('spanz-img/printer.svg') }}" alt="Print" class="w-5 h-5 cursor-pointer hover:opacity-70" onclick="window.print()">
            <img src="{{ asset('spanz-img/share.svg') }}" alt="Share" class="w-5 h-5 cursor-pointer hover:opacity-70" onclick="copyCurrentUrl()">
        </div>
    </div>

    <!-- Mobile Filter Button - Only visible on small screens -->
    <div class="lg:hidden bg-slate-50 p-4">
        <button id="mobile-filter-btn"
            class="flex items-center gap-2 bg-white border border-gray-300 px-4 py-2 rounded-md hover:bg-gray-50 w-full justify-center">
            <img src="{{ asset('spanz-img/filter.svg') }}" alt="Filter" class="w-5 h-5">
            <span class="text-sm font-medium">Filter & Categories</span>
        </button>
    </div>

    <!-- Mobile Filter Modal - Hidden by default -->
    <div id="mobile-filter-modal" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="fixed bottom-0 left-0 right-0 bg-white rounded-t-lg max-h-[80vh] overflow-y-auto">
            <div class="p-4">
                <!-- Modal Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-[#092C48]">Filters & Categories</h2>
                    <button id="close-filter-modal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Filter Controls -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <button id="mobile-collapse-all"
                        class="border border-black px-3 py-1.5 bg-white hover:bg-gray-100 rounded-sm text-sm">Collapse
                        All</button>
                    <button id="mobile-clear-all" class="border border-black px-3 py-1.5 bg-white hover:bg-gray-100 rounded-sm text-sm">Clear
                        All</button>
                </div>

                <hr class="my-4 border-t border-gray-300" />

                <!-- Categories -->
                <div class="filter-section">
                    <div class="filter-header cursor-pointer flex items-center justify-between">
                        <h3 class="text-md font-semibold text-[#092C48] mb-3">Related Categories</h3>
                        <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="filter-content">
                        <ul class="space-y-3" id="mobile-categories-list">
                            @foreach($categories as $category)
                            <li>
                                <a href="{{ route('products.search', array_merge(request()->query(), ['category' => $category->id])) }}"
                                   class="text-sm hover:underline hover:text-blue-600 block py-1 {{ request('category') == $category->id ? 'font-semibold text-blue-600' : '' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @if($hasMoreCategories)
                        <div class="flex items-center gap-2 pt-4 text-[#092C48] cursor-pointer hover:text-blue-600" id="mobile-show-more-categories">
                            <img src="{{ asset('spanz-img/plus.svg') }}" alt="Expand" class="w-4 h-4">
                            <span class="text-sm">Show More Categories</span>
                        </div>
                        @endif
                    </div>
                    <hr class="my-4 border-t border-gray-300" />
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Grid Layout -->
<div class="block lg:grid lg:grid-cols-12 w-full bg-slate-50">
    <div class="hidden lg:block lg:col-span-2 p-4 lg:pl-10">
        <div class="flex gap-2 items-center mb-4">
            <img src="{{ asset('spanz-img/filter.svg') }}" alt="Filter" class="w-5 h-5">
            <span class="text-sm font-medium">Filter</span>
        </div>
            <div class="flex flex-wrap gap-2 mb-4">
                <button id="desktop-collapse-all"
                    class="border border-black px-3 py-1.5 bg-white hover:bg-gray-100 rounded-sm text-xs sm:text-sm">Collapse
                    All</button>
                <button id="desktop-clear-all"
                    class="border border-black px-3 py-1.5 bg-white hover:bg-gray-100 rounded-sm text-xs sm:text-sm">Clear
                    All</button>
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
                    @if($hasMoreCategories)
                    <div class="flex items-center gap-2 pt-3 text-[#092C48] cursor-pointer hover:text-blue-600" id="desktop-show-more-categories">
                        <img src="{{ asset('spanz-img/plus.svg') }}" alt="Expand" class="w-4 h-4">
                        <span class="text-sm">Show More Categories</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
        <!-- Content area for desktop -->
    <div class="w-full lg:col-span-9 p-4 lg:p-6">
            <div class="text-[#092C48] mb-5">
                <div class="text-sm sm:text-base mb-2">
                    <span>Displaying </span>
                    <span class="font-semibold">1 to {{ $products->count() }} </span>
                    <span>out of </span>
                    <span class="font-semibold">{{ $products->total() }} </span>
                    <span>products </span>
                    @if(request('q'))
                        <span class="text-blue-600">for "{{ request('q') }}"</span>
                    @endif
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-xl sm:text-2xl lg:text-3xl xl:text-4xl font-semibold leading-tight">
                        @if(request('q'))
                            Search Results for "{{ request('q') }}"
                        @else
                            Featured Products
                        @endif
                    </p>
                    @if(request('q'))
                        <a href="{{ route('products.search') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-sm text-sm">
                            Clear Search
                        </a>
                @endif
                </div>
        </div>

            @forelse($products as $product)
            <div class="bg-white border border-gray-200 rounded-sm p-4 sm:p-6 {{ !$loop->first ? 'mt-5' : '' }}">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
                    <a href="{{ route('products.show', $product) }}" class="text-[#092C48] font-semibold text-lg sm:text-xl hover:text-blue-600">{{ $product->title }}</a>
                    <div class="flex gap-4 sm:gap-6">
                        @if($product->featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Featured</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2 my-3">
                    <img src="{{ asset('spanz-img/factory.svg') }}" alt="Category" class="w-4 sm:w-5">
                    <span class="text-[#092C48] font-semibold text-sm sm:text-base">{{ optional($product->category)->name ?? 'Uncategorized' }}</span>
                </div>
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1 lg:w-[75%]">
                        <div class="flex items-center mb-2 gap-2">
                            <span class="text-[#092C48] font-semibold text-xs sm:text-sm lg:text-base">
                                Posted {{ $product->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div>
                            <p class="text-[#092C48] text-sm sm:text-base leading-relaxed">
                                {{ Str::limit(strip_tags($product->description), 200) }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end items-end mt-4">
                    <a href="{{ route('products.show', $product) }}"
                        class="bg-[#0D6AED] hover:bg-blue-700 px-6 py-3 text-white rounded-sm text-base font-medium">
                        View Details
                    </a>
                </div>
            </div>
            @empty
            <div class="bg-white border border-gray-200 rounded-sm p-4 sm:p-6">
                <div class="text-center py-12">
                    @if(request('q'))
                        <h3 class="text-lg font-semibold text-[#092C48] mb-2">No Products Found</h3>
                        <p class="text-gray-600 mb-4">No products found for "{{ request('q') }}". Try different keywords or browse all products.</p>
                        <a href="{{ route('products.search') }}" class="bg-[#0D6AED] hover:bg-blue-700 text-white px-4 py-2 rounded-sm text-sm">
                            View All Products
                        </a>
                    @else
                        <h3 class="text-lg font-semibold text-[#092C48] mb-2">No Products Found</h3>
                        <p class="text-gray-600">There are currently no products available.</p>
                    @endif
                </div>
            </div>
            @endforelse

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
    <section class="bg-[#092C47] text-white py-10 px-5">
        <div class="flex flex-col md:flex-row md:justify-evenly gap-8 md:gap-0">
            <div class="space-y-3">
                <div class="font-semibold">
                    <span>For Buyers</span>
                </div>
                <div>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:underline">Supplier Discovery</a></li>
                        <li><a href="#" class="hover:underline">Product Catalogs</a></li>
                        <li><a href="#" class="hover:underline">CAD</a></li>
                        <li><a href="#" class="hover:underline">Diversity</a></li>
                        <li><a href="#" class="hover:underline">Instant Quotes</a></li>
                        <li><a href="#" class="hover:underline">Buyer & Engineer Reviews</a></li>
                    </ul>
                </div>
            </div>
            <div class="space-y-3">
                <div class="font-semibold">
                    <span>Industry Insights</span>
                </div>
                <div>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:underline">Topic</a></li>
                        <li><a href="#" class="hover:underline">SPANZ Index</a></li>
                        <li><a href="#" class="hover:underline">Guides</a></li>
                        <li><a href="#" class="hover:underline">White Papers</a></li>
                        <li><a href="#" class="hover:underline">Certification Glossary</a></li>
                        <li><a href="#" class="hover:underline">Subscribe</a></li>
                    </ul>
                </div>
            </div>
            <div class="space-y-3">
                <div class="font-semibold">
                    <span>For Business</span>
                </div>
                <div>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:underline">Advertise</a></li>
                        <li><a href="#" class="hover:underline">Content & Data Services</a></li>
                        <li><a href="#" class="hover:underline">Marketing Services</a></li>
                        <li><a href="#" class="hover:underline">SPANZ Reviews</a></li>
                        <li><a href="#" class="hover:underline">Claim Your Company Profile</a></li>
                        <li><a href="#" class="hover:underline">SPANZ Analytics</a></li>
                        <li><a href="#" class="hover:underline">Events & Webinars</a></li>
                    </ul>
                </div>
            </div>
            <div class="space-y-3">
                <div class="font-semibold">
                    <span>Site Map</span>
                </div>
                <div>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:underline">Categories</a></li>
                        <li><a href="#" class="hover:underline">Featured Companies</a></li>
                        <li><a href="#" class="hover:underline">Featured Categories</a></li>
                        <li><a href="#" class="hover:underline">Featured Products</a></li>
                        <li><a href="#" class="hover:underline">Featured Catalogs</a></li>

                    </ul>
                </div>
            </div>
            <div class="space-y-3">
                <div class="font-semibold">
                    <span>About Us</span>
                </div>
                <div>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:underline">SPANZ Brand Center</a></li>
                        <li><a href="#" class="hover:underline">Careers</a></li>
                        <li><a href="#" class="hover:underline">Press Room</a></li>
                        <li><a href="#" class="hover:underline">Sign Up</a></li>
                        <li><a href="#" class="hover:underline">Sign In</a></li>
                        <li><a href="#" class="hover:underline">Contact</a></li>
                        <li><a href="#" class="hover:underline">Help Center</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center text-sm pt-10">
            <span class="px-5">Copyright © 2025 SPANZ Publishing Company. All Rights Reserved. See Terms And Conditions,
                Privacy Statement and California Do Not Track Notice. Website Last Motified September 3, 2025.
                SPANZ Register and SPANZ Regional are part of spanz.Com. SPANZ is a registered trademark of SPANZ
                Publishing Company.
            </span>
</div>
    </section>

<script>
        // Copy current URL to clipboard
        function copyCurrentUrl() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                // Show a brief success message
                const shareIcon = event.target;
                const originalSrc = shareIcon.src;
                shareIcon.src = "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTkgMTJMMTUgNk0xNSAxOEw5IDEyTTE1IDEySDlNMTUgNkg5IiBzdHJva2U9IiMxMEE5N0YiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=";
                shareIcon.style.opacity = "0.5";

                setTimeout(function() {
                    shareIcon.src = originalSrc;
                    shareIcon.style.opacity = "1";
                }, 1000);
            }).catch(function(err) {
                console.error('Could not copy URL: ', err);
                alert('Failed to copy URL to clipboard');
            });
        }

        // Mobile navigation menu toggle
        const menuBtn = document.getElementById("menu-btn");
        const mobileMenu = document.getElementById("mobile-menu");

        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener("click", () => {
                mobileMenu.classList.toggle("hidden");
            });
        }

        // Mobile filter modal toggle
        const mobileFilterBtn = document.getElementById("mobile-filter-btn");
        const mobileFilterModal = document.getElementById("mobile-filter-modal");
        const closeFilterModal = document.getElementById("close-filter-modal");

        if (mobileFilterBtn && mobileFilterModal && closeFilterModal) {
            // Open filter modal
            mobileFilterBtn.addEventListener("click", () => {
                mobileFilterModal.classList.remove("hidden");
                document.body.style.overflow = "hidden"; // Prevent background scrolling
            });

            // Close filter modal
            closeFilterModal.addEventListener("click", () => {
                mobileFilterModal.classList.add("hidden");
                document.body.style.overflow = ""; // Restore scrolling
            });

            // Close modal when clicking outside
            mobileFilterModal.addEventListener("click", (e) => {
                if (e.target === mobileFilterModal) {
                    mobileFilterModal.classList.add("hidden");
                    document.body.style.overflow = "";
                }
            });
        }

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
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
                // default products: submit current form
                e.target.submit();
        return false;
    }

            // Add form submit handler
            const productsSearchForm = document.getElementById('products-search-form');
            if (productsSearchForm) {
                productsSearchForm.addEventListener('submit', handleProductsSearch);
            }

            // Auto-submit search on Enter key
            const searchInputs = document.querySelectorAll('input[name="q"]');
            searchInputs.forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const type = document.getElementById('products-search-type')?.value || 'products';
                        if (type === 'tenders') {
                            const url = new URL("{{ route('tenders.search') }}", window.location.origin);
                            if (input.value.trim()) url.searchParams.set('search', input.value);
                            window.location.href = url.toString();
                        } else {
                            this.closest('form').submit();
                        }
                    }
                });
            });

            // Collapse/Expand functionality
            function toggleFilterSection(header) {
            const content = header.nextElementSibling;
            const arrow = header.querySelector('svg');

                if (content.style.display === 'none') {
                    content.style.display = 'block';
                    arrow.style.transform = 'rotate(0deg)';
                } else {
                    content.style.display = 'none';
                    arrow.style.transform = 'rotate(-90deg)';
                }
            }

            // Add click listeners to filter headers
            document.querySelectorAll('.filter-header').forEach(header => {
                header.addEventListener('click', () => toggleFilterSection(header));
            });

            // Collapse All functionality
            document.getElementById('mobile-collapse-all')?.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('#mobile-filter-modal .filter-content').forEach(content => {
                    content.style.display = 'none';
                });
                document.querySelectorAll('#mobile-filter-modal .filter-header svg').forEach(arrow => {
                    arrow.style.transform = 'rotate(-90deg)';
                });
            });

            document.getElementById('desktop-collapse-all')?.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.lg\\:block .filter-content').forEach(content => {
                    content.style.display = 'none';
                });
                document.querySelectorAll('.lg\\:block .filter-header svg').forEach(arrow => {
                    arrow.style.transform = 'rotate(-90deg)';
                });
            });

            // Clear All button functionality
            document.getElementById('mobile-clear-all')?.addEventListener('click', function(e) {
                e.preventDefault();
                // Remove filter parameters from URL
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.delete('category');
                window.location.href = currentUrl.toString();
            });

            document.getElementById('desktop-clear-all')?.addEventListener('click', function(e) {
                e.preventDefault();
                // Remove filter parameters from URL
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.delete('category');
                window.location.href = currentUrl.toString();
            });

            // Show More Categories functionality
            function loadMoreCategories(containerId, showMoreId, isMobile = false) {
                const container = document.getElementById(containerId);
                const showMoreBtn = document.getElementById(showMoreId);

                if (showMoreBtn) {
                    showMoreBtn.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Get current page from URL or default to 1
                        const currentUrl = new URL(window.location);
                        const currentPage = parseInt(currentUrl.searchParams.get('category_page') || '1');
                        const nextPage = currentPage + 1;

                        // Update URL with next page
                        currentUrl.searchParams.set('category_page', nextPage);

                        // Redirect to load more categories
                        window.location.href = currentUrl.toString();
                    });
                }
            }

            // Initialize Show More functionality
            loadMoreCategories('mobile-categories-list', 'mobile-show-more-categories', true);
            loadMoreCategories('desktop-categories-list', 'desktop-show-more-categories', false);
        });
</script>

@include('components.subscription-modal', ['subscriptions' => $subscriptions ?? collect()])

</body>

</html>