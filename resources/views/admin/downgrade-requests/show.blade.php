@extends('layouts.admin')

@section('title', 'Downgrade Request Details')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="w-full">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-[#092C48] to-[#1b3963] text-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Downgrade Request Details</h1>
                    <p class="text-blue-200 mt-1">Review and process downgrade request</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.downgrade-requests.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors duration-200 text-blue-200 hover:text-white">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Requests
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-6">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">

                    <!-- Request Information -->
                    <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Request Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">User Information</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">Name:</span> {{ $downgradeRequest->user->name }}</p>
                                <p><span class="font-medium">Email:</span> {{ $downgradeRequest->user->email }}</p>
                                <p><span class="font-medium">Role:</span> {{ ucfirst($downgradeRequest->user->role) }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Current Subscription</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">Plan:</span> {{ $downgradeRequest->currentSubscription->name }}</p>
                                <p><span class="font-medium">Price:</span> ${{ number_format((float)$downgradeRequest->currentSubscription->price, 2) }}/{{ $downgradeRequest->currentSubscription->billing_period }}</p>
                                <p><span class="font-medium">Credits:</span> {{ $downgradeRequest->currentSubscription->credits_per_month }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Request Details</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium">Status:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($downgradeRequest->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($downgradeRequest->status === 'approved') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($downgradeRequest->status) }}
                                </span>
                            </p>
                            <p><span class="font-medium">Requested:</span> {{ $downgradeRequest->requested_at ? \Carbon\Carbon::parse($downgradeRequest->requested_at)->format('M d, Y \a\t g:i A') : 'N/A' }}</p>
                            @if($downgradeRequest->processed_at)
                                <p><span class="font-medium">Processed:</span> {{ $downgradeRequest->processed_at ? \Carbon\Carbon::parse($downgradeRequest->processed_at)->format('M d, Y \a\t g:i A') : 'N/A' }}</p>
                                @if($downgradeRequest->processedBy)
                                    <p><span class="font-medium">Processed by:</span> {{ $downgradeRequest->processedBy->name }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reason for Downgrade -->
                @if($downgradeRequest->reason)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">Reason for Downgrade</h3>
                    <p class="text-blue-700">{{ $downgradeRequest->reason }}</p>
                </div>
                @endif

                <!-- Admin Notes -->
                @if($downgradeRequest->admin_notes)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Admin Notes</h3>
                    <p class="text-gray-700">{{ $downgradeRequest->admin_notes }}</p>
                </div>
                @endif

                <!-- Actions -->
                @if($downgradeRequest->status !== 'pending')
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Request Status</h3>
                    <p class="text-gray-600">
                        This request has been {{ $downgradeRequest->status }}.
                        @if($downgradeRequest->processed_at)
                            It was processed on {{ $downgradeRequest->processed_at ? \Carbon\Carbon::parse($downgradeRequest->processed_at)->format('M d, Y \a\t g:i A') : 'N/A' }}.
                        @endif
                    </p>
                </div>
                @endif
                    </div>
                </div>
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

// Show success/error messages if they exist
@if(session('success'))
    showSuccessAlert('Success!', '{{ session('success') }}');
@endif

@if(session('error'))
    showErrorAlert('Error!', '{{ session('error') }}');
@endif

@if(session('info'))
    showAlert('Information', '{{ session('info') }}', 'info');
@endif

// Handle approve/decline actions with SweetAlert
document.addEventListener('DOMContentLoaded', function() {
    // Handle approve form
    const approveForm = document.querySelector('form[action*="/approve"]');
    console.log('Found approve form:', approveForm);
    if (approveForm) {
        approveForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Approve form submitted');

            Swal.fire({
                title: 'Approve Downgrade Request?',
                text: 'Are you sure you want to approve this downgrade request? The user will become free after their subscription expires.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, Approve',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    approveForm.submit();
                }
            });
        });
    }

    // Handle decline form
    const declineForm = document.querySelector('form[action*="/decline"]');
    console.log('Found decline form:', declineForm);
    if (declineForm) {
        declineForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Decline form submitted');

            Swal.fire({
                title: 'Decline Downgrade Request?',
                text: 'Are you sure you want to decline this downgrade request?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, Decline',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    declineForm.submit();
                }
            });
        });
    }
});
</script>
@endsection
