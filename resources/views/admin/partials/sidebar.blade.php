<!-- Sidebar Container -->
<div class="flex flex-col h-full">
    @php
        $user = Auth::user();
    @endphp
    <!-- Logo Section -->
    <div class="hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 h-20 transition-colors duration-300">
        <a href="{{ route('home') }}" class="text-4xl font-bold text-white h-20 flex items-center pl-5 hover:text-blue-300 transition-colors duration-300">SPANZ</a>
    </div>
    <hr class="border-[#657a9871]" />

    <!-- Profile Section -->
    <div class="h-32 relative flex items-center gap-5 px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
        @php
            $profilePhotoUrl = null;
            if (Auth::check()) {
                foreach (['jpg','jpeg','png','webp'] as $ext) {
                    $candidate = 'profile-photos/' . Auth::id() . '.' . $ext;
                    if (\Storage::disk('public')->exists($candidate)) {
                        $profilePhotoUrl = asset('storage/' . $candidate) . '?t=' . time();
                        break;
                    }
                }
            }
        @endphp
        <img src="{{ $profilePhotoUrl ?: asset('spanz-img/profile.jpg') }}" alt="" class="w-16 h-16 rounded-full object-cover cursor-pointer hover:opacity-80 transition-opacity duration-200" id="profileImage" onclick="openProfileImageModal()">
        <span class="text-white" id="profileName">{{ $user?->name ?? 'Guest' }}</span>
        <svg onclick="openEditModal()" width="15px" height="15px" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg"
            class="absolute right-5 top-5 transform -translate-y-1/2 transition-colors duration-300 hover:cursor-pointer group">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M20.8477 1.87868C19.6761 0.707109 17.7766 0.707105 16.605 1.87868L2.44744 16.0363C2.02864 16.4551 1.74317 16.9885 1.62702 17.5692L1.03995 20.5046C0.760062 21.904 1.9939 23.1379 3.39334 22.858L6.32868 22.2709C6.90945 22.1548 7.44285 21.8693 7.86165 21.4505L22.0192 7.29289C23.1908 6.12132 23.1908 4.22183 22.0192 3.05025L20.8477 1.87868ZM18.0192 3.29289C18.4098 2.90237 19.0429 2.90237 19.4335 3.29289L20.605 4.46447C20.9956 4.85499 20.9956 5.48815 20.605 5.87868L17.9334 8.55027L15.3477 5.96448L18.0192 3.29289ZM13.9334 7.3787L3.86165 17.4505C3.72205 17.5901 3.6269 17.7679 3.58818 17.9615L3.00111 20.8968L5.93645 20.3097C6.13004 20.271 6.30784 20.1759 6.44744 20.0363L16.5192 9.96448L13.9334 7.3787Z"
                fill="#ffffff" class="group-hover:fill-[#0D6AED]" />
        </svg>
    </div>
    <hr class="border-[#657a9871]" />

    <!-- Dashboard Link -->
    <a href="{{ route('dashboard') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" fill="#3B82F6"/>
        </svg>
        <span class="text-sm">Dashboard</span>
    </a>
    <hr class="border-[#657a9871]" />

    @if($user && !$user->isAdmin())
        {{-- ============================================ --}}
        {{-- MANAGE BUSINESS SECTION                       --}}
        {{-- ============================================ --}}
        <div class="px-5 pt-4 pb-2">
            <span class="text-xs text-blue-300 uppercase tracking-wider font-bold">Manage Business</span>
        </div>

        @if($user->isBuyer() || $user->isSupplier() || $user->isSubSupplier())
            {{-- Projects / RFXs I Posted --}}
            <a href="{{ route('tenders.my-tenders') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 2h6a1 1 0 011 1v1h3a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2h3V3a1 1 0 011-1zm1 2v2h4V4h-4zM7 10h2v2H7v-2zm0 4h2v2H7v-2zm4-4h6v2h-6v-2zm0 4h6v2h-6v-2z" fill="#3B82F6"/>
                </svg>
                <span class="text-sm">My Posted Projects / RFXs</span>
            </a>
        @endif

        @if($user->isSupplier() || $user->isSubSupplier())
            {{-- New Projects / RFXs Received --}}
            <a href="{{ route('user.interests') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 7V3.5L18.5 9H13z" fill="#3B82F6"/>
                    <path d="M12.5 14l1.5 3 3.3.3-2.5 2.2.8 3.2-2.6-1.7-2.6 1.7.8-3.2-2.5-2.2 3.3-.3 1.5-3z" fill="#FBBF24"/>
                </svg>
                <span class="text-sm">My Received Projects / RFXs</span>
            </a>
        @endif

        @if($user->isBuyer() || $user->isSupplier() || $user->isSubSupplier())
            {{-- My Saved Projects / RFXs --}}
            <a href="{{ route('tenders.saved') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 5.75h10a.75.75 0 0 1 .75.75v12.2a.3.3 0 0 1-.46.25L12 15.4l-5.29 3.55a.3.3 0 0 1-.46-.25V6.5A.75.75 0 0 1 7 5.75z" fill="#3B82F6"/>
                </svg>
                <span class="text-sm">My Saved Projects / RFXs</span>
            </a>

            {{-- Post New Project / RFX --}}
            <a href="{{ route('tenders.create') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 7V3.5L18.5 9H13z" fill="#3B82F6"/>
                    <path d="M12 11v6m-3-3h6" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="text-sm">Post New Project / RFX</span>
            </a>
        @endif

        @if($user->isSupplier())
            {{-- Add Work Colleagues --}}
            <a href="{{ route('suppliers.invite') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h12v-2c0-2.66-5.33-4-8-4z" fill="#3B82F6"/>
                    <path d="M19 10v-2h-2v2h-2v2h2v2h2v-2h2v-2h-2z" fill="#3B82F6"/>
                </svg>
                <span class="text-sm">Add Work Colleagues</span>
            </a>
        @endif

        @if($user->isSupplier() && $user->subSuppliers()->count() > 0)
            {{-- Sub Suppliers --}}
            <a href="{{ route('suppliers.sub-suppliers') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" fill="#3B82F6"/>
                </svg>
                <span class="text-sm">Sub Suppliers</span>
            </a>
        @endif

        @if($user->isSupplier() || $user->isSubSupplier())
            {{-- Downloaded Documents --}}
            <a href="{{ route('suppliers.received-documents') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 7V3.5L18.5 9H13z" fill="#3B82F6"/>
                    <path d="M12 18l-3-3h2v-4h2v4h2l-3 3z" fill="#ffffff"/>
                </svg>
                <span class="text-sm">Downloaded Documents</span>
            </a>
        @endif

        {{-- Back to SPANZ --}}
        <a href="{{ route('home') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" fill="#3B82F6"/>
            </svg>
            <span class="text-sm">Back to SPANZ</span>
        </a>

        <hr class="border-[#657a9871] my-2" />

        {{-- ============================================ --}}
        {{-- MANAGE MY ACCOUNT SECTION                     --}}
        {{-- ============================================ --}}
        <div class="px-5 pt-2 pb-2">
            <span class="text-xs text-blue-300 uppercase tracking-wider font-bold">Manage My Account</span>
        </div>

        {{-- My Messages --}}
        <a href="{{ route('account.profile') }}#notifications" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300 relative">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6V11c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z" fill="#3B82F6"/>
            </svg>
            <span class="text-sm">My Messages</span>
        </a>

        {{-- My Business Profile - Edit / Strengthen --}}
        <a href="{{ route('company.register') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="#3B82F6"/>
                <circle cx="18" cy="6" r="3" fill="#10B981"/>
                <path d="M17 5h2M18 4v2" stroke="#ffffff" stroke-width="1" stroke-linecap="round"/>
            </svg>
            <span class="text-sm">My Business Profile</span>
        </a>

        {{-- My Current Plan - Change --}}
        <a href="{{ route('account.plan') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1z" fill="#3B82F6"/>
                <path d="M10.5 16.5l-3-3 1.41-1.41L10.5 13.67l4.59-4.58L16.5 10.5l-6 6z" fill="#ffffff"/>
            </svg>
            <span class="text-sm">My Current Plan</span>
        </a>

        {{-- My Credit Points - View History --}}
        <a href="{{ route('account.credits') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="#3B82F6"/>
                <path d="M12 9.5C13.38 9.5 14.5 8.38 14.5 7s-1.12-2.5-2.5-2.5S9.5 5.62 9.5 7s1.12 2.5 2.5 2.5z" fill="#ffffff"/>
            </svg>
            <span class="text-sm">My Credit Points</span>
        </a>
    @endif

    @if($user && $user->isAdmin())
        {{-- ============================================ --}}
        {{-- ADMIN MENU (flat, no dropdowns)               --}}
        {{-- ============================================ --}}
        <div class="px-5 pt-4 pb-2">
            <span class="text-xs text-blue-300 uppercase tracking-wider font-bold">Administration</span>
        </div>

        <a href="{{ route('admin.users.index') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" fill="#3B82F6"/>
            </svg>
            <span class="text-sm">Users</span>
        </a>

        <a href="{{ route('admin.categories.index') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 5h18v2H3V5zm0 6h18v2H3v-2zm0 6h18v2H3v-2z" fill="#3B82F6"/>
            </svg>
            <span class="text-sm">Categories</span>
        </a>

        <a href="{{ route('admin.products.index') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0020 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" fill="#3B82F6"/>
            </svg>
            <span class="text-sm">Products</span>
        </a>

        <a href="{{ route('admin.purchase-requests.index') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v1c0 2.55 1.92 4.63 4.39 4.94.63 1.5 1.98 2.63 3.61 2.96V19H7v2h10v-2h-4v-3.1c1.63-.33 2.98-1.46 3.61-2.96C19.08 12.63 21 10.55 21 8V7c0-1.1-.9-2-2-2z" fill="#3B82F6"/>
            </svg>
            <span class="text-sm">Purchase Requests</span>
        </a>

        <hr class="border-[#657a9871] my-2" />

        <div class="px-5 pt-2 pb-2">
            <span class="text-xs text-blue-300 uppercase tracking-wider font-bold">Subscriptions</span>
        </div>

        <a href="{{ route('admin.subscriptions.index') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" fill="#3B82F6"/>
            </svg>
            <span class="text-sm">Manage Plans</span>
        </a>

        <a href="{{ route('admin.tender-view-pricing.index') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z" fill="#3B82F6"/>
            </svg>
            <span class="text-sm">Tender View Pricing</span>
        </a>

        <a href="{{ route('admin.subscription-requests') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zM10 17l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" fill="#3B82F6"/>
            </svg>
            <span class="text-sm">Upgrade Requests</span>
        </a>

        <a href="{{ route('admin.downgrade-requests.index') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zM12 19l-5-5h3v-4h4v4h3l-5 5z" fill="#3B82F6"/>
            </svg>
            <span class="text-sm">Downgrade Requests</span>
        </a>

        <hr class="border-[#657a9871] my-2" />

        <div class="px-5 pt-2 pb-2">
            <span class="text-xs text-blue-300 uppercase tracking-wider font-bold">Account</span>
        </div>

        <a href="{{ route('account.profile') }}" class="text-white h-12 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="#3B82F6"/>
            </svg>
            <span class="text-sm">My Profile</span>
        </a>
    @endif

    <hr class="border-[#657a9871] my-2" />

    <!-- Spacer to push logout to bottom -->
    <div class="flex-grow"></div>

    <!-- Logout Section -->
    <div class="text-white h-14 gap-3 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M14 4L17.5 4C20.5577 4 20.5 8 20.5 12C20.5 16 20.5577 20 17.5 20H14M3 12L15 12M3 12L7 8M3 12L7 16"
                stroke="#3B82F6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @if($user)
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-white hover:text-blue-300 bg-transparent border-none cursor-pointer text-sm">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="text-white hover:text-blue-300 text-sm">Login</a>
        @endif
    </div>
    <hr class="border-[#657a9871]" />
