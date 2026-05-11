<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Supplier Directory</title>
    @php($directoryCssQuery = is_file(public_path('css/output.css')) ? filemtime(public_path('css/output.css')) : time())
    <link rel="stylesheet" href="{{ asset('css/output.css') }}?v={{ $directoryCssQuery }}">
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
        #suppliers-results {
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
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 8px 24px rgba(3, 39, 71, 0.06);
        }

        /* Document-share picker: visible focus on directory cards */
        .supplier-select-checkbox:focus {
            outline: none;
        }

        .supplier-select-checkbox:focus-visible {
            box-shadow: 0 0 0 3px rgba(13, 106, 237, 0.35);
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

        /* Plain CSS fallbacks: fixes broken layout if live serves a stale/missing output.css (browser/CDN cache). */
        #supplier-share-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        @media (max-width: 639.98px) {
            #supplier-share-bar {
                flex-direction: column;
                align-items: stretch;
            }
        }

        /* Share-docs CTA: keep solid blue even if compiled Tailwind omits arbitrary bg-[#…] (live saw only hover:bg-* applying). */
        #open-share-docs-modal {
            background-color: var(--thomas-blue) !important;
            color: #fff !important;
            border: 1px solid #0b5fd7;
        }

        #open-share-docs-modal:hover {
            background-color: #0b5fd7 !important;
            color: #fff !important;
        }

        #open-share-docs-modal:disabled {
            background-color: var(--thomas-blue) !important;
            color: #fff !important;
        }

        #open-share-docs-modal:disabled:hover {
            background-color: var(--thomas-blue) !important;
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

        /* ----- Sidebar filter checkboxes: larger hit area + custom look ----- */
        .filter-section input[type="checkbox"].category-filter,
        .filter-section input[type="checkbox"].company-type-filter,
        .filter-section input[type="checkbox"].location-filter {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 20px;
            height: 20px;
            min-width: 20px;
            min-height: 20px;
            border: 1.5px solid #cbd5e1;
            border-radius: 5px;
            background-color: #ffffff;
            cursor: pointer;
            display: inline-block;
            position: relative;
            transition: background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
            flex-shrink: 0;
            vertical-align: middle;
        }

        .filter-section input[type="checkbox"].category-filter:hover,
        .filter-section input[type="checkbox"].company-type-filter:hover,
        .filter-section input[type="checkbox"].location-filter:hover {
            border-color: #0d6aed;
            box-shadow: 0 0 0 3px rgba(13, 106, 237, 0.08);
        }

        .filter-section input[type="checkbox"].category-filter:focus-visible,
        .filter-section input[type="checkbox"].company-type-filter:focus-visible,
        .filter-section input[type="checkbox"].location-filter:focus-visible {
            outline: none;
            border-color: #0d6aed;
            box-shadow: 0 0 0 3px rgba(13, 106, 237, 0.25);
        }

        .filter-section input[type="checkbox"].category-filter:checked,
        .filter-section input[type="checkbox"].company-type-filter:checked,
        .filter-section input[type="checkbox"].location-filter:checked {
            background-color: #0d6aed;
            border-color: #0d6aed;
        }

        .filter-section input[type="checkbox"].category-filter:checked::after,
        .filter-section input[type="checkbox"].company-type-filter:checked::after,
        .filter-section input[type="checkbox"].location-filter:checked::after {
            content: "";
            position: absolute;
            left: 6px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid #ffffff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
            display: block;
        }

        /* Slightly larger, more inviting labels next to those checkboxes */
        .filter-section .category-label {
            font-size: 0.95rem;
            line-height: 1.35;
            padding: 4px 0;
            cursor: pointer;
        }

        .filter-section li.category-group {
            padding: 2px 0;
        }

        .filter-section li.category-group:hover > div > .category-label {
            color: #0d6aed;
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
                        <a href="{{ route('suppliers.directory') }}" class="thomas-nav-link @if(request()->routeIs('suppliers.directory')) underline decoration-2 underline-offset-4 @endif">Suppliers' Directory</a>
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
                <a href="{{ route('suppliers.directory') }}" class="block hover:text-blue-300">Suppliers' Directory</a>
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
                <form id="suppliers-search-form" method="GET" action="{{ route('suppliers.directory') }}" class="flex flex-col sm:flex-row items-center gap-2 sm:gap-0 w-full">
                    <input id="suppliers-search-input" type="search" name="search" value="{{ request('search') }}" placeholder="Search by company name, category, contact or keywords..."
                        class="w-full pl-5 pr-3 py-3 sm:py-2 text-gray-700 focus:outline-none text-sm" />

                    @foreach((array) request('category', []) as $cat)
                        <input type="hidden" name="category[]" value="{{ $cat }}">
                    @endforeach
                    @foreach((array) request('location', []) as $loc)
                        <input type="hidden" name="location[]" value="{{ $loc }}">
                    @endforeach

                    <div class="w-full sm:w-auto">
                        <button type="submit" class="btn-primary btn-primary-sm w-full sm:w-auto">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div
        class="thomas-breadcrumb flex flex-col md:flex-row justify-between items-start md:items-center p-4 sm:p-5 gap-4 md:gap-0">
        <div class="flex flex-wrap items-center text-sm flex-1">
            <span><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-300">Home</a></span>
            <span class="mx-1 text-gray-500">/</span>
            <span class="text-gray-700">Suppliers' Directory</span>
        </div>
        <div class="flex space-x-3 items-center flex-shrink-0">
            <img src="{{ asset('spanz-img/printer.svg') }}" alt="Print" class="w-5 h-5 cursor-pointer hover:opacity-70" onclick="window.print()">
            <img src="{{ asset('spanz-img/share.svg') }}" alt="Share" class="w-5 h-5 cursor-pointer hover:opacity-70" onclick="copyCurrentUrl()">
        </div>
    </div>

    @if(session('success'))
        <div class="thomas-main-wrap">
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-900" role="status">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="thomas-main-wrap">
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
                <p class="font-semibold">Could not send documents</p>
                <ul class="mt-2 list-disc pl-5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if(!empty($canShareDocuments))
        <div class="thomas-main-wrap">
            <div id="supplier-share-bar" class="mb-4 flex flex-col gap-3 rounded-lg border border-[#d8e2ee] bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-[#032747]">
                    <span class="font-semibold">Document share:</span>
                    <span id="supplier-share-count">0</span> / 3 suppliers selected
                </div>
                <button type="button" id="open-share-docs-modal" disabled
                    title="Select one or more suppliers (up to 3) on the cards below, then click to attach files and send."
                    class="btn-primary btn-primary-sm">
                    Share documents…
                </button>
            </div>
        </div>
    @endif

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
            <div id="suppliers-results">
                @include('suppliers.partials.directory-results', ['suppliers' => $suppliers, 'canShareDocuments' => $canShareDocuments ?? false])
            </div>
        </div>
    </div>
    </div>
    @include('components.mainfooter')

    @if(!empty($canShareDocuments))
        @include('suppliers.partials.share-documents-modal')
    @endif

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
            function handleSuppliersSearch(e) {
                e.preventDefault();
                const input = document.getElementById('suppliers-search-input');
                const query = input ? input.value : '';
                if (input) {
                    input.value = query;
                }
                e.target.submit();
                return false;
            }

            const suppliersSearchForm = document.getElementById('suppliers-search-form');
            if (suppliersSearchForm) {
                suppliersSearchForm.addEventListener('submit', handleSuppliersSearch);
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
            const resultsContainer = document.getElementById('suppliers-results');
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
                    if (typeof window.supplierShareUiSync === 'function') {
                        window.supplierShareUiSync();
                    }
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

                const selectedCategories = document.querySelectorAll('input[name="category[]"]:checked');
                selectedCategories.forEach(cb => params.append('category[]', cb.value));

                const selectedLocations = document.querySelectorAll('input[name="location[]"]:checked');
                selectedLocations.forEach(cb => params.append('location[]', cb.value));

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
            document.querySelectorAll('.location-filter').forEach(checkbox => {
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
                document.querySelectorAll('#mobile-filter-modal .location-filter, #mobile-filter-modal .category-filter').forEach(cb => {
                    cb.checked = false;
                });
                // Remove filter parameters from URL
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.delete('category');
                currentUrl.searchParams.delete('category[]');
                currentUrl.searchParams.delete('location');
                currentUrl.searchParams.delete('location[]');
                window.location.href = currentUrl.toString();
            });

            document.getElementById('desktop-clear-all')?.addEventListener('click', function(e) {
                e.preventDefault();
                // Uncheck all filter checkboxes
                document.querySelectorAll('.lg\\:block .location-filter, .lg\\:block .category-filter').forEach(cb => {
                    cb.checked = false;
                });
                // Remove filter parameters from URL
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.delete('category');
                currentUrl.searchParams.delete('category[]');
                currentUrl.searchParams.delete('location');
                currentUrl.searchParams.delete('location[]');
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

            (function setupSupplierDocumentShare() {
                const bar = document.getElementById('supplier-share-bar');
                const modal = document.getElementById('share-docs-modal');
                const openBtn = document.getElementById('open-share-docs-modal');
                const closeBtn = document.getElementById('close-share-docs-modal');
                const cancelBtn = document.getElementById('cancel-share-docs-modal');
                const recipientInputs = document.getElementById('share-recipient-inputs');
                const countEl = document.getElementById('supplier-share-count');
                const shareForm = document.getElementById('share-docs-form');
                const fileInput = document.getElementById('share-docs-files');
                const dropzone = document.getElementById('share-docs-dropzone');
                const fileListEl = document.getElementById('share-docs-file-list');
                const clearFilesBtn = document.getElementById('share-docs-clear-files');
                if (!bar || !modal || !openBtn || !recipientInputs) return;

                const MAX_SHARE_FILES = 10;
                const MAX_FILE_BYTES = 15 * 1024 * 1024;
                /** Keeps multi-file selection across repeated file-picker opens (browser replaces input.files each time). */
                let shareDocFileStash = [];

                function selectedCheckboxes() {
                    return Array.from(document.querySelectorAll('.supplier-select-checkbox:checked'));
                }

                function syncSupplierShareUi() {
                    const checked = selectedCheckboxes();
                    if (countEl) countEl.textContent = String(checked.length);
                    openBtn.disabled = checked.length === 0;
                    const n = checked.length;
                    openBtn.title = n === 0
                        ? 'Select one or more suppliers (up to 3) on the cards below, then click to attach files and send.'
                        : 'Attach files and send to ' + n + ' selected supplier' + (n === 1 ? '' : 's') + '.';
                    const atMax = checked.length >= 3;
                    document.querySelectorAll('.supplier-select-checkbox').forEach(cb => {
                        if (!cb.checked) cb.disabled = atMax;
                    });
                }
                window.supplierShareUiSync = syncSupplierShareUi;

                document.addEventListener('change', function(e) {
                    if (!e.target.classList.contains('supplier-select-checkbox')) return;
                    const checked = selectedCheckboxes();
                    if (checked.length > 3) {
                        e.target.checked = false;
                        alert('You can select at most 3 suppliers.');
                    }
                    syncSupplierShareUi();
                });

                function setFilesOnInput(fileArray) {
                    shareDocFileStash = (fileArray || []).slice(0, MAX_SHARE_FILES);
                    if (fileInput) {
                        const dt = new DataTransfer();
                        shareDocFileStash.forEach(f => dt.items.add(f));
                        fileInput.files = dt.files;
                    }
                    renderShareFileList();
                }

                function getFilesArray() {
                    return shareDocFileStash.slice();
                }

                function formatBytes(n) {
                    if (n < 1024) return n + ' B';
                    if (n < 1024 * 1024) return (n / 1024).toFixed(1) + ' KB';
                    return (n / (1024 * 1024)).toFixed(1) + ' MB';
                }

                function renderShareFileList() {
                    if (!fileListEl || !clearFilesBtn) return;
                    const files = shareDocFileStash;
                    fileListEl.innerHTML = '';
                    if (files.length === 0) {
                        fileListEl.classList.add('hidden');
                        clearFilesBtn.classList.add('hidden');
                        return;
                    }
                    fileListEl.classList.remove('hidden');
                    clearFilesBtn.classList.remove('hidden');
                    files.forEach((file, index) => {
                        const li = document.createElement('li');
                        li.className = 'flex items-center justify-between gap-3 rounded-md border border-gray-200 bg-white px-3 py-2 text-sm';
                        const left = document.createElement('div');
                        left.className = 'min-w-0 flex-1';
                        const name = document.createElement('p');
                        name.className = 'truncate font-medium text-gray-900';
                        name.textContent = file.name;
                        const meta = document.createElement('p');
                        meta.className = 'text-xs text-gray-500';
                        meta.textContent = formatBytes(file.size);
                        left.appendChild(name);
                        left.appendChild(meta);
                        const rm = document.createElement('button');
                        rm.type = 'button';
                        rm.className = 'shrink-0 rounded px-2 py-1 text-xs font-semibold text-red-600 hover:bg-red-50';
                        rm.textContent = 'Remove';
                        rm.addEventListener('click', () => {
                            const next = getFilesArray().filter((_, i) => i !== index);
                            setFilesOnInput(next);
                        });
                        li.appendChild(left);
                        li.appendChild(rm);
                        fileListEl.appendChild(li);
                    });
                }

                function mergeNewFiles(incoming) {
                    const existing = [...shareDocFileStash];
                    const seen = new Set(existing.map(f => f.name + '|' + f.size + '|' + f.lastModified));
                    const merged = [...existing];
                    const skippedOversize = [];
                    let hitMax = false;
                    for (const f of incoming) {
                        if (f.size > MAX_FILE_BYTES) {
                            skippedOversize.push(f.name);
                            continue;
                        }
                        const key = f.name + '|' + f.size + '|' + f.lastModified;
                        if (seen.has(key)) continue;
                        if (merged.length >= MAX_SHARE_FILES) {
                            hitMax = true;
                            break;
                        }
                        seen.add(key);
                        merged.push(f);
                    }
                    if (skippedOversize.length) {
                        alert('Each file must be 15 MB or smaller. Skipped: ' + skippedOversize.join(', '));
                    }
                    if (hitMax) {
                        alert('Maximum ' + MAX_SHARE_FILES + ' files. Extra files were not added.');
                    }
                    setFilesOnInput(merged);
                }

                function resetShareFiles() {
                    shareDocFileStash = [];
                    if (fileInput) {
                        fileInput.value = '';
                        const dt = new DataTransfer();
                        fileInput.files = dt.files;
                    }
                    renderShareFileList();
                }

                if (fileInput) {
                    fileInput.addEventListener('change', () => {
                        const picked = Array.from(fileInput.files || []);
                        if (!picked.length) {
                            renderShareFileList();
                            return;
                        }
                        mergeNewFiles(picked);
                    });
                }

                if (dropzone && fileInput) {
                    dropzone.addEventListener('click', () => fileInput.click());
                    dropzone.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            fileInput.click();
                        }
                    });
                    ['dragenter', 'dragover'].forEach(ev => {
                        dropzone.addEventListener(ev, e => {
                            e.preventDefault();
                            e.stopPropagation();
                            dropzone.classList.add('border-[#0d6aed]', 'bg-blue-50');
                        });
                    });
                    ['dragleave', 'drop'].forEach(ev => {
                        dropzone.addEventListener(ev, e => {
                            e.preventDefault();
                            e.stopPropagation();
                            dropzone.classList.remove('border-[#0d6aed]', 'bg-blue-50');
                        });
                    });
                    dropzone.addEventListener('drop', e => {
                        const dt = e.dataTransfer;
                        if (!dt || !dt.files) return;
                        mergeNewFiles(Array.from(dt.files));
                    });
                }

                clearFilesBtn?.addEventListener('click', () => resetShareFiles());

                shareForm?.addEventListener('submit', (e) => {
                    if (!fileInput || !getFilesArray().length) {
                        e.preventDefault();
                        alert('Please add at least one file.');
                    }
                });

                function openModal() {
                    const checked = selectedCheckboxes();
                    if (!checked.length) return;
                    recipientInputs.innerHTML = '';
                    checked.forEach(cb => {
                        const inp = document.createElement('input');
                        inp.type = 'hidden';
                        inp.name = 'recipient_ids[]';
                        inp.value = cb.getAttribute('data-user-id');
                        recipientInputs.appendChild(inp);
                    });
                    resetShareFiles();
                    modal.classList.remove('hidden');
                    modal.style.display = 'flex';
                    modal.style.alignItems = 'center';
                    modal.style.justifyContent = 'center';
                    document.body.style.overflow = 'hidden';
                }

                function closeModal() {
                    modal.style.display = 'none';
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                    resetShareFiles();
                }

                openBtn.addEventListener('click', openModal);
                closeBtn?.addEventListener('click', closeModal);
                cancelBtn?.addEventListener('click', closeModal);
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) closeModal();
                });

                syncSupplierShareUi();
            })();
        });
    </script>

@include('components.subscription-modal', ['subscriptions' => $subscriptions ?? collect()])

</body>

</html>
