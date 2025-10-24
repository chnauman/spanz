@extends('layouts.admin')

@section('title', 'Update Plan')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">Update Plan</h1>
                <p class="text-sm text-blue-200">Manage your subscription plan and billing</p>
            </div>

            <div class="mt-6">
                <!-- Current Plan Section -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Current Plan</h2>
                    @if($activeSubscription)
                        <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div>
                                <h3 class="text-lg font-medium text-green-800">{{ $activeSubscription->subscription->name }}</h3>
                                <p class="text-green-600">Active until {{ $activeSubscription->expires_at->format('M d, Y') }}</p>
                            </div>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                Active
                            </span>
                        </div>
                    @else
                        <div class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div>
                                <h3 class="text-lg font-medium text-yellow-800">No Active Plan</h3>
                                <p class="text-yellow-600">You don't have an active subscription plan</p>
                            </div>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                Inactive
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Available Plans Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Available Plans</h2>

                    @if($subscriptions->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($subscriptions as $subscription)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                                    <div class="text-center">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $subscription->name }}</h3>
                                        <div class="text-3xl font-bold text-[#0D6AED] mb-2">
                                            ${{ number_format((float)$subscription->price, 2) }}
                                            <span class="text-sm font-normal text-gray-500">/{{ $subscription->billing_period }}</span>
                                        </div>
                                        <p class="text-gray-600 text-sm mb-4">{{ $subscription->description }}</p>

                                        <div class="space-y-2 mb-6">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $subscription->credits }} Credits
                                            </div>
                                            @if($subscription->features)
                                                @foreach(explode(',', $subscription->features) as $feature)
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ trim($feature) }}
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                        @if($activeSubscription && $activeSubscription->subscription_id == $subscription->id)
                                            <button class="w-full px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed" disabled>
                                                Current Plan
                                            </button>
                                        @else
                                            <a href="{{ route('subscription-requests.request', $subscription->id) }}"
                                               class="block w-full px-4 py-2 bg-[#0D6AED] text-white rounded-lg hover:bg-[#0B5AC7] transition-colors duration-200 text-center">
                                                {{ $activeSubscription ? 'Upgrade Plan' : 'Subscribe' }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">No subscription plans available at the moment.</p>
                        </div>
                    @endif
                </div>

                <!-- Subscription History -->
                @if($user->subscriptions()->count() > 0)
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Subscription History</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($user->subscriptions()->orderBy('created_at', 'desc')->get() as $userSubscription)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $userSubscription->subscription->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $userSubscription->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $userSubscription->is_active ? 'Active' : 'Expired' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $userSubscription->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $userSubscription->expires_at->format('M d, Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// SweetAlert2 helper functions
function showAlert(title, text, type = 'info') {
    Swal.fire({
        title: title,
        text: text,
        icon: type,
        confirmButtonText: 'OK',
        confirmButtonColor: '#0D6AED'
    });
}

function showSuccessAlert(title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'success',
        confirmButtonText: 'Great!',
        confirmButtonColor: '#10B981'
    });
}

function showErrorAlert(title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'error',
        confirmButtonText: 'Try Again',
        confirmButtonColor: '#EF4444'
    });
}
</script>
@endsection
