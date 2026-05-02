<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Featured Tenders Search</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
    <style>
        :root {
            --thomas-navy: #032747;
            --thomas-blue: #0d6efd;
            --thomas-bg: #f3f6fa;
            --thomas-border: #d8e2ee;
            --thomas-text: #15314c;
        }

        body.thomas-results {
            background: var(--thomas-bg);
            color: var(--thomas-text);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            font-size: 16px;
        }

        /* Tender listing: readable sans-serif for body copy */
        #tenders-results {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
        }

        .thomas-topbar {
            background: linear-gradient(180deg, #032747 0%, #0a3255 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .thomas-search-strip {
            background: #f6f8fb;
            border-bottom: 1px solid var(--thomas-border);
            padding: 14px 0;
        }

        .thomas-search-shell {
            background: #fff;
            border: 1px solid var(--thomas-border);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(3, 39, 71, 0.16);
            max-width: 980px;
            margin: 0 auto;
        }

        .thomas-search-shell input {
            border: 0;
            min-height: 42px;
            font-size: 0.95rem;
        }

        .thomas-search-shell button {
            min-height: 42px;
            border-radius: 999px;
            min-width: 86px;
            margin: 3px;
            font-weight: 700;
            background: var(--thomas-blue) !important;
        }

        .thomas-breadcrumb {
            background: #fff;
            border-bottom: 1px solid var(--thomas-border);
            font-size: 0.9rem;
        }

        .thomas-main-wrap {
            max-width: 1320px;
            margin: 0 auto;
            padding: 18px 16px 32px;
        }

        .thomas-filter-panel {
            background: #fff;
            border: 1px solid var(--thomas-border);
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(3, 39, 71, 0.06);
        }

        .thomas-result-card {
            background: #fff;
            border: 1px solid #e8ecf1;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06), 0 4px 14px rgba(15, 23, 42, 0.05);
        }

        .thomas-outline-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 14px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #0d6aed;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            line-height: 1;
            cursor: pointer;
            transition: background 0.15s ease, border-color 0.15s ease;
        }

        .thomas-outline-btn:hover:not(.disabled) {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .thomas-action-link {
            color: #1f3f5f;
            font-size: 1rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            border: 0;
            padding: 0;
            line-height: 1;
        }

        .thomas-primary-btn {
            background: #0d6aed;
            color: #fff;
            border-radius: 8px;
            font-weight: 700;
            padding: 10px 18px;
            line-height: 1;
            font-size: 0.9rem;
            border: 1px solid #0b5fd7;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .thomas-primary-btn:hover {
            background: #0b5fd7;
        }

        .thomas-action-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .thomas-chip {
            border: 1px solid #d3ddeb;
            border-radius: 999px;
            padding: 8px 16px;
            background: #fbfdff;
            color: #173a5a;
            font-size: 1rem;
        }

        .thomas-filter-label {
            color: #274b6b;
        }

        .thomas-filter-label:hover {
            color: var(--thomas-blue);
        }

        .thomas-sidebar-toggle {
            background: #fff;
            border: 1px solid var(--thomas-border);
            color: #123b5f;
        }

        .thomas-nav-link {
            font-size: 1.05rem;
            font-weight: 700;
            color: #e6eef7;
        }

        .thomas-nav-link:hover {
            color: #ffffff;
        }

        @media (min-width: 1024px) {
            #desktop-layout {
                display: flex !important;
                gap: 1.25rem;
                align-items: start;
            }

            #desktop-sidebar {
                display: block;
                flex: 0 0 min(300px, 28%);
                min-width: 0;
            }

            #desktop-results {
                flex: 1 1 auto;
                min-width: 0;
            }

            #desktop-layout.is-sidebar-collapsed {
                display: block !important;
            }

            #desktop-layout.is-sidebar-collapsed #desktop-sidebar {
                display: none !important;
            }
        }

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

        .dropdown-menu a {
            display: block;
            padding: 0.65rem 1rem !important;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            color: #0f3556 !important;
        }

        .dropdown-menu a:hover {
            background: #eaf2ff !important;
            color: #0d6aed !important;
        }
    </style>
</head>

