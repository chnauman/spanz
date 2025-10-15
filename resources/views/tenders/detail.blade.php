<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $tender->title }} - Tender Details</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
</head>

<body>
    <div class="bg-[#092C48] py-2">
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
                            <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-1">
                                    @auth
                                        <a href="{{ route('tenders.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Post a Tender</a>
                                        <a href="{{ route('tenders.my-tenders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Tenders</a>
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.create')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Post a Tender</a>
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.my-tenders')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Tenders</a>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <!-- For Suppliers Dropdown -->
                        <div class="relative group">
                            <button class="text-white hover:text-blue-400 flex items-center">
                                For Suppliers ▾
                            </button>
                            <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-1">
                                    @auth
                                        @if(auth()->user()->isSupplier() || auth()->user()->isSubSupplier())
                                            <a href="{{ route('tenders.saved') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Saved Tenders</a>
                                            <a href="{{ route('user.interests') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Interests</a>
                                            <a href="{{ route('tenders.viewed') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Viewed Tenders</a>
                                            <a href="{{ route('invite.sub-suppliers') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Invite Sub Supplier</a>
                                            <a href="#" onclick="openSubscriptionModal(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Subscription Plans</a>
                                        @else
                                            <a href="#" onclick="openSubscriptionModal(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Become a Supplier</a>
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
                        <a href="#" class="text-white hover:text-blue-400">Products</a>
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
                <a href="#" class="block hover:text-blue-300">Products</a>
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

    <!-- project overview -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        <div class="border border-gray-300 rounded-sm p-3 sm:p-4 lg:p-6">
            <div>
                <h3 class="text-lg sm:text-xl font-semibold">Project Overview</h3>
            </div>
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">{{ $tender->title }}</h1>
                <span class="text-sm sm:text-base">Estimated Budget: {{ $tender->currency }} {{ $tender->budget ? number_format($tender->budget, 0) : 'Not specified' }}</span>
            </div>
            <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row sm:justify-between space-y-4 sm:space-y-0">
                <h3 class="text-base sm:text-lg font-semibold">Tender Details</h3>
                <div class="flex flex-col sm:items-end space-y-3 sm:space-y-4">
                    <div class="flex items-center text-[#6C6C6C] space-x-2">
                        <svg width="16px" height="16px" class="sm:w-5 sm:h-5" viewBox="-4 0 32 32" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">

                            <title>location</title>
                            <desc>Created with Sketch Beta.</desc>
                            <defs>

                            </defs>
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                                sketch:type="MSPage">
                                <g id="Icon-Set" sketch:type="MSLayerGroup"
                                    transform="translate(-104.000000, -411.000000)" fill="#6C6C6C">
                                    <path
                                        d="M116,426 C114.343,426 113,424.657 113,423 C113,421.343 114.343,420 116,420 C117.657,420 119,421.343 119,423 C119,424.657 117.657,426 116,426 L116,426 Z M116,418 C113.239,418 111,420.238 111,423 C111,425.762 113.239,428 116,428 C118.761,428 121,425.762 121,423 C121,420.238 118.761,418 116,418 L116,418 Z M116,440 C114.337,440.009 106,427.181 106,423 C106,417.478 110.477,413 116,413 C121.523,413 126,417.478 126,423 C126,427.125 117.637,440.009 116,440 L116,440 Z M116,411 C109.373,411 104,416.373 104,423 C104,428.018 114.005,443.011 116,443 C117.964,443.011 128,427.95 128,423 C128,416.373 122.627,411 116,411 L116,411 Z"
                                        id="location" sketch:type="MSShapeGroup">

                                    </path>
                                </g>
                            </g>
                        </svg>
                        <span class="text-sm sm:text-base">{{ $tender->location ?? 'Location not specified' }}</span>
                    </div>

                </div>

            </div>
            <div class="flex flex-col lg:flex-row lg:gap-8 mt-6 sm:mt-8">
                <div class="flex-1">
                    <div class="mt-6 sm:mt-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 max-w-sm sm:max-w-md">
                            <p class="text-sm sm:text-base font-medium text-gray-700">Tender #:</p>
                            <p class="text-sm sm:text-base">#{{ str_pad($tender->id, 8, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-sm sm:text-base font-medium text-gray-700">Publish Date:</p>
                            <p class="text-sm sm:text-base">{{ $tender->created_at->format('d F Y') }}</p>
                            <p class="text-sm sm:text-base font-medium text-gray-700">Deadline:</p>
                            <p class="text-sm sm:text-base">{{ $tender->getFormattedDeadline('d F Y') }}</p>
                            <p class="text-sm sm:text-base font-medium text-gray-700">Category:</p>
                            <p class="text-sm sm:text-base">{{ $tender->category->name }}</p>
                        </div>
                    </div>

                    @if($tender->budget)
                    <div class="mt-6 sm:mt-8">
                        <h3 class="font-semibold mb-3 text-base sm:text-lg">Budget Information:</h3>
                        <div class="space-y-1 text-sm sm:text-base">
                            <p>Total Budget: {{ $tender->currency }} {{ number_format($tender->budget, 0) }}</p>
                            @if($tender->budget > 0)
                            <p>Estimated Project Duration: {{ ceil($tender->budget / 10000) }} months</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="mt-6 sm:mt-8">
                        <h3 class="font-semibold mb-3 text-base sm:text-lg">Project Title:</h3>
                        <p class="text-sm sm:text-base">{{ $tender->title }}</p>
                    </div>

                    <div class="mt-8">
                        <h3 class="font-semibold mb-3">Description:</h3>
                        <div class="text-sm sm:text-base leading-relaxed">
                            {!! nl2br(e($tender->description)) !!}
                        </div>
                    </div>

                    @if($tender->requirements)
                    <div class="mt-6 sm:mt-8">
                        <h3 class="font-semibold mb-3 text-base sm:text-lg">Requirements:</h3>
                        <div class="text-sm sm:text-base leading-relaxed">
                            {!! nl2br(e($tender->requirements)) !!}
                        </div>
                    </div>
                    @endif
                </div>

                <!-- view buyer details -->
                @auth
                    @if(auth()->user()->hasActiveSubscription())
                        <!-- View Buyer Details Button for Subscribed Users -->
                        <div id="buyer-details-section" class="w-full lg:w-96 lg:flex-shrink-0 mt-8 lg:mt-0">
                            <div class="border border-gray-300 rounded-sm p-4 sm:p-5 bg-white shadow-sm">
                                <h2 class="font-bold text-lg sm:text-xl mb-4 sm:mb-5 text-gray-800">Access Buyer Details</h2>

                                <div class="text-center py-6">
                                    <div class="mb-4">
                                        <svg class="mx-auto h-12 w-12 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">View Buyer Details</h3>
                                    <p class="text-sm text-gray-600 mb-4">
                                        Click below to view buyer contact details and download project documents.
                                    </p>
                                    <button onclick="viewBuyerDetails({{ $tender->id }})" id="view-buyer-btn-{{ $tender->id }}" class="w-full bg-[#0D6AED] hover:bg-blue-700 text-white px-4 py-2 rounded-sm text-sm">
                                        <span id="view-buyer-text-{{ $tender->id }}">View Buyer Details</span>
                                    </button>
                                </div>

                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <button onclick="toggleSave({{ $tender->id }})" id="save-btn-{{ $tender->id }}" class="w-full bg-white hover:bg-gray-100 border border-gray-300 text-gray-700 px-4 py-2 rounded-sm text-sm">
                                        <span id="save-text-{{ $tender->id }}">Save Tender</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Subscription required section for free users -->
                        <div class="w-full lg:w-96 lg:flex-shrink-0 mt-8 lg:mt-0">
                            <div class="border border-gray-300 rounded-sm p-4 sm:p-5 bg-white shadow-sm">
                                <h2 class="font-bold text-lg sm:text-xl mb-4 sm:mb-5 text-gray-800">Upgrade to View Buyer Details</h2>

                                <div class="text-center py-6">
                                    <div class="mb-4">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Premium Content</h3>
                                    <p class="text-sm text-gray-600 mb-4">
                                        Upgrade your subscription to view buyer contact details, download project documents, and access premium features.
                                    </p>
                                    <button onclick="openSubscriptionModal()" class="bg-[#0D6AED] hover:bg-blue-700 text-white px-6 py-2 rounded-sm text-sm font-medium">
                                        Upgrade Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Not logged in section -->
                    <div class="w-full lg:w-96 lg:flex-shrink-0 mt-8 lg:mt-0">
                        <div class="border border-gray-300 rounded-sm p-4 sm:p-5 bg-white shadow-sm">
                            <h2 class="font-bold text-lg sm:text-xl mb-4 sm:mb-5 text-gray-800">Login Required</h2>

                            <div class="text-center py-6">
                                <div class="mb-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Login Required</h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    Please login to view buyer details and contact information.
                                </p>
                                <a href="{{ route('login') }}" class="bg-[#0D6AED] hover:bg-blue-700 text-white px-6 py-2 rounded-sm text-sm font-medium inline-block">
                                    Login
                                </a>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- footer -->
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
        // Mobile navigation menu toggle
        const menuBtn = document.getElementById("menu-btn");
        const mobileMenu = document.getElementById("mobile-menu");

        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener("click", () => {
                mobileMenu.classList.toggle("hidden");
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
                            saveText.textContent = 'Save Tender';
                            saveBtn.classList.remove('bg-green-100', 'text-green-700', 'border-green-300');
                            saveBtn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
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
                            saveBtn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                            saveBtn.classList.add('bg-green-100', 'text-green-700', 'border-green-300');
                        })
                        .catch(error => console.error('Error:', error));
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Check saved status on page load
        document.addEventListener('DOMContentLoaded', function() {
            @auth
            fetch(`/tenders/{{ $tender->id }}/saved-status`)
                .then(response => response.json())
                .then(data => {
                    if (data.saved) {
                        const saveText = document.getElementById(`save-text-{{ $tender->id }}`);
                        const saveBtn = document.getElementById(`save-btn-{{ $tender->id }}`);
                        if (saveText) saveText.textContent = 'Saved';
                        if (saveBtn) {
                            saveBtn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                            saveBtn.classList.add('bg-green-100', 'text-green-700', 'border-green-300');
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            @endauth
        });
    </script>

    @auth
        @php
            $subscriptions = \App\Models\Subscription::where('is_active', true)
                ->where('name', '!=', 'Basic')
                ->get();
        @endphp
        @include('components.subscription-modal', ['subscriptions' => $subscriptions])
    @endauth

    <!-- Credit Insufficient Modal - Now redirects to subscription modal -->
    <!-- This modal is kept for backward compatibility but functionality redirects to subscription modal -->

    <!-- Buyer Details Modal -->
    <div id="buyerDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Buyer Details</h3>
                    <button onclick="closeBuyerDetailsModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="buyerDetailsContent" class="space-y-4">
                    <!-- Buyer details will be populated here -->
                </div>
                <div class="mt-6 flex justify-end">
                    <button onclick="closeBuyerDetailsModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewBuyerDetails(tenderId) {
            const button = document.getElementById(`view-buyer-btn-${tenderId}`);
            const buttonText = document.getElementById(`view-buyer-text-${tenderId}`);

            // Disable button and show loading
            button.disabled = true;
            buttonText.textContent = 'Loading...';

            fetch(`/tenders/${tenderId}/view-buyer-details`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show buyer details
                    displayBuyerDetails(data.buyer_details);
                } else if (data.action === 'subscribe' || data.action === 'renew' || data.action === 'upgrade') {
                    // Show subscription modal with appropriate message
                    showSubscriptionModalWithMessage(data.error, data.action);
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while loading buyer details.');
            })
            .finally(() => {
                // Re-enable button
                button.disabled = false;
                buttonText.textContent = 'View Buyer Details';
            });
        }

        function displayBuyerDetails(details) {
            // Find the buyer details section by ID
            const buttonSection = document.getElementById('buyer-details-section');
            if (buttonSection) {
                buttonSection.innerHTML = `
                    <div class="border border-gray-300 rounded-sm p-4 sm:p-5 bg-white shadow-sm">
                        <h2 class="font-bold text-lg sm:text-xl mb-4 sm:mb-5 text-gray-800">Buyer Details & Project Documents</h2>

                        <div class="space-y-3 sm:space-y-4">
                            <div class="flex flex-col sm:flex-row sm:gap-x-6">
                                <span class="font-medium text-gray-700 text-sm sm:text-base">Project Location:</span>
                                <p class="text-gray-600 text-sm sm:text-base">${details.location || 'Not specified'}</p>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:gap-x-6">
                                <span class="font-medium text-gray-700 text-sm sm:text-base">Project Category:</span>
                                <p class="text-gray-600 text-sm sm:text-base">${details.category}</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex flex-col mb-3 sm:flex-row sm:gap-x-6">
                                <h3 class="font-semibold sm:text-lg text-gray-800">Requested By:</h3>
                                <p>${details.name}</p>
                            </div>

                            <div class="space-y-3">
                                ${details.phone ? `
                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">Business Phone:</span>
                                    <span class="text-gray-600 text-sm">${details.phone}</span>
                                </div>
                                ` : ''}

                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">Name:</span>
                                    <span class="text-gray-600 text-sm">${details.name}</span>
                                </div>

                                ${details.email ? `
                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">Email:</span>
                                    <span class="text-gray-600 text-sm">${details.email}</span>
                                </div>
                                ` : ''}

                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">Posted:</span>
                                    <span class="text-gray-600 text-sm">${details.posted_at}</span>
                                </div>

                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">Deadline:</span>
                                    <span class="text-gray-600 text-sm">${details.deadline}</span>
                                </div>

                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">Your Remaining Credits:</span>
                                    <span class="text-gray-600 text-sm">${details.remaining_credits < 0 ? 'Unlimited' : details.remaining_credits}</span>
                                </div>

                                ${details.already_viewed ? `
                                <div class="flex gap-x-4">
                                    <span class="font-medium text-green-700 text-xs sm:text-sm block mb-1">Status:</span>
                                    <span class="text-green-600 text-sm font-semibold">✓ Previously Viewed (No Credits Deducted)</span>
                                </div>
                                ` : ''}
                            </div>
                        </div>

                        ${details.attachments && details.attachments.length > 0 ? `
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <h3 class="font-semibold text-lg text-gray-800 mb-4">📄 Project Documents</h3>
                            <div class="space-y-3">
                                ${details.attachments.map(attachment => `
                                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <svg class="w-6 h-6 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 break-words"
                                                   style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">
                                                   ${attachment.filename}
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">${Math.round(attachment.size / 1024)} KB</p>
                                                <button onclick="downloadAttachment('${attachment.path}', '${attachment.filename}')"
                                                        class="mt-3 bg-[#0D6AED] hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-2 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    <span>Download</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        ` : ''}

                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <button onclick="toggleSave(${details.tender_id})" id="save-btn-${details.tender_id}" class="w-full bg-white hover:bg-gray-100 border border-gray-300 text-gray-700 px-4 py-2 rounded-sm text-sm">
                                <span id="save-text-${details.tender_id}">Save Tender</span>
                            </button>
                        </div>
                    </div>
                `;
            }
        }

        function showCreditModal(totalCredits, requiredCredits) {
            // Show subscription modal instead of credit modal
            openSubscriptionModal();
        }

        function closeCreditModal() {
            // Close subscription modal instead
            closeSubscriptionModal();
        }

        function showSubscriptionModalWithMessage(message, action) {
            // Show a notification with the message first
            showNotification(message, 'info');

            // Then open the subscription modal
            setTimeout(() => {
                openSubscriptionModal();
            }, 1000);
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
                type === 'success' ? 'bg-green-600' :
                type === 'error' ? 'bg-red-600' :
                type === 'info' ? 'bg-blue-600' : 'bg-gray-600'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        function closeBuyerDetailsModal() {
            document.getElementById('buyerDetailsModal').classList.add('hidden');
        }

        // openSubscriptionModal function is defined in the subscription modal component

        // closeSubscriptionModal function is defined in the subscription modal component

        function downloadAttachment(filePath, filename) {
            // Show loading state
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="ml-2">Downloading...</span>';
            button.disabled = true;

            // Create a temporary link to download the file
            const link = document.createElement('a');
            link.href = `/tenders/{{ $tender->id }}/download/${filename}`;
            link.download = filename;
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Reset button state after a short delay
            setTimeout(() => {
                button.innerHTML = originalContent;
                button.disabled = false;
            }, 2000);
        }
    </script>

</body>

</html>
