@extends('layouts.admin')
@section('title', 'Invite Sub Suppliers - SPANZ')
@section('content')
<div class="bg-gray-100 p-4 sm:p-6 lg:p-8 min-h-screen">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold">Invite Sub Suppliers</h1>
                    <p class="text-gray-300 mt-1">Search for suppliers to invite as your sub suppliers</p>
                </div>
                <a href="{{ route('suppliers.sub-suppliers') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Sub Suppliers
                </a>
            </div>

            <div class="mt-6">
                <!-- Search Form -->
                <div class="mb-6">
                    <div class="relative">
                        <input type="text"
                               id="searchInput"
                               placeholder="Search by name or email..."
                               class="w-full px-4 py-3 pl-10 pr-4 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Search Results -->
                <div id="searchResults" class="space-y-4">
                    <div class="text-center text-gray-500 py-6">
                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <p class="mt-2">Start typing to search for suppliers</p>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div id="loadingIndicator" class="hidden text-center py-6">
                    <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                    <p class="mt-2 text-gray-600">Searching...</p>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<!-- Invitation Modal -->
<div id="invitationModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300 w-full max-w-md">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white">Send Invitation</h3>
                            <p class="text-blue-100 text-sm">Invite a supplier to join your network</p>
                        </div>
                    </div>
                    <button onclick="closeModal()" class="text-white hover:text-blue-200 transition-colors duration-200 p-1 rounded-full hover:bg-white hover:bg-opacity-20">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="px-6 py-6">
                <div id="invitationForm">
                    <!-- Selected Supplier Display -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-800 mb-3">Selected Supplier</label>
                        <div id="selectedSupplier" class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl shadow-sm"></div>
                    </div>

                    <!-- Message Input -->
                    <div class="mb-6">
                        <label for="invitationMessage" class="block text-sm font-semibold text-gray-800 mb-3">
                            Personal Message
                            <span class="text-gray-500 font-normal">(Optional)</span>
                        </label>
                        <textarea id="invitationMessage"
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                                  placeholder="Add a personal message to make your invitation more engaging..."></textarea>
                        <p class="text-xs text-gray-500 mt-2">A personal message can increase the chances of acceptance</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                        <button onclick="closeModal()"
                                class="flex-1 px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </button>
                        <button onclick="sendInvitation()"
                                id="sendInvitationBtn"
                                class="flex-1 px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Send Invitation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedSupplier = null;
let searchTimeout = null;

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const query = e.target.value.trim();

    if (query.length < 2) {
        document.getElementById('searchResults').innerHTML = `
            <div class="text-center text-gray-500 py-6">
                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="mt-2">Start typing to search for suppliers</p>
            </div>
        `;
        return;
    }

    // Clear previous timeout
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }

    // Show loading indicator
    document.getElementById('loadingIndicator').classList.remove('hidden');
    document.getElementById('searchResults').innerHTML = '';

    // Debounce search
    searchTimeout = setTimeout(() => {
        searchSuppliers(query);
    }, 500);
});

