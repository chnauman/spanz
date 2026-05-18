<!-- Sidebar Container -->
<style>
    /* ===== Clean & Minimal Sidebar ===== */
    #sidebar {
        font-family: inherit;
    }

    /* Brand */
    #sidebar .sb-brand {
        height: 64px;
        display: flex;
        align-items: center;
        padding: 0 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }
    #sidebar .sb-brand a {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        color: #ffffff;
        text-decoration: none;
        transition: color .15s ease;
    }
    #sidebar .sb-brand a:hover { color: #7fb1ff; }

    /* Profile */
    #sidebar .sb-profile {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }
    #sidebar .sb-profile-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid rgba(255, 255, 255, 0.1);
        transition: border-color .15s ease, opacity .15s ease;
    }
    #sidebar .sb-profile-avatar:hover {
        border-color: rgba(13, 106, 237, 0.6);
        opacity: 0.9;
    }
    #sidebar .sb-profile-info {
        flex: 1;
        min-width: 0;
    }
    #sidebar .sb-profile-name {
        display: block;
        color: #ffffff;
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    #sidebar .sb-profile-role {
        display: block;
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.72rem;
        margin-top: 2px;
        letter-spacing: 0.02em;
    }
    #sidebar .sb-profile-edit {
        flex-shrink: 0;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        color: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: background-color .15s ease, color .15s ease;
    }
    #sidebar .sb-profile-edit:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
    }

    /* Navigation */
    #sidebar .sb-nav {
        padding: 8px 0;
    }

    /* Section header */
    #sidebar .sb-section {
        padding: 16px 20px 6px;
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.92);
    }

    /* Nav links */
    #sidebar .sb-link {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 9px 20px;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 1.2;
        text-decoration: none;
        transition: background-color .15s ease, color .15s ease;
    }
    #sidebar .sb-link > svg {
        flex-shrink: 0;
        width: 18px !important;
        height: 18px !important;
        opacity: 0.75;
        transition: opacity .15s ease, color .15s ease;
    }
    #sidebar .sb-link > span {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    #sidebar .sb-link:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #ffffff;
    }
    #sidebar .sb-link:hover > svg { opacity: 1; }

    /* Active link */
    #sidebar .sb-link.active {
        background: rgba(13, 106, 237, 0.14);
        color: #ffffff;
        font-weight: 600;
    }
    #sidebar .sb-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 6px;
        bottom: 6px;
        width: 3px;
        background: #0D6AED;
        border-radius: 0 3px 3px 0;
    }
    #sidebar .sb-link.active > svg { opacity: 1; }

    /* Logout */
    #sidebar .sb-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        padding: 8px 0;
    }
    #sidebar .sb-logout {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        padding: 11px 20px;
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.875rem;
        font-weight: 500;
        text-align: left;
        cursor: pointer;
        text-decoration: none;
        transition: background-color .15s ease, color .15s ease;
    }
    #sidebar .sb-logout > svg {
        flex-shrink: 0;
        width: 18px !important;
        height: 18px !important;
        opacity: 0.75;
        transition: opacity .15s ease;
    }
    #sidebar .sb-logout:hover {
        background: rgba(239, 68, 68, 0.10);
        color: #ffffff;
    }
    #sidebar .sb-logout:hover > svg { opacity: 1; color: #ef4444; }

    /* Scrollbar */
    #sidebar .sb-scroll {
        flex: 1 1 0;
        min-height: 0;
        overflow-y: auto;
        overflow-x: hidden;
    }
    #sidebar .sb-scroll::-webkit-scrollbar { width: 6px; }
    #sidebar .sb-scroll::-webkit-scrollbar-track { background: transparent; }
    #sidebar .sb-scroll::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 3px;
    }
    #sidebar .sb-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.16);
    }
</style>

@php
    $user = Auth::user();
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

    $userRole = 'Guest';
    if ($user) {
        if ($user->isAdmin()) {
            $userRole = 'Administrator';
        } elseif (method_exists($user, 'isSupplier') && $user->isSupplier()) {
            $userRole = 'Supplier';
        } elseif (method_exists($user, 'isSubSupplier') && $user->isSubSupplier()) {
            $userRole = 'Colleague';
        } elseif (method_exists($user, 'isBuyer') && $user->isBuyer()) {
            $userRole = 'Buyer';
        }
    }
