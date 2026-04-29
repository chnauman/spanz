<!-- Subscription Modal -->
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
    display: flex;
}

.subscription-plans-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
    align-items: stretch;
}

.subscription-card.active {
    z-index: 10;
}

.subscription-card.active .bg-white {
    background: linear-gradient(135deg, #092C48 0%, #0D6AED 100%);
    border-color: #0D6AED;
    color: white;
    box-shadow: 0 20px 40px rgba(13, 106, 237, 0.3);
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

<div id="subscriptionModal" class="fixed inset-0 bg-black bg-opacity-60 overflow-y-auto h-full w-full hidden z-[9999] backdrop-blur-sm flex items-start justify-center p-4 pt-8" onclick="closeModalOnBackdrop(event)">
    <div class="w-full max-w-[1320px]" onclick="event.stopPropagation()">
        <!-- Modal Content with Animation -->
        <div class="bg-white rounded-2xl shadow-2xl transform transition-all duration-500 ease-out scale-95 opacity-0" id="modalContent">
            <!-- Header with Gradient -->
            <div class="bg-gradient-to-r from-[#092C48] to-[#1b3963] text-white p-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold mb-1">🚀 Upgrade Your Subscription</h3>
                        <p class="text-blue-100 text-sm">Unlock premium features and access detailed tender information</p>
                    </div>
                    <button onclick="closeSubscriptionModal()" class="text-white hover:text-blue-300 transition-colors p-2 rounded-full hover:bg-white hover:bg-opacity-20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4 pb-6">
                <div class="mb-2">
                    @include('components.pricing-intro-banner')
                </div>

                <div class="mt-3 subscription-plans-grid">
                    @foreach($subscriptions as $index => $subscription)
                        @if($subscription->is_active)
                            <div class="subscription-card relative group cursor-pointer {{ $index === 0 ? 'active' : '' }}"
                                 data-plan="{{ strtolower($subscription->name) }}"
                                 data-subscription-id="{{ $subscription->id }}">
                                <div class="bg-white border-2 border-gray-200 rounded-2xl p-4 hover:shadow-lg transition-all duration-300 h-full min-h-[560px]">
                                    @if($subscription->name === 'Professional')
                                        <!-- Most Popular Badge -->
                                        <div class="absolute -top-3 right-4 most-popular-badge">
                                            <div class="bg-[#0D6AED] text-white px-3 py-1 rounded-full text-xs font-bold">
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

                                        <button class="w-full bg-[#092C48] text-white px-4 py-3 rounded-lg text-base font-semibold hover:bg-[#0D6AED] transition-all duration-300 transform hover:scale-105"
                                                onclick="selectSubscriptionPlan('{{ $subscription->id }}', '{{ strtolower($subscription->name) }}', this)">
                                            Choose Plan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                @include('components.pricing-notes-footer', ['compact' => true])

                <div id="subscriptionRequestConsentBoxModal" class="mt-4 rounded-xl border border-gray-200 bg-white p-4">
                    <label class="flex items-start gap-3 text-sm text-gray-700">
                        <input id="pricingTermsCheckboxModal" type="checkbox" class="mt-1 h-4 w-4 rounded border-gray-300 text-[#092C48] focus:ring-[#092C48]">
                        <span>I have read the SPANZ Terms &amp; Conditions and fully agree with them.</span>
                    </label>
                    <div class="mt-4 flex justify-start">
                        <button id="submitSubscriptionRequestBtnModal" type="button" class="w-[170px] bg-[#092C48] text-white px-4 py-3 rounded-lg text-base font-semibold hover:bg-[#0D6AED] transition-all duration-300 transform hover:scale-105 disabled:opacity-40 disabled:bg-gray-400 disabled:hover:bg-gray-400 disabled:cursor-not-allowed disabled:hover:cursor-not-allowed disabled:transform-none" disabled onclick="submitSelectedSubscriptionRequest()">
                            Submit Request
                        </button>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 text-center">
                    <div class="bg-blue-50 rounded-xl p-4">
                        <div class="flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h4 class="text-lg font-semibold text-blue-900">Secure & Reliable</h4>
                        </div>
                        <p class="text-blue-700 text-sm">
                            All subscription requests are reviewed by our admin team.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openSubscriptionModal() {
    const modal = document.getElementById('subscriptionModal');
    const modalContent = document.getElementById('modalContent');

    modal.classList.remove('hidden');

    // Check localStorage status when modal opens
    checkLocalStorageStatus();
    updateSubmitRequestButtonState();

    // Trigger animation
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeSubscriptionModal() {
    console.log('Closing subscription modal...');
    const modal = document.getElementById('subscriptionModal');
    const modalContent = document.getElementById('modalContent');

    if (modal && modalContent) {
        // Trigger close animation
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        // Hide modal after animation
        setTimeout(() => {
            modal.classList.add('hidden');
            console.log('Modal hidden');
        }, 300);
    }
}

function closeModalOnBackdrop(event) {
    // Only close if clicking the backdrop, not the modal content
    if (event.target === event.currentTarget) {
        console.log('Backdrop clicked, closing modal');
        closeSubscriptionModal();
    }
}

// Handle card selection and button clicks
document.addEventListener('DOMContentLoaded', function() {
    initializeSubscriptionCards();
    initializeSubscriptionSubmitControls();
});

let selectedSubscriptionId = null;
let selectedPlanName = null;

function initializeSubscriptionCards() {
    const cards = document.querySelectorAll('.subscription-card');
    const buttons = document.querySelectorAll('.subscription-card button');

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

    // Keep button click from selecting the whole card twice
    buttons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent card click
        });
    });
}

