@extends('layouts.admin')
@section('title', 'Sub Suppliers - SPANZ')
@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="w-full">
        <div class="bg-white shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gradient-to-r from-[#092C48] to-[#1b3963] text-white p-6 space-y-2 sm:space-y-0">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold">Sub Suppliers</h1>
                    <p class="text-gray-300 mt-2">Manage your sub suppliers and their activities</p>
                </div>
                <a href="{{ route('suppliers.invite') }}"
                   class="bg-[#0D6AED] text-white px-6 py-3 rounded-lg text-sm font-medium hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Invite Sub Supplier
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6 mx-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg mb-6 mx-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Sub Suppliers List -->
            @if($subSuppliers->count() > 0)
                <div class="p-6">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-[#092C48] to-[#1b3963]">
                                    <tr>
                                        <th class="px-8 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Sub Supplier</th>
                                        <th class="px-8 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Email</th>
                                        <th class="px-8 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Joined</th>
                                        <th class="px-8 py-4 text-center text-sm font-semibold text-white uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($subSuppliers as $subSupplier)
                                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-300">
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-12 w-12">
                                                    <div class="h-12 w-12 bg-gradient-to-br from-[#0D6AED] to-[#1b3963] rounded-full flex items-center justify-center shadow-lg">
                                                        <span class="text-white font-bold text-lg">
                                                            {{ strtoupper(substr($subSupplier->name, 0, 2)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-lg font-semibold text-gray-900">{{ $subSupplier->name }}</div>
                                                    <div class="text-sm text-gray-500">Sub Supplier</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $subSupplier->email }}</div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $subSupplier->created_at->format('M d, Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ $subSupplier->created_at->format('g:i A') }}</div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap text-center">
                                            <div class="actions-buttons">
                                                <button onclick="viewSubSupplier({{ $subSupplier->id }}, '{{ $subSupplier->name }}', '{{ $subSupplier->email }}', '{{ $subSupplier->created_at->format('M d, Y g:i A') }}')"
                                                        class="bg-[#0D6AED] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1 min-w-[100px]">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    View
                                                </button>
                                                <button onclick="removeSubSupplier({{ $subSupplier->id }}, '{{ $subSupplier->name }}')"
                                                        class="remove-btn text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Remove
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center">
                        <div class="bg-white rounded-lg shadow-lg p-4">
                            {{ $subSuppliers->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="p-6">
                    <div class="text-center py-16">
                        <div class="mx-auto h-24 w-24 bg-gradient-to-br from-[#0D6AED] to-[#1b3963] rounded-full flex items-center justify-center mb-6">
                            <svg class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">No Sub Suppliers Yet</h3>
                        <p class="text-lg text-gray-600 mb-8">Start building your supplier network by inviting your first sub supplier.</p>
                        <a href="{{ route('suppliers.invite') }}"
                           class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-[#0D6AED] to-[#1b3963] text-white font-semibold rounded-xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Invite Your First Sub Supplier
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* Ensure buttons are always visible */
.actions-buttons {
    display: flex !important;
    gap: 12px;
    justify-content: center;
    align-items: center;
}

.actions-buttons button {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    min-width: 100px;
    white-space: nowrap;
}

/* Ensure red button is clearly visible */
.remove-btn {
    background-color: #ef4444 !important;
    border: 2px solid #dc2626 !important;
    color: white !important;
}

.remove-btn:hover {
    background-color: #dc2626 !important;
    border-color: #b91c1c !important;
}
</style>

<script>
function viewSubSupplier(id, name, email, joinedDate) {
    Swal.fire({
        title: 'Sub Supplier Details',
        html: `
            <div class="text-left">
                <div class="mb-4">
                    <div class="flex items-center mb-3">
                        <div class="h-16 w-16 bg-gradient-to-br from-[#0D6AED] to-[#1b3963] rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold text-xl">${name.charAt(0).toUpperCase()}</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">${name}</h3>
                            <p class="text-gray-600">Sub Supplier</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="font-semibold text-gray-700">Email:</span>
                        <span class="text-gray-900">${email}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="font-semibold text-gray-700">Joined:</span>
                        <span class="text-gray-900">${joinedDate}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="font-semibold text-gray-700">Status:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    </div>
                </div>
            </div>
        `,
        showConfirmButton: true,
        confirmButtonText: 'Close',
        confirmButtonColor: '#0D6AED',
        width: '500px',
        customClass: {
            popup: 'rounded-xl'
        }
    });
}

function removeSubSupplier(id, name) {
    Swal.fire({
        title: 'Remove Sub Supplier',
        html: `
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-orange-100 mb-4">
                    <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <p class="text-lg text-gray-700 mb-2">Are you sure you want to remove</p>
                <p class="text-xl font-bold text-gray-900 mb-2">${name}</p>
                <p class="text-sm text-gray-600 mb-4">as your sub supplier?</p>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <p class="text-sm text-blue-800">
                        <strong>Note:</strong> They will become a regular buyer and can still use the platform.
                    </p>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Yes, Remove',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#6b7280',
        width: '450px',
        customClass: {
            popup: 'rounded-xl'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit the form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/suppliers/${id}/remove`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection
