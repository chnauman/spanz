@extends('layouts.app')

@section('title', 'Dashboard - Spanz')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-indigo-600">Spanz</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-700">Welcome, {{ $user->name }}</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($user->isAdmin())
            <!-- Admin Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $total_users }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Pending Approvals</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $pending_approvals }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Active Subscriptions</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $total_subscriptions }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Admin Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @can('view-users')
                    <a href="#" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <h4 class="font-medium text-gray-900">Manage Users</h4>
                        <p class="text-sm text-gray-500">View and manage all users</p>
                    </a>
                    @endcan
                    
                    @can('view-categories')
                    <a href="#" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <h4 class="font-medium text-gray-900">Categories</h4>
                        <p class="text-sm text-gray-500">Manage tender categories</p>
                    </a>
                    @endcan
                    
                    @can('view-subscriptions')
                    <a href="#" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <h4 class="font-medium text-gray-900">Subscriptions</h4>
                        <p class="text-sm text-gray-500">Manage subscription plans</p>
                    </a>
                    @endcan
                    
                    @can('manage-credits')
                    <a href="#" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <h4 class="font-medium text-gray-900">Credits</h4>
                        <p class="text-sm text-gray-500">Manage user credits</p>
                    </a>
                    @endcan
                </div>
            </div>

        @elseif($user->isSupplier() || $user->isSubSupplier())
            <!-- Supplier Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Available Credits</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $total_credits }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Active Subscription</dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $active_subscription ? $active_subscription->subscription->name : 'None' }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- User Dashboard with Company Management -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Company Profile Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Company Profile</h3>
                            @if($user->companyDetail)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Profile Complete
                                </span>
                            @else
                                <a href="{{ route('company.register') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Complete Profile
                                </a>
                            @endif
                        </div>
                        
                        @if($user->companyDetail)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->companyDetail->company_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Website</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($user->companyDetail->website)
                                            <a href="{{ $user->companyDetail->website }}" target="_blank" class="text-indigo-600 hover:text-indigo-500">
                                                {{ $user->companyDetail->website }}
                                            </a>
                                        @else
                                            Not provided
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->companyDetail->description }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->companyDetail->address }}</dd>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No company profile</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by completing your company profile.</p>
                                <div class="mt-6">
                                    <a href="{{ route('company.register') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        Complete Profile
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="space-y-6">
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">My Tenders</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Total Tenders</span>
                                <span class="text-lg font-semibold text-gray-900">{{ $my_tenders ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Active Tenders</span>
                                <span class="text-lg font-semibold text-green-600">{{ $user->tenders()->where('status', 'active')->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Invitations</span>
                                <span class="text-lg font-semibold text-blue-600">{{ $my_invitations ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('tenders.create') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Post New Tender
                            </a>
                            <a href="{{ route('tenders.my-tenders') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                View My Tenders
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            @if(isset($recent_tenders) && $recent_tenders->count() > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Tenders</h3>
                <div class="space-y-4">
                    @foreach($recent_tenders as $tender)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $tender->title }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($tender->description, 100) }}</p>
                                <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                    <span>Budget: ${{ number_format($tender->budget, 2) }}</span>
                                    <span>Deadline: {{ $tender->deadline->format('M d, Y') }}</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tender->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($tender->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('tenders.detail', $tender) }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('tenders.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                        View all tenders →
                    </a>
                </div>
            </div>
            @endif
        @endif
    </div>
</div>
@endsection
