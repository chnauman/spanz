<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing Plans - Spanz</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
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

        .subscription-card.active {
            z-index: 10;
            transform: translateZ(20px) scale(1.05);
        }

        .subscription-card.active .bg-white {
            background: linear-gradient(135deg, #092C48 0%, #0D6AED 100%);
            border-color: #0D6AED;
            color: white;
            box-shadow: 0 20px 40px rgba(13, 106, 237, 0.3);
            transform: translateZ(20px) scale(1.05);
            border-radius: 1rem;
        }

        .subscription-card.active .text-gray-900 {
            color: white;
        }

        .subscription-card.active .text-gray-700 {
            color: #e5e7eb;
        }

        .subscription-card.active .text-gray-600 {
            color: #d1d5db;
        }

        .subscription-card.active .text-gray-500 {
            color: #9ca3af;
        }

        .subscription-card.active button {
            background: white;
            color: #092C48;
        }

        .subscription-card.active button:hover {
            background: #f3f4f6;
            color: #092C48;
        }

        /* Requested button styling - always visible */
        .subscription-card button.bg-yellow-500 {
            background-color: #eab308 !important;
            color: white !important;
            cursor: not-allowed !important;
        }

        .subscription-card.active button.bg-yellow-500 {
            background-color: #eab308 !important;
            color: white !important;
        }

        /* Current Plan button styling - always visible */
        .subscription-card button.bg-gray-400 {
            background-color: #9ca3af !important;
            color: white !important;
            cursor: not-allowed !important;
        }

        .subscription-card.active button.bg-gray-400 {
            background-color: #9ca3af !important;
            color: white !important;
        }

        /* Current Plan button styling for green variant */
        .subscription-card button.bg-green-500 {
            background-color: #10b981 !important;
            color: white !important;
            cursor: not-allowed !important;
        }

        .subscription-card.active button.bg-green-500 {
            background-color: #10b981 !important;
            color: white !important;
        }
    </style>
</head>
<body>
    <div class="bg-image bg-cover bg-center" style="background-image:url('{{ asset('spanz-img/spanz-bg.jpg') }}')">
        <!-- Sidebar -->
        <div class="flex h-screen">
            <!-- Sidebar -->
            <div class="w-64 bg-gradient-to-b from-[#092C48] to-[#1b3963] shadow-lg">
                @include('admin.partials.sidebar')
            </div>

            <!-- Main Content -->
            <div class="flex-1 overflow-y-auto">
                <div class="min-h-screen bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Header -->
                <div class="text-center mb-16">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Plan</h1>
                    <p class="text-xl text-gray-600">Select the perfect subscription plan for your business needs</p>
                </div>

                <!-- Pricing Cards -->
                <div class="flex flex-wrap justify-center gap-8 mt-4" style="min-height: 4.5in;">
                    @foreach($subscriptions as $index => $subscription)
                        @if($subscription->is_active)
                            @php
                                $userHasActiveSubscription = auth()->check() && $user->getActiveSubscription();
                                $isBasicPlan = strtolower($subscription->name) === 'basic';
                                $shouldShowBasic = !$userHasActiveSubscription;
                                $shouldHideBasic = $userHasActiveSubscription && $isBasicPlan;
                            @endphp
                            
                            @if(!$shouldHideBasic)
                                <div class="subscription-card relative group cursor-pointer {{ $index === 0 ? 'active' : '' }}" 
                                     data-plan="{{ strtolower($subscription->name) }}" 
                                     data-subscription-id="{{ $subscription->id }}">
                                    <div class="bg-white border-2 border-gray-200 rounded-2xl p-4 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1" style="width: 3in; height: 3.5in;">
                                        @if($subscription->name === 'Pro')
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
                                                    ${{ $subscription->price }}
                                                    <span class="text-sm text-gray-500">/month</span>
                                                </div>
                                                <p class="text-gray-600 text-sm mb-4">{{ $subscription->description ?? 'Premium subscription plan' }}</p>
                                                
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
                                                                onclick="requestSubscription({{ $subscription->id }}, '{{ strtolower($subscription->name) }}', this)">
                                                            {{ $subscription->price == 0 ? 'Get Started' : 'Request Subscription' }}
                                                        </button>
                                                    @endif
                                                @else
                                                    <a href="{{ route('register') }}"
                                                       class="w-full bg-[#092C48] text-white px-4 py-3 rounded-lg text-base font-semibold hover:bg-[#0D6AED] transition-all duration-300 transform hover:scale-105 inline-block text-center">
                                                        Sign Up
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

                <!-- FAQ Section -->
                <div class="mt-16 max-w-3xl mx-auto">
                    <h2 class="text-3xl font-bold text-center text-gray-900 mb-8">Frequently Asked Questions</h2>
                    <div class="space-y-6">
                        <div class="bg-white rounded-lg p-6 shadow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">What are credits used for?</h3>
                            <p class="text-gray-600">Credits are used to view detailed tender information, contact buyers, and access premium features.</p>
                        </div>
                        <div class="bg-white rounded-lg p-6 shadow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I change my plan later?</h3>
                            <p class="text-gray-600">Yes, you can upgrade or downgrade your subscription at any time. Changes will be reflected in your next billing cycle.</p>
                        </div>
                        <div class="bg-white rounded-lg p-6 shadow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a free trial?</h3>
                            <p class="text-gray-600">Yes, the Basic plan is free and allows you to browse tenders. Premium features require a paid subscription.</p>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle card selection and button clicks
        document.addEventListener('DOMContentLoaded', function() {
            initializeSubscriptionCards();
            clearStaleLocalStorage();
            checkSubscriptionStatus();
        });

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

        function initializeSubscriptionCards() {
            const cards = document.querySelectorAll('.subscription-card');
            
            // Handle card clicks for selection
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    // Remove active class from all cards
                    cards.forEach(c => c.classList.remove('active'));
                    
                    // Add active class to clicked card
                    this.classList.add('active');
                    
                    // Hide "MOST POPULAR" badge on all cards
                    const badges = document.querySelectorAll('.most-popular-badge');
                    badges.forEach(badge => badge.style.display = 'none');
                });
            });
        }

        function requestSubscription(subscriptionId, planName, button) {
            console.log('Requesting subscription:', { subscriptionId, planName });
            console.log('Subscription ID type:', typeof subscriptionId);
            console.log('Subscription ID value:', subscriptionId);
            
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
                    
                    // Clear the request in progress flag
                    window.subscriptionRequestInProgress = false;
                    
                } else {
                    showNotification(data.message || 'An error occurred. Please try again.', 'error');
                    resetButtonToOriginal(button, originalText);
                    window.subscriptionRequestInProgress = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification(error.message || 'An error occurred. Please try again.', 'error');
                resetButtonToOriginal(button, originalText);
                window.subscriptionRequestInProgress = false;
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
                    
                    cards.forEach(card => {
                        const subscriptionId = card.getAttribute('data-subscription-id');
                        const status = statuses[subscriptionId];
                        const button = card.querySelector('button');
                        
                        // Clear localStorage for this subscription if no status from server
                        if (!status) {
                            localStorage.removeItem(`subscription_request_${subscriptionId}`);
                        }
                        
                        if (status === 'pending') {
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
                })
                .catch(error => {
                    console.error('Error checking subscription status:', error);
                    // If server check fails, fall back to localStorage
                    checkLocalStorageStatus();
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

        // Make the function available globally for debugging
        window.clearAllSubscriptionStates = clearAllSubscriptionStates;
    </script>
</body>
</html>
