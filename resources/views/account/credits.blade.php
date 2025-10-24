@extends('layouts.admin')

@section('title', 'Show Credits')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">Show Credits</h1>
                <p class="text-sm text-blue-200">View your credit balance and transaction history</p>
            </div>

            <div class="mt-6">
                <!-- Credit Balance Summary -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Credit Balance</h2>
                    <div class="flex items-center justify-between p-6 bg-gradient-to-r from-[#0D6AED] to-[#0B5AC7] rounded-lg text-white">
                        <div>
                            <h3 class="text-2xl font-bold">{{ number_format($totalCredits) }}</h3>
                            <p class="text-blue-200">Available Credits</p>
                        </div>
                        <div class="text-right">
                            <svg class="w-12 h-12 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Credit History -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Credit History</h2>

                    @if($creditHistory->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($creditHistory as $credit)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $credit->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $credit->amount > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $credit->amount > 0 ? 'Credit' : 'Debit' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $credit->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $credit->amount > 0 ? '+' : '' }}{{ number_format($credit->amount) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $credit->description ?? 'Credit transaction' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($credit->balance_after) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No credit history</h3>
                            <p class="mt-1 text-sm text-gray-500">You haven't made any credit transactions yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Credit Usage Tips -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Credit Usage Tips</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Credits are used when you view tender details or contact buyers</li>
                                    <li>Each credit allows you to access premium features</li>
                                    <li>You can purchase additional credits through subscription plans</li>
                                    <li>Credits don't expire and can be used anytime</li>
                                </ul>
                            </div>
                        </div>
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
</script>
@endsection