<body class="thomas-results">
    <div class="thomas-topbar py-5">
        <!-- Navbar -->
        <nav>
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-14">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}" class="block text-white text-3xl font-extrabold italic tracking-widest leading-none" style="font-family: 'Eurostile', 'Orbitron', 'Arial Black', sans-serif;">
                            SPANZ
                        </a>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex space-x-6">
                        <!-- For Buyers Dropdown -->
                        <div class="dropdown-group">
                            <button class="thomas-nav-link flex items-center">
                                For Buyers ▾
                            </button>
                            <div class="dropdown-menu">
                                <div class="py-1 whitespace-nowrap">
                                    @auth
                                        @if(!auth()->user()->isAdmin())
                                            <a href="{{ route('tenders.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Post a RFX</a>
                                            <a href="{{ route('tenders.saved') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Saved RFXs</a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.create')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Post a RFX</a>
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.saved')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Saved RFXs</a>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <!-- For Suppliers Dropdown -->
                        <div class="dropdown-group">
                            <button class="thomas-nav-link flex items-center">
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

                        <a href="#" class="thomas-nav-link">About</a>
                    </div>

                    <!-- Right Actions -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('tenders.search') }}" class="thomas-nav-link">Tenders</a>
                        <a href="{{ route('products.search') }}" class="thomas-nav-link">Products</a>
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
    </div>
    <div class="thomas-search-strip">
        <div class="w-full max-w-6xl mx-auto px-4 sm:px-8">
            <div class="thomas-search-shell flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-0">
                <form id="tenders-search-form" method="GET" action="{{ route('tenders.search') }}" class="flex flex-col sm:flex-row items-center gap-2 sm:gap-0 w-full">
                    <input id="tenders-search-input" type="search" name="search" value="{{ request('search') }}" placeholder="Search by tender title, category, company or brand..."
                        class="w-full pl-5 pr-3 py-3 sm:py-2 text-gray-700 focus:outline-none text-sm" />

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

                    <div class="w-full sm:w-auto">
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 sm:py-2 bg-[#0D6AED] text-white text-sm font-medium">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div
        class="thomas-breadcrumb flex flex-col md:flex-row justify-between items-start md:items-center p-4 sm:p-5 gap-4 md:gap-0">
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
                    <h2 class="text-xl font-semibold text-[#092C48]">Filters & Categories</h2>
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
                        class="border border-black px-3 py-2 bg-white hover:bg-gray-100 rounded-sm text-sm font-medium sm:text-base">Collapse
                        All</button>
                    <button id="mobile-clear-all" class="border border-black px-3 py-2 bg-white hover:bg-gray-100 rounded-sm text-sm font-medium sm:text-base">Clear
                        All</button>
                </div>

                <hr class="my-4 border-t border-gray-300" />

                <!-- Categories -->
                <div class="filter-section">
                    <div class="filter-header cursor-pointer flex items-center justify-between">
                        <h3 class="text-base sm:text-lg font-semibold text-[#092C48] mb-3">Related Categories</h3>
                        <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="filter-content">
                        <div class="mb-3">
                            <input id="mobile-category-search" type="search" placeholder="Search categories..."
                                class="w-full px-3 py-2.5 rounded-sm border border-gray-300 text-gray-700 focus:outline-none text-base placeholder:text-gray-400" />
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
                                            class="text-left text-base text-[#092C48] hover:underline select-none category-toggle category-label {{ $isExpanded ? 'font-semibold' : '' }}"
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
                                                <span class="text-base text-gray-700 select-none category-label">{{ $child->name }}</span>
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
                        <h3 class="text-base sm:text-lg font-semibold text-[#092C48] mb-3">Located In</h3>
                        <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="filter-content">
                        <div class="flex flex-col filtersContainer" id="mobile-locations-list">
                            @foreach($locations as $slug => $label)
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" name="location[]" value="{{ $slug }}"
                                    id="mobile-loc-{{ $slug }}" class="location-filter"
                                    {{ in_array($slug, (array) request('location', []), true) ? 'checked' : '' }}>
                                <label for="mobile-loc-{{ $slug }}" class="txt-body-sm mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 text-base font-normal">{{ $label }}</a></label>
                            </div>
                            @endforeach
                        </div>
                        @if($hasMoreLocations)
                        <div class="flex items-center gap-2 pt-4 text-[#092C48] cursor-pointer hover:text-blue-600" id="mobile-show-more-locations">
                            <img src="{{ asset('spanz-img/plus.svg') }}" alt="Expand" class="w-4 h-4">
                            <span class="text-base font-medium">Show More Locations</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Grid Layout -->
    <div class="thomas-main-wrap">
    <div class="hidden md:flex items-center justify-between mb-3">
        <button id="desktop-sidebar-toggle" type="button" class="thomas-sidebar-toggle inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-semibold shadow-sm">
            <span id="desktop-sidebar-toggle-icon" aria-hidden="true">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="none">
                    <path d="M3 5h14M3 10h14M3 15h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </span>
            <span id="desktop-sidebar-toggle-text">Hide Filters</span>
        </button>
    </div>
    <div id="desktop-layout" class="block lg:grid lg:grid-cols-12 w-full gap-5">
        <div id="desktop-sidebar" class="hidden lg:block lg:col-span-3">
            <div class="thomas-filter-panel p-4">
            <div class="flex gap-2 items-center mb-4">
                <img src="{{ asset('spanz-img/filter.svg') }}" alt="Filter" class="w-6 h-6">
                <span class="text-base font-semibold uppercase tracking-wide text-[#123b5f]">Filters</span>
            </div>
            <div class="flex flex-wrap gap-2 mb-4">
                <button id="desktop-collapse-all"
                    class="border border-black px-3 py-2 bg-white hover:bg-gray-100 rounded-sm text-sm sm:text-base font-medium">Collapse
                    All</button>
                <button id="desktop-clear-all"
                    class="border border-black px-3 py-2 bg-white hover:bg-gray-100 rounded-sm text-sm sm:text-base font-medium">Clear
                    All</button>
            </div>
            <hr class="my-3 border-t border-gray-400 w-[70%]" />
            <div class="mt-2">
                <div class="filter-section">
                    <div class="filter-header cursor-pointer flex items-center justify-between">
                        <h1 class="text-base sm:text-lg font-semibold text-[#092C48]">Related Categories</h1>
                        <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="filter-content">
                        <div class="mt-2">
                            <input id="desktop-category-search" type="search" placeholder="Search categories..."
                                class="w-full px-3 py-2.5 rounded-sm border border-gray-300 text-gray-700 focus:outline-none text-base placeholder:text-gray-400" />
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
                                            class="thomas-filter-label text-left text-base lg:text-lg hover:underline select-none category-toggle category-label {{ $isExpanded ? 'font-semibold' : '' }}"
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
                                                <span class="thomas-filter-label text-base text-gray-700 select-none category-label">{{ $child->name }}</span>
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
                        <h3 class="text-base sm:text-lg font-semibold text-[#092C48] mb-3">Located In / Near</h3>
                        <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="filter-content">
                        <section class="flex flex-col filtersContainer" id="desktop-locations-list">
                            @foreach($locations as $slug => $label)
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" name="location[]" value="{{ $slug }}"
                                    id="desktop-loc-{{ $slug }}" class="location-filter"
                                    {{ in_array($slug, (array) request('location', []), true) ? 'checked' : '' }}>
                                <label for="desktop-loc-{{ $slug }}" class="txt-body-sm mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 text-base font-normal hover:underline">{{ $label }}</a></label>
                            </div>
                            @endforeach
                        </section>
                        @if($hasMoreLocations)
                        <div class="flex mt-3 items-center rounded-sm gap-04 py-1 justify-center bg-white hover:bg-gray-100 border border-gray-300 cursor-pointer" id="desktop-show-more-locations">
                            <img src="{{ asset('spanz-img/plus.svg') }}" alt="" class="w-4 ">
                            <span class="pl-2 text-base font-medium">Show More Locations</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- Content area for desktop -->
        <div id="desktop-results" class="w-full lg:col-span-9">
            <div id="tenders-results">
                @include('tenders.partials.search-results', ['tenders' => $tenders])
            </div>
        </div>
    </div>
    </div>
    @include('components.mainfooter')

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
                const input = document.getElementById('tenders-search-input');
                const query = input ? input.value : '';
                if (input) {
                    input.value = query;
                }
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
                        this.closest('form').submit();
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

            // Desktop sidebar toggle
            const sidebarToggleBtn = document.getElementById('desktop-sidebar-toggle');
            const sidebarToggleText = document.getElementById('desktop-sidebar-toggle-text');
            const sidebarToggleIcon = document.getElementById('desktop-sidebar-toggle-icon');
            const desktopLayout = document.getElementById('desktop-layout');
            const desktopSidebar = document.getElementById('desktop-sidebar');

            function syncDesktopSidebar() {
                if (!desktopSidebar || !desktopLayout || !sidebarToggleText || !sidebarToggleIcon) return;

                if (window.innerWidth < 1024) {
                    desktopLayout.classList.remove('is-sidebar-collapsed');
                    return;
                }

                const collapsed = desktopSidebar.dataset.collapsed === 'true';
                desktopLayout.classList.toggle('is-sidebar-collapsed', collapsed);
                sidebarToggleText.textContent = collapsed ? 'Show Filters' : 'Hide Filters';
                sidebarToggleIcon.innerHTML = collapsed
                    ? `<svg class="w-4 h-4" viewBox="0 0 20 20" fill="none">
                           <path d="M3 5h14M3 10h14M3 15h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                       </svg>`
                    : `<svg class="w-4 h-4" viewBox="0 0 20 20" fill="none">
                           <path d="M5 5l10 10M15 5l-10 10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                       </svg>`;
            }

            if (sidebarToggleBtn && desktopSidebar) {
                desktopSidebar.dataset.collapsed = 'false';
                sidebarToggleBtn.addEventListener('click', () => {
                    desktopSidebar.dataset.collapsed = desktopSidebar.dataset.collapsed === 'true' ? 'false' : 'true';
                    syncDesktopSidebar();
                });
                window.addEventListener('resize', syncDesktopSidebar);
                syncDesktopSidebar();
            }
        });
    </script>

@include('components.subscription-modal', ['subscriptions' => $subscriptions ?? collect()])

</body>

</html>
