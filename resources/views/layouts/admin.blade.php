<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    </style>
    @stack('styles')
</head>
<body>
    <div class="flex w-full min-h-screen bg-gradient-to-l from-[#092C48] to-[#1b3963]">
        <!-- Mobile/Tablet Header (completely hidden on laptop/desktop) -->
        <div id="mobile-header" class="xl:hidden fixed top-0 left-0 right-0 z-50 bg-gradient-to-l from-[#092C48] to-[#1b3963] h-16 flex items-center justify-between px-4 shadow-lg">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-[#0D6AED]">SPANZ</a>
            </div>
            <button id="mobile-menu-btn" class="text-white focus:outline-none hover:text-blue-300 transition-colors duration-200 p-2">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Mobile/Tablet Sidebar Overlay (hidden on laptop/desktop) -->
        <div id="mobile-sidebar-overlay" class="xl:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

        <!-- Sidebar -->
        <div id="sidebar" class="fixed xl:static xl:block xl:w-64 w-64 bg-gradient-to-l from-[#092C48] to-[#1b3963] h-full xl:h-screen z-50 transform -translate-x-full xl:translate-x-0 xl:transform-none xl:left-auto transition-transform duration-300 ease-in-out flex-shrink-0" style="left: -256px;">
            @include('admin.partials.sidebar')
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 w-full max-w-full overflow-x-auto xl:ml-0 bg-white">
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
                        <img id="modalProfileImage" src="{{ $profilePhotoUrlModal ?: asset('spanz-img/profile.jpg') }}" alt="Profile"
                            class="w-20 h-20 rounded-full object-cover mx-auto">
                    </div>
                    <label for="profileImageInput"
                        class="inline-block bg-[#0D6AED] text-white px-4 py-2 rounded-lg cursor-pointer hover:bg-[#0B5AC7] transition-colors duration-200">
                        Change Photo
                    </label>
                    <input type="file" id="profileImageInput" name="photo" accept="image/*" class="hidden"
                        onchange="previewImage(event)">
                </div>

                <!-- Name Input -->
                <div>
                    <label for="profileNameInput" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="profileNameInput" name="name" value="{{ Auth::user()->name ?? 'Admin User' }}"
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
