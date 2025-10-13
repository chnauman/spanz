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
                        <a href="#" class="text-white hover:text-blue-400">For Buyers ▾</a>
                        <a href="#" class="text-white hover:text-blue-400">For Suppliers ▾</a>
                        <a href="{{ route('tenders.index') }}" class="text-white hover:text-blue-400">Tenders</a>
                        <a href="#" class="text-white hover:text-blue-400">About</a>
                    </div>

                    <!-- Right Actions -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('company.register') }}" class="text-white hover:text-blue-400">Claim Your Company</a>
                        <a href="#" class="text-white hover:text-blue-400">Start Advertising</a>
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
                <a href="{{ route('tenders.index') }}" class="block hover:text-blue-300">Tenders</a>
                <a href="#" class="block hover:text-blue-300">About</a>
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
                    @auth
                        @if(auth()->user()->canViewTenderDetails())
                            <button
                                class="bg-[#0D6AED] rounded-sm text-white px-4 sm:px-6 py-2 text-sm sm:text-base w-full sm:w-auto">View
                                buyer details</button>
                        @else
                            <button onclick="openSubscriptionModal()"
                                class="bg-[#0D6AED] rounded-sm text-white px-4 sm:px-6 py-2 text-sm sm:text-base w-full sm:w-auto">View
                                buyer details</button>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-[#0D6AED] rounded-sm text-white px-4 sm:px-6 py-2 text-sm sm:text-base w-full sm:w-auto inline-block text-center">Login to View Details</a>
                    @endauth
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
                <div class="w-full lg:w-96 lg:flex-shrink-0 mt-8 lg:mt-0">
                    <div class="border border-gray-300 rounded-sm p-4 sm:p-5 bg-white shadow-sm">
                        <h2 class="font-bold text-lg sm:text-xl mb-4 sm:mb-5 text-gray-800">Download Project Documents</h2>
                        
                        <div class="space-y-3 sm:space-y-4">
                            <div class="flex flex-col sm:flex-row sm:gap-x-6">
                                <span class="font-medium text-gray-700 text-sm sm:text-base">Project Location:</span>
                                <p class="text-gray-600 text-sm sm:text-base">{{ $tender->location ?? 'Not specified' }}</p>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:gap-x-6">
                                <span class="font-medium text-gray-700 text-sm sm:text-base">Project Category:</span>
                                <p class="text-gray-600 text-sm sm:text-base">{{ $tender->category->name }}</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex flex-col mb-3 sm:flex-row sm:gap-x-6">
                                <h3 class="font-semibold sm:text-lg text-gray-800">Requested By:</h3>
                                <p>{{ $tender->user->name }}</p>
                            </div>
                            <a href="#" class="text-blue-600 hover:text-blue-800 underline text-sm sm:text-base mb-4 block">
                                {{ $tender->user->name }} (View Profile)
                            </a>
                            
                            <div class="space-y-3">
                                @if($tender->contact_phone)
                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">Business Phone:</span>
                                    <span class="text-gray-600 text-sm">{{ $tender->contact_phone }}</span>
                                </div>
                                @endif
                                
                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">Name:</span>
                                    <span class="text-gray-600 text-sm">{{ $tender->user->name }}</span>
                                </div>
                                
                                @if($tender->contact_email)
                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">Email:</span>
                                    <span class="text-gray-600 text-sm">{{ $tender->contact_email }}</span>
                                </div>
                                @endif
                                
                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">Posted:</span>
                                    <span class="text-gray-600 text-sm">{{ $tender->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">Deadline:</span>
                                    <span class="text-gray-600 text-sm">{{ $tender->getFormattedDeadline('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        @auth
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="space-y-3">
                                <button class="w-full bg-[#0D6AED] hover:bg-blue-700 text-white px-4 py-2 rounded-sm text-sm">
                                    Contact Buyer
                                </button>
                                <button onclick="toggleSave({{ $tender->id }})" id="save-btn-{{ $tender->id }}" class="w-full bg-white hover:bg-gray-100 border border-gray-300 text-gray-700 px-4 py-2 rounded-sm text-sm">
                                    <span id="save-text-{{ $tender->id }}">Save Tender</span>
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="text-center">
                                <p class="text-sm text-gray-600 mb-3">Login to contact the buyer</p>
                                <a href="{{ route('login') }}" class="bg-[#0D6AED] hover:bg-blue-700 text-white px-4 py-2 rounded-sm text-sm inline-block">
                                    Login
                                </a>
                            </div>
                        </div>
                        @endauth
                    </div>
                </div>
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
        @if(!auth()->user()->canViewTenderDetails())
            @php
                $subscriptions = \App\Models\Subscription::where('is_active', true)
                    ->where('name', '!=', 'Basic')
                    ->get();
            @endphp
            @include('components.subscription-modal', ['subscriptions' => $subscriptions])
        @endif
    @endauth

</body>

</html>
