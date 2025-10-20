<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
</head>

<body>
    <div class="bg-[#092C48] py-5">
        <nav>
            <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-[#0D6AED]">Spanz</a>
                    </div>

                    <div class="hidden md:flex space-x-6">
                        <div class="relative group">
                            <button class="text-white hover:text-blue-400 flex items-center">For Buyers ▾</button>
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

    <div class="flex flex-col md:flex-row bg-slate-50 justify-between items-start md:items-center p-4 sm:p-5 gap-4 md:gap-0">
        <div class="flex flex-wrap items-center text-sm flex-1">
            <span><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-300">Home</a></span>
            <span class="mx-1"><a href="#" class="text-blue-600 hover:text-blue-300">/</a></span>
            <span><a href="#" class="text-blue-600 hover:text-blue-300">Products</a></span>
        </div>
        <div class="flex space-x-3 items-center flex-shrink-0">
            <img src="{{ asset('spanz-img/printer.svg') }}" alt="Print" class="w-5 h-5 cursor-pointer hover:opacity-70" onclick="window.print()">
        </div>
    </div>

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
                                <a href="{{ route('products.index', array_merge(request()->query(), ['category' => $category->id])) }}"
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
            <div class="text-[#092C48] mb-5">
                <div class="text-sm sm:text-base mb-2">
                    <span>Displaying </span>
                    <span class="font-semibold">{{ $products->count() }} </span>
                    <span>of </span>
                    <span class="font-semibold">{{ $products->total() }} </span>
                    <span>products</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-xl sm:text-2xl lg:text-3xl xl:text-4xl font-semibold leading-tight">
                        Featured Products
                    </p>
                </div>
            </div>

            @forelse($products as $product)
            <div class="bg-white border border-gray-200 rounded-sm p-4 sm:p-6 {{ !$loop->first ? 'mt-5' : '' }}">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
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
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1 lg:w-[75%]">
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
            <div class="bg-white border border-gray-200 rounded-sm p-4 sm:p-6">
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
    </script>

</body>

</html>


