<div class="bg-[#092C48] py-2">
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
                        <a href="{{ url('tenderposting') }}"
                           class="border border-white text-white px-3 py-1 rounded hover:bg-white hover:text-black">
                            Post Tender
                        </a>
                        <button class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800">
                            Logout
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
                <a href="{{ url('pricing') }}" class="block hover:text-blue-300">Pricing</a>
                <a href="{{ url('tenders') }}" class="block hover:text-blue-300">Tender</a>
                <a href="{{ url('tenderposting') }}" id="post-tender-btn" name="post-tender-btn"
                   class="w-full border border-white text-white px-3 py-2 rounded hover:bg-white hover:text-black block text-center">
                    Post Tender
                </a>
                <button class="w-full bg-blue-700 text-white px-3 py-2 rounded hover:bg-blue-800">
                    Logout
                </button>
            </div>
        </nav>
    </div>
