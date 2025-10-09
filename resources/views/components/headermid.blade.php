<div class="bg-[#092C48] py-5">
        <!-- Navbar -->
        <nav>
            <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ url('/') }}" class="text-2xl font-bold text-[#0D6AED]">Spanz</a>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex space-x-6">
                        <a href="#" class="text-white hover:text-blue-400">For Buyers ▾</a>
                        <a href="#" class="text-white hover:text-blue-400">For Suppliers ▾</a>
                        <a href="#" class="text-white hover:text-blue-400">About</a>
                    </div>

                    <!-- Right Actions -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ url('pricing') }}" class="text-white hover:text-blue-400">Pricing</a>
                        <a href="{{ url('tenders') }}" class="text-white hover:text-blue-400">Tender</a>
                        <button class="border border-white text-white px-3 py-1 rounded hover:bg-white hover:text-black"
                            id="login-btn" name="login-btn">
                            Login
                        </button>
                        <button class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800" id="register-btn"
                            name="register-btn">
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
                <button class="w-full border border-white text-white px-3 py-2 rounded hover:bg-white hover:text-black"
                    id="login-btn" name="login-btn">
                    Login
                </button>
                <button class="w-full bg-blue-700 text-white px-3 py-2 rounded hover:bg-blue-800" id="register-btn"
                    name="register-btn">
                    Register
                </button>
            </div>
        </nav>
        <!-- Hero main content (centered) -->
        <div class="flex flex-col lg:flex-row justify-between lg:pr-10">
            <!-- Search row: mobile stacked, sm inline -->
            <div
                class="container mx-auto mt-5 px-4 sm:px-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-0 lg:mx-0 flex-1">
                <!-- dropdown button -->
                <div class="w-full sm:w-auto">
                    <button
                        class="flex items-center justify-between w-full sm:w-40 px-3 py-3 sm:py-2 bg-gray-100 border border-gray-300 text-gray-700 text-sm">
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
                    class="w-full sm:w-96 px-3 py-3 sm:py-2 border border-gray-300 text-gray-700 focus:outline-none text-sm" />

                <!-- search button -->
                <div class="w-full sm:w-auto sm:ml-3">
                    <button class="w-full sm:w-auto px-4 py-3 sm:py-2 bg-[#0D6AED] text-white text-sm">Search</button>
                </div>
            </div>
            <div class="hidden lg:block">
                <ul class="flex space-x-2 mt-7 pl-5 ">
                    <li><a href="#" class="block text-white hover:text-blue-300 text-sm">For Buyers</a></li>
                    <li><a href="#" class="block text-white hover:text-blue-300 text-sm">Supplier Discovery</a></li>
                    <li><a href="#" class="block text-white hover:text-blue-300 text-sm">Instant Quote</a></li>
                    <li><a href="#" class="block text-white hover:text-blue-300 text-sm">Product Catalogs</a></li>
                    <li><a href="#" class="block text-white hover:text-blue-300 text-sm">CAD Models</a></li>
                </ul>
            </div>
        </div>
    </div>
    