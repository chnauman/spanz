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
</style>

<div id="subscriptionModal" class="fixed inset-0 bg-black bg-opacity-60 overflow-y-auto h-full w-full hidden z-[9999] backdrop-blur-sm flex items-start justify-center p-4 pt-8" onclick="closeModalOnBackdrop(event)">
    <div class="w-full max-w-4xl" onclick="event.stopPropagation()">
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
                <div class="flex flex-wrap justify-center gap-8 mt-4" style="min-height: 4.5in;">
                    @foreach($subscriptions as $index => $subscription)
                        @if($subscription->is_active)
                            <div class="subscription-card relative group cursor-pointer {{ $index === 0 ? 'active' : '' }}" 
                                 data-plan="{{ strtolower($subscription->name) }}" 
                                 data-subscription-id="{{ $subscription->id }}">
                                <div class="bg-white border-2 border-gray-200 rounded-2xl p-4 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1" style="width: 3in; height: 3.5in;">
                                    @if($subscription->name === 'Enterprise')
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
                                                 <p class="text-xs text-gray-600">To view tenders and buyers</p>
                                             </div>
                                        </div>
                                        
                                        <button class="w-full bg-[#092C48] text-white px-4 py-3 rounded-lg text-base font-semibold hover:bg-[#0D6AED] transition-all duration-300 transform hover:scale-105">
                                            Choose Plan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
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
                            All subscription requests are reviewed by our admin team. You'll be notified once your request is processed.
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
});

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
    
    // Handle button clicks for subscription requests
    buttons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent card click
            
            const card = this.closest('.subscription-card');
            const subscriptionId = card.getAttribute('data-subscription-id');
            const planName = card.getAttribute('data-plan');
            
            // Request subscription
            requestSubscription(subscriptionId, planName, this);
        });
    });
}

function requestSubscription(subscriptionId, planName, button) {
    // Show loading state
    const originalText = button.textContent;
    button.textContent = 'Processing...';
    button.disabled = true;
    button.classList.add('opacity-75', 'cursor-not-allowed');
    
    // Make AJAX request
    fetch(`/subscription-requests/${subscriptionId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
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
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('Subscription request submitted successfully! Admin has been notified.', 'success');
            closeSubscriptionModal();
            
            // Update button state to "Requested"
            button.textContent = 'Requested';
            button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]');
            button.classList.add('bg-yellow-500', 'cursor-not-allowed');
            button.disabled = true;
            
            // Store the request state in localStorage
            localStorage.setItem(`subscription_request_${subscriptionId}`, 'requested');
        } else {
            showNotification(data.message || 'An error occurred. Please try again.', 'error');
            button.textContent = originalText;
            button.disabled = false;
            button.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message || 'An error occurred. Please try again.', 'error');
        button.textContent = originalText;
        button.disabled = false;
        button.classList.remove('opacity-75', 'cursor-not-allowed');
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
    checkSubscriptionStatus();
});

function checkSubscriptionStatus() {
    // Check actual status from server
    fetch('/subscription-requests/status')
        .then(response => response.json())
        .then(statuses => {
            const cards = document.querySelectorAll('.subscription-card');
            
            cards.forEach(card => {
                const subscriptionId = card.getAttribute('data-subscription-id');
                const status = statuses[subscriptionId];
                const button = card.querySelector('button');
                
                if (status === 'pending') {
                    button.textContent = 'Requested';
                    button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]');
                    button.classList.add('bg-yellow-500', 'cursor-not-allowed');
                    button.disabled = true;
                } else if (status === 'approved') {
                    button.textContent = 'Current Plan';
                    button.classList.remove('bg-[#092C48]', 'hover:bg-[#0D6AED]');
                    button.classList.add('bg-green-500', 'cursor-not-allowed');
                    button.disabled = true;
                } else if (status === 'declined') {
                    button.textContent = 'Choose Plan';
                    button.classList.remove('bg-yellow-500', 'bg-green-500', 'cursor-not-allowed');
                    button.classList.add('bg-[#092C48]', 'hover:bg-[#0D6AED]');
                    button.disabled = false;
                }
            });
        })
        .catch(error => {
            console.error('Error checking subscription status:', error);
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
</script>
