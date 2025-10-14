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
<div id="invitationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Send Invitation</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div id="invitationForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                    <div id="selectedSupplier" class="p-3 bg-gray-50 rounded-lg"></div>
                </div>

                <div class="mb-4">
                    <label for="invitationMessage" class="block text-sm font-medium text-gray-700 mb-2">Message (Optional)</label>
                    <textarea id="invitationMessage"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Add a personal message to your invitation..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button onclick="closeModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button onclick="sendInvitation()"
                            id="sendInvitationBtn"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Send Invitation
                    </button>
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
    const resultsHtml = suppliers.map(supplier => `
        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-medium text-sm">${supplier.name.charAt(0).toUpperCase()}</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">${supplier.name}</h3>
                        <p class="text-sm text-gray-500">${supplier.email}</p>
                        ${supplier.company_detail ? `<p class="text-xs text-gray-400">${supplier.company_detail.company_name}</p>` : ''}
                    </div>
                </div>
                <button onclick="openInvitationModal(${JSON.stringify(supplier).replace(/"/g, '&quot;')})"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Invite
                </button>
            </div>
        </div>
    `).join('');

    document.getElementById('searchResults').innerHTML = resultsHtml;
}

function openInvitationModal(supplier) {
    selectedSupplier = supplier;
    document.getElementById('selectedSupplier').innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="text-blue-600 font-medium text-sm">${supplier.name.charAt(0).toUpperCase()}</span>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-900">${supplier.name}</h4>
                <p class="text-xs text-gray-500">${supplier.email}</p>
            </div>
        </div>
    `;
    document.getElementById('invitationModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('invitationModal').classList.add('hidden');
    selectedSupplier = null;
    document.getElementById('invitationMessage').value = '';
}

async function sendInvitation() {
    if (!selectedSupplier) return;

    const message = document.getElementById('invitationMessage').value;
    const sendBtn = document.getElementById('sendInvitationBtn');

    // Disable button and show loading
    sendBtn.disabled = true;
    sendBtn.innerHTML = 'Sending...';

    try {
        const response = await fetch('/invite-sub-suppliers/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                invitee_id: selectedSupplier.id,
                message: message
            })
        });

        const data = await response.json();

        if (data.success) {
            alert('Invitation sent successfully!');
            closeModal();
            // Remove the supplier from search results
            document.getElementById('searchResults').innerHTML = `
                <div class="text-center text-green-500 py-6">
                    <svg class="mx-auto h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="mt-2">Invitation sent to ${selectedSupplier.name}!</p>
                </div>
            `;
        } else {
            alert(data.message || 'Failed to send invitation. Please try again.');
        }
    } catch (error) {
        console.error('Send invitation error:', error);
        alert('Error sending invitation. Please try again.');
    } finally {
        // Re-enable button
        sendBtn.disabled = false;
        sendBtn.innerHTML = 'Send Invitation';
    }
}
</script>
@endsection
