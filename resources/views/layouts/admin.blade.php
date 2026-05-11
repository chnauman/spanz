<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="user-has-company" content="{{ auth()->user()->companyDetail ? '1' : '0' }}">
    @endauth
    <title>@yield('title', 'Admin Dashboard - SPANZ')</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        /* Ensure sidebar is always visible on desktop screens */
        @media (min-width: 1280px) {
            #sidebar {
                position: static !important;
                transform: none !important;
                left: auto !important;
                width: 16rem !important;
            }

            /* Completely hide mobile header on desktop */
            .xl\\:hidden {
                display: none !important;
            }

            /* Force hide mobile header bar on desktop */
            div[class*="xl:hidden"] {
                display: none !important;
            }

            /* Target the specific mobile header */
            div.xl\\:hidden.fixed.top-0 {
                display: none !important;
            }

            /* Force hide mobile header by ID */
            #mobile-header {
                display: none !important;
            }
        }

        /* Ensure full height background for sidebar */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #sidebar {
            min-height: 100vh;
        }

        .panel-top-dropdown-group {
            position: relative;
        }

        .panel-top-dropdown-menu {
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
        }

        .panel-top-dropdown-group:hover .panel-top-dropdown-menu {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
            transform: translateY(0) !important;
            transition-delay: 0.1s;
        }

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

        /* Keep dropdown open when hovering over it */
        .dropdown-menu:hover {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
            transform: translateY(0) !important;
        }

        /* Small hover-bridge so moving to menu doesn't flicker */
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

        /* ---------- Canonical SPANZ button system ---------- */
        /* Primary CTA: matches the home page "Post RFX, its free" style */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background-color: #0D6AED;
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.25;
            border: 1px solid transparent;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 200ms ease, color 200ms ease, border-color 200ms ease, box-shadow 200ms ease;
        }
        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #0B5ED7;
            color: #ffffff;
            text-decoration: none;
        }
        .btn-primary:disabled,
        .btn-primary.is-disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        /* Smaller primary for tight rows / inline actions */
        .btn-primary-sm {
            padding: 0.4rem 0.9rem;
            font-size: 0.875rem;
            border-radius: 0.5rem;
        }

        /* Secondary / outline action */
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background-color: #ffffff;
            color: #0D6AED;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.25;
            border: 1px solid #0D6AED;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 200ms ease, color 200ms ease, border-color 200ms ease;
        }
        .btn-secondary:hover,
        .btn-secondary:focus {
            background-color: #EAF2FF;
            color: #0B5ED7;
            border-color: #0B5ED7;
            text-decoration: none;
        }
        .btn-secondary-sm {
            padding: 0.4rem 0.9rem;
            font-size: 0.875rem;
        }

        /* Block / full-width modifier */
        .btn-block {
            width: 100%;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="flex w-full min-h-screen bg-gradient-to-l from-[#092C48] to-[#1b3963]">
        <!-- Mobile/Tablet Header (completely hidden on laptop/desktop) -->
        <div id="mobile-header" class="md:hidden fixed top-0 left-0 right-0 z-50 bg-gradient-to-l from-[#092C48] to-[#1b3963] h-16 flex items-center justify-between px-4 shadow-lg">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-white">SPANZ</a>
            </div>
            <button id="mobile-menu-btn" class="text-white focus:outline-none hover:text-blue-300 transition-colors duration-200 p-2">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Mobile/Tablet Sidebar Overlay (hidden on laptop/desktop) -->
        <div id="mobile-sidebar-overlay" class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

        <!-- Sidebar -->
        <div id="sidebar" class="fixed xl:static xl:block xl:w-64 w-64 bg-gradient-to-l from-[#092C48] to-[#1b3963] h-full xl:h-screen z-50 transform -translate-x-full xl:translate-x-0 xl:transform-none xl:left-auto transition-transform duration-300 ease-in-out flex-shrink-0" style="left: -256px;">
            @include('admin.partials.sidebar')
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 w-full max-w-full overflow-x-auto xl:ml-0 bg-white">
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
                                                    <a href="#" onclick="openSubscriptionModal(); return false;">Subscription Plans</a>
                                                @else
                                                    <a href="#" onclick="openSubscriptionModal(); return false;">Become a Supplier</a>
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
                                        Register
                                    </a>
                                @endauth
                            </div>

                            <div class="md:hidden">
                                <button id="menu-btn" class="text-white focus:outline-none">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 6h16M4 12h16M4 18h16"/>
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
                        <a href="{{ route('products.search') }}" class="block hover:text-blue-300">Products</a>
                        <a href="{{ route('suppliers.directory') }}" class="block hover:text-blue-300">Suppliers' Directory</a>
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

            <!-- Mobile/Tablet spacing (hidden on laptop/desktop) -->
            <div class="xl:hidden h-16"></div>
            @yield('content')
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Edit Profile</h2>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <form id="editProfileForm" onsubmit="saveProfile(event)" class="space-y-4" enctype="multipart/form-data">
                @csrf
                <!-- Profile Image Upload -->
                <div class="text-center">
                    <div class="mb-4">
                        @php
                            $profilePhotoUrlModal = null;
                            if (Auth::check()) {
                                foreach (['jpg','jpeg','png','webp'] as $ext) {
                                    $candidate = 'profile-photos/' . Auth::id() . '.' . $ext;
                                    if (\Storage::disk('public')->exists($candidate)) {
                                        $profilePhotoUrlModal = asset('storage/' . $candidate) . '?t=' . time();
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <img id="modalProfileImage" src="{{ $profilePhotoUrlModal ?: asset('spanz-img/profile.jpg') }}" alt="Company Logo"
                            class="w-20 h-20 rounded-full object-cover mx-auto">
                    </div>
                    <label for="profileImageInput"
                        class="inline-block bg-[#0D6AED] text-white px-4 py-2 rounded-lg cursor-pointer hover:bg-[#0B5AC7] transition-colors duration-200">
                        Change Company Logo
                    </label>
                    <input type="file" id="profileImageInput" name="photo" accept="image/*" class="hidden"
                        onchange="previewImage(event)">
                </div>

                <!-- Name Input -->
                <div>
                    <label for="profileNameInput" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="profileNameInput" name="name" value="{{ Auth::user()?->name ?? 'Guest' }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0D6AED] focus:border-transparent">
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeEditModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-[#0D6AED] text-white rounded-lg hover:bg-[#0B5AC7] transition-colors duration-200">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuBtn = document.getElementById("menu-btn");
            const mobileMenu = document.getElementById("mobile-menu");
            if (menuBtn && mobileMenu) {
                menuBtn.addEventListener("click", () => {
                    mobileMenu.classList.toggle("hidden");
                });
            }
        });
    </script>

    <!-- Include Company Registration Modal for authenticated non-admin users -->
    @auth
        @if(!auth()->user()->isAdmin())
            @include('components.company-registration-modal')
        @endif
    @endauth

    @include('components.subscription-modal', ['subscriptions' => $subscriptions ?? collect()])

    <!-- JavaScript to intercept Post Tender links -->
    @auth
        @if(!auth()->user()->isAdmin() && !auth()->user()->companyDetail)
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to check and intercept Post Tender links
            function interceptPostTenderLinks() {
                // Intercept all "Post Tender" links - multiple selectors to catch all variations
                const selectors = [
                    'a[href*="tenders.create"]',
                    'a[href*="tenders/create"]',
                    'a[href*="/tenders/create"]',
                    'a[href="{{ route("tenders.create") }}"]'
                ];
                
                let postTenderLinks = [];
                selectors.forEach(function(selector) {
                    const links = document.querySelectorAll(selector);
                    links.forEach(function(link) {
                        // Avoid duplicate listeners
                        if (!link.dataset.companyCheckAdded) {
                            postTenderLinks.push(link);
                            link.dataset.companyCheckAdded = 'true';
                        }
                    });
                });
                
                postTenderLinks.forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Check if user has company registered
                        const hasCompany = document.querySelector('meta[name="user-has-company"]');
                        
                        if (hasCompany && hasCompany.getAttribute('content') === '0') {
                            // Show company registration modal
                            if (typeof openCompanyRegistrationModal === 'function') {
                                openCompanyRegistrationModal();
                            } else {
                                // If modal function not available, redirect to registration page
                                window.location.href = '{{ route("company.register") }}';
                            }
                        } else {
                            // User has company, proceed normally
                            window.location.href = this.href;
                        }
                        return false;
                    });
                });
            }
            
            // Run on page load
            interceptPostTenderLinks();
            
            // Also run after a short delay to catch dynamically loaded links
            setTimeout(interceptPostTenderLinks, 500);
            
            // Use MutationObserver to catch dynamically added links
            const observer = new MutationObserver(function(mutations) {
                interceptPostTenderLinks();
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });
        </script>
        @endif
    @endauth

    <!-- Force hide mobile header on desktop screens -->
    <script>
        // Immediate check to hide mobile header on desktop
        if (window.innerWidth >= 1280) {
            document.addEventListener('DOMContentLoaded', function() {
                const mobileHeader = document.getElementById('mobile-header');
                if (mobileHeader) {
                    mobileHeader.style.display = 'none';
                    console.log('Mobile header force hidden on desktop');
                }
            });
        }
    </script>

    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle functionality
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');
            let isSidebarOpen = false;

            // Check if elements exist
            if (!mobileMenuBtn || !sidebar || !overlay) {
                console.error('Mobile sidebar elements not found');
                return;
            }

            // Skip initialization on desktop screens
            if (window.innerWidth >= 1280) {
                console.log('Desktop mode - skipping mobile sidebar initialization');
                return;
            }

            function toggleSidebar() {
                // Only allow toggle on smaller screens (mobile/tablet)
                if (window.innerWidth >= 1280) {
                    console.log('Toggle disabled on desktop screens');
                    return;
                }

                console.log('Toggle sidebar clicked, current state:', isSidebarOpen);
                isSidebarOpen = !isSidebarOpen;

                if (isSidebarOpen) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    sidebar.style.left = '0px';
                    overlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // Prevent background scrolling
                    console.log('Sidebar opened');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    sidebar.style.left = '-256px';
                    overlay.classList.add('hidden');
                    document.body.style.overflow = ''; // Restore scrolling
                    console.log('Sidebar closed');
                }
            }

            function closeSidebar() {
                // Only allow close on smaller screens (mobile/tablet)
                if (window.innerWidth >= 1280) {
                    return;
                }

                if (isSidebarOpen) {
                    isSidebarOpen = false;
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    sidebar.style.left = '-256px';
                    overlay.classList.add('hidden');
                    document.body.style.overflow = '';
                    console.log('Sidebar closed via closeSidebar');
                }
            }

            // Event listeners - only bind on mobile/tablet screens
            if (window.innerWidth < 1280) {
                mobileMenuBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    toggleSidebar();
                });

                overlay.addEventListener('click', closeSidebar);
            }

            // Close sidebar when clicking on sidebar links (mobile/tablet only)
            const sidebarLinks = document.querySelectorAll('#sidebar a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    // Only close sidebar on mobile/tablet screens
                    if (window.innerWidth < 1280) { // xl breakpoint (1280px)
                        closeSidebar();
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1280) { // xl breakpoint (1280px)
                    // Reset sidebar to desktop state - remove all mobile styles
                    isSidebarOpen = false;
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    sidebar.style.left = 'auto'; // Reset to auto for desktop
                    overlay.classList.add('hidden');
                    document.body.style.overflow = '';

                    // Force hide mobile header on desktop
                    const mobileHeader = document.getElementById('mobile-header');
                    if (mobileHeader) {
                        mobileHeader.style.display = 'none';
                    }

                    console.log('Switched to desktop mode - sidebar reset to static position, mobile header hidden');
                } else {
                    // Switch to mobile mode
                    sidebar.style.left = '-256px';

                    // Show mobile header on mobile/tablet
                    const mobileHeader = document.getElementById('mobile-header');
                    if (mobileHeader) {
                        mobileHeader.style.display = 'flex';
                    }

                    console.log('Switched to mobile mode');
                }
            });

            // Close sidebar when pressing escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && isSidebarOpen) {
                    closeSidebar();
                }
            });

            // Initialize sidebar position based on screen size
            if (window.innerWidth >= 1280) {
                // Desktop mode - ensure sidebar is visible and hide mobile header
                sidebar.style.left = 'auto';
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');

                // Force hide mobile header on desktop
                const mobileHeader = document.getElementById('mobile-header');
                if (mobileHeader) {
                    mobileHeader.style.display = 'none';
                }

                console.log('Desktop mode initialized - sidebar always visible, mobile header hidden');
            } else {
                // Mobile mode - ensure sidebar is hidden
                sidebar.style.left = '-256px';
                console.log('Mobile mode initialized - sidebar hidden');
            }

            console.log('Mobile sidebar functionality initialized');
        });
    </script>

    <script>
        async function saveProfile(event) {
            event.preventDefault();
            const formEl = document.getElementById('editProfileForm');
            const formData = new FormData(formEl);

            try {
                const response = await fetch("{{ route('profile.update') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to update profile');
                }

                // Update UI: name and photo (sidebar and modal)
                const nameEls = [document.getElementById('profileName'), document.getElementById('profileNameInput')];
                nameEls.forEach(el => { if (el && data.name) el.textContent = data.name; });

                if (data.photo_url) {
                    const sidebarImg = document.getElementById('profileImage');
                    const modalImg = document.getElementById('modalProfileImage');
                    if (sidebarImg) sidebarImg.src = data.photo_url;
                    if (modalImg) modalImg.src = data.photo_url;
                }

                closeEditModal();
            } catch (e) {
                alert(e.message);
            }
        }

        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const modalImg = document.getElementById('modalProfileImage');
                    if (modalImg) modalImg.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
