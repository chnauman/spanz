<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - SPANZ')</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
    @stack('styles')
</head>
<body>
    <div class="flex w-full min-h-screen">
        <!-- Sidebar -->
        <div class="hidden lg:block lg:w-64 bg-gradient-to-l from-[#092C48] to-[#1b3963]">
            @include('admin.partials.sidebar')
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 overflow-x-auto">
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

            <form onsubmit="saveProfile(event)" class="space-y-4">
                <!-- Profile Image Upload -->
                <div class="text-center">
                    <div class="mb-4">
                        <img id="modalProfileImage" src="{{ asset('spanz-img/profile.jpg') }}" alt="Profile"
                            class="w-20 h-20 rounded-full mx-auto object-cover">
                    </div>
                    <label for="profileImageInput"
                        class="inline-block bg-[#0D6AED] text-white px-4 py-2 rounded-lg cursor-pointer hover:bg-[#0B5AC7] transition-colors duration-200">
                        Change Photo
                    </label>
                    <input type="file" id="profileImageInput" accept="image/*" class="hidden"
                        onchange="previewImage(event)">
                </div>

                <!-- Name Input -->
                <div>
                    <label for="profileNameInput" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="profileNameInput" value="{{ Auth::user()->name ?? 'Admin User' }}"
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
</body>
</html>
