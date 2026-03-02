<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Featured Tenders Search</title>
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

        /* Fix dropdown hover behavior */
        .dropdown-group {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            left: 0;
            top: 100%;
            margin-top: 0.5rem;
            width: auto;
            background: white;
            border-radius: 0.375rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(-10px);
            transition: all 0.3s ease-in-out;
            z-index: 50;
            min-width: 16rem;
        }

        /* Show dropdown on hover with delay */
        .dropdown-group:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0);
            transition-delay: 0.1s;
        }

        /* Keep dropdown open when hovering over it */
        .dropdown-menu:hover {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0);
        }

        /* Add a small gap to prevent flickering when moving from button to dropdown */
        .dropdown-group::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            height: 0.5rem;
            background: transparent;
            z-index: 49;
        }

        /* Ensure dropdowns are hidden by default */
        .dropdown-menu {
            display: block;
        }

        /* Prevent any CSS conflicts */
        .dropdown-group .dropdown-menu {
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
            transform: translateY(-10px) !important;
        }

        .dropdown-group:hover .dropdown-menu {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
            transform: translateY(0) !important;
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
                        <div class="dropdown-group">
                            <button class="text-white hover:text-blue-400 flex items-center">
                                For Buyers ▾
                            </button>
                            <div class="dropdown-menu">
                                <div class="py-1 whitespace-nowrap">
                                    @auth
                                        @if(!auth()->user()->isAdmin())
                                            <a href="{{ route('tenders.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Post a Tender</a>
                                            <a href="{{ route('tenders.my-tenders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">My Tenders</a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.create')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Post a Tender</a>
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.my-tenders')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">My Tenders</a>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <!-- For Suppliers Dropdown -->
                        <div class="dropdown-group">
                            <button class="text-white hover:text-blue-400 flex items-center">
                                For Suppliers ▾
                            </button>
                            <div class="dropdown-menu">
                                <div class="py-1 whitespace-nowrap">
                                    @auth
                                        @if(auth()->user()->isSupplier() || auth()->user()->isSubSupplier())
                                            <a href="{{ route('tenders.saved') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Saved Tenders</a>
                                            <a href="{{ route('user.interests') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">My Interests</a>
                                            <a href="{{ route('tenders.viewed') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Viewed Tenders</a>
                                            @if(auth()->user()->isSupplier())
                                                <a href="{{ route('invite.sub-suppliers') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Invite Sub Supplier</a>
                                            @endif
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
                        <select id="tenders-search-type" class="w-full sm:w-40 px-3 py-3 sm:py-2 bg-gray-100 border border-gray-300 text-gray-700 text-sm">
                            <option value="tenders" selected>Tenders</option>
                            <option value="products">Products</option>
                        </select>
                    </div>

                    <!-- input -->
                    <form id="tenders-search-form" method="GET" action="{{ route('tenders.search') }}" class="flex flex-col sm:flex-row items-center gap-2 sm:gap-0 w-full max-w-2xl">
                        <input id="tenders-search-input" type="search" name="search" value="{{ request('search') }}" placeholder="By Category, Company or Brand..."
                            class="w-full px-3 py-3 sm:py-2 border border-gray-300 text-gray-700 focus:outline-none text-sm" />

                        <!-- Hidden inputs to preserve current filters -->
                        @foreach((array) request('category', []) as $cat)
                            <input type="hidden" name="category[]" value="{{ $cat }}">
                        @endforeach
                        @foreach((array) request('location', []) as $loc)
                            <input type="hidden" name="location[]" value="{{ $loc }}">
                        @endforeach
                        @if(request('company_type'))
                            @foreach((array) request('company_type') as $type)
                                <input type="hidden" name="company_type[]" value="{{ $type }}">
                            @endforeach
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
            <span><a href="#" class="text-blue-600 hover:text-blue-300">Tenders</a></span>
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
                        <div class="mb-3">
                            <input id="mobile-category-search" type="search" placeholder="Search categories..."
                                class="w-full px-3 py-2 rounded-sm border border-gray-300 text-gray-700 focus:outline-none text-sm" />
                        </div>

                        <ul class="space-y-2" id="mobile-categories-list">
                            @php($selectedCategories = collect((array) request('category', []))->map(fn($v) => (int) $v)->filter()->all())
                            @foreach($categories as $category)
                                @php($isParentChecked = in_array((int) $category->id, $selectedCategories, true))
                                @php($isAnyChildChecked = $category->children->pluck('id')->map(fn($v) => (int) $v)->intersect($selectedCategories)->isNotEmpty())
                                @php($isExpanded = $isParentChecked || $isAnyChildChecked)
                                <li class="category-group" data-scope="mobile">
                                    <div class="flex items-start gap-3">
                                        <input type="checkbox"
                                            name="category[]"
                                            value="{{ $category->id }}"
                                            id="mobile-cat-{{ $category->id }}"
                                            class="category-filter category-parent-filter mt-1"
                                            data-parent-id="{{ $category->id }}"
                                            {{ $isParentChecked ? 'checked' : '' }}>
                                        <button type="button"
                                            class="text-left text-sm text-[#092C48] hover:underline select-none category-toggle category-label {{ $isExpanded ? 'font-semibold' : '' }}"
                                            aria-controls="mobile-subcats-{{ $category->id }}"
                                            data-parent-id="{{ $category->id }}">
                                            {{ $category->name }}
                                        </button>
                                    </div>

                                    <div id="mobile-subcats-{{ $category->id }}" class="ml-6 mt-2 space-y-2 subcategory-list {{ $isExpanded ? '' : 'hidden' }} border-l border-gray-200 pl-4" data-parent-id="{{ $category->id }}">
                                        @foreach($category->children as $child)
                                            <div class="flex items-start gap-2">
                                                <span class="mt-1 text-gray-300 select-none leading-none">└</span>
                                                <input type="checkbox"
                                                    name="category[]"
                                                    value="{{ $child->id }}"
                                                    id="mobile-cat-{{ $child->id }}"
                                                    class="category-filter category-child-filter mt-1"
                                                    data-parent-id="{{ $category->id }}"
                                                    {{ in_array((int) $child->id, $selectedCategories, true) ? 'checked' : '' }}>
                                                <span class="text-sm text-gray-700 select-none category-label">{{ $child->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <hr class="my-4 border-t border-gray-300" />
                </div>

                <div class="filter-section">
                    <div class="filter-header cursor-pointer flex items-center justify-between">
                        <h3 class="text-md font-semibold text-[#092C48] mb-3">Located In</h3>
                        <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="filter-content">
                        <div class="flex flex-col filtersContainer" id="mobile-locations-list">
                            @foreach($locations as $location)
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" name="location[]" value="{{ $location }}"
                                    id="mobile-{{ str_replace(' ', '-', strtolower($location)) }}" class="location-filter"
                                    {{ in_array($location, (array) request('location', [])) ? 'checked' : '' }}>
                                <label for="mobile-{{ str_replace(' ', '-', strtolower($location)) }}" class="txt-body-sm  mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 txt-smallest font-reg ">{{ $location }}</a></label>
                            </div>
                            @endforeach
                        </div>
                        @if($hasMoreLocations)
                        <div class="flex items-center gap-2 pt-4 text-[#092C48] cursor-pointer hover:text-blue-600" id="mobile-show-more-locations">
                            <img src="{{ asset('spanz-img/plus.svg') }}" alt="Expand" class="w-4 h-4">
                            <span class="text-sm">Show More Locations</span>
                        </div>
                        @endif
                    </div>
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
                        <div class="mt-2">
                            <input id="desktop-category-search" type="search" placeholder="Search categories..."
                                class="w-full px-3 py-2 rounded-sm border border-gray-300 text-gray-700 focus:outline-none text-sm" />
                        </div>

                        <ul class="space-y-2 mt-3" id="desktop-categories-list">
                            @php($selectedCategories = collect((array) request('category', []))->map(fn($v) => (int) $v)->filter()->all())
                            @foreach($categories as $category)
                                @php($isParentChecked = in_array((int) $category->id, $selectedCategories, true))
                                @php($isAnyChildChecked = $category->children->pluck('id')->map(fn($v) => (int) $v)->intersect($selectedCategories)->isNotEmpty())
                                @php($isExpanded = $isParentChecked || $isAnyChildChecked)
                                <li class="category-group" data-scope="desktop">
                                    <div class="flex items-start gap-3">
                                        <input type="checkbox"
                                            name="category[]"
                                            value="{{ $category->id }}"
                                            id="desktop-cat-{{ $category->id }}"
                                            class="category-filter category-parent-filter mt-1"
                                            data-parent-id="{{ $category->id }}"
                                            {{ $isParentChecked ? 'checked' : '' }}>
                                        <button type="button"
                                            class="text-left text-sm sm:text-md text-[#092C48] hover:underline select-none category-toggle category-label {{ $isExpanded ? 'font-semibold' : '' }}"
                                            aria-controls="desktop-subcats-{{ $category->id }}"
                                            data-parent-id="{{ $category->id }}">
                                            {{ $category->name }}
                                        </button>
                                    </div>

                                    <div id="desktop-subcats-{{ $category->id }}" class="ml-6 mt-2 space-y-2 subcategory-list {{ $isExpanded ? '' : 'hidden' }} border-l border-gray-200 pl-4" data-parent-id="{{ $category->id }}">
                                        @foreach($category->children as $child)
                                            <div class="flex items-start gap-2">
                                                <span class="mt-1 text-gray-300 select-none leading-none">└</span>
                                                <input type="checkbox"
                                                    name="category[]"
                                                    value="{{ $child->id }}"
                                                    id="desktop-cat-{{ $child->id }}"
                                                    class="category-filter category-child-filter mt-1"
                                                    data-parent-id="{{ $category->id }}"
                                                    {{ in_array((int) $child->id, $selectedCategories, true) ? 'checked' : '' }}>
                                                <span class="text-sm sm:text-md text-gray-700 select-none category-label">{{ $child->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <hr class="my-3 border-t border-gray-400 w-[70%]" />

                <div class="filter-section">
                    <div class="filter-header cursor-pointer flex items-center justify-between">
                        <h3 class="text-md font-semibold text-[#092C48] mb-3">Located In / Near</h3>
                        <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="filter-content">
                        <section class="flex flex-col filtersContainer" id="desktop-locations-list">
                            @foreach($locations as $location)
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" name="location[]" value="{{ $location }}"
                                    id="desktop-{{ str_replace(' ', '-', strtolower($location)) }}" class="location-filter"
                                    {{ in_array($location, (array) request('location', [])) ? 'checked' : '' }}>
                                <label for="desktop-{{ str_replace(' ', '-', strtolower($location)) }}" class="txt-body-sm  mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline ">{{ $location }}</a></label>
                            </div>
                            @endforeach
                        </section>
                        @if($hasMoreLocations)
                        <div class="flex mt-3 items-center rounded-sm gap-04 py-1 justify-center bg-white hover:bg-gray-100 border border-gray-300 cursor-pointer" id="desktop-show-more-locations">
                            <img src="{{ asset('spanz-img/plus.svg') }}" alt="" class="w-4 ">
                            <span class="pl-2">Show More Locations</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Content area for desktop -->
        <div class="w-full lg:col-span-9 p-4 lg:p-6">
            <div id="tenders-results">
                @include('tenders.partials.search-results', ['tenders' => $tenders])
            </div>
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

        // Save/Unsave functionality
        function toggleSave(tenderId) {
            const saveBtn = document.getElementById(`save-btn-${tenderId}`);
            const saveText = document.getElementById(`save-text-${tenderId}`);

            // Check if already saved
            fetch(`/tenders/${tenderId}/saved-status`)
                .then(response => response.json())
                .then(data => {
                    if (data.saved) {
                        // Unsave
                        fetch(`/tenders/${tenderId}/unsave`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            saveText.textContent = 'Save';
                            saveBtn.classList.remove('text-green-600');
                            saveBtn.classList.add('text-[#092C48]');
                        })
                        .catch(error => console.error('Error:', error));
                    } else {
                        // Save
                        fetch(`/tenders/${tenderId}/save`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            saveText.textContent = 'Saved';
                            saveBtn.classList.remove('text-[#092C48]');
                            saveBtn.classList.add('text-green-600');
                        })
                        .catch(error => console.error('Error:', error));
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Check saved status on page load
        document.addEventListener('DOMContentLoaded', function() {
            @auth
            @foreach($tenders as $tender)
            fetch(`/tenders/{{ $tender->id }}/saved-status`)
                .then(response => response.json())
                .then(data => {
                    if (data.saved) {
                        const saveText = document.getElementById(`save-text-{{ $tender->id }}`);
                        const saveBtn = document.getElementById(`save-btn-{{ $tender->id }}`);
                        if (saveText) saveText.textContent = 'Saved';
                        if (saveBtn) {
                            saveBtn.classList.remove('text-[#092C48]');
                            saveBtn.classList.add('text-green-600');
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            @endforeach
            @endauth
        });

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            function handleTendersSearch(e) {
                e.preventDefault();
                const type = document.getElementById('tenders-search-type')?.value || 'tenders';
                const input = document.getElementById('tenders-search-input');
                const query = input ? input.value : '';

                if (type === 'products') {
                    const url = new URL("{{ route('products.search') }}", window.location.origin);
                    if (query.trim()) url.searchParams.set('q', query);
                    window.location.href = url.toString();
                    return false;
                }
                // default tenders: submit current form
                e.target.submit();
                return false;
            }

            // Add form submit handler
            const tendersSearchForm = document.getElementById('tenders-search-form');
            if (tendersSearchForm) {
                tendersSearchForm.addEventListener('submit', handleTendersSearch);
            }

            // Auto-submit search on Enter key
            const searchInputs = document.querySelectorAll('input[name="search"]');
            searchInputs.forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const type = document.getElementById('tenders-search-type')?.value || 'tenders';
                        if (type === 'products') {
                            const url = new URL("{{ route('products.search') }}", window.location.origin);
                            if (input.value.trim()) url.searchParams.set('q', input.value);
                            window.location.href = url.toString();
                        } else {
                            this.closest('form').submit();
                        }
                    }
                });
            });

            // Filter functionality
            const resultsContainer = document.getElementById('tenders-results');
            let abortController = null;

            async function fetchResults(url) {
                if (!resultsContainer) return;

                if (abortController) abortController.abort();
                abortController = new AbortController();

                resultsContainer.setAttribute('aria-busy', 'true');
                resultsContainer.style.opacity = '0.6';

                try {
                    const res = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        signal: abortController.signal
                    });
                    if (!res.ok) throw new Error(`Request failed: ${res.status}`);
                    const data = await res.json();
                    if (typeof data.html === 'string') {
                        resultsContainer.innerHTML = data.html;
                    }
                } catch (e) {
                    if (e?.name !== 'AbortError') console.error(e);
                } finally {
                    resultsContainer.removeAttribute('aria-busy');
                    resultsContainer.style.opacity = '1';
                }
            }

            function buildFilterParams({ keepPage = false } = {}) {
                const currentUrl = new URL(window.location);
                const params = new URLSearchParams(currentUrl.search);

                if (!keepPage) params.delete('page');

                params.delete('category');
                params.delete('category[]');
                params.delete('location');
                params.delete('location[]');
                params.delete('company_type');
                params.delete('company_type[]');

                const selectedCategories = document.querySelectorAll('input[name="category[]"]:checked');
                selectedCategories.forEach(cb => params.append('category[]', cb.value));

                const selectedLocations = document.querySelectorAll('input[name="location[]"]:checked');
                selectedLocations.forEach(cb => params.append('location[]', cb.value));

                const selectedCompanyTypes = document.querySelectorAll('input[name="company_type[]"]:checked');
                selectedCompanyTypes.forEach(cb => params.append('company_type[]', cb.value));

                return params;
            }

            async function applyFiltersAjax() {
                const currentUrl = new URL(window.location);
                const params = buildFilterParams({ keepPage: false });
                const nextUrl = currentUrl.pathname + (params.toString() ? `?${params.toString()}` : '');
                history.pushState({}, '', nextUrl);
                await fetchResults(nextUrl);
            }

            // Add event listeners to filter checkboxes (AJAX)
            document.querySelectorAll('.company-type-filter, .location-filter').forEach(checkbox => {
                checkbox.addEventListener('change', applyFiltersAjax);
            });

            function getParentCheckbox(parentId) {
                return document.querySelector(`.category-parent-filter[data-parent-id="${parentId}"]`);
            }

            function setSubcategoryVisibility(parentId, visible) {
                document.querySelectorAll(`.subcategory-list[data-parent-id="${parentId}"]`).forEach(el => {
                    el.classList.toggle('hidden', !visible);
                });

                document.querySelectorAll(`.category-toggle[data-parent-id="${parentId}"]`).forEach(btn => {
                    btn.classList.toggle('font-semibold', !!visible);
                });
            }

            // Clicking category NAME toggles subcategories (no checking)
            document.querySelectorAll('.category-toggle').forEach(btn => {
                btn.addEventListener('click', () => {
                    const parentId = btn.getAttribute('data-parent-id');
                    const list = document.querySelector(`.subcategory-list[data-parent-id="${parentId}"]`);
                    if (!list) return;
                    const willShow = list.classList.contains('hidden');
                    setSubcategoryVisibility(parentId, willShow);
                });
            });

            // Auto-show subcategories when main is checked
            document.querySelectorAll('.category-parent-filter').forEach(parentCb => {
                const parentId = parentCb.getAttribute('data-parent-id');
                setSubcategoryVisibility(parentId, parentCb.checked);

                parentCb.addEventListener('change', () => {
                    setSubcategoryVisibility(parentId, parentCb.checked);

                    // If main is unchecked, uncheck its children
                    if (!parentCb.checked) {
                        document.querySelectorAll(`.category-child-filter[data-parent-id="${parentId}"]`).forEach(childCb => {
                            childCb.checked = false;
                        });
                    }
                    applyFiltersAjax();
                });
            });

            // If a child is checked (e.g., via search), auto-check and expand its parent
            document.querySelectorAll('.category-child-filter').forEach(childCb => {
                childCb.addEventListener('change', () => {
                    const parentId = childCb.getAttribute('data-parent-id');
                    const parentCb = getParentCheckbox(parentId);

                    if (childCb.checked && parentCb && !parentCb.checked) {
                        parentCb.checked = true;
                        setSubcategoryVisibility(parentId, true);
                    }
                    applyFiltersAjax();
                });
            });

            function setupCategorySearch(inputId, listId) {
                const input = document.getElementById(inputId);
                const list = document.getElementById(listId);
                if (!input || !list) return;

                const groups = Array.from(list.querySelectorAll('.category-group'));

                function normalize(str) {
                    return (str || '').toLowerCase().trim();
                }

                function applyCategorySearch() {
                    const q = normalize(input.value);

                    groups.forEach(group => {
                        const labels = Array.from(group.querySelectorAll('.category-label')).map(el => normalize(el.textContent));
                        const match = q === '' || labels.some(t => t.includes(q));
                        group.classList.toggle('hidden', !match);

                        // While searching: show subcategories for matched groups (to help discovery),
                        // but checking a child will still auto-check the parent.
                        if (q !== '' && match) {
                            const parentCb = group.querySelector('.category-parent-filter');
                            const parentId = parentCb?.getAttribute('data-parent-id');
                            if (parentId) setSubcategoryVisibility(parentId, true);
                        } else if (q === '') {
                            const parentCb = group.querySelector('.category-parent-filter');
                            const parentId = parentCb?.getAttribute('data-parent-id');
                            if (parentId) setSubcategoryVisibility(parentId, !!parentCb?.checked);
                        }
                    });
                }

                input.addEventListener('input', applyCategorySearch);
            }

            setupCategorySearch('desktop-category-search', 'desktop-categories-list');
            setupCategorySearch('mobile-category-search', 'mobile-categories-list');

            // AJAX paginate (intercept clicks)
            if (resultsContainer) {
                resultsContainer.addEventListener('click', (e) => {
                    const a = e.target.closest('a');
                    if (!a || !a.getAttribute('href')) return;
                    const href = a.getAttribute('href');
                    if (!href.includes('page=')) return;
                    e.preventDefault();
                    history.pushState({}, '', href);
                    fetchResults(href);
                });
            }

            // Back/forward navigation
            window.addEventListener('popstate', () => {
                fetchResults(window.location.href);
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
                // Uncheck all filter checkboxes
                document.querySelectorAll('#mobile-filter-modal .company-type-filter, #mobile-filter-modal .location-filter, #mobile-filter-modal .category-filter').forEach(cb => {
                    cb.checked = false;
                });
                // Remove filter parameters from URL
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.delete('category');
                currentUrl.searchParams.delete('category[]');
                currentUrl.searchParams.delete('location');
                currentUrl.searchParams.delete('location[]');
                currentUrl.searchParams.delete('company_type');
                currentUrl.searchParams.delete('company_type[]');
                window.location.href = currentUrl.toString();
            });

            document.getElementById('desktop-clear-all')?.addEventListener('click', function(e) {
                e.preventDefault();
                // Uncheck all filter checkboxes
                document.querySelectorAll('.lg\\:block .company-type-filter, .lg\\:block .location-filter, .lg\\:block .category-filter').forEach(cb => {
                    cb.checked = false;
                });
                // Remove filter parameters from URL
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.delete('category');
                currentUrl.searchParams.delete('category[]');
                currentUrl.searchParams.delete('location');
                currentUrl.searchParams.delete('location[]');
                currentUrl.searchParams.delete('company_type');
                currentUrl.searchParams.delete('company_type[]');
                window.location.href = currentUrl.toString();
            });

            // Show More Locations functionality
            function loadMoreLocations(containerId, showMoreId, isMobile = false) {
                const container = document.getElementById(containerId);
                const showMoreBtn = document.getElementById(showMoreId);

                if (showMoreBtn) {
                    showMoreBtn.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Get current page from URL or default to 1
                        const currentUrl = new URL(window.location);
                        const currentPage = parseInt(currentUrl.searchParams.get('location_page') || '1');
                        const nextPage = currentPage + 1;

                        // Update URL with next page
                        currentUrl.searchParams.set('location_page', nextPage);

                        // Redirect to load more locations
                        window.location.href = currentUrl.toString();
                    });
                }
            }

            // Initialize Show More functionality
            loadMoreLocations('mobile-locations-list', 'mobile-show-more-locations', true);
            loadMoreLocations('desktop-locations-list', 'desktop-show-more-locations', false);
        });
    </script>

@include('components.subscription-modal', ['subscriptions' => $subscriptions ?? collect()])

</body>

</html>
