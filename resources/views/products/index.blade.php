<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
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
        }

        .thomas-topbar {
            background: linear-gradient(180deg, #032747 0%, #0a3255 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .thomas-breadcrumb {
            background: #fff;
            border-bottom: 1px solid var(--thomas-border);
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

        .thomas-filter-link {
            color: #274b6b;
        }

        .thomas-filter-link:hover {
            color: var(--thomas-blue);
            text-decoration: underline;
        }

        .thomas-results-header {
            background: #fff;
            border: 1px solid var(--thomas-border);
            border-radius: 6px;
            padding: 14px 16px;
            margin-bottom: 14px;
        }

        .thomas-product-card {
            background: #fff;
            border: 1px solid var(--thomas-border);
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(3, 39, 71, 0.06);
        }

        .thomas-sidebar-toggle {
            background: #fff;
            border: 1px solid var(--thomas-border);
            color: #123b5f;
        }

        @media (min-width: 1024px) {
            #desktop-layout {
                display: grid !important;
                grid-template-columns: minmax(260px, 300px) minmax(0, 1fr);
                gap: 1.25rem;
                align-items: start;
            }

            #desktop-sidebar {
                display: block;
                min-width: 0;
            }

            #desktop-results {
                min-width: 0;
            }

            #desktop-layout.is-sidebar-collapsed {
                grid-template-columns: minmax(0, 1fr);
            }

            #desktop-layout.is-sidebar-collapsed #desktop-sidebar {
                display: none !important;
            }
        }
    </style>
</head>

