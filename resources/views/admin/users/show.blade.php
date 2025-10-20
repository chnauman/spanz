@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="p-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
            <p class="text-gray-600 mt-2">View profile, remaining credits, and posted tenders</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Back to Users</a>
    </div>

    <!-- Profile Summary -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center">
            <img class="h-16 w-16 rounded-full" src="{{ asset('spanz-img/profile.jpg') }}" alt="">
            <div class="ml-4">
                <div class="text-xl font-semibold text-gray-900">{{ $user->name }}</div>
                <div class="text-gray-600">{{ $user->email }}</div>
                <div class="mt-2">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($user->role === 'admin') bg-red-100 text-red-800
                        @elseif($user->role === 'buyer') bg-green-100 text-green-800
                        @elseif($user->role === 'supplier') bg-blue-100 text-blue-800
                        @elseif($user->role === 'sub_supplier') bg-purple-100 text-purple-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </span>
                    @if($user->is_approved)
                        <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                    @else
                        <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                    @endif
                    @php($plan = $user->getSubscriptionStatus())
                    <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($plan === 'enterprise') bg-purple-100 text-purple-800
                        @elseif($plan === 'pro') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($plan) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-sm text-gray-500">Joined</div>
                <div class="text-lg font-semibold text-gray-900">{{ $user->created_at->format('M d, Y') }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-sm text-gray-500">Company</div>
                <div class="text-lg font-semibold text-gray-900">{{ $user->companyDetail->company_name ?? 'N/A' }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-sm text-gray-500">Remaining Credits</div>
                <div class="text-lg font-semibold text-gray-900">
                    @php($remainingCredits = $user->getTotalCredits())
                    {{ $remainingCredits < 0 ? 'Unlimited' : number_format($remainingCredits) }}
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-sm text-gray-500">Tenders Posted</div>
                <div class="text-lg font-semibold text-gray-900">{{ $user->tenders->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Tenders List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Tenders Posted</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($user->tenders as $tender)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $tender->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($tender->status === 'active') bg-green-100 text-green-800
                                @elseif($tender->status === 'closed') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($tender->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ optional($tender->deadline)->format('M d, Y') ?: '—' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $tender->budget ? ($tender->currency ? $tender->currency . ' ' : '') . number_format($tender->budget, 2) : '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No tenders posted by this user.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
