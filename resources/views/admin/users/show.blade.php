@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="p-6">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
            <p class="text-gray-600 mt-2">View profile, remaining credits, and posted tenders</p>
        </div>
        <div class="flex items-center justify-end gap-2 flex-shrink-0">
            <a href="{{ route('admin.users.edit', $user) }}"
               class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
               title="Edit User"
               aria-label="Edit User">
                <svg class="h-4 w-4" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500"
               title="Back to Users"
               aria-label="Back to Users">
                <svg class="h-4 w-4"  stroke="currentColor" viewBox="0 0 24 24" >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
        </div>
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
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Tenders Posted</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-5/12">Title</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">Status</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">Deadline</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">Budget</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($user->tenders as $tender)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900 max-w-xs">
                            <span class="block truncate" title="{{ $tender->titleHeadline() }}">{{ $tender->titleHeadline() }}</span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($tender->status === 'active') bg-green-100 text-green-800
                                @elseif($tender->status === 'closed') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($tender->status) }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ optional($tender->deadline)->format('M d, Y') ?: '—' }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $tender->budget ? ($tender->currency ? $tender->currency . ' ' : '') . number_format($tender->budget, 2) : '—' }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('tenders.detail', $tender->id) }}"
                               class="text-blue-600 hover:text-blue-900 inline-flex items-center p-2 rounded-md hover:bg-blue-50 transition-colors"
                               title="View"
                               aria-label="View">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 sm:px-6 py-4 text-center text-gray-500">No tenders posted by this user.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