@endphp

<div class="flex flex-col h-full min-h-0 flex-1">
    <!-- Brand -->
    <div class="sb-brand">
        <a href="{{ route('home') }}">SPANZ</a>
    </div>

    <!-- Profile Section -->
    <div class="sb-profile">
        <img src="{{ $profilePhotoUrl ?: asset('spanz-img/profile.jpg') }}" alt="Profile" class="sb-profile-avatar" id="profileImage" onclick="openProfileImageModal()">
        <div class="sb-profile-info">
            <span class="sb-profile-name" id="profileName">{{ $user?->name ?? 'Guest' }}</span>
            <span class="sb-profile-role">{{ $userRole }}</span>
        </div>
        <div class="sb-profile-edit" onclick="openEditModal()" title="Edit Profile">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>

    <!-- Scrollable Nav -->
    <div class="sb-scroll">
        <nav class="sb-nav">
            <!-- Overview -->
            <div class="sb-section">Overview</div>
            <a href="{{ route('dashboard') }}" class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="3" width="7" height="9" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
                    <rect x="14" y="3" width="7" height="5" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
                    <rect x="14" y="12" width="7" height="9" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
                    <rect x="3" y="16" width="7" height="5" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
                </svg>
                <span>Dashboard</span>
            </a>

            @if($user && !$user->isAdmin())
                {{-- ============================================ --}}
                {{-- MANAGE BUSINESS SETTINGS SECTION              --}}
                {{-- ============================================ --}}
                <div class="sb-section">Manage Business Settings</div>

                @if($user->isBuyer() || $user->isSupplier() || $user->isSubSupplier())
                    <a href="{{ route('tenders.my-tenders') }}" class="sb-link {{ request()->routeIs('tenders.my-tenders') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <rect x="9" y="3" width="6" height="4" rx="1" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M9 12h6M9 16h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                        <span>Posted RFXs</span>
                    </a>
                @endif

                @if($user->isBuyer() || $user->isSupplier() || $user->isSubSupplier())
                    <a href="{{ route('user.rfx-received') }}" class="sb-link {{ request()->routeIs('user.rfx-received*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 20V10M9 20V4M14 20v-6M19 20V8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M3 20h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                        <span>RFXs Received</span>
                    </a>
                    <a href="{{ route('user.messages') }}" class="sb-link {{ request()->routeIs('user.messages*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>My Messages</span>
                    </a>
                @endif

                @if($user->isBuyer() || $user->isSupplier() || $user->isSubSupplier())
                    <a href="{{ route('tenders.saved') }}" class="sb-link {{ request()->routeIs('tenders.saved') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Saved RFXs</span>
                    </a>

                    <a href="{{ route('tenders.create') }}" class="sb-link {{ request()->routeIs('tenders.create') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14 2v6h6M12 18v-6M9 15h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Post New RFX</span>
                    </a>
                @endif

                @if($user->isSupplier())
                    <a href="{{ route('suppliers.invite') }}" class="sb-link {{ request()->routeIs('suppliers.invite') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M19 8v6M22 11h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                        <span>Invite Colleagues</span>
                    </a>
                @endif

                @if($user->isSupplier() && $user->subSuppliers()->count() > 0)
                    <a href="{{ route('suppliers.sub-suppliers') }}" class="sb-link {{ request()->routeIs('suppliers.sub-suppliers') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Colleagues</span>
                    </a>
                @endif

                @if($user->isSupplier() || $user->isSubSupplier())
                    <a href="{{ route('suppliers.received-documents') }}" class="sb-link {{ request()->routeIs('suppliers.received-documents') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7 10l5 5 5-5M12 15V3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Downloaded Documents</span>
                    </a>
                @endif

                <a href="{{ route('account.profile') }}" class="sb-link {{ request()->routeIs('account.profile') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                    <span>View / Edit Profile</span>
                </a>

                @if($user->isBuyer() || $user->isSupplier() || $user->isSubSupplier())
                    <a href="{{ route('company.register', ['mode' => 'edit']) }}" class="sb-link {{ request()->routeIs('company.register') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>{{ $user->canEditCompanyProfile() ? 'Strengthen Profile' : 'View Company Profile' }}</span>
                    </a>
                @endif

                <a href="{{ route('home') }}" class="sb-link">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Back to SPANZ</span>
                </a>

                {{-- ============================================ --}}
                {{-- MANAGE MY ACCOUNT SECTION                     --}}
                {{-- ============================================ --}}
                <div class="sb-section">My Account</div>

                <a href="{{ route('account.profile') }}" class="sb-link {{ request()->routeIs('account.profile') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                    <span>View / Edit Profile</span>
                </a>

                @if($user->isBuyer() || $user->isSupplier() || $user->isSubSupplier())
                    <a href="{{ route('company.register', ['mode' => 'edit']) }}" class="sb-link {{ request()->routeIs('company.register') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>{{ $user->canEditCompanyProfile() ? 'Strengthen Profile' : 'View Company Profile' }}</span>
                    </a>
                @endif

                <a href="{{ route('account.plan') }}" class="sb-link {{ request()->routeIs('account.plan') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M3 10h18M8 4V2M16 4V2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M8 15l2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>My Current Plan</span>
                </a>

                <a href="{{ route('account.credits') }}" class="sb-link {{ request()->routeIs('account.credits') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M12 7v10M9 9.5h4.5a1.5 1.5 0 0 1 0 3H10.5a1.5 1.5 0 0 0 0 3H15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>My Credit Points</span>
                </a>
            @endif

            @if($user && $user->isAdmin())
                {{-- ============================================ --}}
                {{-- ADMIN MENU                                    --}}
                {{-- ============================================ --}}
                <div class="sb-section">Administration</div>

                <a href="{{ route('admin.users.index') }}" class="sb-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Users</span>
                </a>

                <a href="{{ route('admin.email-users.create') }}" class="sb-link {{ request()->routeIs('admin.email-users.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="m22 6-10 7L2 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Message User</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="sb-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="8" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <line x1="8" y1="12" x2="21" y2="12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <line x1="8" y1="18" x2="21" y2="18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <circle cx="4" cy="6" r="1.2" fill="currentColor"/>
                        <circle cx="4" cy="12" r="1.2" fill="currentColor"/>
                        <circle cx="4" cy="18" r="1.2" fill="currentColor"/>
                    </svg>
                    <span>Categories</span>
                </a>

                <a href="{{ route('admin.products.index') }}" class="sb-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 7l-8-4-8 4 8 4 8-4z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4 7v10l8 4 8-4V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 11v10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <span>Products</span>
                </a>

                <a href="{{ route('admin.purchase-requests.index') }}" class="sb-link {{ request()->routeIs('admin.purchase-requests.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4H6z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3 6h18M16 10a4 4 0 0 1-8 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Purchase Requests</span>
                </a>

                <div class="sb-section">Subscriptions</div>

                <a href="{{ route('admin.subscriptions.index') }}" class="sb-link {{ request()->routeIs(['admin.subscriptions.index', 'admin.subscriptions.create', 'admin.subscriptions.edit', 'admin.subscriptions.show']) ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="12" y1="1" x2="12" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <span>Manage Plans</span>
                </a>

                <a href="{{ route('admin.subscription-requests') }}" class="sb-link {{ request()->routeIs(['admin.subscription-requests', 'admin.subscriptions.show-request']) ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 19V5M5 12l7-7 7 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Upgrade Requests</span>
                </a>

                <a href="{{ route('admin.downgrade-requests.index') }}" class="sb-link {{ request()->routeIs('admin.downgrade-requests.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5v14M19 12l-7 7-7-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Downgrade Requests</span>
                </a>

                <a href="{{ route('admin.tender-view-pricing.index') }}" class="sb-link {{ request()->routeIs('admin.tender-view-pricing.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="7" cy="7" r="1.5" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                    <span>Tender View Pricing</span>
                </a>

                <div class="sb-section">Account</div>

                <a href="{{ route('account.profile') }}" class="sb-link {{ request()->routeIs('account.profile') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                    <span>View / Edit Profile</span>
                </a>

                <a href="{{ route('company.register', ['mode' => 'edit']) }}" class="sb-link {{ request()->routeIs('company.register') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Strengthen Profile</span>
                </a>
            @endif
        </nav>
    </div>

    <!-- Footer / Logout -->
    <div class="sb-footer">
        @if($user)
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="sb-logout">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="sb-logout">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 17l5-5-5-5M15 12H3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Login</span>
            </a>
        @endif
    </div>
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
</script>
