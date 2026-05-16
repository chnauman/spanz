@extends('layouts.admin')

@section('title', 'Downgrade Requests')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    .soft-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        z-index: 9999;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .soft-alert.show {
        transform: translateX(0);
    }

    .soft-alert.success {
        background: rgba(34, 197, 94, 0.9);
    }

    .soft-alert.error {
        background: rgba(239, 68, 68, 0.9);
    }

    .soft-alert.info {
        background: rgba(9, 44, 72, 0.95);
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateX(0);
        }
        40% {
            transform: translateX(-10px);
        }
        60% {
            transform: translateX(-5px);
        }
    }

    .button-container {
        display: flex;
        gap: 10px; /* Space between buttons */
        justify-content: center;
        margin-top: 20px;
    }

    .accept-button, .reject-button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .accept-button {
        background-color: #4CAF50; /* Green */
        color: white;
    }

    .accept-button:hover {
        background-color: #45a049;
    }

    .reject-button {
        background-color: #f44336; /* Red */
        color: white;
    }

    .reject-button:hover {
        background-color: #da190b;
    }

    .view-button {
        background-color: #2196F3; /* Blue */
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .view-button:hover {
        background-color: #1976D2;
    }
</style>
@endpush

@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">Downgrade Requests</h1>
                <a href="{{ route('dashboard') }}" class="bg-[#0D6AED] text-white px-4 py-2 rounded text-sm hover:bg-[#0B5AC7] transition-colors">
                    Back to Dashboard
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filter Tabs -->
            <div class="mt-6 border-b border-gray-200">
                <nav class="-mb-px flex space-x-12">
                    <a href="{{ route('admin.downgrade-requests.index') }}"
                       class="py-2 px-3 border-b-2 font-medium text-sm {{ !request('status') ? 'border-[#0D6AED] text-[#0D6AED]' : 'border-transparent text-gray-500 hover:text-[#0D6AED] hover:border-[#0D6AED]' }}">
                        All Requests ({{ \App\Models\DowngradeRequest::count() }})
                    </a>
                    <a href="{{ route('admin.downgrade-requests.index', ['status' => 'pending']) }}"
                       class="py-2 px-3 border-b-2 font-medium text-sm {{ request('status') == 'pending' ? 'border-[#0D6AED] text-[#0D6AED]' : 'border-transparent text-gray-500 hover:text-[#0D6AED] hover:border-[#0D6AED]' }}">
                        Pending ({{ \App\Models\DowngradeRequest::where('status', 'pending')->count() }})
                    </a>
                    <a href="{{ route('admin.downgrade-requests.index', ['status' => 'approved']) }}"
                       class="py-2 px-3 border-b-2 font-medium text-sm {{ request('status') == 'approved' ? 'border-[#0D6AED] text-[#0D6AED]' : 'border-transparent text-gray-500 hover:text-[#0D6AED] hover:border-[#0D6AED]' }}">
                        Approved ({{ \App\Models\DowngradeRequest::where('status', 'approved')->count() }})
                    </a>
                    <a href="{{ route('admin.downgrade-requests.index', ['status' => 'declined']) }}"
                       class="py-2 px-3 border-b-2 font-medium text-sm {{ request('status') == 'declined' ? 'border-[#0D6AED] text-[#0D6AED]' : 'border-transparent text-gray-500 hover:text-[#0D6AED] hover:border-[#0D6AED]' }}">
                        Declined ({{ \App\Models\DowngradeRequest::where('status', 'declined')->count() }})
                    </a>
                </nav>
            </div>

            <!-- Current Filter Status -->
            @if(request('status'))
                <div class="mt-4 p-3 bg-[#0D6AED] bg-opacity-10 border border-[#0D6AED] border-opacity-30 rounded-lg">
                    <p class="text-sm text-[#0D6AED]">
                        <strong>Filtered by:</strong> {{ ucfirst(request('status')) }} requests
                        ({{ $requests->total() }} {{ $requests->total() == 1 ? 'result' : 'results' }})
                    </p>
                </div>
            @endif

            <!-- Requests List -->
            <div class="mt-6">
                @if($requests->count() > 0)
                    <div class="space-y-4">
                        @foreach($requests as $request)
                        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $request->user->name }}</h3>
                                            <p class="text-sm text-gray-600">{{ $request->user->email }}</p>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#0D6AED] bg-opacity-10 text-[#0D6AED] border border-[#0D6AED] border-opacity-30">
                                                    {{ $request->currentSubscription->name }}
                                                </span>
                                                <span class="text-sm text-gray-500">A${{ number_format((float)$request->currentSubscription->price, 2) }}/{{ $request->currentSubscription->billing_period }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Requested: {{ $request->requested_at ? \Carbon\Carbon::parse($request->requested_at)->format('M d, Y H:i') : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>

                                    @if($request->status !== 'pending')
                                    <div class="mt-3">
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                by {{ $request->processedBy->name ?? 'System' }} on {{ $request->processed_at ? \Carbon\Carbon::parse($request->processed_at)->format('M d, Y H:i') : 'N/A' }}
                                            </span>
                                        </div>
                                        @if($request->admin_notes)
                                        <p class="text-sm text-gray-600 mt-2">
                                            <strong>Admin Notes:</strong> {{ $request->admin_notes }}
                                        </p>
                                        @endif
                                    </div>
                                    @endif
                                </div>

                                <div class="button-container" style="margin-top: 0; justify-content: flex-end;">
                                    <!-- Approve Button -->
                                    @if($request->status === 'pending')
                                    <form action="{{ route('admin.downgrade-requests.approve', $request->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="button"
                                                onclick="handleButtonClick('Are you sure you want to approve this downgrade request?', 'info', this, 'accept')"
                                                class="accept-button">
                                            Approve
                                        </button>
                                    </form>
                                    @elseif($request->status === 'approved')
                                    <span class="accept-button" style="opacity: 0.7; cursor: default;">
                                        Approved
                                    </span>
                                    @endif

                                    <!-- Decline Button -->
                                    @if($request->status === 'pending')
                                    <form action="{{ route('admin.downgrade-requests.decline', $request->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="button"
                                                onclick="handleButtonClick('Are you sure you want to decline this downgrade request?', 'info', this, 'reject')"
                                                class="reject-button">
                                            Decline
                                        </button>
                                    </form>
                                    @elseif($request->status === 'declined')
                                    <span class="reject-button" style="opacity: 0.7; cursor: default;">
                                        Declined
                                    </span>
                                    @endif

                                    <!-- View Details Button -->
                                    <a href="{{ route('admin.downgrade-requests.show', $request->id) }}"
                                       class="view-button">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $requests->links() }}
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No downgrade requests</h3>
                        <p class="mt-1 text-xs text-gray-500">No requests found for the selected filter.</p>
                        <div class="mt-4">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Soft Alert Functions
function showSoftAlert(message, type, button) {
    // Close any existing alerts first
    closeAllAlerts();

    // Create alert element
    const alert = document.createElement('div');
    alert.className = `soft-alert ${type}`;
    alert.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px;">
            <span>${message}</span>
            <div style="display: flex; gap: 5px;">
                <button onclick="confirmAction(this)" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;">Yes</button>
                <button onclick="cancelAction(this)" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;">No</button>
            </div>
        </div>
    `;

    // Store the form reference and button info
    const form = button.closest('form');
    alert._form = form; // Store form reference directly on the element
    alert.dataset.buttonId = button.id || button.className;
    alert.dataset.actionType = message.includes('approve') ? 'approve' : 'decline';

    document.body.appendChild(alert);

    // Show alert
    setTimeout(() => {
        alert.classList.add('show');
    }, 100);
}

function closeAllAlerts() {
    // Find and close all existing alerts
    const existingAlerts = document.querySelectorAll('.soft-alert');
    existingAlerts.forEach(alert => {
        alert.classList.remove('show');
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 300);
    });
}

function handleButtonClick(message, type, button, actionType) {
    // Add thrill effect to button
    button.style.transform = 'scale(0.95)';
    button.style.transition = 'transform 0.1s ease';

    setTimeout(() => {
        button.style.transform = 'scale(1)';
    }, 100);

    // Check if there's already an alert for this action
    const existingAlert = document.querySelector('.soft-alert');
    if (existingAlert) {
        // If clicking the same button again, show a "thrill" message
        const currentMessage = existingAlert.querySelector('span').textContent;
        if (currentMessage.includes(actionType === 'accept' ? 'approve' : 'decline')) {
            showThrillAlert(actionType, button);
            return;
        }
    }

    // Show the confirmation alert
    showSoftAlert(message, type, button);
}

function showThrillAlert(actionType, button) {
    // Close existing alerts first
    closeAllAlerts();

    // Create thrill alert
    const alert = document.createElement('div');
    alert.className = 'soft-alert info';
    alert.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px;">
            <span>🎯 I'm here! Click "Yes" to ${actionType === 'accept' ? 'approve' : 'decline'} this request!</span>
            <div style="display: flex; gap: 5px;">
                <button onclick="confirmAction(this)" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;">Yes</button>
                <button onclick="cancelAction(this)" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;">No</button>
            </div>
        </div>
    `;

    // Store the form reference from the button that was clicked
    const form = button.closest('form');
    alert._form = form; // Store form reference directly on the element
    alert.dataset.actionType = actionType === 'accept' ? 'approve' : 'decline';

    document.body.appendChild(alert);

    // Show alert with bounce effect
    setTimeout(() => {
        alert.classList.add('show');
        alert.style.animation = 'bounce 0.5s ease';
    }, 100);
}

function confirmAction(button) {
    const alert = button.closest('.soft-alert');
    const form = alert._form; // Use the stored form reference

    // Hide alert
    alert.classList.remove('show');
    setTimeout(() => {
        alert.remove();
    }, 300);

    // Submit the form
    if (form) {
        console.log('Submitting form:', form);
        form.submit();
    } else {
        console.error('No form found to submit');
    }
}

function cancelAction(button) {
    const alert = button.closest('.soft-alert');

    // Hide alert
    alert.classList.remove('show');
    setTimeout(() => {
        alert.remove();
    }, 300);
}
</script>
@endsection
