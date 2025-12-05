<!-- Company Registration Modal -->
<style>
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

#companyRegistrationModal.show {
    animation: fadeIn 0.3s ease-out;
}

#companyModalContent.show {
    animation: modalFadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>

<div id="companyRegistrationModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden backdrop-blur-sm flex items-center justify-center p-4" onclick="closeCompanyModalOnBackdrop(event)" style="z-index: 99999;">
    <div class="w-full max-w-md mx-auto" onclick="event.stopPropagation()">
        <!-- Modal Content with Animation -->
        <div class="bg-white rounded-2xl shadow-2xl transform transition-all duration-300 ease-out scale-95 opacity-0 overflow-hidden" id="companyModalContent" style="z-index: 100000;">
            <!-- Header with Gradient -->
            <div class="bg-gradient-to-r from-[#092C48] to-[#0D6AED] text-white px-6 py-5">
                <div class="flex justify-between items-start">
                    <div class="flex items-start space-x-4 flex-1">
                        <div class="flex-shrink-0 w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold mb-1">Complete Your Company Profile</h3>
                            <p class="text-blue-100 text-sm">Register your company to post tenders on SPANZ</p>
                        </div>
                    </div>
                    <button onclick="closeCompanyRegistrationModal()" class="text-white hover:text-blue-200 transition-colors p-1.5 rounded-full hover:bg-white hover:bg-opacity-20 ml-4 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-6">
                <div class="mb-5">
                    <p class="text-gray-700 text-sm leading-relaxed mb-4">
                        To post tenders on SPANZ, you need to complete your company profile. This helps us verify your business and ensures a secure marketplace for all users.
                    </p>
                </div>

                <!-- Benefits List -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 mb-6 border border-blue-100">
                    <h5 class="font-semibold text-gray-800 mb-3 text-sm flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Benefits of Completing Your Profile:
                    </h5>
                    <ul class="space-y-2.5">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-3 mt-0.5 flex-shrink-0 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Post and manage tenders</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-3 mt-0.5 flex-shrink-0 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Connect with verified suppliers</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-3 mt-0.5 flex-shrink-0 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Build trust with potential partners</span>
                        </li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('company.register') }}" class="flex-1 bg-[#0D6AED] text-white px-5 py-3 rounded-lg text-center text-sm font-semibold hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-[1.02] active:scale-[0.98]">
                        Complete Company Profile
                    </a>
                    <button onclick="closeCompanyRegistrationModal()" class="flex-1 bg-gray-100 text-gray-700 px-5 py-3 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-colors border border-gray-200">
                        Maybe Later
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openCompanyRegistrationModal() {
    const modal = document.getElementById('companyRegistrationModal');
    const modalContent = document.getElementById('companyModalContent');

    if (modal && modalContent) {
        modal.classList.remove('hidden');

        // Trigger animation
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);

        // Prevent body scroll when modal is open
        document.body.style.overflow = 'hidden';
    }
}

function closeCompanyRegistrationModal() {
    const modal = document.getElementById('companyRegistrationModal');
    const modalContent = document.getElementById('companyModalContent');

    if (modal && modalContent) {
        // Trigger close animation
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        // Hide modal after animation
        setTimeout(() => {
            modal.classList.add('hidden');
            // Restore body scroll
            document.body.style.overflow = 'auto';
        }, 300);
    }
}

function closeCompanyModalOnBackdrop(event) {
    // Only close if clicking the backdrop, not the modal content
    if (event.target === event.currentTarget) {
        closeCompanyRegistrationModal();
    }
}

// Add keyboard support for closing modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('companyRegistrationModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeCompanyRegistrationModal();
        }
    }
});

// Make functions available globally
window.openCompanyRegistrationModal = openCompanyRegistrationModal;
window.closeCompanyRegistrationModal = closeCompanyRegistrationModal;
</script>

