@php
    $bgImage = "spanz-img/spanz-bg.jpg";
@endphp
<div class="bg-image bg-cover bg-center" style="background-image: url('{{ asset($bgImage) }}');">
    <!-- Navbar -->
    <nav class="bg-image bg-cover bg-center"
        style="background-image:url('./spanz-img/spanz-bg.jpg') absolute top-0 left-0 w-full z-50">
        <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ url('/') }}" class="text-2xl font-bold text-[#0D6AED]">Spanz</a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-6">
                    <!-- Buyers dropdown -->
                    <div class="relative">
                        <button id="buyersBtn" type="button"
                            class="text-white hover:text-blue-400 flex items-center gap-1 focus:outline-none"
                            onclick="toggleBuyersDropdown()">
                            <span>For Buyers</span>
                            <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div id="buyersDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-md shadow-lg z-50">
                            <a href="{{ url('buyers') }}" class="block px-4 py-2 hover:bg-gray-100">Browse Buyers</a>
                            <a href="{{ url('buyers/orders') }}" class="block px-4 py-2 hover:bg-gray-100">My Orders</a>
                            <a href="{{ url('buyers/watchlist') }}" class="block px-4 py-2 hover:bg-gray-100">Watchlist</a>
                        </div>
                    </div>

                    <!-- Suppliers dropdown -->
                    <div class="relative">
                        <button id="suppliersBtn" type="button"
                            class="text-white hover:text-blue-400 flex items-center gap-1 focus:outline-none"
                            onclick="toggleSuppliersDropdown()">
                            <span>For Suppliers</span>
                            <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div id="suppliersDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-md shadow-lg z-50">
                            <a href="{{ url('suppliers') }}" class="block px-4 py-2 hover:bg-gray-100">Browse Suppliers</a>
                            <a href="{{ url('suppliers/add') }}" class="block px-4 py-2 hover:bg-gray-100">Add Supplier</a>
                            <a href="{{ url('suppliers/orders') }}" class="block px-4 py-2 hover:bg-gray-100">Order Management</a>
                        </div>
                    </div>

                    <a href="#" class="text-white hover:text-blue-400">About</a>
                </div>

                <!-- Right Actions -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ url('pricing') }}" class="text-white hover:text-blue-400">Pricing</a>
                    <a href="{{ url('tenders') }}" class="text-white hover:text-blue-400">Tenders</a>
                    <button class=" border border-white text-white px-3 py-1 rounded hover:bg-white hover:text-black">
                        Login
                    </button>
                    <button class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800">
                        Register
                    </button>
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
            <a href="#" class="block hover:text-blue-300">Claim Your Company</a>
            <a href="#" class="block hover:text-blue-300">Start Advertising</a>
            <button class="w-full border border-white text-white px-3 py-2 rounded hover:bg-white hover:text-black">
                Login
            </button>
            <button class="w-full bg-blue-700 text-white px-3 py-2 rounded hover:bg-blue-800">
                Register
            </button>
        </div>
    </nav><!-- Hero main content (centered) -->
    <div class="mt-28">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl sm:text-6xl text-[#0D6AED] font-bold leading-tight">SPANZ</h2>
            <p class="text-white mt-3 text-sm sm:text-lg">Search the largest network of trusted suppliers</p>

            <!-- Search row: mobile stacked, sm inline -->
            <div
                class="mt-5 flex flex-col sm:flex-row items-stretch sm:items-center gap-0 sm:gap-0 justify-center max-w-xl mx-auto">
                <!-- dropdown button -->
                <div class="w-full sm:w-auto">
                    <button
                        class="flex items-center justify-between w-full sm:w-40 px-3 py-2 bg-gray-100 border border-gray-300 text-gray-700">
                        Suppliers
                        <svg class="w-4 h-4 ml-2 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 011.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <!-- input -->
                <input type="search" placeholder="By Category, Company or Brand..."
                    class="w-full sm:w-96 px-3 py-2 border border-gray-300 text-gray-700 focus:outline-none" />

                <!-- search button -->
                <div class="w-full sm:w-auto sm:ml-3">
                    <button class="w-full sm:w-auto px-4 py-2 bg-[#0D6AED] text-white">Search</button>
                </div>
            </div>

            <!-- CTA row below search -->
            <div class="flex p-6 pb-14 justify-center gap-2 flex-wrap">
                <span class="text-white text-sm sm:text-base">New to SPANZ?</span>
                <span class="text-[#0D6AED] text-sm sm:text-base">Join FREE for FULL Access</span>
            </div>
        </div>
    </div>
</div>

{{-- dropdown toggle script --}}
<script>
    function toggleSuppliersDropdown() {
        const dd = document.getElementById('suppliersDropdown');
        if (!dd) return;
        dd.classList.toggle('hidden');
    }
    function toggleBuyersDropdown() {
        const dd = document.getElementById('buyersDropdown');
        if (!dd) return;
        dd.classList.toggle('hidden');
    }

    document.addEventListener('click', function (e) {
        const btn = document.getElementById('suppliersBtn');
        const dd = document.getElementById('suppliersDropdown');
        const buyersBtn = document.getElementById('buyersBtn');
        const buyersDd = document.getElementById('buyersDropdown');
        if (!btn || !dd) return;
        // if click outside button and dropdown, hide it
        if (!btn.contains(e.target) && !dd.contains(e.target)) {
            dd.classList.add('hidden');
        }
        if (buyersBtn && buyersDd && !buyersBtn.contains(e.target) && !buyersDd.contains(e.target)) {
            buyersDd.classList.add('hidden');
        }
    });
</script>