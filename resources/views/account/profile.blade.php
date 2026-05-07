@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">My Profile</h1>
                <a href="{{ route('company.register', ['mode' => 'edit']) }}"
                        class="px-4 py-2 bg-[#0D6AED] text-white rounded-lg hover:bg-[#0B5AC7] transition-colors duration-200 flex items-center gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Edit Profile
                </a>
            </div>

            <div class="mt-6">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Profile Photo Section -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-4">Profile Photo</label>
                            <div class="flex items-center space-x-6">
                                @php
                                    $profilePhotoUrl = null;
                                    foreach (['jpg','jpeg','png','webp'] as $ext) {
                                        $candidate = 'profile-photos/' . $user->id . '.' . $ext;
                                        if (\Storage::disk('public')->exists($candidate)) {
                                            $profilePhotoUrl = asset('storage/' . $candidate) . '?t=' . time();
                                            break;
                                        }
                                    }
                                @endphp
                                <img id="profileImagePreview" src="{{ $profilePhotoUrl ?: asset('spanz-img/profile.jpg') }}"
                                     alt="Profile Photo" 
                                     style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; cursor: pointer;"
                                     onclick="openProfileImageModal()"
                                     class="hover:opacity-80 transition-opacity duration-200">
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                {{ $user->name }}
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                {{ $user->email }}
                            </div>
                        </div>

                        <!-- Role Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Account Type</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </div>
                        </div>

                        <!-- Company Name (if available) -->
                        @if($user->companyDetail)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                {{ $user->companyDetail->company_name }}
                            </div>
                        </div>
                        @endif

                        <!-- Contact / Location (read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                {{ $user->country ?: 'Not provided' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                {{ $user->state ?: 'Not provided' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                {{ $user->city ?: 'Not provided' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                                {{ $user->phone ?: 'Not provided' }}
                            </div>
                        </div>
                    </div>

                    <!-- Notification Settings Section -->
                    <div class="mt-8 pt-8 border-t border-white-300">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Notification Settings</h2>
                        <p class="text-sm text-gray-600 mb-4">Choose how often you want to receive email updates about new tenders matching your interests.</p>
                        
                        <form method="POST" action="{{ route('account.update-notifications') }}" id="notificationForm">
                            @csrf
                            <div class="space-y-3">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="notification_frequency" value="none" 
                                           {{ $user->notification_frequency === 'none' || !$user->notification_frequency ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#0D6AED] focus:ring-[#0D6AED] border-gray-300">
                                    <span class="text-gray-700 font-medium">None - Don't send me email notifications</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="notification_frequency" value="daily" 
                                           {{ $user->notification_frequency === 'daily' ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#0D6AED] focus:ring-[#0D6AED] border-gray-300">
                                    <span class="text-gray-700 font-medium">Daily - Send me a daily summary</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="notification_frequency" value="weekly" 
                                           {{ $user->notification_frequency === 'weekly' ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#0D6AED] focus:ring-[#0D6AED] border-gray-300">
                                    <span class="text-gray-700 font-medium">Weekly - Send me a weekly summary</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="notification_frequency" value="monthly" 
                                           {{ $user->notification_frequency === 'monthly' ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#0D6AED] focus:ring-[#0D6AED] border-gray-300">
                                    <span class="text-gray-700 font-medium">Monthly - Send me a monthly summary</span>
                                </label>
                            </div>
                            
                            <div class="mt-6">
                                <button type="submit" 
                                        class="px-6 py-2 bg-[#0D6AED] text-white rounded-lg hover:bg-[#0B5AC7] transition-colors duration-200 font-medium">
                                    Save Notification Settings
                                </button>
                            </div>
                        </form>
                        
                        @if(session('notification_success'))
                            <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                                {{ session('notification_success') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Profile Image Popup Modal -->
<div id="profileImageModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-75 transition-opacity duration-300" onclick="closeProfileImageModal()">
    <div class="relative max-w-xs max-h-[60vh] p-4" onclick="event.stopPropagation()">
        <!-- Close Button -->
        <button onclick="closeProfileImageModal()" class="absolute -top-3 -right-3 bg-white rounded-full p-1.5 hover:bg-gray-200 transition-colors duration-200 shadow-lg z-[10000] flex items-center justify-center" style="z-index: 10000;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 6L6 18M6 6L18 18" stroke="#000000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <!-- Profile Image -->
        <img id="profileImageModalImg" src="{{ $profilePhotoUrl ?: asset('spanz-img/profile.jpg') }}" alt="Profile Picture" class="max-w-full max-h-[60vh] rounded-lg shadow-2xl object-contain">
    </div>
</div>

<script>
// Profile image popup modal functionality
function openProfileImageModal() {
    const modal = document.getElementById('profileImageModal');
    const profileImage = document.getElementById('profileImagePreview');
    const modalImage = document.getElementById('profileImageModalImg');
    
    if (modal && profileImage && modalImage) {
        // Set the modal image source to match the profile image
        modalImage.src = profileImage.src;
        // Show the modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        // Prevent body scroll when modal is open
        document.body.style.overflow = 'hidden';
    }
}

function closeProfileImageModal() {
    const modal = document.getElementById('profileImageModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        // Restore body scroll
        document.body.style.overflow = 'auto';
    }
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeProfileImageModal();
    }
});

// Update profile image when edit modal updates it
document.addEventListener('DOMContentLoaded', function() {
    const sidebarImage = document.getElementById('profileImage');
    const previewImage = document.getElementById('profileImagePreview');
    
    if (sidebarImage && previewImage) {
        // Watch for changes to the sidebar image src (when edit modal saves)
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'src') {
                    previewImage.src = sidebarImage.src;
                }
            });
        });
        
        observer.observe(sidebarImage, { 
            attributes: true, 
            attributeFilter: ['src'] 
        });
    }
});
</script>
@endsection
