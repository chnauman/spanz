<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SPANZ')</title>

    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        /* Match the public tenders header (search page) */
        .thomas-topbar {
            background: linear-gradient(180deg, #032747 0%, #0a3255 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .thomas-nav-link {
            font-size: 1.05rem;
            font-weight: 700;
            color: #e6eef7;
        }

        .thomas-nav-link:hover {
            color: #ffffff;
        }

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
            z-index: 60;
            min-width: 16rem;
            display: block;
            white-space: nowrap;
        }

        .dropdown-group:hover .dropdown-menu {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
            transform: translateY(0) !important;
            transition-delay: 0.1s;
        }

        .dropdown-menu:hover {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
            transform: translateY(0) !important;
        }

        .dropdown-group::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            height: 0.5rem;
            background: transparent;
            z-index: 59;
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
    </style>

    @stack('styles')
</head>
<body class="min-h-screen bg-white">
    <div class="thomas-topbar sticky top-0 z-40">
        <nav>
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-14">
                    <div class="hidden md:flex space-x-6">
                        <div class="dropdown-group">
                            <button class="thomas-nav-link flex items-center">
                                For Buyers ▾
                            </button>
                            <div class="dropdown-menu">
                                <div class="py-1 whitespace-nowrap">
                                    @auth
                                        @if(!auth()->user()->isAdmin())
                                            <a href="{{ route('tenders.create') }}">Post a RFX</a>
                                            <a href="{{ route('tenders.saved') }}">Saved RFXs</a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.create')) }}">Post a RFX</a>
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.saved')) }}">Saved RFXs</a>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-group">
                            <button class="thomas-nav-link flex items-center">
                                For Suppliers ▾
                            </button>
                            <div class="dropdown-menu">
                                <div class="py-1 whitespace-nowrap">
                                    @auth
                                        @if(auth()->user()->isSupplier() || auth()->user()->isSubSupplier())
                                            <a href="{{ route('tenders.saved') }}">Saved Tenders</a>
                                            <a href="{{ route('user.interests') }}">My Interests</a>
                                            <a href="{{ route('tenders.viewed') }}">Viewed Tenders</a>
                                            @if(auth()->user()->isSupplier())
                                                <a href="{{ route('invite.sub-suppliers') }}">Invite Sub Supplier</a>
                                            @endif
                                            <a href="{{ route('pricing') }}">Subscription Plans</a>
                                        @else
                                            <a href="{{ route('pricing') }}">Become a Supplier</a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}">Become a Supplier</a>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <a href="#" class="thomas-nav-link">About</a>
                    </div>

                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('tenders.search') }}" class="thomas-nav-link">Tenders</a>
                        <a href="{{ route('products.search') }}" class="thomas-nav-link">Products</a>
                        <a href="{{ route('suppliers.directory') }}" class="thomas-nav-link">Suppliers' Directory</a>

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
                                Sign up
                            </a>
                        @endauth
                    </div>

                    <div class="md:hidden flex items-center justify-between w-full">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-white">SPANZ</a>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('tenders.search') }}" class="text-white font-semibold">Tenders</a>
                            <a href="{{ route('pricing') }}" class="text-white font-semibold">Pricing</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <main>
        @yield('content')
    </main>

    @include('components.subscription-modal', ['subscriptions' => $subscriptions ?? collect()])
    @stack('scripts')
</body>
</html>

