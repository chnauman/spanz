@extends('layouts.admin')

@section('title', 'Update Plan')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    @keyframes slideInUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }

    .animate-slide-in {
        animation: slideInUp 0.6s ease-out;
    }

    .animate-pulse-slow {
        animation: pulse 2s infinite;
    }

    .shimmer-effect {
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }

    .glow-effect {
        box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
    }

    .silver-glow {
        box-shadow: 0 0 20px rgba(192, 192, 192, 0.3);
    }

    .subscription-card {
        transition: all 0.3s ease;
        position: relative;
    }

    /* Requested button styling - always visible */
    .subscription-card button.bg-yellow-500 {
        background-color: #eab308 !important;
        color: white !important;
        cursor: not-allowed !important;
    }

    /* Current Plan button styling - always visible */
    .subscription-card button.bg-gray-400 {
        background-color: #9ca3af !important;
        color: white !important;
        cursor: not-allowed !important;
    }

    /* Current Plan button styling for green variant */
    .subscription-card button.bg-green-500 {
        background-color: #10b981 !important;
        color: white !important;
        cursor: not-allowed !important;
    }
</style>
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
                                <p class="text-green-600">Active until {{ $activeSubscription->expires_at ? \Carbon\Carbon::parse($activeSubscription->expires_at)->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                    Active
                                </span>
                                @if($activeSubscription->subscription->name !== 'Basic')
                                    @if($pendingDowngradeRequest)
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                            Downgrade Requested
                                        </span>
                                        <a href="{{ route('downgrade-requests.my-requests') }}"
                                           class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium hover:bg-blue-200 transition-colors duration-200">
                                            View Request
                                        </a>
                                    @elseif($approvedDowngradeRequest)
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                            Downgrade Approved
                                        </span>
                                        <a href="{{ route('downgrade-requests.my-requests') }}"
                                           class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium hover:bg-blue-200 transition-colors duration-200">
                                            View Details
                                        </a>
                                    @elseif($declinedDowngradeRequest)
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                            Downgrade Declined
                                        </span>
                                        <a href="{{ route('downgrade-requests.my-requests') }}"
                                           class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium hover:bg-blue-200 transition-colors duration-200">
                                            View Details
                                        </a>
                                    @else
                                        <a href="{{ route('downgrade-requests.create') }}"
                                           class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-medium hover:bg-orange-200 transition-colors duration-200">
                                            Request Downgrade
                                        </a>
                                    @endif
                                @endif
                            </div>
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
                        @include('components.pricing-intro-banner')

                        <div class="flex flex-wrap justify-center gap-4 mt-3 xl:flex-nowrap xl:justify-between" style="min-height: 3.6in;">
                            @foreach($subscriptions as $index => $subscription)
                                @if($subscription->is_active)
                                    @php
                                        $userHasActiveSubscription = auth()->check() && $user->getActiveSubscription();
                                        $isBasicPlan = in_array(strtolower($subscription->name), ['basic', 'buyer']);
                                        $shouldShowBasic = !$userHasActiveSubscription;
                                        $shouldHideBasic = $userHasActiveSubscription && $isBasicPlan;
                                    @endphp

                                    @if(!$shouldHideBasic)
                                        <div class="subscription-card relative group cursor-pointer {{ $index === 0 ? 'active' : '' }}"
                                             data-plan="{{ strtolower($subscription->name) }}"
                                             data-subscription-id="{{ $subscription->id }}">
                                            <div class="bg-white border-2 border-gray-200 rounded-2xl p-4 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1" style="width: 3in; min-height: 4.6in;">
                                                @if($subscription->name === 'Professional')
                                                    <!-- Most Popular Badge -->
                                                    <div class="absolute -top-2 right-2 most-popular-badge z-20">
                                                        <div class="bg-[#0D6AED] text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                                            MOST POPULAR
                                            </div>
                                                    </div>
                                                @endif

                                                <div class="text-center h-full flex flex-col justify-between">
                                                    <div>
                                                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $subscription->name }}</h3>
                                                        <div class="text-3xl font-bold text-gray-900 mb-2">
                                                            AU${{ number_format((float) $subscription->price, 0) }}
                                                            <span class="text-sm text-gray-500">/mo.</span>
                                                        </div>
                                                        <p class="text-gray-600 text-sm mb-3">{{ $subscription->description ?? 'Buyer + Supplier' }}</p>

                                                        @if(!empty($subscription->features))
                                                            <ul class="text-left text-xs text-gray-700 space-y-1.5 mb-4">
                                                                @foreach($subscription->features as $feature)
                                                                    <li class="flex items-start gap-2">
                                                                        <span class="mt-0.5 text-[#0D6AED]">✓</span>
                                                                        <span>{{ $feature }}</span>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif

                                                         <!-- Quota/Credits Display -->
                                                         <div class="bg-blue-50 rounded-lg p-3 mb-4">
                                                             <div class="text-lg font-semibold text-[#0D6AED] mb-1">
                                                                 @if($subscription->credits_per_month < 0)
                                                                     Unlimited Credits
                                                                 @elseif($subscription->credits_per_month == 0)
                                                                     No Credits
                                                                 @else
                                                                     {{ $subscription->credits_per_month }} Credits
                                            @endif
                                                             </div>
                                                             <p class="text-xs text-gray-800 font-medium">To view tenders and buyers</p>
                                                         </div>
                                        </div>

                                                    <div class="text-center">
                                                        @auth
                                                            @if($user->getActiveSubscription() && $user->getActiveSubscription()->subscription_id == $subscription->id)
                                                                <button class="w-full bg-gray-400 text-white px-4 py-3 rounded-lg text-base font-semibold cursor-not-allowed">
                                                                    Current Plan
                                                                </button>
                                                            @elseif($isBasicPlan && !$userHasActiveSubscription)
                                                                <button class="w-full bg-gray-400 text-white px-4 py-3 rounded-lg text-base font-semibold cursor-not-allowed">
                                                Current Plan
                                            </button>
                                        @else
                                                                <button class="w-full bg-[#092C48] text-white px-4 py-3 rounded-lg text-base font-semibold hover:bg-[#0D6AED] transition-all duration-300 transform hover:scale-105"
                                                                        onclick="selectSubscriptionPlan({{ $subscription->id }}, '{{ strtolower($subscription->name) }}', this)">
                                                                    Choose Plan
                                            </button>
                                        @endif
                                                        @else
                                                            <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}"
                                                               class="w-full bg-[#092C48] text-white px-4 py-3 rounded-lg text-base font-semibold hover:bg-[#0D6AED] transition-all duration-300 transform hover:scale-105 inline-block text-center">
                                                                Choose Plan
                                                            </a>
                                                        @endauth
                                                    </div>
                                                </div>
                                    </div>
                                </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>

                        @include('components.pricing-notes-footer')
                        <div id="subscriptionRequestConsentBox" class="mt-6 rounded-xl border border-gray-200 bg-white p-4">
                            <label class="flex items-start gap-3 text-sm text-gray-700">
                                <input id="pricingTermsCheckbox" type="checkbox" class="mt-1 h-4 w-4 rounded border-gray-300 text-[#092C48] focus:ring-[#092C48]">
                                <span>I have read the SPANZ Terms &amp; Conditions and fully agree with them.</span>
                            </label>
                            <div class="mt-4">
                                <button id="submitSubscriptionRequestBtn" type="button" class="w-full bg-[#092C48] text-white px-4 py-3 rounded-lg text-base font-semibold hover:bg-[#0D6AED] transition-all duration-300 transform hover:scale-105 disabled:opacity-40 disabled:bg-gray-400 disabled:hover:bg-gray-400 disabled:cursor-not-allowed disabled:hover:cursor-not-allowed disabled:transform-none" disabled onclick="submitSelectedSubscriptionRequest()">
                                    Submit Request
                                </button>
                            </div>
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
                                        {{ $userSubscription->created_at ? \Carbon\Carbon::parse($userSubscription->created_at)->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $userSubscription->expires_at ? \Carbon\Carbon::parse($userSubscription->expires_at)->format('M d, Y') : 'N/A' }}
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
    // Handle card selection and button clicks
    document.addEventListener('DOMContentLoaded', function() {
        clearStaleLocalStorage();
        checkSubscriptionStatus();
        checkDowngradeRequestStatus();
        initializeSubscriptionSubmitControls();
    });

    let selectedSubscriptionId = null;
    let selectedPlanName = null;

    function clearStaleLocalStorage() {
        // Clear any stale localStorage entries that don't have corresponding database records
        const cards = document.querySelectorAll('.subscription-card');
        cards.forEach(card => {
            const subscriptionId = card.getAttribute('data-subscription-id');
            if (subscriptionId) {
                // We'll let the server response determine if localStorage should be cleared
                // This function is just a placeholder for future cleanup logic
            }
        });
    }

    function initializeSubscriptionSubmitControls() {
        const checkbox = document.getElementById('pricingTermsCheckbox');
        if (checkbox) {
            checkbox.addEventListener('change', updateSubmitRequestButtonState);
        }
        updateSubmitRequestButtonState();
    }

    function selectSubscriptionPlan(subscriptionId, planName, button) {
        if (button.disabled) return;

        selectedSubscriptionId = String(subscriptionId);
        selectedPlanName = planName;

        // Visual selection: only the clicked "Choose Plan" button changes color
        const allButtons = document.querySelectorAll('.subscription-card button');
        allButtons.forEach(btn => {
            const label = (btn.textContent || '').trim().toLowerCase();
            if (btn.disabled || label === 'requested' || label === 'current plan' || label === 'request pending') return;

            btn.textContent = 'Choose Plan';
            btn.classList.remove('bg-yellow-500', 'ring-2', 'ring-yellow-300');
            btn.classList.add('bg-[#092C48]', 'hover:bg-[#0D6AED]');
        });

        button.textContent = 'Selected';
        button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]');
        button.classList.add('bg-yellow-500', 'ring-2', 'ring-yellow-300');

        updateSubmitRequestButtonState();
    }

    function updateSubmitRequestButtonState() {
        const submitButton = document.getElementById('submitSubscriptionRequestBtn');
        const termsCheckbox = document.getElementById('pricingTermsCheckbox');
        if (!submitButton || !termsCheckbox) return;

        submitButton.disabled = !(selectedSubscriptionId && termsCheckbox.checked);
    }

    function submitSelectedSubscriptionRequest() {
        const submitButton = document.getElementById('submitSubscriptionRequestBtn');
        const termsCheckbox = document.getElementById('pricingTermsCheckbox');

        if (!selectedSubscriptionId) {
            showNotification('Please choose a plan first.', 'error');
            return;
        }

        if (!termsCheckbox || !termsCheckbox.checked) {
            showNotification('Please accept Terms & Conditions first.', 'error');
            return;
        }

        const selectedCard = document.querySelector(`.subscription-card[data-subscription-id="${selectedSubscriptionId}"]`);
        const selectedCardButton = selectedCard ? selectedCard.querySelector('button') : null;
        if (!selectedCardButton || selectedCardButton.disabled) {
            showNotification('Selected plan is not available for request.', 'error');
            return;
        }

        submitButton.disabled = true;
        submitButton.textContent = 'Submitting...';
        requestSubscription(selectedSubscriptionId, selectedPlanName, selectedCardButton, submitButton);
    }

    function requestSubscription(subscriptionId, planName, button, submitButton = null) {
        console.log('Requesting subscription:', { subscriptionId, planName });
        console.log('Subscription ID type:', typeof subscriptionId);
        console.log('Subscription ID value:', subscriptionId);

        // Check if user is authenticated by looking for auth indicators in the page
        const isAuthenticated = document.querySelector('a[href*="logout"]') !== null ||
                               document.querySelector('a[href*="dashboard"]') !== null;

        if (!isAuthenticated) {
            // Redirect to login page with current URL as redirect
            window.location.href = `{{ route('login') }}?redirect=${encodeURIComponent(window.location.href)}`;
            return;
        }

        // Test if basic routing is working first
        fetch('/test-subscription-route')
            .then(response => response.json())
            .then(data => {
                console.log('Test route response:', data);
            })
            .catch(error => {
                console.error('Test route error:', error);
            });

        // Show loading state
        const originalText = button.textContent;
        button.textContent = 'Processing...';
        button.disabled = true;
        button.classList.add('opacity-75', 'cursor-not-allowed');

        // Set a flag to prevent status checks from interfering
        window.subscriptionRequestInProgress = true;

        // Log the request start
        console.log('Starting subscription request for:', subscriptionId);

        // Use the fallback route that doesn't use model binding
        const url = `/subscription-requests-by-id/${subscriptionId}`;
        console.log('Making request to:', url);

        // Make AJAX request
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);

            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error('You must be logged in to request a subscription. Please login first.');
                } else if (response.status === 404) {
                    throw new Error('Subscription not found.');
                } else if (response.status === 500) {
                    throw new Error('Server error. Please try again later.');
                } else {
                    throw new Error('Request failed. Please try again.');
                }
            }

            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.error('Response is not JSON, content-type:', contentType);
                // Let's see what the actual response is
                return response.text().then(text => {
                    console.error('Server response (HTML):', text.substring(0, 500));
                    throw new Error('Server returned HTML instead of JSON. Please check server logs.');
                });
            }

            return response.json();
        })
        .catch(error => {
            console.error('JSON parsing error:', error);
            throw new Error('Server returned invalid response. Please try again.');
        })
        .then(data => {
            console.log('Subscription request response:', data);
            if (data.success) {
                // Show success message
                showNotification('Subscription request submitted successfully!', 'success');
                console.log('Subscription request successful');

                // Update button state to "Requested" immediately (don't close modal)
                button.textContent = 'Requested';
                button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'opacity-75', 'hover:scale-105');
                button.classList.add('bg-yellow-500', 'cursor-not-allowed');
                button.disabled = true;

                // Force the styling to be applied immediately
                button.style.backgroundColor = '#eab308';
                button.style.color = 'white';
                button.style.cursor = 'not-allowed';

                // Store the request state in localStorage
                localStorage.setItem(`subscription_request_${subscriptionId}`, 'requested');

                // Prevent any other status checks from overriding this state
                const card = button.closest('.subscription-card');
                if (card) {
                    card.setAttribute('data-just-requested', 'true');
                }

                // Disable all other subscription cards
                disableAllOtherSubscriptionCards(subscriptionId);

                // Clear the request in progress flag
                window.subscriptionRequestInProgress = false;

                selectedSubscriptionId = null;
                selectedPlanName = null;
                const termsCheckbox = document.getElementById('pricingTermsCheckbox');
                if (termsCheckbox) termsCheckbox.checked = false;
                if (submitButton) submitButton.textContent = 'Submit Request';
                updateSubmitRequestButtonState();

            } else {
                showNotification(data.message || 'An error occurred. Please try again.', 'error');
                resetButtonToOriginal(button, originalText);
                window.subscriptionRequestInProgress = false;
                if (submitButton) submitButton.textContent = 'Submit Request';
                updateSubmitRequestButtonState();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error.message || 'An error occurred. Please try again.', 'error');
            resetButtonToOriginal(button, originalText);
            window.subscriptionRequestInProgress = false;
            if (submitButton) submitButton.textContent = 'Submit Request';
            updateSubmitRequestButtonState();
        });
    }

    function updateButtonToRequested(button, subscriptionId) {
        // Update button text
        button.textContent = 'Requested';

        // Remove all existing classes that might interfere
        button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'opacity-75', 'hover:scale-105', 'bg-yellow-500', 'cursor-not-allowed');

        // Add new classes for requested state
        button.classList.add('bg-yellow-500', 'cursor-not-allowed');

        // Set disabled state
        button.disabled = true;

        // Force the styling to be applied immediately with !important
        button.style.setProperty('background-color', '#eab308', 'important');
        button.style.setProperty('color', 'white', 'important');
        button.style.setProperty('cursor', 'not-allowed', 'important');
        button.style.setProperty('opacity', '1', 'important');

        // Store the request state in localStorage
        localStorage.setItem(`subscription_request_${subscriptionId}`, 'requested');

        // Update the card's data attribute for consistency
        const card = button.closest('.subscription-card');
        if (card) {
            card.setAttribute('data-request-status', 'requested');
            card.setAttribute('data-just-requested', 'true');
        }

        // Log for debugging
        console.log('Button updated to Requested for subscription:', subscriptionId);

        // Force a re-render to ensure the changes are visible
        button.offsetHeight; // Trigger reflow
    }

    function resetButtonToOriginal(button, originalText) {
        // Reset button to original state
                    button.textContent = originalText;
                    button.disabled = false;
        button.classList.remove('opacity-75', 'cursor-not-allowed', 'bg-yellow-500', 'bg-green-500', 'bg-gray-400');
        button.classList.add('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'hover:scale-105');

        // Reset inline styles
        button.style.removeProperty('background-color');
        button.style.removeProperty('color');
        button.style.removeProperty('cursor');
        button.style.removeProperty('opacity');
    }

    function checkLocalStorageStatus() {
        // Don't check status if a request is in progress
        if (window.subscriptionRequestInProgress) {
            console.log('Skipping localStorage check - request in progress');
            return;
        }

        const cards = document.querySelectorAll('.subscription-card');
        console.log('Checking localStorage status for', cards.length, 'cards');
        let hasRequestedCard = false;
        let requestedSubscriptionId = null;

        cards.forEach(card => {
            const subscriptionId = card.getAttribute('data-subscription-id');
            const button = card.querySelector('button');

            // Skip if this card was just requested (don't override the state)
            if (card.getAttribute('data-just-requested') === 'true') {
                return;
            }

            // Also skip if button is already showing "Requested"
            if (button && button.textContent === 'Requested') {
                return;
            }

            if (button) {
                console.log('Checking subscription', subscriptionId, 'button text:', button.textContent);

                // Check for requested status in localStorage
                const requestStatus = localStorage.getItem(`subscription_request_${subscriptionId}`);
                console.log('localStorage status for', subscriptionId, ':', requestStatus);

                if (requestStatus === 'requested') {
                    hasRequestedCard = true;
                    requestedSubscriptionId = subscriptionId;

                    console.log('Setting button to requested for subscription', subscriptionId);
                    updateButtonToRequested(button, subscriptionId);
                }

                // Check for current plan buttons and apply styling immediately
                if (button.textContent === 'Current Plan') {
                    if (button.classList.contains('bg-gray-400')) {
                        button.style.setProperty('background-color', '#9ca3af', 'important');
                    } else if (button.classList.contains('bg-green-500')) {
                        button.style.setProperty('background-color', '#10b981', 'important');
                    }
                    button.style.setProperty('color', 'white', 'important');
                    button.style.setProperty('cursor', 'not-allowed', 'important');
                }
            }
        });

        // If there's a requested card, disable all other cards
        if (hasRequestedCard && requestedSubscriptionId) {
            disableAllOtherSubscriptionCards(requestedSubscriptionId);
        }
    }

    function checkSubscriptionStatus() {
        // Check actual status from server first
        fetch('/subscription-requests/status')
            .then(response => {
                if (!response.ok) {
                    console.log('Server status check failed, relying on localStorage');
                    return {};
                }
                return response.json();
            })
            .then(statuses => {
                const cards = document.querySelectorAll('.subscription-card');
                let hasPendingRequest = false;
                let pendingSubscriptionId = null;

                cards.forEach(card => {
                    const subscriptionId = card.getAttribute('data-subscription-id');
                    const status = statuses[subscriptionId];
                    const button = card.querySelector('button');

                    // Clear localStorage for this subscription if no status from server
                    if (!status) {
                        localStorage.removeItem(`subscription_request_${subscriptionId}`);
                    }

                    if (status === 'pending') {
                        hasPendingRequest = true;
                        pendingSubscriptionId = subscriptionId;

                        button.textContent = 'Requested';
                        button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'hover:scale-105');
                        button.classList.add('bg-yellow-500', 'cursor-not-allowed');
                        button.disabled = true;

                        // Force the styling to be applied immediately
                        button.style.backgroundColor = '#eab308';
                        button.style.color = 'white';
                        button.style.cursor = 'not-allowed';

                        // Update localStorage to match server state
                        localStorage.setItem(`subscription_request_${subscriptionId}`, 'requested');
                    } else if (status === 'approved') {
                        button.textContent = 'Current Plan';
                        button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'hover:scale-105');
                        button.classList.add('bg-green-500', 'cursor-not-allowed');
                        button.disabled = true;

                        // Force the styling to be applied immediately
                        button.style.backgroundColor = '#10b981';
                        button.style.color = 'white';
                        button.style.cursor = 'not-allowed';

                        // Update localStorage to match server state
                        localStorage.setItem(`subscription_request_${subscriptionId}`, 'approved');
                    } else if (status === 'declined') {
                        button.textContent = 'Choose Plan';
                        button.classList.remove('bg-yellow-500', 'bg-green-500', 'cursor-not-allowed');
                        button.classList.add('bg-[#092C48]', 'hover:bg-[#0D6AED]');
                        button.disabled = false;

                        // Clear localStorage for declined requests
                        localStorage.removeItem(`subscription_request_${subscriptionId}`);

                        // Re-enable all cards when a request is declined
                        enableAllSubscriptionCards();
                    } else {
                        // No status from server, reset button to default
                        button.textContent = 'Choose Plan';
                        button.classList.remove('bg-yellow-500', 'bg-green-500', 'cursor-not-allowed');
                        button.classList.add('bg-[#092C48]', 'hover:bg-[#0D6AED]');
                        button.disabled = false;

                        // Clear localStorage
                        localStorage.removeItem(`subscription_request_${subscriptionId}`);
                    }
                });

                // If there's a pending request, disable all other cards
                if (hasPendingRequest && pendingSubscriptionId) {
                    disableAllOtherSubscriptionCards(pendingSubscriptionId);
                }
            })
            .catch(error => {
                console.error('Error checking subscription status:', error);
                // If server check fails, fall back to localStorage
                checkLocalStorageStatus();
            });
    }

    function checkDowngradeRequestStatus() {
        // Check downgrade request status
        fetch('/downgrade-requests/status')
            .then(response => {
                if (!response.ok) {
                    console.log('Downgrade request status check failed');
                    return {};
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'pending') {
                    // If there's a pending downgrade request, disable all subscription cards
                    const cards = document.querySelectorAll('.subscription-card');
                    cards.forEach(card => {
                        const button = card.querySelector('button');
                        if (button && !button.disabled && button.textContent !== 'Current Plan') {
                            button.textContent = 'Request Pending';
                            button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'hover:scale-105');
                            button.classList.add('bg-gray-400', 'cursor-not-allowed');
                            button.disabled = true;

                            // Force the styling to be applied immediately
                            button.style.backgroundColor = '#9ca3af';
                            button.style.color = 'white';
                            button.style.cursor = 'not-allowed';

                            // Add a visual indicator that this card is disabled
                            card.style.opacity = '0.6';
                            card.style.pointerEvents = 'none';
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error checking downgrade request status:', error);
            });
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    // Function to clear all subscription request states from localStorage
    function clearAllSubscriptionStates() {
        const cards = document.querySelectorAll('.subscription-card');
        cards.forEach(card => {
            const subscriptionId = card.getAttribute('data-subscription-id');
            localStorage.removeItem(`subscription_request_${subscriptionId}`);
        });

        // Reset all buttons to default state
        const buttons = document.querySelectorAll('.subscription-card button');
        buttons.forEach(button => {
            button.textContent = 'Choose Plan';
            button.classList.remove('bg-yellow-500', 'bg-green-500', 'cursor-not-allowed');
            button.classList.add('bg-[#092C48]', 'hover:bg-[#0D6AED]');
            button.disabled = false;
        });

        console.log('All subscription states cleared from localStorage');
    }

    // Function to disable all other subscription cards when one is requested
    function disableAllOtherSubscriptionCards(requestedSubscriptionId) {
        const cards = document.querySelectorAll('.subscription-card');

        cards.forEach(card => {
            const subscriptionId = card.getAttribute('data-subscription-id');
            const button = card.querySelector('button');

            // Skip the card that was just requested
            if (subscriptionId === requestedSubscriptionId) {
                return;
            }

            // Disable all other cards
            if (button && !button.disabled) {
                button.textContent = 'Request Pending';
                button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'hover:scale-105');
                button.classList.add('bg-gray-400', 'cursor-not-allowed');
                button.disabled = true;

                // Force the styling to be applied immediately
                button.style.backgroundColor = '#9ca3af';
                button.style.color = 'white';
                button.style.cursor = 'not-allowed';

                // Add a visual indicator that this card is disabled
                card.style.opacity = '0.6';
                card.style.pointerEvents = 'none';
            }
        });

        console.log('All other subscription cards disabled due to pending request');
    }

    // Function to re-enable all subscription cards (when request is declined or cancelled)
    function enableAllSubscriptionCards() {
        const cards = document.querySelectorAll('.subscription-card');

        cards.forEach(card => {
            const button = card.querySelector('button');

            // Only re-enable cards that are not the current plan or already requested
            if (button && button.textContent === 'Request Pending') {
                button.textContent = 'Request Subscription';
                button.classList.remove('bg-gray-400', 'cursor-not-allowed');
                button.classList.add('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'hover:scale-105');
                button.disabled = false;

                // Reset inline styles
                button.style.removeProperty('background-color');
                button.style.removeProperty('color');
                button.style.removeProperty('cursor');

                // Re-enable card interactions
                card.style.opacity = '1';
                card.style.pointerEvents = 'auto';
            }
        });

        console.log('All subscription cards re-enabled');
    }

    // Make the function available globally for debugging
    window.clearAllSubscriptionStates = clearAllSubscriptionStates;
    window.disableAllOtherSubscriptionCards = disableAllOtherSubscriptionCards;
    window.enableAllSubscriptionCards = enableAllSubscriptionCards;
</script>
@endsection
