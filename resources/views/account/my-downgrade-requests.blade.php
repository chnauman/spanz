@extends('layouts.admin')

@section('title', 'My Downgrade Requests')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">My Downgrade Requests</h1>
                <a href="{{ route('account.plan') }}" class="text-sm text-blue-200 hover:text-blue-300">← Back to Plan</a>
            </div>

            <div class="mt-6">
                @if($requests->count() > 0)
                    <div class="space-y-4">
                        @foreach($requests as $request)
                            <div class="border border-gray-200 rounded-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Downgrade from {{ $request->currentSubscription->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            Requested on {{ $request->requested_at ? \Carbon\Carbon::parse($request->requested_at)->format('M d, Y \a\t g:i A') : 'N/A' }}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium
                                        @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($request->status === 'approved') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>

                                @if($request->reason)
                                    <div class="mb-4">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Reason:</h4>
                                        <p class="text-gray-600 text-sm">{{ $request->reason }}</p>
                                    </div>
                                @endif

                                @if($request->admin_notes)
                                    <div class="mb-4">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Admin Notes:</h4>
                                        <p class="text-gray-600 text-sm">{{ $request->admin_notes }}</p>
                                    </div>
                                @endif

                                @if($request->processed_at)
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-600">
                                            Processed on {{ $request->processed_at ? \Carbon\Carbon::parse($request->processed_at)->format('M d, Y \a\t g:i A') : 'N/A' }}
                                            @if($request->processedBy)
                                                by {{ $request->processedBy->name }}
                                            @endif
                                        </p>
                                    </div>
                                @endif

                                @if($request->status === 'pending')
                                    <div class="flex gap-3">
                                        <form action="{{ route('downgrade-requests.cancel', $request->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 text-sm font-medium"
                                            >
                                                Cancel Request
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Downgrade Requests</h3>
                        <p class="text-gray-500 mb-4">You haven't submitted any downgrade requests yet.</p>
                        <a href="{{ route('account.plan') }}" class="inline-flex items-center px-4 py-2 bg-[#0D6AED] text-white rounded-lg hover:bg-[#0B5AC7] transition-colors duration-200">
                            View Plans
                        </a>
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

// Handle cancel request with SweetAlert
document.addEventListener('DOMContentLoaded', function() {
    const cancelForms = document.querySelectorAll('form[action*="/cancel"]');
    cancelForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Cancel Downgrade Request?',
                text: 'Are you sure you want to cancel this downgrade request? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, Cancel Request',
                cancelButtonText: 'Keep Request'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
