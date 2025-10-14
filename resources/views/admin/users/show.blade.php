@extends('layouts.admin')

@section('title', 'User Details')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                <p class="text-gray-600 mt-2">{{ $user->email }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    Back to Users
                </a>
                @if(!$user->is_approved && in_array($user->role, ['supplier', 'sub_supplier']))
                    <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Approve User
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">User Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Full Name</label>
                        <p class="text-lg text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Email</label>
                        <p class="text-lg text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Role</label>
                        <p class="text-lg">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($user->role === 'admin') bg-red-100 text-red-800
                                @elseif($user->role === 'buyer') bg-green-100 text-green-800
                                @elseif($user->role === 'supplier') bg-blue-100 text-blue-800
                                @elseif($user->role === 'sub_supplier') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Status</label>
                        <p class="text-lg">
                            @if($user->is_approved)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Approved
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending Approval
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Member Since</label>
                        <p class="text-lg text-gray-900">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Last Updated</label>
                        <p class="text-lg text-gray-900">{{ $user->updated_at->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Company Information -->
            @if($user->companyDetail)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Company Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Company Name</label>
                        <p class="text-lg text-gray-900">{{ $user->companyDetail->company_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Industry</label>
                        <p class="text-lg text-gray-900">{{ $user->companyDetail->industry ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Company Size</label>
                        <p class="text-lg text-gray-900">{{ $user->companyDetail->company_size ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Location</label>
                        <p class="text-lg text-gray-900">{{ $user->companyDetail->location ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- User Interests -->
            @if($user->interests->count() > 0)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">User Interests</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->interests as $interest)
                    <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                        {{ $interest->category->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- User Activity -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">User Activity</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $user->tenders->count() }}</div>
                        <div class="text-sm text-gray-600">Tenders Created</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $user->tenderInvitations->count() }}</div>
                        <div class="text-sm text-gray-600">Invitations Received</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $user->savedTenders->count() }}</div>
                        <div class="text-sm text-gray-600">Saved Tenders</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Subscription Information -->
            @if($user->subscriptions->count() > 0)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Subscriptions</h3>
                @foreach($user->subscriptions as $subscription)
                <div class="mb-4 p-3 border rounded-lg">
                    <div class="font-medium text-gray-900">{{ $subscription->subscription->name }}</div>
                    <div class="text-sm text-gray-600">
                        @if($subscription->is_active)
                            <span class="text-green-600">Active</span>
                        @else
                            <span class="text-red-600">Inactive</span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-500">
                        Expires: {{ $subscription->expires_at ? $subscription->expires_at->format('M d, Y') : 'Never' }}
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    @if(!$user->is_approved && in_array($user->role, ['supplier', 'sub_supplier']))
                        <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Approve User
                            </button>
                        </form>
                    @endif
                    
                    @if($user->is_approved && in_array($user->role, ['supplier', 'sub_supplier']))
                        <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                                Reject User
                            </button>
                        </form>
                    @endif
                    
                    @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                              class="w-full" 
                              onsubmit="return confirm('Are you sure you want to delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Delete User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
