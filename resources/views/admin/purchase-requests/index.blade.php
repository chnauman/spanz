@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="p-4 max-w-7xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-[#092C48]">Purchase Requests</h1>
        <div class="flex gap-2">
            <select id="status-filter" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($purchaseRequests as $request)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        @if($request->user)
                                            <span class="text-sm font-medium text-gray-700">{{ substr($request->user->name, 0, 1) }}</span>
                                        @else
                                            <span class="text-sm font-medium text-gray-700">G</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-4">
                                    @if($request->user)
                                        <div class="text-sm font-medium text-gray-900">{{ $request->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $request->user->email }}</div>
                                    @else
                                        <div class="text-sm font-medium text-gray-900">Guest User</div>
                                        <div class="text-sm text-gray-500">Contact info in notes</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $request->product->title }}</div>
                            <div class="text-sm text-gray-500">{{ optional($request->product->category)->name ?? 'Uncategorized' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $request->quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($request->status === 'approved') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $request->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                @if($request->status === 'pending')
                                    <button onclick="updateStatus({{ $request->id }}, 'approved')" 
                                            class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 px-3 py-1 rounded text-xs">
                                        Approve
                                    </button>
                                    <button onclick="updateStatus({{ $request->id }}, 'rejected')" 
                                            class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded text-xs">
                                        Reject
                                    </button>
                                @endif
                                <button onclick="viewDetails({{ $request->id }})" 
                                        class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded text-xs">
                                    View
                                </button>
                                <button onclick="deleteRequest({{ $request->id }})" 
                                        class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded text-xs">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No purchase requests found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($purchaseRequests->hasPages())
    <div class="mt-6">
        {{ $purchaseRequests->links() }}
    </div>
    @endif
</div>

<!-- Request Details Modal -->
<div id="details-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full shadow-xl border border-gray-200">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center space-x-2">
                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Request Details</h3>
                </div>
                <button id="close-details-modal" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4 max-h-80 overflow-y-auto">
                <div id="request-details" class="space-y-3">
                    <!-- Details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Status filter functionality
document.getElementById('status-filter').addEventListener('change', function() {
    const status = this.value;
    const url = new URL(window.location);
    
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    
    window.location.href = url.toString();
});

// Update request status
function updateStatus(requestId, status) {
    Swal.fire({
        title: 'Are you sure?',
        text: `Are you sure you want to ${status} this request?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0D6AED',
        cancelButtonColor: '#d33',
        confirmButtonText: `Yes, ${status} it!`
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/purchase-requests/${requestId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#0D6AED'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'An error occurred.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#0D6AED'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0D6AED'
                });
            });
        }
    });
}

// Delete request
function deleteRequest(requestId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#0D6AED',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/purchase-requests/${requestId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#0D6AED'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'An error occurred.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#0D6AED'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0D6AED'
                });
            });
        }
    });
}

// View request details
function viewDetails(requestId) {
    // Show loading state
    const modal = document.getElementById('details-modal');
    const detailsContainer = document.getElementById('request-details');
    
    // Show modal with loading state
    modal.classList.remove('hidden');
    detailsContainer.innerHTML = `
        <div class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#0D6AED]"></div>
            <span class="ml-2 text-gray-600">Loading details...</span>
        </div>
    `;
    
    // Fetch purchase request details
    fetch(`/admin/purchase-requests/${requestId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const request = data.data;
            
            // Build the details HTML
            let userInfo = '';
            if (request.user) {
                userInfo = `
                    <div class="bg-gray-50 border border-gray-200 p-3 rounded">
                        <h4 class="font-medium text-gray-900 mb-2 text-sm">User Information</h4>
                        <div class="space-y-1 text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-medium text-gray-900">${request.user.name}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium text-gray-900">${request.user.email}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span class="font-medium text-gray-900">${request.user.phone}</span>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                userInfo = `
                    <div class="bg-gray-50 border border-gray-200 p-3 rounded">
                        <h4 class="font-medium text-gray-900 mb-2 text-sm">Guest User</h4>
                        <p class="text-gray-600 text-xs">Contact information is included in the notes below.</p>
                    </div>
                `;
            }
            
            detailsContainer.innerHTML = `
                <div class="space-y-3">
                    ${userInfo}
                    
                    <div class="bg-gray-50 border border-gray-200 p-3 rounded">
                        <h4 class="font-medium text-gray-900 mb-2 text-sm">Product Information</h4>
                        <div class="space-y-1 text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Product:</span>
                                <span class="font-medium text-gray-900">${request.product.title}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Category:</span>
                                <span class="font-medium text-gray-900">${request.product.category}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Price:</span>
                                <span class="font-medium text-gray-900">${request.product.price}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 border border-gray-200 p-3 rounded">
                        <h4 class="font-medium text-gray-900 mb-2 text-sm">Request Details</h4>
                        <div class="space-y-1 text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Quantity:</span>
                                <span class="font-medium text-gray-900">${request.quantity}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Status:</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    ${request.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                      request.status === 'approved' ? 'bg-green-100 text-green-800' : 
                                      'bg-red-100 text-red-800'}">
                                    ${request.status.charAt(0).toUpperCase() + request.status.slice(1)}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Requested:</span>
                                <span class="font-medium text-gray-900">${request.created_at}</span>
                            </div>
                        </div>
                    </div>
                    
                    ${request.notes ? `
                    <div class="bg-gray-50 border border-gray-200 p-3 rounded">
                        <h4 class="font-medium text-gray-900 mb-2 text-sm">Notes</h4>
                        <p class="text-gray-700 text-xs whitespace-pre-wrap">${request.notes}</p>
                    </div>
                    ` : ''}
                </div>
            `;
        } else {
            detailsContainer.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-600">Error loading request details.</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        detailsContainer.innerHTML = `
            <div class="text-center py-8">
                <p class="text-red-600">Error loading request details.</p>
            </div>
        `;
    });
    
    // Close modal functionality
    document.getElementById('close-details-modal').addEventListener('click', function() {
        modal.classList.add('hidden');
    });
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
}
</script>
@endsection
