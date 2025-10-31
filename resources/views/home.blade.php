<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Spanz</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
    <style>
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

        /* Pricing Cards Styles */
        @keyframes slideInUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }

        .animate-slide-in {
            animation: slideInUp 0.6s ease-out;
        }

        .animate-pulse-slow {
            animation: pulse 2s infinite;
        }

        .shimmer-effect {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        .glow-effect {
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
        }

        .silver-glow {
            box-shadow: 0 0 20px rgba(192, 192, 192, 0.3);
        }

        .subscription-card {
            transition: all 0.3s ease;
            position: relative;
        }

        .subscription-card.active {
            z-index: 10;
            transform: translateZ(20px) scale(1.05);
        }

        .subscription-card.active .bg-white {
            background: linear-gradient(135deg, #092C48 0%, #0D6AED 100%);
            border-color: #0D6AED;
            color: white;
            box-shadow: 0 20px 40px rgba(13, 106, 237, 0.3);
            transform: translateZ(20px) scale(1.05);
            border-radius: 1rem;
        }

        .subscription-card.active .text-gray-900 {
            color: white;
        }

        .subscription-card.active .text-gray-700 {
            color: #e5e7eb;
        }

        .subscription-card.active .text-gray-600 {
            color: #d1d5db;
        }

        .subscription-card.active .text-gray-500 {
            color: #9ca3af;
        }

        .subscription-card.active button {
            background: white;
            color: #092C48;
        }

        .subscription-card.active button:hover {
            background: #f3f4f6;
            color: #092C48;
        }

        /* Requested button styling - always visible */
        .subscription-card button.bg-yellow-500 {
            background-color: #eab308 !important;
            color: white !important;
            cursor: not-allowed !important;
        }

        .subscription-card.active button.bg-yellow-500 {
            background-color: #eab308 !important;
            color: white !important;
        }

        /* Current Plan button styling - always visible */
        .subscription-card button.bg-gray-400 {
            background-color: #9ca3af !important;
            color: white !important;
            cursor: not-allowed !important;
        }

        .subscription-card.active button.bg-gray-400 {
            background-color: #9ca3af !important;
            color: white !important;
        }

        /* Current Plan button styling for green variant */
        .subscription-card button.bg-green-500 {
            background-color: #10b981 !important;
            color: white !important;
            cursor: not-allowed !important;
        }

        .subscription-card.active button.bg-green-500 {
            background-color: #10b981 !important;
            color: white !important;
        }
    </style>
</head>
<body>
   <div class="bg-image bg-cover bg-center" style="background-image:url('{{ asset('spanz-img/spanz-bg.jpg') }}')">
        <!-- Navbar -->
        <nav class="bg-image bg-cover bg-center" style="background-image:url('{{ asset('spanz-img/spanz-bg.jpg') }}') absolute top-0 left-0 w-full z-50">
            <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="#" class="text-2xl font-bold text-[#0D6AED]">Spanz</a>
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
                                            <a href="{{ route('tenders.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Post a Tender</a>
                                            <a href="{{ route('tenders.my-tenders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Tenders</a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.create')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Post a Tender</a>
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.my-tenders')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Tenders</a>
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
                                            <a href="{{ route('tenders.saved') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Saved Tenders</a>
                                            <a href="{{ route('user.interests') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Interests</a>
                                            <a href="{{ route('tenders.viewed') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Viewed Tenders</a>
                                            @if(auth()->user()->isSupplier())
                                                <a href="{{ route('invite.sub-suppliers') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Invite Sub Supplier</a>
                                            @endif
                                            <a href="#" onclick="openSubscriptionModal(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Subscription Plans</a>
                                        @else
                                            <a href="#" onclick="openSubscriptionModal(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Become a Supplier</a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('home')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Become a Supplier</a>
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
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden bg-[#092c47] text-white px-4 py-4 space-y-3">
                <!-- Mobile For Buyers -->
                <div class="space-y-2">
                    <div class="font-semibold text-blue-300">For Buyers</div>
                    @auth
                        <a href="{{ route('tenders.create') }}" class="block pl-4 hover:text-blue-300">Post a Tender</a>
                        <a href="{{ route('tenders.my-tenders') }}" class="block pl-4 hover:text-blue-300">My Tenders</a>
                    @else
                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.create')) }}" class="block pl-4 hover:text-blue-300">Post a Tender</a>
                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.my-tenders')) }}" class="block pl-4 hover:text-blue-300">My Tenders</a>
                    @endauth
                </div>

                <!-- Mobile For Suppliers -->
                <div class="space-y-2">
                    <div class="font-semibold text-blue-300">For Suppliers</div>
                    @auth
                        @if(auth()->user()->isSupplier() || auth()->user()->isSubSupplier())
                            <a href="{{ route('tenders.saved') }}" class="block pl-4 hover:text-blue-300">Saved Tenders</a>
                            <a href="{{ route('user.interests') }}" class="block pl-4 hover:text-blue-300">My Interests</a>
                            <a href="{{ route('tenders.viewed') }}" class="block pl-4 hover:text-blue-300">Viewed Tenders</a>
                            <a href="{{ route('suppliers.invite') }}" class="block pl-4 hover:text-blue-300">Invite Sub Supplier</a>
                            <a href="{{ route('tenders.invitations') }}" class="block pl-4 hover:text-blue-300">Invitations</a>
                            <a href="#" onclick="openSubscriptionModal(); return false;" class="block pl-4 hover:text-blue-300">Subscription Plans</a>
                        @else
                            <a href="#" onclick="openSubscriptionModal(); return false;" class="block pl-4 hover:text-blue-300">Become a Supplier</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}?redirect={{ urlencode(route('home')) }}" class="block pl-4 hover:text-blue-300">Become a Supplier</a>
                    @endauth
                </div>

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
        </nav><!-- Hero main content (centered) -->
            <div class="mt-28">
                <div class="container mx-auto px-4 text-center">
                    <h2 class="text-3xl sm:text-6xl text-[#0D6AED] font-bold leading-tight">SPANZ</h2>
                    <p class="text-white mt-3 text-sm sm:text-lg">Search the largest network of trusted suppliers</p>

                    <!-- Search row: mobile stacked, sm inline -->
                    <form id="home-search-form" class="mt-5 flex flex-col sm:flex-row items-stretch sm:items-center gap-0 sm:gap-0 justify-center max-w-xl mx-auto" onsubmit="return handleHomeSearch(event)">
                        <!-- search type selector -->
                        <div class="w-full sm:w-auto">
                            <select id="home-search-type" class="w-full sm:w-40 px-3 py-2 bg-gray-100 border border-gray-300 text-gray-700">
                                <option value="tenders">Tenders</option>
                                <option value="products">Products</option>
                            </select>
                        </div>

                        <!-- input -->
                        <input id="home-search-input" type="search" placeholder="By Category, Company or Brand..." class="w-full sm:w-96 px-3 py-2 border border-gray-300 text-gray-700 focus:outline-none"/>

                        <!-- search button -->
                        <div class="w-full sm:w-auto sm:ml-3">
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-[#0D6AED] text-white">Search</button>
                        </div>
                    </form>

                    <!-- CTA row below search -->
                    <div class="flex p-6 pb-14 justify-center gap-2 flex-wrap">
                        <span class="text-white text-sm sm:text-base">New to SPANZ?</span>
                        <span class="text-[#0D6AED] text-sm sm:text-base">Join FREE for FULL Access</span>
                    </div>
                </div>
            </div>
    </div>
    <div class="text-blue-950 font-semibold text-xl flex justify-center py-5 text-center px-3">
        <span>For 125+ years, SPANZ has connected buyers with industrial suppliers</span>
    </div>

    <!-- Buyers Section -->
    <div class="flex flex-col-reverse lg:flex-row justify-evenly items-center px-5 lg:px-20 py-12">
        <!-- Left Text Section -->
        <div class=" text-center lg:text-left mt-8 lg:mt-0">
            <span class="text-white bg-blue-950 px-5 py-1 rounded-full inline-block">For Buyers</span>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl text-blue-950 py-5 mx-auto lg:mx-0 lg:w-[26rem]">Every 10 seconds, a buyer finds what they need on SPANZ</h1>
            <ul class="list-disc pl-5 text-blue-950 space-y-2 text-left inline-block lg:block">
                <li>Access our network of 500,000+ trusted suppliers</li>
                <li>Filter by Distance, Certification, and more</li>
                <li>Evaluate Supplier Capabilities and Services</li>
                <li>Get Direct Quotes</li>
                <li>Source Parts and Services Today</li>
            </ul>
            <button class="bg-[#0D6AED] text-white px-4 py-2 mt-5">Search for a Supplier</button>
        </div>
        <!-- Right Image Section -->
        <div class="w-full lg:w-1/2 flex justify-center mt-8 lg:mt-0">
            <img src="{{ asset('spanz-img/for-buyers.webp') }}" alt="For Buyers" class="w-full max-w-sm sm:max-w-md lg:w-[29rem]">
        </div>
    </div>

    <!-- Suppliers Section -->
    <div class="flex flex-col-reverse lg:flex-row justify-evenly items-center px-5 lg:px-20 py-12">
        <!-- Left: Image -->
        <div class="flex mt-8 lg:mt-0">
            <img src="{{ asset('spanz-img/for-suppliers.webp') }}" alt="For Suppliers" class="w-full max-w-sm sm:max-w-md lg:w-[29rem]">
        </div>
        <!-- Right: Text -->
        <div class="w-full lg:w-1/2 text-center flex justify-center lg:text-left mt-8 lg:mt-0">
            <div>
                <span class="text-white bg-blue-950 px-5 py-1 rounded-full inline-block">For Suppliers</span>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl text-blue-950 py-5 mx-auto lg:mx-0 lg:w-[26rem]">Make every marketing dollar count. Get discovered on SPANZ.</h1>
                <ul class="list-disc pl-5 text-blue-950 space-y-2 text-left inline-block lg:block">
                    <li>Join a trusted Network of Suppliers</li>
                    <li>Customize your Ad budget</li>
                    <li>Define your target market</li>
                    <li>Pay for what you get</li>
                    <li>Drive results & track your progress</li>
                </ul>

                <button class="bg-[#0D6AED] text-white px-4 py-2 mt-5">Get Started Today</button>
            </div>
        </div>
    </div>

    <!-- Pricing Cards Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Plan</h1>
                <p class="text-xl text-gray-600">Select the perfect subscription plan for your business needs</p>
            </div>

            <!-- Pricing Cards -->
            <div class="flex flex-wrap justify-center gap-8 mt-10" style="min-height: 4.5in;">
                @foreach($subscriptions as $index => $subscription)
                    @if($subscription->is_active)
                        @php
                            $userHasActiveSubscription = auth()->check() && auth()->user()->getActiveSubscription();
                            $isBasicPlan = strtolower($subscription->name) === 'basic';
                            $shouldShowBasic = !$userHasActiveSubscription;
                            $shouldHideBasic = $userHasActiveSubscription && $isBasicPlan;
                        @endphp

                        @if(!$shouldHideBasic)
                            <div class="subscription-card relative group cursor-pointer {{ $index === 0 ? 'active' : '' }}"
                                 data-plan="{{ strtolower($subscription->name) }}"
                                 data-subscription-id="{{ $subscription->id }}">
                                <div class="bg-white border-2 border-gray-200 rounded-2xl p-4 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1" style="width: 3in; height: 3.5in;">
                                    @if($subscription->name === 'Pro')
                                        <!-- Most Popular Badge -->
                                        <div class="absolute -top-2 right-2 most-popular-badge z-20">
                                            <div class="bg-[#0D6AED] text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                                MOST POPULAR
                                            </div>
                                        </div>
                                    @endif

                                    <div class="text-center h-full flex flex-col justify-between">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $subscription->name }}</h3>
                                            <div class="text-3xl font-bold text-gray-900 mb-2">
                                                ${{ $subscription->price }}
                                                <span class="text-sm text-gray-500">/month</span>
                                            </div>
                                            <p class="text-gray-600 text-sm mb-4">{{ $subscription->description ?? 'Premium subscription plan' }}</p>

                                             <!-- Quota/Credits Display -->
                                             <div class="bg-blue-50 rounded-lg p-3 mb-4">
                                                 <div class="text-lg font-semibold text-[#0D6AED] mb-1">
                                                     @if($subscription->credits_per_month < 0)
                                                         Unlimited Credits
                                                     @elseif($subscription->credits_per_month == 0)
                                                         No Credits
                                                     @else
                                                         {{ $subscription->credits_per_month }} Credits
                                                     @endif
                                                 </div>
                                                 <p class="text-xs text-gray-800 font-medium">To view tenders and buyers</p>
                                             </div>
                                        </div>

                                        <div class="text-center">
                                            @auth
                                                @if(auth()->user()->getActiveSubscription() && auth()->user()->getActiveSubscription()->subscription_id == $subscription->id)
                                                    <button class="w-full bg-gray-400 text-white px-4 py-3 rounded-lg text-base font-semibold cursor-not-allowed">
                                                        Current Plan
                                                    </button>
                                                @elseif($isBasicPlan && !$userHasActiveSubscription)
                                                    <button class="w-full bg-gray-400 text-white px-4 py-3 rounded-lg text-base font-semibold cursor-not-allowed">
                                                        Current Plan
                                                    </button>
                                                @else
                                                    <button class="w-full bg-[#092C48] text-white px-4 py-3 rounded-lg text-base font-semibold hover:bg-[#0D6AED] transition-all duration-300 transform hover:scale-105"
                                                            onclick="requestSubscription({{ $subscription->id }}, '{{ strtolower($subscription->name) }}', this)">
                                                        {{ $subscription->price == 0 ? 'Get Started' : 'Request Subscription' }}
                                                    </button>
                                                @endif
                                            @else
                                                <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}"
                                                   class="w-full bg-[#092C48] text-white px-4 py-3 rounded-lg text-base font-semibold hover:bg-[#0D6AED] transition-all duration-300 transform hover:scale-105 inline-block text-center">
                                                    Choose Plan
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>

            <!-- FAQ Section -->
            <div class="mt-16 max-w-3xl mx-auto">
                <h2 class="text-3xl font-bold text-center text-gray-900 mb-8">Frequently Asked Questions</h2>
                <div class="space-y-6">
                    <div class="bg-white rounded-lg p-6 shadow">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">What are credits used for?</h3>
                        <p class="text-gray-600">Credits are used to view detailed tender information, contact buyers, and access premium features.</p>
                    </div>
                    <div class="bg-white rounded-lg p-6 shadow">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I change my plan later?</h3>
                        <p class="text-gray-600">Yes, you can upgrade or downgrade your subscription at any time. Changes will be reflected in your next billing cycle.</p>
                    </div>
                    <div class="bg-white rounded-lg p-6 shadow">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a free trial?</h3>
                        <p class="text-gray-600">Yes, the Basic plan is free and allows you to browse tenders. Premium features require a paid subscription.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-100 py-10">
        <div class="justify-center flex flex-wrap text-center px-4">
            <span>Join North America's top companies actively searching on </span>
            <span class="text-[#0D6AED] font-semibold ml-1">SPANZ</span>
        </div>

        <!-- Logo section -->
        <div class="flex flex-wrap justify-center gap-6 sm:gap-10 my-5 px-4">
            <img src="{{ asset('spanz-img/Boeing_full_logo.svg') }}" alt="Boeing" class="w-20 sm:w-24">
            <img src="{{ asset('spanz-img/3M_wordmark.svg') }}" alt="3M" class="w-16 sm:w-24">
            <img src="{{ asset('spanz-img/general-dynamics-logo.svg') }}" alt="General Dynamics" class="w-24 sm:w-28">
            <img src="{{ asset('spanz-img/NASA_logo.svg') }}" alt="NASA" class="w-20 sm:w-24">
            <img src="{{ asset('spanz-img/Kraft_logo.svg') }}" alt="Kraft" class="w-20 sm:w-24">
            <img src="{{ asset('spanz-img/Rockwell_Collins_logo.svg') }}" alt="Rockwell" class="w-24 sm:w-28">
        </div>

        <div class="flex justify-center">
            <a href="{{ route('company.register') }}" class="bg-[#0D6AED] text-white px-4 py-2 mt-5 rounded-sm inline-block">
                Claim your company profile
            </a>
        </div>
    </div>
    <div class="flex flex-col md:flex-row lg:justify-center text-blue-950 px-4 md:pl-40 pt-10 gap-6 md:gap-0">
        <div class="w-full md:w-[60rem]">
            <p class="text-2xl sm:text-3xl md:text-4xl pb-4 md:pb-6">For industry. For 125+ Years.</p>
            <span class="text-base sm:text-xl md:text-2xl">Spanz connects buyers and suppliers to inform strategic decision-making. build supply chains and grow business.</span>
        </div>
        <div class="flex items-center justify-start md:justify-center mt-4 md:mt-0">
            <button class="bg-[#0D6AED] text-white px-4 py-2 rounded-sm w-full md:w-auto">
                Learn More About Us
            </button>
        </div>
    </div>
    <div class="bg-gray-100 mt-10 pb-10">
        <div class="flex justify-center">
            <h1 class="text-2xl sm:text-3xl my-10 lg:text-4xl text-blue-950 py-5 mx-auto lg:mx-0">Browse RFX Categories</h1>
        </div>
        <!-- grid layout for all categories -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 w-full px-5 md:px-28 lg:px-28 ml-auto mr-auto">
        @foreach($categories as $category)
            <div>
                <div class="font-semibold pb-4 {{ $category->name === 'Custom Manufacturing & Fabricating' ? 'w-44' : '' }}">
                    <h2>{{ $category->name }}</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        @foreach($category->subcategories as $subcategory)
                            <li>
                                <a href="/{{ strtolower(str_replace([' ', '&', '/'], ['-', '', ''], $subcategory->name)) }}">
                                    {{ $subcategory->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
        </div>
    </div>
    <section class="bg-gradient-to-r from-[#092C47] to-[#21435E] py-16 px-6">
        <div class="max-w-7xl mx-auto flex flex-col gap-10">

                <!-- Header -->
                <header class="flex flex-col gap-4 text-center">
                <h1 class="text-white text-3xl md:text-4xl">
                    Find Suppliers, Insights, Tools and More...
                </h1>
                <h3 class="text-white text-lg md:text-xl">
                    Become part of North America's largest and most active network of B2B buyers and industrial/commercial suppliers.
                </h3>
                </header>

                <!-- Highlights Grid -->
                <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                <!-- Item 1 -->
                <li class="relative group">
                    <a class="flex flex-col items-center gap-5">
                    <svg viewBox="0 0 32 32" class="text-white p-4 shadow-[0_2px_4px_rgba(0,0,0,0.5)] rounded-full  w-20 h-20">
                        <path d="M18.354 5.9l4.83-1.294a1 1 0 0 1 1.224.707l3.624 13.523a1 1 0 0 1-.707 1.225l-8.774 2.35a7.35 7.35 0 0 0-7.075-3.542L8.953 9.454A1 1 0 0 1 9.66 8.23l4.83-1.295 1.035 3.864 3.864-1.035L18.354 5.9zM6.905 21.13L3.347 7.85l-1.932.517a1 1 0 0 1-1.224-.707l-.26-.966A1 1 0 0 1 .64 5.47l1.932-.517 1.932-.518a1 1 0 0 1 1.224.707L9.534 19.35a7.33 7.33 0 0 0-2.629 1.78zM19.34 24.27l9.47-2.537a1 1 0 0 1 1.224.707l.259.966a1 1 0 0 1-.707 1.225l-10.088 2.703a7.306 7.306 0 0 0-.158-3.064zm-9.566-3.11a5.59 5.59 0 1 1 4.942 10.028 5.59 5.59 0 0 1-4.942-10.027zm3.41 6.921a2.128 2.128 0 0 0 .968-2.846 2.128 2.128 0 0 0-2.847-.967 2.128 2.128 0 0 0-.967 2.846 2.128 2.128 0 0 0 2.846.967z" fill="currentColor"/>
                    </svg>
                    <h3 class="text-white text-center text-sm font-medium">
                        Select From Over 500,000 <br> Industrial Suppliers
                    </h3>
                    </a>
                    <a class="absolute inset-0 opacity-0 group-hover:opacity-100 shadow-[0_2px_4px_rgba(0,0,0,0.5)] bg-[#21435E] flex items-center justify-center text-white text-sm px-6 py-4 transition">
                    Find and evaluate OEMs, Custom Manufacturers, Service Companies and Distributors.
                    </a>
                </li>

                <!-- Item 2 -->
                <li class="relative group">
                    <a class="flex flex-col items-center gap-5">
                    <svg viewBox="0 0 32 32" class="shadow-[0_2px_4px_rgba(0,0,0,0.5)] text-white p-4 rounded-full w-20 h-20">
                        <path d="M24.026 10.301c.267.26.497.614.688 1.06.19.447.286.856.286 1.228v16.072c0 .372-.134.688-.401.948s-.592.391-.974.391H4.375c-.382 0-.707-.13-.974-.39A1.275 1.275 0 0 1 3 28.66V6.34c0-.373.134-.689.401-.95.267-.26.592-.39.974-.39h12.833c.382 0 .802.093 1.26.279.46.186.822.41 1.09.67l4.468 4.352zM6 9v6h6V9H6zm0 8v2h16v-2H6zm0 4v2h16v-2H6zm0 4v2h16v-2H6zm11.807-12.616V8.61a3.517 3.517 0 0 0-2.032.96 3.307 3.307 0 0 0 0 4.783c.681.66 1.575.99 2.468.99.894 0 1.787-.33 2.468-.99.57-.553.899-1.25.992-1.969h-3.896zm1.24-1.151h2.49a2.507 2.507 0 0 0-2.491-2.49v2.49z" fill="currentColor"/>
                    </svg>
                    <h3 class="text-white text-center text-sm font-medium">
                        Receive Daily <br> Industry Updates
                    </h3>
                    </a>
                    <a class="absolute inset-0 opacity-0 group-hover:opacity-100 shadow-[0_2px_4px_rgba(0,0,0,0.5)] bg-[#21435E] flex items-center justify-center text-white text-sm px-6 py-4 transition">
                    Stay up to date on industry news and trends, product announcements and the latest innovations.
                    </a>
                </li>

                <!-- You can copy same pattern for Item 3 + Item 4 with their respective SVGs -->
                <li class="relative group">
                    <a class="flex flex-col items-center gap-5">
                    <svg viewBox="0 0 32 32" class="shadow-[0_2px_4px_rgba(0,0,0,0.5)] text-white p-4 rounded-full w-20 h-20">
                    <path d="M29.63 19.282a2.25 2.25 0 0 0-.644-1.852L15.99 4.433a4.006 4.006 0 0 0-2.882-1.191l-6.45.06a3.375 3.375 0 0 0-.515.045A3.21 3.21 0 0 1 8.93 1.69l6.228-.059a3.868 3.868 0 0 1 2.783 1.15l12.548 12.55a2.198 2.198 0 0 1-.018 3.11l-.84.84z" fill="white" data-sentry-element="path" data-sentry-source-file="Icons.tsx"></path><path d="M5.843 8.034a1.541 1.541 0 0 1 2.18-.017 1.54 1.54 0 0 1-.018 2.179c-.607.606-1.583.614-2.18.018a1.542 1.542 0 0 1 .018-2.18M3.561 5.752a3.21 3.21 0 0 1 2.248-.941l6.227-.059a3.868 3.868 0 0 1 2.783 1.15l12.55 12.55a2.198 2.198 0 0 1-.02 3.11l-7.986 7.987a2.197 2.197 0 0 1-3.11.018L3.704 17.018a3.868 3.868 0 0 1-1.15-2.783l.059-6.228c.008-.875.37-1.676.948-2.255zM18.876 13.4l-1.148.77a4.796 4.796 0 0 0-.787-.314l-.235-1.368a.302.302 0 0 0-.283-.22h-1.384a.302.302 0 0 0-.283.22l-.252 1.352a3.35 3.35 0 0 0-.77.33l-1.148-.801c-.095-.063-.268-.048-.363.047l-.959.959c-.094.094-.11.268-.047.362l.786 1.132a5.336 5.336 0 0 0-.345.818l-1.369.267c-.125 0-.236.142-.236.268l.016 1.368c0 .126.094.252.22.283l1.384.252c.063.283.189.534.315.786l-.771 1.148c-.078.11-.078.267.016.361l.975.975c.094.095.251.095.361.016l1.149-.77c.251.125.519.236.802.33l.251 1.352c.032.126.142.236.268.236h1.384a.27.27 0 0 0 .267-.236l.267-1.368c.267-.078.535-.189.803-.33l1.132.786c.094.064.267.047.346-.031l.975-.975c.094-.094.11-.267.047-.362l-.802-1.147c.125-.253.251-.504.33-.771l1.337-.268a.27.27 0 0 0 .236-.267l-.016-1.368c.015-.142-.094-.252-.22-.283l-1.353-.252a4.798 4.798 0 0 0-.314-.786l.77-1.148a.264.264 0 0 0-.031-.346l-.975-.975a.264.264 0 0 0-.346-.032z" fill="white" data-sentry-element="path" data-sentry-source-file="Icons.tsx"></path><path d="M17.382 19.58a2.373 2.373 0 0 1-3.334 0 2.359 2.359 0 0 1 0-3.333 2.345 2.345 0 0 1 3.334 0 2.358 2.358 0 0 1 0 3.334" fill="white" data-sentry-element="path" data-sentry-source-file="Icons.tsx"></path>
                    </svg>
                    <h3 class="text-white text-center text-sm font-medium">
                        Receive Daily <br> Industry Updates
                    </h3>
                    </a>
                    <a class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-[#21435E] shadow-[0_2px_4px_rgba(0,0,0,0.5)] flex items-center justify-center text-white text-sm px-6 py-4 transition">
                    Stay up to date on industry news and trends, product announcements and the latest innovations.
                    </a>
                </li>
                <li class="relative group">
                    <a class="flex flex-col items-center gap-5">
                    <svg viewBox="0 0 32 32" class="shadow-[0_2px_4px_rgba(0,0,0,0.5)] text-white p-4 rounded-full w-20 h-20">
                    <g fill="currentColor" fill-rule="evenodd" data-sentry-element="g" data-sentry-source-file="Icons.tsx"><path d="M16.553 2.22L28.5 7.512 23.03 10.22l-6.668-2.66a.482.482 0 0 0-.413-.003l-6.59 2.663-5.6-2.708 11.985-5.293a1 1 0 0 1 .81 0zM8.638 21.82a.492.492 0 0 0 .114.163.79.79 0 0 0 .098.068l5.895 2.65v5.445l-11.09-5.283a1 1 0 0 1-.57-.903V9.432l5.51 2.778v9.427c.014.1.025.142.043.182z" data-sentry-element="path" data-sentry-source-file="Icons.tsx"></path><path d="M15.094 17.1l-4.433 2.15v-6.775l4.433-2.149zM16.136 18.86l4.603 2.068-4.603 2.23-4.603-2.23z" data-sentry-element="path" data-sentry-source-file="Icons.tsx"></path><path d="M28.71 24.892l-11.203 5.284V24.73l6.012-2.65a.52.52 0 0 0 .194-.19.567.567 0 0 0 .052-.168c.004-.03.006-3.19.008-9.483l5.51-2.777v14.525a1 1 0 0 1-.573.905z" data-sentry-element="path" data-sentry-source-file="Icons.tsx"></path><path d="M21.605 12.475v6.774l-4.434-2.148v-6.775z" data-sentry-element="path" data-sentry-source-file="Icons.tsx"></path></g>
                    </svg>
                    <h3 class="text-white text-center text-sm font-medium">
                        Receive Daily <br> Industry Updates
                    </h3>
                    </a>
                    <a class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-[#21435E] shadow-[0_2px_4px_rgba(0,0,0,0.5)] flex items-center justify-center text-white text-sm px-6 py-4 transition">
                    Stay up to date on industry news and trends, product announcements and the latest innovations.
                    </a>
                </li>

                </ul>

                <!-- Footer -->
                <footer class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="bg-[#0D6AED] text-white text-sm px-4 py-2 rounded-sm flex items-center gap-2 hover:bg-blue-600 transition">
                        🔍 Start Sourcing Suppliers
                    </button>
                    <button class="border border-white text-white text-sm px-4 py-2 rounded flex items-center gap-2 hover:bg-white hover:text-blue-900 transition">
                        Claim Your Company Profile →
                    </button>
                </footer>
        </div>
    </section>
<section class="bg-[#092C47] text-white py-10 px-5">
    <div class="flex flex-col md:flex-row md:justify-evenly gap-8 md:gap-0">
        <div class="space-y-3">
            <div class="font-semibold">
                <span>For Buyers</span>
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
        <span class="px-5">Copyright © 2025 SPANZ Publishing Company. All Rights Reserved. See Terms And Conditions, Privacy Statement and California Do Not Track Notice. Website Last Motified September 3, 2025.
            SPANZ Register and SPANZ Regional are part of spanz.Com. SPANZ is a registered trademark of SPANZ Publishing Company.
        </span>
    </div>
</section>
<script>
  const menuBtn = document.getElementById("menu-btn");
  const mobileMenu = document.getElementById("mobile-menu");

  menuBtn.addEventListener("click", () => {
    mobileMenu.classList.toggle("hidden");
  });

  function handleHomeSearch(e) {
    e.preventDefault();
    const type = document.getElementById('home-search-type')?.value || 'tenders';
    const query = document.getElementById('home-search-input')?.value || '';
    if (!query.trim()) {
      // navigate to listing page if no query
      window.location.href = type === 'products' ? "{{ route('products.search') }}" : "{{ route('tenders.search') }}";
      return false;
    }
    if (type === 'products') {
      const url = new URL("{{ route('products.search') }}", window.location.origin);
      url.searchParams.set('q', query);
      window.location.href = url.toString();
    } else {
      const url = new URL("{{ route('tenders.search') }}", window.location.origin);
      url.searchParams.set('search', query);
      window.location.href = url.toString();
    }
    return false;
  }

  // Pricing Cards Functionality
  document.addEventListener('DOMContentLoaded', function() {
    initializeSubscriptionCards();
    clearStaleLocalStorage();
    checkSubscriptionStatus();
    checkDowngradeRequestStatus();
    
    // Check if subscription modal should be opened from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('modal') === 'subscription') {
      // Small delay to ensure modal is rendered
      setTimeout(function() {
        if (typeof openSubscriptionModal === 'function') {
          openSubscriptionModal();
        }
      }, 300);
    }
  });

  function clearStaleLocalStorage() {
    // Clear any stale localStorage entries that don't have corresponding database records
    const cards = document.querySelectorAll('.subscription-card');
    cards.forEach(card => {
      const subscriptionId = card.getAttribute('data-subscription-id');
      if (subscriptionId) {
        // We'll let the server response determine if localStorage should be cleared
        // This function is just a placeholder for future cleanup logic
      }
    });
  }

  function initializeSubscriptionCards() {
    const cards = document.querySelectorAll('.subscription-card');

    // Handle card clicks for selection
    cards.forEach(card => {
      card.addEventListener('click', function() {
        // Remove active class from all cards
        cards.forEach(c => c.classList.remove('active'));

        // Add active class to clicked card
        this.classList.add('active');

        // Hide "MOST POPULAR" badge on all cards
        const badges = document.querySelectorAll('.most-popular-badge');
        badges.forEach(badge => badge.style.display = 'none');
      });
    });
  }

  function requestSubscription(subscriptionId, planName, button) {
    console.log('Requesting subscription:', { subscriptionId, planName });
    console.log('Subscription ID type:', typeof subscriptionId);
    console.log('Subscription ID value:', subscriptionId);

    // Check if user is authenticated by looking for auth indicators in the page
    const isAuthenticated = document.querySelector('a[href*="logout"]') !== null ||
                           document.querySelector('a[href*="dashboard"]') !== null;

    if (!isAuthenticated) {
      // Redirect to login page with current URL as redirect
      window.location.href = `{{ route('login') }}?redirect=${encodeURIComponent(window.location.href)}`;
      return;
    }

    // Test if basic routing is working first
    fetch('/test-subscription-route')
      .then(response => response.json())
      .then(data => {
        console.log('Test route response:', data);
      })
      .catch(error => {
        console.error('Test route error:', error);
      });

    // Show loading state
    const originalText = button.textContent;
    button.textContent = 'Processing...';
    button.disabled = true;
    button.classList.add('opacity-75', 'cursor-not-allowed');

    // Set a flag to prevent status checks from interfering
    window.subscriptionRequestInProgress = true;

    // Log the request start
    console.log('Starting subscription request for:', subscriptionId);

    // Use the fallback route that doesn't use model binding
    const url = `/subscription-requests-by-id/${subscriptionId}`;
    console.log('Making request to:', url);

    // Make AJAX request
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => {
      console.log('Response status:', response.status);
      console.log('Response headers:', response.headers);

      if (!response.ok) {
        if (response.status === 401) {
          throw new Error('You must be logged in to request a subscription. Please login first.');
        } else if (response.status === 404) {
          throw new Error('Subscription not found.');
        } else if (response.status === 500) {
          throw new Error('Server error. Please try again later.');
        } else {
          throw new Error('Request failed. Please try again.');
        }
      }

      // Check if response is JSON
      const contentType = response.headers.get('content-type');
      if (!contentType || !contentType.includes('application/json')) {
        console.error('Response is not JSON, content-type:', contentType);
        // Let's see what the actual response is
        return response.text().then(text => {
          console.error('Server response (HTML):', text.substring(0, 500));
          throw new Error('Server returned HTML instead of JSON. Please check server logs.');
        });
      }

      return response.json();
    })
    .catch(error => {
      console.error('JSON parsing error:', error);
      throw new Error('Server returned invalid response. Please try again.');
    })
    .then(data => {
      console.log('Subscription request response:', data);
      if (data.success) {
        // Show success message
        showNotification('Subscription request submitted successfully!', 'success');
        console.log('Subscription request successful');

        // Update button state to "Requested" immediately (don't close modal)
        button.textContent = 'Requested';
        button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'opacity-75', 'hover:scale-105');
        button.classList.add('bg-yellow-500', 'cursor-not-allowed');
        button.disabled = true;

        // Force the styling to be applied immediately
        button.style.backgroundColor = '#eab308';
        button.style.color = 'white';
        button.style.cursor = 'not-allowed';

        // Store the request state in localStorage
        localStorage.setItem(`subscription_request_${subscriptionId}`, 'requested');

        // Prevent any other status checks from overriding this state
        const card = button.closest('.subscription-card');
        if (card) {
          card.setAttribute('data-just-requested', 'true');
        }

        // Disable all other subscription cards
        disableAllOtherSubscriptionCards(subscriptionId);

        // Clear the request in progress flag
        window.subscriptionRequestInProgress = false;

      } else {
        showNotification(data.message || 'An error occurred. Please try again.', 'error');
        resetButtonToOriginal(button, originalText);
        window.subscriptionRequestInProgress = false;
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showNotification(error.message || 'An error occurred. Please try again.', 'error');
      resetButtonToOriginal(button, originalText);
      window.subscriptionRequestInProgress = false;
    });
  }

  function updateButtonToRequested(button, subscriptionId) {
    // Update button text
    button.textContent = 'Requested';

    // Remove all existing classes that might interfere
    button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'opacity-75', 'hover:scale-105', 'bg-yellow-500', 'cursor-not-allowed');

    // Add new classes for requested state
    button.classList.add('bg-yellow-500', 'cursor-not-allowed');

    // Set disabled state
    button.disabled = true;

    // Force the styling to be applied immediately with !important
    button.style.setProperty('background-color', '#eab308', 'important');
    button.style.setProperty('color', 'white', 'important');
    button.style.setProperty('cursor', 'not-allowed', 'important');
    button.style.setProperty('opacity', '1', 'important');

    // Store the request state in localStorage
    localStorage.setItem(`subscription_request_${subscriptionId}`, 'requested');

    // Update the card's data attribute for consistency
    const card = button.closest('.subscription-card');
    if (card) {
      card.setAttribute('data-request-status', 'requested');
      card.setAttribute('data-just-requested', 'true');
    }

    // Log for debugging
    console.log('Button updated to Requested for subscription:', subscriptionId);

    // Force a re-render to ensure the changes are visible
    button.offsetHeight; // Trigger reflow
  }

  function resetButtonToOriginal(button, originalText) {
    // Reset button to original state
    button.textContent = originalText;
    button.disabled = false;
    button.classList.remove('opacity-75', 'cursor-not-allowed', 'bg-yellow-500', 'bg-green-500', 'bg-gray-400');
    button.classList.add('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'hover:scale-105');

    // Reset inline styles
    button.style.removeProperty('background-color');
    button.style.removeProperty('color');
    button.style.removeProperty('cursor');
    button.style.removeProperty('opacity');
  }

  function checkLocalStorageStatus() {
    // Don't check status if a request is in progress
    if (window.subscriptionRequestInProgress) {
      console.log('Skipping localStorage check - request in progress');
      return;
    }

    const cards = document.querySelectorAll('.subscription-card');
    console.log('Checking localStorage status for', cards.length, 'cards');
    let hasRequestedCard = false;
    let requestedSubscriptionId = null;

    cards.forEach(card => {
      const subscriptionId = card.getAttribute('data-subscription-id');
      const button = card.querySelector('button');

      // Skip if this card was just requested (don't override the state)
      if (card.getAttribute('data-just-requested') === 'true') {
        return;
      }

      // Also skip if button is already showing "Requested"
      if (button && button.textContent === 'Requested') {
        return;
      }

      if (button) {
        console.log('Checking subscription', subscriptionId, 'button text:', button.textContent);

        // Check for requested status in localStorage
        const requestStatus = localStorage.getItem(`subscription_request_${subscriptionId}`);
        console.log('localStorage status for', subscriptionId, ':', requestStatus);

        if (requestStatus === 'requested') {
          hasRequestedCard = true;
          requestedSubscriptionId = subscriptionId;

          console.log('Setting button to requested for subscription', subscriptionId);
          updateButtonToRequested(button, subscriptionId);
        }

        // Check for current plan buttons and apply styling immediately
        if (button.textContent === 'Current Plan') {
          if (button.classList.contains('bg-gray-400')) {
            button.style.setProperty('background-color', '#9ca3af', 'important');
          } else if (button.classList.contains('bg-green-500')) {
            button.style.setProperty('background-color', '#10b981', 'important');
          }
          button.style.setProperty('color', 'white', 'important');
          button.style.setProperty('cursor', 'not-allowed', 'important');
        }
      }
    });

    // If there's a requested card, disable all other cards
    if (hasRequestedCard && requestedSubscriptionId) {
      disableAllOtherSubscriptionCards(requestedSubscriptionId);
    }
  }

  function checkSubscriptionStatus() {
    // Check actual status from server first
    fetch('/subscription-requests/status')
      .then(response => {
        if (!response.ok) {
        console.log('Server status check failed, relying on localStorage');
        return {};
      }
      return response.json();
    })
    .then(statuses => {
      const cards = document.querySelectorAll('.subscription-card');
      let hasPendingRequest = false;
      let pendingSubscriptionId = null;

      cards.forEach(card => {
        const subscriptionId = card.getAttribute('data-subscription-id');
        const status = statuses[subscriptionId];
        const button = card.querySelector('button');

        // Clear localStorage for this subscription if no status from server
        if (!status) {
          localStorage.removeItem(`subscription_request_${subscriptionId}`);
        }

        if (status === 'pending') {
          hasPendingRequest = true;
          pendingSubscriptionId = subscriptionId;

          button.textContent = 'Requested';
          button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'hover:scale-105');
          button.classList.add('bg-yellow-500', 'cursor-not-allowed');
          button.disabled = true;

          // Force the styling to be applied immediately
          button.style.backgroundColor = '#eab308';
          button.style.color = 'white';
          button.style.cursor = 'not-allowed';

          // Update localStorage to match server state
          localStorage.setItem(`subscription_request_${subscriptionId}`, 'requested');
        } else if (status === 'approved') {
          button.textContent = 'Current Plan';
          button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'hover:scale-105');
          button.classList.add('bg-green-500', 'cursor-not-allowed');
          button.disabled = true;

          // Force the styling to be applied immediately
          button.style.backgroundColor = '#10b981';
          button.style.color = 'white';
          button.style.cursor = 'not-allowed';

          // Update localStorage to match server state
          localStorage.setItem(`subscription_request_${subscriptionId}`, 'approved');
        } else if (status === 'declined') {
          button.textContent = 'Choose Plan';
          button.classList.remove('bg-yellow-500', 'bg-green-500', 'cursor-not-allowed');
          button.classList.add('bg-[#092C48]', 'hover:bg-[#0D6AED]');
          button.disabled = false;

          // Clear localStorage for declined requests
          localStorage.removeItem(`subscription_request_${subscriptionId}`);

          // Re-enable all cards when a request is declined
          enableAllSubscriptionCards();
        } else {
          // No status from server, reset button to default
          button.textContent = 'Choose Plan';
          button.classList.remove('bg-yellow-500', 'bg-green-500', 'cursor-not-allowed');
          button.classList.add('bg-[#092C48]', 'hover:bg-[#0D6AED]');
          button.disabled = false;

          // Clear localStorage
          localStorage.removeItem(`subscription_request_${subscriptionId}`);
        }
      });

      // If there's a pending request, disable all other cards
      if (hasPendingRequest && pendingSubscriptionId) {
        disableAllOtherSubscriptionCards(pendingSubscriptionId);
      }
    })
    .catch(error => {
      console.error('Error checking subscription status:', error);
      // If server check fails, fall back to localStorage
      checkLocalStorageStatus();
    });
  }

  function checkDowngradeRequestStatus() {
    // Check downgrade request status
    fetch('/downgrade-requests/status')
      .then(response => {
        if (!response.ok) {
          console.log('Downgrade request status check failed');
          return {};
        }
        return response.json();
      })
      .then(data => {
        if (data.status === 'pending') {
          // If there's a pending downgrade request, disable all subscription cards
          const cards = document.querySelectorAll('.subscription-card');
          cards.forEach(card => {
            const button = card.querySelector('button');
            if (button && !button.disabled && button.textContent !== 'Current Plan') {
              button.textContent = 'Request Pending';
              button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'hover:scale-105');
              button.classList.add('bg-gray-400', 'cursor-not-allowed');
              button.disabled = true;

              // Force the styling to be applied immediately
              button.style.backgroundColor = '#9ca3af';
              button.style.color = 'white';
              button.style.cursor = 'not-allowed';

              // Add a visual indicator that this card is disabled
              card.style.opacity = '0.6';
              card.style.pointerEvents = 'none';
            }
          });
        }
      })
      .catch(error => {
        console.error('Error checking downgrade request status:', error);
      });
  }

  function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
      type === 'success' ? 'bg-green-600' : 'bg-red-600'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
      notification.remove();
    }, 5000);
  }

  // Function to clear all subscription request states from localStorage
  function clearAllSubscriptionStates() {
    const cards = document.querySelectorAll('.subscription-card');
    cards.forEach(card => {
      const subscriptionId = card.getAttribute('data-subscription-id');
      localStorage.removeItem(`subscription_request_${subscriptionId}`);
    });

    // Reset all buttons to default state
    const buttons = document.querySelectorAll('.subscription-card button');
    buttons.forEach(button => {
      button.textContent = 'Choose Plan';
      button.classList.remove('bg-yellow-500', 'bg-green-500', 'cursor-not-allowed');
      button.classList.add('bg-[#092C48]', 'hover:bg-[#0D6AED]');
      button.disabled = false;
    });

    console.log('All subscription states cleared from localStorage');
  }

  // Function to disable all other subscription cards when one is requested
  function disableAllOtherSubscriptionCards(requestedSubscriptionId) {
    const cards = document.querySelectorAll('.subscription-card');

    cards.forEach(card => {
      const subscriptionId = card.getAttribute('data-subscription-id');
      const button = card.querySelector('button');

      // Skip the card that was just requested
      if (subscriptionId === requestedSubscriptionId) {
        return;
      }

      // Disable all other cards
      if (button && !button.disabled) {
        button.textContent = 'Request Pending';
        button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'hover:scale-105');
        button.classList.add('bg-gray-400', 'cursor-not-allowed');
        button.disabled = true;

        // Force the styling to be applied immediately
        button.style.backgroundColor = '#9ca3af';
        button.style.color = 'white';
        button.style.cursor = 'not-allowed';

        // Add a visual indicator that this card is disabled
        card.style.opacity = '0.6';
        card.style.pointerEvents = 'none';
      }
    });

    console.log('All other subscription cards disabled due to pending request');
  }

  // Function to re-enable all subscription cards (when request is declined or cancelled)
  function enableAllSubscriptionCards() {
    const cards = document.querySelectorAll('.subscription-card');

    cards.forEach(card => {
      const button = card.querySelector('button');

      // Only re-enable cards that are not the current plan or already requested
      if (button && button.textContent === 'Request Pending') {
        button.textContent = 'Request Subscription';
        button.classList.remove('bg-gray-400', 'cursor-not-allowed');
        button.classList.add('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'hover:scale-105');
        button.disabled = false;

        // Reset inline styles
        button.style.removeProperty('background-color');
        button.style.removeProperty('color');
        button.style.removeProperty('cursor');

        // Re-enable card interactions
        card.style.opacity = '1';
        card.style.pointerEvents = 'auto';
      }
    });

    console.log('All subscription cards re-enabled');
  }

  // Make the function available globally for debugging
  window.clearAllSubscriptionStates = clearAllSubscriptionStates;
  window.disableAllOtherSubscriptionCards = disableAllOtherSubscriptionCards;
  window.enableAllSubscriptionCards = enableAllSubscriptionCards;
</script>

@include('components.subscription-modal', ['subscriptions' => $subscriptions ?? collect()])

</body>
</html>
