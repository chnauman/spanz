@extends('layouts.admin')
@section('title', 'Admin Dashboard - SPANZ')
@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg bg-gradient-to-r from-[#092C48] to-[#0f4773] text-white px-5 py-4 sm:px-6 sm:py-5 space-y-2 sm:space-y-0 shadow-sm">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold leading-tight">Dashboard</h1>
                    @auth
                        <p class="text-xs sm:text-sm text-blue-100/80 mt-0.5">Welcome back, {{ auth()->user()->name }}</p>
                    @endauth
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="mt-5">

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                        {{ session('info') }}
                    </div>
                @endif

            @if(!$user->isAdmin() && !$user->companyDetail)
                <!-- Company Profile Completion Alert -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-blue-800">Complete Your Company Profile</h3>
                            <p class="text-sm text-blue-700 mt-1">To get the most out of SPANZ, please complete your company profile.</p>
                        </div>
                        <a href="{{ route('company.register') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition-colors">
                            Complete Profile
                        </a>
                    </div>
                </div>
            @endif

                @if($user->isAdmin())
                    <!-- Admin Dashboard -->
                      <!-- Quick Actions -->
                      <div class="bg-white rounded-lg shadow mb-8">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <a href="{{ route('admin.users.index') }}" class="bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors text-center">
                                    Manage Users
                                </a>
                                <a href="{{ route('admin.products.index') }}" style="background-color:rgb(25, 119, 99);" class=" text-white px-4 py-3 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors text-center">
                                    Manage Products
                                </a>
                                <a href="{{ route('admin.subscription-requests') }}" style="background-color:rgb(206, 186, 7);" class=" text-white px-4 py-3 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors text-center">
                                    Subscription Requests
                                </a>
                               
                                <a href="{{ route('admin.downgrade-requests.index') }}" style="background-color:rgb(163, 33, 16);" class=" text-white px-4 py-3 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors text-center">
                                    Downgrade Requests
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User Analytics Section -->
                    @if(isset($chart_months))
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                            <!-- User Growth Chart -->
                            <div class="bg-white rounded-lg shadow">
                                <div class="px-6 py-4 border-b border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900">User Growth (Last 6 Months)</h3>
                                </div>
                                <div class="p-6">
                                    <canvas id="userGrowthChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                  
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                            <!-- User Distribution Chart -->
                            <div class="bg-white rounded-lg shadow">
                                <div class="px-6 py-4 border-b border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900">User Distribution by Role</h3>
                                </div>
                                <div class="p-6">
                                    <canvas id="userDistributionChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="bg-white rounded-lg shadow mb-8">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">User Analytics</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <a href="{{ route('admin.users.index', ['role' => 'buyer']) }}" class="bg-blue-50 rounded-lg p-4 hover:bg-blue-100 hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <p class="text-sm font-medium text-gray-600">Buyers</p>
                                    <p class="text-2xl font-bold text-blue-600 mt-1 group-hover:text-blue-700 transition-colors">{{ $total_buyers ?? 0 }}</p>
                                </a>
                                <a href="{{ route('admin.users.index', ['role' => 'supplier']) }}" class="bg-green-50 rounded-lg p-4 hover:bg-green-100 hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <p class="text-sm font-medium text-gray-600">Suppliers</p>
                                    <p class="text-2xl font-bold text-green-600 mt-1 group-hover:text-green-700 transition-colors">{{ $total_suppliers ?? 0 }}</p>
                                </a>
                                <a href="{{ route('admin.users.index', ['role' => 'sub_supplier']) }}" class="bg-purple-50 rounded-lg p-4 hover:bg-purple-100 hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <p class="text-sm font-medium text-gray-600">Sub Suppliers</p>
                                    <p class="text-2xl font-bold text-purple-600 mt-1 group-hover:text-purple-700 transition-colors">{{ $total_sub_suppliers ?? 0 }}</p>
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="bg-yellow-50 rounded-lg p-4 hover:bg-yellow-100 hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <p class="text-sm font-medium text-gray-600">New Users (This Month)</p>
                                    <p class="text-2xl font-bold text-yellow-600 mt-1 group-hover:text-yellow-700 transition-colors">{{ $new_users_this_month ?? 0 }}</p>
                                    @if(isset($new_users_last_month) && $new_users_last_month > 0)
                                    <p class="text-xs text-gray-500 mt-1">
                                        @php
                                            $growth = (($new_users_this_month - $new_users_last_month) / $new_users_last_month) * 100;
                                        @endphp
                                        @if($growth > 0)
                                            <span class="text-green-600">↑ {{ number_format($growth, 1) }}%</span>
                                        @elseif($growth < 0)
                                            <span class="text-red-600">↓ {{ number_format(abs($growth), 1) }}%</span>
                                        @else
                                            <span class="text-gray-600">→ 0%</span>
                                        @endif
                                        vs last month
                                    </p>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                    @if(isset($chart_months))
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        

                            <!-- Tender Growth Chart -->
                            <div class="bg-white rounded-lg shadow">
                                <div class="px-6 py-4 border-b border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900">Tender Growth (Last 6 Months)</h3>
                                </div>
                                <div class="p-6">
                                    <canvas id="tenderGrowthChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                   
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    
                        <!-- Tender Status Chart -->
                        <div class="bg-white rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Tender Status Distribution</h3>
                            </div>
                            <div class="p-6">
                                <canvas id="tenderStatusChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- Tender Analytics Section -->
                    <div class="bg-white rounded-lg shadow mb-8">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Tender Analytics</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                                <a href="{{ route('tenders.search') }}" class="bg-green-50 rounded-lg p-4 hover:bg-green-100 hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <p class="text-sm font-medium text-gray-600">Active Tenders</p>
                                    <p class="text-2xl font-bold text-green-600 mt-1 group-hover:text-green-700 transition-colors">{{ $active_tenders ?? 0 }}</p>
                                </a>
                               
                                <a href="{{ route('tenders.search') }}" class="bg-red-50 rounded-lg p-4 hover:bg-red-100 hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <p class="text-sm font-medium text-gray-600">Expired Tenders</p>
                                    <p class="text-2xl font-bold text-red-600 mt-1 group-hover:text-red-700 transition-colors">{{ $expired_tenders ?? 0 }}</p>
                                </a>
                                <a href="{{ route('tenders.search') }}" class="bg-blue-50 rounded-lg p-4 hover:bg-blue-100 hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <p class="text-sm font-medium text-gray-600">Total Tenders</p>
                                    <p class="text-2xl font-bold text-blue-600 mt-1 group-hover:text-blue-700 transition-colors">{{ $total_tenders ?? 0 }}</p>
                                </a>
                                <a href="{{ route('tenders.search') }}" class="bg-orange-50 rounded-lg p-4 hover:bg-orange-100 hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <p class="text-sm font-medium text-gray-600">Tenders This Month</p>
                                    <p class="text-2xl font-bold text-orange-600 mt-1 group-hover:text-orange-700 transition-colors">{{ $tenders_this_month ?? 0 }}</p>
                                    @if(isset($tenders_last_month) && $tenders_last_month > 0)
                                    <p class="text-xs text-gray-500 mt-1">
                                        @php
                                            $tender_growth = (($tenders_this_month - $tenders_last_month) / $tenders_last_month) * 100;
                                        @endphp
                                        @if($tender_growth > 0)
                                            <span class="text-green-600">↑ {{ number_format($tender_growth, 1) }}%</span>
                                        @elseif($tender_growth < 0)
                                            <span class="text-red-600">↓ {{ number_format(abs($tender_growth), 1) }}%</span>
                                        @else
                                            <span class="text-gray-600">→ 0%</span>
                                        @endif
                                        vs last month
                                    </p>
                                    @endif
                                </a>
                            </div>
  
                        </div>
                    </div>
                    <!-- Charts Section -->
                    @if(isset($chart_months))
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    
                            <!-- Subscription Growth Chart -->
                            <div class="bg-white rounded-lg shadow">
                                <div class="px-6 py-4 border-b border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900">Subscription Growth (Last 6 Months)</h3>
                                </div>
                                <div class="p-6">
                                    <canvas id="subscriptionGrowthChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- Subscription Analytics Section -->
                    <div class="bg-white rounded-lg shadow mb-8">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Subscription Analytics</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <a href="{{ route('admin.subscriptions.index') }}" class="bg-green-50 rounded-lg p-4 hover:bg-green-100 hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <p class="text-sm font-medium text-gray-600">Active Subscriptions</p>
                                    <p class="text-2xl font-bold text-green-600 mt-1 group-hover:text-green-700 transition-colors">{{ $total_subscriptions ?? 0 }}</p>
                                </a>
                                <a href="{{ route('admin.subscriptions.index') }}" class="bg-blue-50 rounded-lg p-4 hover:bg-blue-100 hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <p class="text-sm font-medium text-gray-600">Subscription Plans</p>
                                    <p class="text-2xl font-bold text-blue-600 mt-1 group-hover:text-blue-700 transition-colors">{{ $total_subscription_plans ?? 0 }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $active_subscription_plans ?? 0 }} active plans</p>
                                </a>
                                <a href="{{ route('admin.subscription-requests') }}" class="bg-yellow-50 rounded-lg p-4 hover:bg-yellow-100 hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <p class="text-sm font-medium text-gray-600">Pending Requests</p>
                                    <p class="text-2xl font-bold text-yellow-600 mt-1 group-hover:text-yellow-700 transition-colors">{{ $pending_subscription_requests ?? 0 }}</p>
                                </a>
                                <a href="{{ route('admin.downgrade-requests.index') }}" class="bg-red-50 rounded-lg p-4 hover:bg-red-100 hover:shadow-md transition-all duration-200 cursor-pointer group">
                                    <p class="text-sm font-medium text-gray-600">Downgrade Requests</p>
                                    <p class="text-2xl font-bold text-red-600 mt-1 group-hover:text-red-700 transition-colors">{{ $pending_downgrade_requests ?? 0 }}</p>
                                </a>
                            </div>
                            @if(isset($expiring_subscriptions) && $expiring_subscriptions > 0)
                            <div class="mt-6 bg-orange-50 border border-orange-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-orange-800">
                                        {{ $expiring_subscriptions }} subscription(s) expiring in the next 30 days
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                   
                @else
                    {{-- ============================================ --}}
                    {{-- MANAGE BUSINESS SETTINGS - quick actions      --}}
                    {{-- ============================================ --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
                        <div class="px-6 py-4 border-b border-white/10 bg-gradient-to-r from-[#092C48] to-[#0f4773]">
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-1 h-5 bg-[#5aa9ff] rounded-full" aria-hidden="true"></span>
                                <h3 class="text-base sm:text-lg font-semibold text-white">Manage Business Settings</h3>
                            </div>
                        </div>
                        <div class="p-5 sm:p-6">
                            {{-- Plain CSS grid so layout survives stale Tailwind builds on production; same 4 tiles as local buyer dashboard for all roles. --}}
                            <style>
                                .spanz-manage-business-actions {
                                    display: grid !important;
                                    grid-template-columns: repeat(4, minmax(0, 1fr));
                                    gap: 1rem;
                                    width: 100%;
                                }
                                @media (max-width: 767.98px) {
                                    .spanz-manage-business-actions {
                                        grid-template-columns: repeat(2, minmax(0, 1fr));
                                    }
                                }
                                .spanz-manage-business-actions > a {
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    text-align: center;
                                    text-decoration: none;
                                    min-height: 130px;
                                    padding: 1rem;
                                    border-radius: 0.75rem;
                                    color: #2563eb;
                                    font-size: 0.8125rem;
                                    font-weight: 600;
                                    line-height: 1.35;
                                    transition: background-color 0.2s ease, transform 0.2s ease;
                                }
                                .spanz-manage-business-actions > a:hover {
                                    background-color: rgba(239, 246, 255, 0.85);
                                    color: #1e40af;
                                }
                            </style>
                            @if($user->isBuyer() || $user->isSupplier() || $user->isSubSupplier())
                            <div class="spanz-manage-business-actions">
                                <a href="{{ route('tenders.my-tenders') }}">
                                    <span class="mb-2 sm:mb-3 inline-flex transition-transform duration-200" style="transform-origin:center">
                                        <svg width="56" height="56" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M9 2h6a1 1 0 011 1v1h3a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2h3V3a1 1 0 011-1zm1 2v2h4V4h-4zM7 10h2v2H7v-2zm0 4h2v2H7v-2zm4-4h6v2h-6v-2zm0 4h6v2h-6v-2z" fill="#3B82F6"/>
                                        </svg>
                                    </span>
                                    My Posted<br>Projects / RFXs
                                </a>
                                <a href="{{ route('tenders.saved') }}">
                                    <span class="mb-2 sm:mb-3 inline-flex">
                                        <svg width="56" height="56" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M7 5.75h10a.75.75 0 0 1 .75.75v12.2a.3.3 0 0 1-.46.25L12 15.4l-5.29 3.55a.3.3 0 0 1-.46-.25V6.5A.75.75 0 0 1 7 5.75z" fill="#3B82F6"/>
                                        </svg>
                                    </span>
                                    My Saved<br>Projects / RFXs
                                </a>
                                <a href="{{ route('tenders.create') }}">
                                    <span class="mb-2 sm:mb-3 inline-flex">
                                        <svg width="56" height="56" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 7V3.5L18.5 9H13z" fill="#3B82F6"/>
                                            <path d="M12 11v6m-3-3h6" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    Post New<br>Project / RFX
                                </a>
                                <a href="{{ route('company.register') }}">
                                    <span class="mb-2 sm:mb-3 inline-flex">
                                        <svg width="56" height="56" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="#3B82F6"/>
                                            <circle cx="18" cy="6" r="3" fill="#10B981"/>
                                            <path d="M16.5 5.5h3M18 4v3" stroke="#ffffff" stroke-width="1.2" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    Edit / Strengthen<br>Profile
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Activity Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-white/10 bg-gradient-to-r from-[#092C48] to-[#0f4773]">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-1 h-5 bg-[#5aa9ff] rounded-full" aria-hidden="true"></span>
                        <h3 class="text-base sm:text-lg font-semibold text-white">
                            @if(auth()->user()->isAdmin())
                                Recent Activity
                            @else
                                Tenders Matching Your Interests
                            @endif
                        </h3>
                    </div>
                </div>
                        <div class="p-6">
                    @if($recent_tenders && $recent_tenders->count() > 0)
                            <div class="space-y-4">
                            @foreach($recent_tenders->take(5) as $tender)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $tender->titleHeadline() }}</p>
                                            <p class="text-sm text-gray-500">
                                                @if($tender->category)
                                                    {{ $tender->category->name }} • 
                                                @endif
                                                Posted {{ $tender->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $tender->status ?? 'Active' }}
                                        </span>
                                        <a href="{{ route('tenders.detail', $tender->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">
                                @if(auth()->user()->isAdmin())
                                    No recent activity
                                @else
                                    No matching tenders found
                                @endif
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if(auth()->user()->isAdmin())
                                    Get started by creating your first tender.
                                @else
                                    @if(!auth()->user()->interests_set)
                                        Set up your interests to see matching tenders.
                                    @else
                                        No new tenders match your interests and budget preferences at the moment.
                                    @endif
                                @endif
                            </p>
                            <div class="mt-6">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('tenders.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Create Tender
                                    </a>
                                @else
                                    @if(!auth()->user()->interests_set)
                                        <a href="{{ route('user.interests') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Set Up Interests
                                        </a>
                                    @else
                                        <a href="{{ route('tenders.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Browse All Tenders
                                        </a>
                                    @endif
                                @endif
                            </div>
                            </div>
                    @endif
                </div>
            </div>
        </div>
        </div>
    </div>

@if($user->isAdmin() && isset($chart_months))
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart data from PHP
    const months = @json($chart_months);
    const userCounts = @json($chart_user_counts);
    const tenderCounts = @json($chart_tender_counts);
    const subscriptionCounts = @json($chart_subscription_counts);
    
    const totalBuyers = {{ $total_buyers ?? 0 }};
    const totalSuppliers = {{ $total_suppliers ?? 0 }};
    const totalSubSuppliers = {{ $total_sub_suppliers ?? 0 }};
    
    const activeTenders = {{ $active_tenders ?? 0 }};
    const closedTenders = {{ $closed_tenders ?? 0 }};
    const expiredTenders = {{ $expired_tenders ?? 0 }};
    
    const totalCreditsAllocated = {{ $total_credits_allocated ?? 0 }};
    const totalCreditsUsed = {{ $total_credits_used ?? 0 }};
    const totalCreditsRemaining = {{ $total_credits_remaining ?? 0 }};

    // Chart.js default configuration
    Chart.defaults.responsive = true;
    Chart.defaults.maintainAspectRatio = false;

    // User Growth Chart (Line Chart)
    const userGrowthCtx = document.getElementById('userGrowthChart');
    if (userGrowthCtx) {
        new Chart(userGrowthCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'New Users',
                    data: userCounts,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Tender Growth Chart (Line Chart)
    const tenderGrowthCtx = document.getElementById('tenderGrowthChart');
    if (tenderGrowthCtx) {
        new Chart(tenderGrowthCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'New Tenders',
                    data: tenderCounts,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // User Distribution Chart (Doughnut Chart)
    const userDistributionCtx = document.getElementById('userDistributionChart');
    if (userDistributionCtx) {
        new Chart(userDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Buyers', 'Suppliers', 'Sub Suppliers'],
                datasets: [{
                    data: [totalBuyers, totalSuppliers, totalSubSuppliers],
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(34, 197, 94)',
                        'rgb(168, 85, 247)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Tender Status Chart (Doughnut Chart)
    const tenderStatusCtx = document.getElementById('tenderStatusChart');
    if (tenderStatusCtx) {
        new Chart(tenderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Closed', 'Expired'],
                datasets: [{
                    data: [activeTenders, closedTenders, expiredTenders],
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(107, 114, 128)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Credit Usage Chart (Doughnut Chart)
    const creditUsageCtx = document.getElementById('creditUsageChart');
    if (creditUsageCtx) {
        new Chart(creditUsageCtx, {
            type: 'doughnut',
            data: {
                labels: ['Used', 'Remaining'],
                datasets: [{
                    data: [totalCreditsUsed, totalCreditsRemaining],
                    backgroundColor: [
                        'rgb(239, 68, 68)',
                        'rgb(59, 130, 246)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Subscription Growth Chart (Line Chart)
    const subscriptionGrowthCtx = document.getElementById('subscriptionGrowthChart');
    if (subscriptionGrowthCtx) {
        new Chart(subscriptionGrowthCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Active Subscriptions',
                    data: subscriptionCounts,
                    borderColor: 'rgb(168, 85, 247)',
                    backgroundColor: 'rgba(168, 85, 247, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});
</script>
@endif
@endsection