function initializeSubscriptionSubmitControls() {
    const checkbox = document.getElementById('pricingTermsCheckboxModal');
    if (checkbox) {
        checkbox.addEventListener('change', updateSubmitRequestButtonState);
    }
    updateSubmitRequestButtonState();
}

function selectSubscriptionPlan(subscriptionId, planName, button) {
    if (button.disabled) return;

    selectedSubscriptionId = String(subscriptionId);
    selectedPlanName = planName;

    const cards = document.querySelectorAll('.subscription-card');
    cards.forEach(card => card.classList.remove('active'));
    const activeCard = button.closest('.subscription-card');
    if (activeCard) activeCard.classList.add('active');

    updateSubmitRequestButtonState();
}

function updateSubmitRequestButtonState() {
    const submitButton = document.getElementById('submitSubscriptionRequestBtnModal');
    const termsCheckbox = document.getElementById('pricingTermsCheckboxModal');
    if (!submitButton || !termsCheckbox) return;
    submitButton.disabled = !(selectedSubscriptionId && termsCheckbox.checked);
}

function submitSelectedSubscriptionRequest() {
    const submitButton = document.getElementById('submitSubscriptionRequestBtnModal');
    const termsCheckbox = document.getElementById('pricingTermsCheckboxModal');

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

    // Make AJAX request
    let csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        // Fallback: try to get token from a hidden input or use a default method
        csrfToken = document.querySelector('input[name="_token"]');
        if (!csrfToken) {
            console.error('CSRF token not found');
            showNotification('Security token not found. Please refresh the page.', 'error');
            return;
        }
    }

    // Use the fallback route that doesn't use model binding
    const url = `/subscription-requests-by-id/${subscriptionId}`;
    console.log('Making request to:', url);
    console.log('Subscription ID:', subscriptionId);

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content') || csrfToken.value
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

            // Disable all other subscription cards
            disableAllOtherSubscriptionCards(subscriptionId);
            selectedSubscriptionId = null;
            selectedPlanName = null;
            const termsCheckbox = document.getElementById('pricingTermsCheckboxModal');
            if (termsCheckbox) termsCheckbox.checked = false;
            if (submitButton) submitButton.textContent = 'Submit Request';
            updateSubmitRequestButtonState();
        } else {
            showNotification(data.message || 'An error occurred. Please try again.', 'error');
            button.textContent = originalText;
            button.disabled = false;
            button.classList.remove('opacity-75', 'cursor-not-allowed');
            if (submitButton) submitButton.textContent = 'Submit Request';
            updateSubmitRequestButtonState();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message || 'An error occurred. Please try again.', 'error');
        button.textContent = originalText;
        button.disabled = false;
        button.classList.remove('opacity-75', 'cursor-not-allowed');
        if (submitButton) submitButton.textContent = 'Submit Request';
        updateSubmitRequestButtonState();
    });
}

// Add keyboard support for closing modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('subscriptionModal');
        if (!modal.classList.contains('hidden')) {
            closeSubscriptionModal();
        }
    }
});

// Check subscription request status on page load
document.addEventListener('DOMContentLoaded', function() {
    checkLocalStorageStatus();
    checkSubscriptionStatus();
});

function checkLocalStorageStatus() {
    const cards = document.querySelectorAll('.subscription-card');

    cards.forEach(card => {
        const subscriptionId = card.getAttribute('data-subscription-id');
        const button = card.querySelector('button');

        if (button) {
            // Check for requested status in localStorage
            const requestStatus = localStorage.getItem(`subscription_request_${subscriptionId}`);

            if (requestStatus === 'requested') {
                button.textContent = 'Requested';
                button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]', 'hover:scale-105');
                button.classList.add('bg-yellow-500', 'cursor-not-allowed');
                button.disabled = true;

                // Force the styling to be applied immediately
                button.style.backgroundColor = '#eab308';
                button.style.color = 'white';
                button.style.cursor = 'not-allowed';

                // Also disable all other cards if this one is requested
                disableAllOtherSubscriptionCards(subscriptionId);
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
            button.textContent = 'Choose Plan';
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
