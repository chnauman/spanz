<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Spanz</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
    <style>
        :root {
            --thomas-navy: #032747;
            --thomas-blue: #0d6efd;
            --thomas-text: #13283f;
            --thomas-bg: #f4f7fb;
            --thomas-border: #d9e1ec;
        }

        body.thomas-home {
            background: var(--thomas-bg);
            color: var(--thomas-text);
        }

        .thomas-hero {
            position: relative;
            background-blend-mode: multiply;
            background-color: rgba(3, 39, 71, 0.78);
        }

        .thomas-hero nav {
            background: rgba(3, 39, 71, 0.92);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(4px);
        }

        .thomas-hero .hero-brand {
            letter-spacing: 0.5px;
        }

        .thomas-hero .hero-search-shell {
            background: #fff;
            border: 1px solid var(--thomas-border);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 16px 28px rgba(3, 39, 71, 0.18);
        }

        .thomas-hero .hero-search-shell input {
            height: 46px;
            border: 0;
            outline: none;
        }

        .thomas-hero .hero-search-shell button {
            height: 46px;
            border-radius: 0;
            font-weight: 600;
            background: var(--thomas-blue);
        }

        /* Hero title + tagline: one sans stack (system UI / Segoe on Windows), equal type size */
        .thomas-hero .hero-headline {
            font-family: ui-sans-serif, system-ui, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: clamp(1.75rem, 3.2vw + 0.5rem, 2.75rem);
            line-height: 1.2;
        }

        .thomas-card {
            background: #fff;
            border: 1px solid var(--thomas-border);
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(3, 39, 71, 0.06);
        }

        .thomas-section-heading {
            color: #0e2f4f;
            font-weight: 700;
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
            width: 3in;
            min-width: 3in;
            max-width: 3in;
            flex: 0 0 3in;
        }

        /* Requested button styling - always visible */
        .subscription-card button.bg-yellow-500 {
            background-color: #eab308 !important;
            color: white !important;
            cursor: not-allowed !important;
        }

        /* Current Plan button styling - always visible */
        .subscription-card button.bg-gray-400 {
            background-color: #9ca3af !important;
            color: white !important;
            cursor: not-allowed !important;
        }

        /* Current Plan button styling for green variant */
        .subscription-card button.bg-green-500 {
            background-color: #10b981 !important;
            color: white !important;
            cursor: not-allowed !important;
        }
    </style>