<body class="thomas-results">
    <div class="thomas-topbar py-5">
        <nav>
            <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}" class="block text-white text-3xl font-extrabold italic tracking-widest leading-none" style="font-family: 'Eurostile', 'Orbitron', 'Arial Black', sans-serif;">
                            SPANZ
                        </a>
                    </div>

                    <div class="hidden md:flex space-x-6">
                        <div class="relative group">
                            <button class="text-white hover:text-blue-400 flex items-center">For Buyers ▾</button>
                            <div class="absolute left-0 mt-2 w-auto bg-white rounded-md shadow-lg opacity-0 invisible pointer-events-none group-hover:opacity-100 group-hover:visible group-hover:pointer-events-auto transition-all duration-200 z-50" style="min-width: 16rem;">
                                <div class="py-1 whitespace-nowrap">
                                    @auth
                                        <a href="{{ route('tenders.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Post a RFX</a>
                                        <a href="{{ route('tenders.saved') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Saved RFXs</a>
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.create')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Post a RFX</a>
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.saved')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Saved RFXs</a>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <button class="text-white hover:text-blue-400 flex items-center">For Suppliers ▾</button>
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

                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('tenders.search') }}" class="text-white hover:text-blue-400">Tenders</a>
                        <a href="{{ route('products.index') }}" class="text-white hover:text-blue-400">Products</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="border border-white text-white px-3 py-1 rounded hover:bg-white hover:text-black">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="border border-white text-white px-3 py-1 rounded hover:bg-white hover:text-black">Login</a>
                            <a href="{{ route('register') }}" class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800">Register</a>
                        @endauth
                    </div>

                    <div class="md:hidden">
                        <button id="menu-btn" class="text-white focus:outline-none">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div id="mobile-menu" class="hidden md:hidden bg-[#092c47] text-white px-4 py-4 space-y-3">
                <a href="#" class="block hover:text-blue-300">For Buyers ▾</a>
                <a href="#" class="block hover:text-blue-300">For Suppliers ▾</a>
                <a href="#" class="block hover:text-blue-300">About</a>
                <a href="{{ route('tenders.search') }}" class="block hover:text-blue-300">Tenders</a>
                <a href="{{ route('products.index') }}" class="block hover:text-blue-300">Products</a>
                <a href="{{ route('company.register') }}" class="block hover:text-blue-300">Claim Your Company</a>
                <a href="#" class="block hover:text-blue-300">Start Advertising</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full border border-white text-white px-3 py-2 rounded hover:bg-white hover:text-black text-center block">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full bg-blue-700 text-white px-3 py-2 rounded hover:bg-blue-800 text-center block">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="w-full border border-white text-white px-3 py-2 rounded hover:bg-white hover:text-black text-center block">Login</a>
                    <a href="{{ route('register') }}" class="w-full bg-blue-700 text-white px-3 py-2 rounded hover:bg-blue-800 text-center block">Register</a>
                @endauth
            </div>
        </nav>
    </div>

    <div class="thomas-breadcrumb flex flex-col md:flex-row justify-between items-start md:items-center p-4 sm:p-5 gap-4 md:gap-0">
        <div class="flex flex-wrap items-center text-sm flex-1">
            <span><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-300">Home</a></span>
            <span class="mx-1"><a href="#" class="text-blue-600 hover:text-blue-300">/</a></span>
            <span><a href="#" class="text-blue-600 hover:text-blue-300">Products</a></span>
        </div>
        <div class="flex space-x-3 items-center flex-shrink-0">
            <img src="{{ asset('spanz-img/printer.svg') }}" alt="Print" class="w-5 h-5 cursor-pointer hover:opacity-70" onclick="window.print()">
        </div>
    </div>

    <div class="thomas-main-wrap">
        <div class="hidden lg:flex items-center justify-between mb-3">
            <button id="desktop-sidebar-toggle" type="button" class="thomas-sidebar-toggle inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-semibold">
                <span id="desktop-sidebar-toggle-icon">☰</span>
                <span id="desktop-sidebar-toggle-text">Hide Filters</span>
            </button>
        </div>
        <div id="desktop-layout" class="block lg:grid lg:grid-cols-12 w-full gap-5">
            <div id="desktop-sidebar" class="hidden lg:block lg:col-span-3">
                <div class="thomas-filter-panel p-4">
                    <div class="flex gap-2 items-center mb-4">
                        <img src="{{ asset('spanz-img/filter.svg') }}" alt="Filter" class="w-5 h-5">
                        <span class="text-sm font-semibold uppercase tracking-wide text-[#123b5f]">Filters</span>
                    </div>
                    <hr class="my-3 border-t border-gray-200" />
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
                                        <a href="{{ route('products.index', array_merge(request()->query(), ['category' => $category->id])) }}"
                                           class="thomas-filter-link text-sm sm:text-md block py-1 {{ request('category') == $category->id ? 'font-semibold text-blue-600' : '' }}">
                                            {{ $category->name }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="desktop-results" class="w-full lg:col-span-9">
                <div class="thomas-results-header text-[#092C48]">
                    <div class="text-sm sm:text-base mb-1">
                        <span>Displaying </span>
                        <span class="font-semibold">{{ $products->count() }} </span>
                        <span>of </span>
                        <span class="font-semibold">{{ $products->total() }} </span>
                        <span>products</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <p class="text-xl sm:text-2xl lg:text-3xl font-semibold leading-tight text-[#113250]">
                            Featured Products
                        </p>
                    </div>
                </div>

                @forelse($products as $product)
            <div class="thomas-product-card p-4 sm:p-6 {{ !$loop->first ? 'mt-4' : '' }}">
                <div class="flex flex-col lg:flex-row gap-4 mb-4">
                    <div class="w-full lg:w-48 flex-shrink-0">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22200%22%3E%3Crect fill=%22%23ddd%22 width=%22300%22 height=%22200%22/%3E%3Ctext fill=%22%23999%22 font-family=%22sans-serif%22 font-size=%2214%22 dy=%2210.5%22 font-weight=%22bold%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22%3ENo Image%3C/text%3E%3C/svg%3E' }}" 
                             alt="{{ $product->title }}" 
                             class="w-full h-48 lg:h-full object-cover rounded-lg border border-gray-200" 
                             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22200%22%3E%3Crect fill=%22%23ddd%22 width=%22300%22 height=%22200%22/%3E%3Ctext fill=%22%23999%22 font-family=%22sans-serif%22 font-size=%2214%22 dy=%2210.5%22 font-weight=%22bold%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22%3ENo Image%3C/text%3E%3C/svg%3E';" />
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0 mb-3">
                            <a href="{{ route('products.show', $product) }}" class="text-[#092C48] font-semibold text-lg sm:text-xl hover:text-blue-600">{{ $product->title }}</a>
                            <div class="flex gap-4 sm:gap-6 items-center">
                                @if($product->featured)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Featured</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2 my-3">
                            <img src="{{ asset('spanz-img/factory.svg') }}" alt="Category" class="w-4 sm:w-5">
                            <span class="text-[#092C48] font-semibold text-sm sm:text-base">{{ optional($product->category)->name ?? 'Uncategorized' }}</span>
                        </div>
                        <div>
                            <p class="text-[#092C48] text-sm sm:text-base leading-relaxed">{{ Str::limit(strip_tags($product->description), 200, '...') }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end items-end mt-4">
                    <a href="{{ route('products.show', $product) }}" class="bg-[#0D6AED] hover:bg-blue-700 px-6 py-3 text-white rounded-sm text-base font-medium">View Details</a>
                </div>
            </div>
            @empty
            <div class="thomas-product-card p-4 sm:p-6">
                <div class="text-center py-12">
                    <h3 class="text-lg font-semibold text-[#092C48] mb-2">No Products Found</h3>
                    <p class="text-gray-600">There are currently no active products available.</p>
                </div>
            </div>
            @endforelse

            @if($products->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
    </div>

    <script>
        const menuBtn = document.getElementById("menu-btn");
        const mobileMenu = document.getElementById("mobile-menu");
        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener("click", () => {
                mobileMenu.classList.toggle("hidden");
            });
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
            sidebarToggleIcon.textContent = collapsed ? '☰' : '✕';
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
    </script>

</body>

</html>