async function searchSuppliers(query) {
    try {
        const response = await fetch(`/invite-sub-suppliers/search?query=${encodeURIComponent(query)}`);
        const data = await response.json();

        document.getElementById('loadingIndicator').classList.add('hidden');

        if (data.data && data.data.length > 0) {
            displaySearchResults(data.data);
        } else {
            document.getElementById('searchResults').innerHTML = `
                <div class="text-center text-gray-500 py-6">
                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <p class="mt-2">No suppliers found</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Search error:', error);
        document.getElementById('loadingIndicator').classList.add('hidden');
        document.getElementById('searchResults').innerHTML = `
            <div class="text-center text-red-500 py-6">
                <p>Error searching suppliers. Please try again.</p>
            </div>
        `;
    }
}

function displaySearchResults(suppliers) {
    const resultsHtml = suppliers.map(supplier => {
        const isAlreadyInvited = supplier.already_invited;
        
        return `
        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow ${isAlreadyInvited ? 'opacity-75' : ''}">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 ${isAlreadyInvited ? 'bg-green-100' : 'bg-blue-100'} rounded-full flex items-center justify-center">
                            <span class="${isAlreadyInvited ? 'text-green-600' : 'text-blue-600'} font-medium text-sm">${supplier.name.charAt(0).toUpperCase()}</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">${supplier.name}</h3>
                        <p class="text-sm text-gray-500">${supplier.email}</p>
                        ${supplier.company_detail ? `<p class="text-xs text-gray-400">${supplier.company_detail.company_name}</p>` : ''}
                        ${isAlreadyInvited ? '<p class="text-xs text-green-600 font-medium mt-1">✓ Already Invited</p>' : ''}
                    </div>
                </div>
                ${isAlreadyInvited ? 
                    `<button disabled class="px-4 py-2 text-sm font-medium text-gray-500 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Invited
                    </button>` :
                    `<button onclick="openInvitationModal(${JSON.stringify(supplier).replace(/"/g, '&quot;')})"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Invite
                    </button>`
                }
            </div>
        </div>
    `;
    }).join('');

    document.getElementById('searchResults').innerHTML = resultsHtml;
}

function openInvitationModal(supplier) {
    // Check if supplier is already invited
    if (supplier.already_invited) {
        alert('This supplier has already been invited.');
        return;
    }
    
    selectedSupplier = supplier;
    document.getElementById('selectedSupplier').innerHTML = `
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-white font-semibold text-lg">${supplier.name.charAt(0).toUpperCase()}</span>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-lg font-semibold text-gray-900 truncate">${supplier.name}</h4>
                <p class="text-sm text-gray-600 truncate">${supplier.email}</p>
                ${supplier.company_detail ? `<p class="text-xs text-gray-500 truncate mt-1">${supplier.company_detail.company_name}</p>` : ''}
            </div>
            <div class="flex-shrink-0">
                <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>
    `;
    
    // Add animation classes
    const modal = document.getElementById('invitationModal');
    modal.classList.remove('hidden');
    
    // Trigger animation
    setTimeout(() => {
        modal.classList.add('opacity-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('invitationModal');
    modal.classList.add('opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('opacity-100');
    }, 300);
    
    selectedSupplier = null;
    document.getElementById('invitationMessage').value = '';
}

async function sendInvitation() {
    if (!selectedSupplier) {
        console.error('No supplier selected');
        return;
    }

    const message = document.getElementById('invitationMessage').value;
    const sendBtn = document.getElementById('sendInvitationBtn');

    console.log('Sending invitation to:', selectedSupplier);
    console.log('Message:', message);

    // Disable button and show loading
    sendBtn.disabled = true;
    sendBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Sending...
    `;
    sendBtn.classList.add('opacity-75', 'cursor-not-allowed');

    try {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }
        
        console.log('CSRF Token:', csrfToken.getAttribute('content'));

        const response = await fetch('/invite-sub-suppliers/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                invitee_id: selectedSupplier.id,
                message: message
            })
        });

        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error:', errorText);
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }

        const data = await response.json();
        console.log('Response data:', data);

        if (data.success) {
            // Show success state
            sendBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2 inline text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Sent Successfully!
            `;
            sendBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            sendBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            
            // Close modal after a brief delay
            setTimeout(() => {
                closeModal();
                // Show success message in search results
                document.getElementById('searchResults').innerHTML = `
                    <div class="text-center text-green-600 py-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Invitation Sent!</h3>
                        <p class="text-gray-600">Your invitation has been sent to ${selectedSupplier.name}</p>
                    </div>
                `;
            }, 1500);
        } else {
            throw new Error(data.message || 'Failed to send invitation');
        }
    } catch (error) {
        console.error('Send invitation error:', error);
        
        // Show error state
        sendBtn.innerHTML = `
            <svg class="w-4 h-4 mr-2 inline text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Try Again
        `;
        sendBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        sendBtn.classList.add('bg-red-600', 'hover:bg-red-700');
        
        // Show error message
        alert('Error: ' + error.message);
        
        // Reset button after 3 seconds
        setTimeout(() => {
            sendBtn.disabled = false;
            sendBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                Send Invitation
            `;
            sendBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
            sendBtn.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-blue-700', 'hover:from-blue-700', 'hover:to-blue-800');
        }, 3000);
    }
}
</script>
@endsection
