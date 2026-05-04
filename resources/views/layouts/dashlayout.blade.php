<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Spanz')</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
    <style>
        .menu-container {
            position: relative;
        }
        
        .menu-dropdown {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }
        
        .menu-dropdown:not(.hidden) {
            max-height: 500px; /* Adjust based on your content */
        }
        
        .menu-dropdown.hidden {
            max-height: 0;
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
            z-index: 50;
            min-width: 16rem;
            display: block;
        }

        .dropdown-group:hover .dropdown-menu {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
            transform: translateY(0) !important;
            transition-delay: 0.1s;
        }

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
    </style>
</head>

<body>
    <nav class="bg-[#032747] border-b border-[#0f3a5f] sticky top-0 z-50">
        <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="block text-white text-3xl font-extrabold italic tracking-widest leading-none"
                        style="font-family: 'Eurostile', 'Orbitron', 'Arial Black', sans-serif;">
                        SPANZ
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-6">
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
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('home')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Become a Supplier</a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}?redirect={{ urlencode(route('home')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Become a Supplier</a>
                                @endauth
                            </div>
                        </div>
                    </div>

                    <a href="#" class="text-white hover:text-blue-400 text-lg font-bold">About</a>
                    <a href="#" class="text-white hover:text-blue-400 text-lg font-bold">Pricing</a>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('tenders.search') }}" class="text-white hover:text-blue-400 text-lg font-bold">Tenders</a>
                    <a href="{{ route('products.search') }}" class="text-white hover:text-blue-400 text-lg font-bold">Products</a>
                    <a href="{{ route('suppliers.directory') }}" class="text-white hover:text-blue-400 text-lg font-bold">Suppliers' Directory</a>
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
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden bg-[#092c47] text-white px-4 py-4 space-y-3">
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

            <a href="#" class="block hover:text-blue-300">About</a>
            <a href="#" class="block hover:text-blue-300">Pricing</a>
            <a href="{{ route('tenders.search') }}" class="block hover:text-blue-300">Tenders</a>
            <a href="{{ route('products.search') }}" class="block hover:text-blue-300">Products</a>
            <a href="{{ route('suppliers.directory') }}" class="block hover:text-blue-300">Suppliers' Directory</a>
        </div>
    </nav>

    @yield('content')

    <script>
        // Toggle Buyer Dropdown
        function toggleDropdown() {
            const dropdown = document.getElementById('buyerDropdown');
            const arrow = document.getElementById('dropdownArrow');
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                dropdown.style.maxHeight = dropdown.scrollHeight + 'px';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.style.maxHeight = '0px';
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 300);
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Toggle Supplier Dropdown
        function toggleSupplierDropdown() {
            const dropdown = document.getElementById('supplierDropdown');
            const arrow = document.getElementById('supplierDropdownArrow');
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                dropdown.style.maxHeight = dropdown.scrollHeight + 'px';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.style.maxHeight = '0px';
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 300);
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Toggle Tender Dropdown
        function toggleTenderDropdown() {
            const dropdown = document.getElementById('tenderDropdown');
            const arrow = document.getElementById('tenderDropdownArrow');
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                dropdown.style.maxHeight = dropdown.scrollHeight + 'px';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.style.maxHeight = '0px';
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 300);
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Toggle Categories Dropdown
        function toggleCategoriesDropdown() {
            const dropdown = document.getElementById('categoriesDropdown');
            const arrow = document.getElementById('categoriesDropdownArrow');
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                dropdown.style.maxHeight = dropdown.scrollHeight + 'px';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.style.maxHeight = '0px';
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 300);
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Edit Profile Modal Functions
        function openEditModal() {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        // Preview Image Function
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('modalProfileImage').src = e.target.result;
                    document.getElementById('profileImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Save Profile Function
        function saveProfile(event) {
            event.preventDefault();
            
            // Get form data
            const formData = new FormData();
            const nameInput = document.getElementById('profileNameInput');
            const imageInput = document.getElementById('profileImageInput');
            
            // Update profile name in sidebar
            document.getElementById('profileName').textContent = nameInput.value;
            
            // Close modal
            closeEditModal();
            
            // Here you would typically send the data to your backend
            // For now, we'll just show a success message
            alert('Profile updated successfully!');
        }

        // Initialize dropdowns on page load
        document.addEventListener('DOMContentLoaded', function() {
            const menuBtn = document.getElementById("menu-btn");
            const mobileMenu = document.getElementById("mobile-menu");
            if (menuBtn && mobileMenu) {
                menuBtn.addEventListener("click", () => {
                    mobileMenu.classList.toggle("hidden");
                });
            }

            const buyerDropdown = document.getElementById('buyerDropdown');
            const supplierDropdown = document.getElementById('supplierDropdown');
            const tenderDropdown = document.getElementById('tenderDropdown');
            const categoriesDropdown = document.getElementById('categoriesDropdown');
            
            // Set initial max-height to 0 for smooth animations
            if (buyerDropdown) {
                buyerDropdown.style.maxHeight = '0px';
            }
            if (supplierDropdown) {
                supplierDropdown.style.maxHeight = '0px';
            }
            if (tenderDropdown) {
                tenderDropdown.style.maxHeight = '0px';
            }
            if (categoriesDropdown) {
                categoriesDropdown.style.maxHeight = '0px';
            }
        });
    </script>
</body>

</html>