</head>
<body class="thomas-home">
   <div class="thomas-hero bg-image bg-cover bg-center" style="background-image:url('{{ asset('spanz-img/spanz-bg.jpg') }}')">
        <!-- Navbar -->
        <nav class="bg-image bg-cover bg-center" style="background-image:url('{{ asset('spanz-img/spanz-bg.jpg') }}') absolute top-0 left-0 w-full z-50">
            <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}" class="hero-brand block text-white text-3xl font-extrabold italic tracking-widest leading-none" style="font-family: 'Eurostile', 'Orbitron', 'Arial Black', sans-serif;">
                            SPANZ
                        </a>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex space-x-6">
                        <!-- For Buyers Dropdown -->
                        <div class="dropdown-group">
                            <button class="text-white hover:text-blue-400 flex items-center text-lg font-bold">
                                For Buyers ▾
                            </button>
                            <div class="dropdown-menu">
                                <div class="py-1 whitespace-nowrap">
                                    @auth
                                        @if(!auth()->user()->isAdmin())
                                            <a href="{{ route('tenders.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Post a RFX</a>
                                            <a href="{{ route('tenders.saved') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Saved RFXs</a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.create')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Post a RFX</a>
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.saved')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Saved RFXs</a>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <!-- For Suppliers Dropdown -->
                        <div class="dropdown-group">
                            <button class="text-white hover:text-blue-400 flex items-center text-lg font-bold">
                                For Suppliers ▾
                            </button>
                            <div class="dropdown-menu">
                                <div class="py-1 whitespace-nowrap">
                                    @auth
                                        @if(auth()->user()->isSupplier() || auth()->user()->isSubSupplier())
                                            <a href="{{ route('tenders.saved') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Saved Tenders</a>
                                            <a href="{{ route('user.interests') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Interests</a>
                                            <a href="{{ route('tenders.viewed') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Viewed Tenders</a>
                                        @else
                                            <a href="#" onclick="openSubscriptionModal(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Become a Supplier</a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('home')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Become a Supplier</a>
                                    @endauth

                                </div>
                            </div>
                        </div>

                        <a href="#" class="text-white hover:text-blue-400 text-lg font-bold">About</a>
                        <a href="#" onclick="openSubscriptionModal(); return false;" class="text-white hover:text-blue-400 text-lg font-bold">Pricing</a>
                    </div>

                    <!-- Right Actions -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('tenders.search') }}" class="text-white hover:text-blue-400 text-lg font-bold">Tenders</a>
                        <a href="{{ route('products.search') }}" class="text-white hover:text-blue-400 text-lg font-bold">Products</a>
                        <a href="{{ route('suppliers.directory') }}" class="text-white hover:text-blue-400 text-lg font-bold">Suppliers' Directory</a>
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
                        <a href="{{ route('tenders.create') }}" class="block pl-4 hover:text-blue-300">Post a RFX</a>
                        <a href="{{ route('tenders.saved') }}" class="block pl-4 hover:text-blue-300">Saved RFXs</a>
                    @else
                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.create')) }}" class="block pl-4 hover:text-blue-300">Post a RFX</a>
                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.saved')) }}" class="block pl-4 hover:text-blue-300">Saved RFXs</a>
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
                            <a href="{{ route('tenders.invitations') }}" class="block pl-4 hover:text-blue-300">Invitations</a>
                        @else
                            <a href="#" onclick="openSubscriptionModal(); return false;" class="block pl-4 hover:text-blue-300">Become a Supplier</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}?redirect={{ urlencode(route('home')) }}" class="block pl-4 hover:text-blue-300">Become a Supplier</a>
                    @endauth
                </div>

                <a href="#" class="block hover:text-blue-300">About</a>
                <a href="#" onclick="openSubscriptionModal(); return false;" class="block hover:text-blue-300">Pricing</a>
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
                    <h2 class="hero-headline text-[#0D6AED] font-bold tracking-tight">SPANZ</h2>
                    <p class="hero-headline text-white mt-2 font-medium" style="font-family: Georgia, 'Times New Roman', serif;">ANZ Supplier Panel. Global Reach</p>

                    <!-- Search row: mobile stacked, sm inline -->
                    <form id="home-search-form" class="hero-search-shell mt-5 flex flex-col sm:flex-row items-stretch sm:items-center gap-0 sm:gap-0 justify-center max-w-3xl mx-auto" onsubmit="return handleHomeSearch(event)">
                        <!-- input -->
                        <input id="home-search-input" type="search" placeholder="Search Open Tenders, RFQs, RFPs, EOIs" class="w-full sm:w-[34rem] pl-5 pr-3 py-2 text-gray-700 focus:outline-none"/>

                        <!-- search button -->
                        <div class="w-full sm:w-auto">
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-[#0D6AED] text-white">Search</button>
                        </div>
                    </form>

                    <!-- CTA row below search -->
                    <div class="flex flex-col p-6 pb-14 justify-center gap-1 flex-wrap items-center" style="font-family: Georgia, 'Times New Roman', serif;">
                        <span class="text-white text-sm sm:text-2xl">Capture - Projects, Tenders, RFQs, RFPs &amp; More.</span>
                        <p class="text-white text-xs sm:text-base"><span class="font-bold">FREE</span> - no lock in contract or credit card required.</p>
                    </div>
                </div>
            </div>
    </div>
    <section class="border-y border-slate-200/70 bg-[#f0f5f9]" aria-label="Platform tagline">
        <div class="mx-auto max-w-4xl px-5 py-10 sm:px-8 sm:py-14 text-center">
            <h2 class="text-[clamp(2rem,3.2vw+0.8rem,3.1rem)] font-semibold leading-[1.2] tracking-[-0.01em] text-[#0f2d52] antialiased" style="font-family: Georgia, 'Times New Roman', serif;">
                Connecting Qualified Buyers &amp; Suppliers. Globally
            </h2>
        </div>
    </section>

    <!-- Buyers Section -->
    <div class="flex flex-col-reverse lg:flex-row justify-evenly items-center px-5 lg:px-20 py-16 gap-8 lg:gap-14">
        <!-- Left Text Section -->
        <div class=" text-center lg:text-left mt-8 lg:mt-0">
            <span class="bg-blue-100 text-[#0D6AED] px-5 py-1.5 rounded-full inline-block text-sm sm:text-base font-semibold">For Buyers</span>
            <h1 class="text-3xl sm:text-4xl lg:text-6xl font-semibold text-blue-950 py-6 mx-auto lg:mx-0 lg:w-[36rem] leading-tight" style="font-family: Georgia, 'Times New Roman', serif;">Get Qualified Quotes.</h1>
            <ul class="list-disc pl-6 lg:pl-7 ml-4 lg:ml-0 text-blue-950 space-y-3 text-left inline-block lg:block text-lg sm:text-xl">
                <li>Expand your supplier network across local & global markets</li>
                <li>Designed for speed, simplicity and results</li>
                <li>No purchase request is too Small - or too Big</li>
                <li>Free to join - no complex setup or credit card required</li>
                <li>Platform built on collaboration, quality, and support</li>
            </ul>
            <button class="bg-[#0D6AED] text-white px-6 py-3 mt-7 rounded-lg w-full sm:w-auto sm:min-w-[220px] text-lg font-semibold hover:bg-[#0B5ED7] transition-colors duration-200">Post RFX, its free</button>
        </div>
        <!-- Right Image Section -->
        <div class="w-full lg:w-1/2 flex justify-center mt-8 lg:mt-0">
            <img src="{{ asset('spanz-img/home-hero-industrial-team.png') }}"
                 onerror="this.onerror=null;this.src='{{ url('public/spanz-img/home-hero-industrial-team.png') }}';"
                 alt="For Buyers"
                 class="w-full max-w-lg sm:max-w-2xl lg:w-[38rem]">
        </div>
    </div>

    <!-- Suppliers Section -->
    <div class="flex flex-col-reverse lg:flex-row justify-evenly items-center px-5 lg:px-20 py-16">
        <!-- Left: Image -->
        <div class="flex mt-8 lg:mt-0">
            <img src="{{ asset('images/home-for-suppliers.png') }}"
                 onerror="this.onerror=null;this.src='{{ url('public/images/home-for-suppliers.png') }}';"
                 alt="For Suppliers"
class="w-full max-w-lg sm:max-w-2xl lg:w-[38rem]">
        </div>
        <!-- Right: Text -->
        <div class="w-full lg:w-1/2 text-center flex justify-center lg:justify-start lg:text-left mt-8 lg:mt-0 lg:pl-12">
            <div>
                <span class="bg-blue-100 text-[#0D6AED] px-5 py-1.5 rounded-full inline-block text-sm sm:text-base font-semibold">For Suppliers</span>
                <h1 class="text-3xl sm:text-4xl lg:text-6xl font-semibold text-blue-950 py-6 mx-auto lg:mx-0 lg:w-[36rem] leading-tight" style="font-family: Georgia, 'Times New Roman', serif;">Capture Buyer Leads & RFXs</h1>
                <ul class="list-disc pl-6 lg:pl-7 ml-4 lg:ml-0 text-blue-950 space-y-3 text-left inline-block lg:block text-lg sm:text-xl">
                    <li>Capture business leads without heavy marketing spend</li>
                    <li>Discover and engage verified, credible buyers</li>
                    <li>Promote your business capabilities to ANZ and global buyers</li>
                    <li>Free to join - no complex setup or credit card required.</li>
                    <li>Simple, intuitive, and user-friendly interface</li>
                </ul>

                <button class="bg-[#0D6AED] text-white px-6 py-3 mt-7 rounded-lg w-full sm:w-auto sm:min-w-[220px] text-lg font-semibold hover:bg-[#0B5ED7] transition-colors duration-200">Join the Pannel</button>
            </div>
        </div>
    </div>

    {{--
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
            <button class="bg-[#0D6AED] text-white px-4 py-2 rounded-lg w-full md:w-auto hover:bg-[#0B5ED7] transition-colors duration-200">
                Learn More About Us
            </button>
        </div>
    </div>
    --}}
    <div class="bg-gray-100 mt-10 pb-10">
        <div class="flex justify-center">
            <h1 class="text-3xl sm:text-4xl my-10 lg:text-5xl font-bold text-blue-950 py-5 mx-auto lg:mx-0">Browse RFX Categories</h1>
        </div>
        <!-- grid layout for all categories -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 w-full px-5 md:px-28 lg:px-28 ml-auto mr-auto">
        @foreach($categories as $category)
            <div>
                <div class="pb-4 {{ $category->name === 'Custom Manufacturing & Fabricating' ? 'w-44' : '' }}">
                    <h2>
                        <a href="{{ route('tenders.search', ['category' => [$category->id]]) }}" class="text-lg sm:text-xl font-bold leading-snug hover:text-[#0D6AED]">
                            {{ $category->name }}
                        </a>
                        <span class="text-sm font-semibold text-gray-600">
                            ({{ (int) ($category->subcategories_tenders_total ?? 0) }})
                        </span>
                    </h2>
                </div>
                <div class="text-base sm:text-lg">
                    <ul>
                        @foreach($category->subcategories as $subcategory)
                            <li>
                                <a href="{{ route('tenders.search', ['category' => [$subcategory->id]]) }}" class="leading-relaxed hover:text-[#0D6AED]">
                                    {{ $subcategory->name }}
                                </a>
                                <span class="text-sm font-semibold text-gray-600">
                                    ({{ (int) ($subcategory->tenders_count ?? 0) }})
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
        </div>
    </div>
@include('components.mainfooter')
<script>
  const menuBtn = document.getElementById("menu-btn");
  const mobileMenu = document.getElementById("mobile-menu");

  menuBtn.addEventListener("click", () => {
    mobileMenu.classList.toggle("hidden");
  });

  function handleHomeSearch(e) {
    e.preventDefault();
    const query = document.getElementById('home-search-input')?.value || '';
    if (!query.trim()) {
      // navigate to tenders listing page if no query
      window.location.href = "{{ route('tenders.search') }}";
      return false;
    }
    const url = new URL("{{ route('tenders.search') }}", window.location.origin);
    url.searchParams.set('search', query);
    window.location.href = url.toString();
    return false;
  }

  // Pricing Cards Functionality
  document.addEventListener('DOMContentLoaded', function() {
    clearStaleLocalStorage();
    checkSubscriptionStatus();
    checkDowngradeRequestStatus();
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
