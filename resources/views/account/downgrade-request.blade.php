@extends('layouts.admin')

@section('title', 'Request Downgrade')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full max-w-2xl mx-auto">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">Request Plan Downgrade</h1>
                <p class="text-sm text-blue-200">Downgrade to Basic (Free) plan</p>
            </div>

            <div class="mt-6">
                <!-- Current Plan Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">Current Plan</h3>
                    <p class="text-blue-700">{{ $activeSubscription->subscription->name }} - ${{ number_format((float)$activeSubscription->subscription->price, 2) }}/{{ $activeSubscription->subscription->billing_period }}</p>
                    <p class="text-blue-600 text-sm">Expires: {{ $activeSubscription->expires_at ? \Carbon\Carbon::parse($activeSubscription->expires_at)->format('M d, Y') : 'N/A' }}</p>
                </div>

                <!-- Downgrade Info -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold text-orange-800 mb-2">Important Information</h3>
                    <ul class="text-orange-700 text-sm space-y-1">
                        <li>• Your current subscription will remain active until its expiration date</li>
                        <li>• After expiration, you will automatically become a Basic (Free) user</li>
                        <li>• You will lose access to premium features after the downgrade takes effect</li>
                        <li>• This action cannot be undone once approved</li>
                    </ul>
                </div>

                <!-- Downgrade Request Form -->
                <form action="{{ route('downgrade-requests.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Downgrade <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="reason"
                            name="reason"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#0D6AED] focus:border-[#0D6AED] @error('reason') border-red-500 @enderror"
                            placeholder="Please explain why you want to downgrade your plan..."
                            required
                        >{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button
                            type="submit"
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-blue-400 transition-colors duration-200 font-medium text-center"
                        >
                            Submit Request
                        </button>
                        <a
                            href="{{ route('account.plan') }}"
                            class="flex-1 px-4 py-2 bg-blue-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200 font-medium text-center"
                        >
                            Cancel
                        </a>
                    </div>
                </form>
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
</script>
@endsection