</div>

<!-- Profile Image Popup Modal -->
<div id="profileImageModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-75 transition-opacity duration-300" onclick="closeProfileImageModal()">
    <div class="relative max-w-4xl max-h-[90vh] p-4" onclick="event.stopPropagation()">
        <button onclick="closeProfileImageModal()" class="absolute -top-3 -right-3 bg-white rounded-full p-1.5 hover:bg-gray-200 transition-colors duration-200 shadow-lg z-[10000] flex items-center justify-center" style="z-index: 10000;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 6L6 18M6 6L18 18" stroke="#000000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <img id="profileImageModalImg" src="{{ $profilePhotoUrl ?: asset('spanz-img/profile.jpg') }}" alt="Profile Picture" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl object-contain">
    </div>
</div>

<script>
// Profile image popup modal functionality
function openProfileImageModal() {
    const modal = document.getElementById('profileImageModal');
    const profileImage = document.getElementById('profileImage');
    const modalImage = document.getElementById('profileImageModalImg');

    if (modal && profileImage && modalImage) {
        modalImage.src = profileImage.src;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeProfileImageModal() {
    const modal = document.getElementById('profileImageModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeProfileImageModal();
    }
});

function openEditModal() {
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('modalProfileImage').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function saveProfile(event) {
    event.preventDefault();
    closeEditModal();
}
</script>
