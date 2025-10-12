@extends('layouts.admin')
@section('title', 'Subscription Request Details - SPANZ')
@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">Subscription Request Details</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.subscription-requests') }}" class="bg-gray-600 text-white px-4 py-2 rounded text-sm hover:bg-gray-700 transition-colors">
                        Back to Requests
                    </a>
                </div>
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

            <!-- Request Details -->
            <div class="mt-6">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- User Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">User Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Name</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $request->user->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Email</label>
                                    <p class="text-lg text-gray-900">{{ $request->user->email }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Company</label>
                                    <p class="text-lg text-gray-900">{{ $request->user->company_name ?: 'Not provided' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Role</label>
                                    <p class="text-lg text-gray-900">{{ ucfirst($request->user->role ?: 'User') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Member Since</label>
                                    <p class="text-lg text-gray-900">{{ $request->user->created_at ? $request->user->created_at->format('M d, Y') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Subscription Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Subscription Request</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Requested Plan</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $request->subscription->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Price</label>
                                    <p class="text-lg font-semibold text-gray-900">${{ number_format($request->subscription->price, 2) }}/month</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Credits per Month</label>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ $request->subscription->credits_per_month == -1 ? 'Unlimited' : $request->subscription->credits_per_month }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Status</label>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ 
                                        $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        ($request->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') 
                                    }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Requested At</label>
                                    <p class="text-lg text-gray-900">{{ $request->requested_at ? $request->requested_at->format('M d, Y H:i') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Processing Information -->
                    @if($request->status !== 'pending')
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Processing Information</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Processed By</label>
                                <p class="text-lg text-gray-900">{{ $request->processedBy->name ?? 'System' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Processed At</label>
                                <p class="text-lg text-gray-900">{{ $request->processed_at ? $request->processed_at->format('M d, Y H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                        
                        @if($request->admin_notes)
                        <div class="mt-4">
                            <label class="text-sm font-medium text-gray-600">Admin Notes</label>
                            <div class="mt-2 p-4 bg-gray-50 rounded-lg">
                                <p class="text-gray-900">{{ $request->admin_notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Plan Description -->
                    @if($request->subscription->description)
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Plan Description</h3>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-gray-900">{{ $request->subscription->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                @if(!$request->status === 'pending')
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Status</h3>
                    <div class="p-4 rounded-lg {{ $request->status === 'approved' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">{{ $request->status === 'approved' ? '✅' : '❌' }}</span>
                            <div>
                                <p class="text-lg font-semibold {{ $request->status === 'approved' ? 'text-green-800' : 'text-red-800' }}">
                                    Request {{ ucfirst($request->status) }}
                                </p>
                                <p class="text-sm {{ $request->status === 'approved' ? 'text-green-600' : 'text-red-600' }}">
                                    This request has been {{ $request->status }} by {{ $request->processedBy->name ?? 'System' }}
                                    @if($request->processed_at)
                                        on {{ $request->processed_at->format('M d, Y H:i') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Decline Modal -->
<div id="declineModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center mb-4">
                <span class="text-2xl mr-3">❌</span>
                <h3 class="text-xl font-bold text-gray-900">Decline Subscription Request</h3>
            </div>
            
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-800">
                    <strong>Warning:</strong> This action will decline the subscription request for 
                    <strong>{{ $request->user->name }}</strong> for the <strong>{{ $request->subscription->name }}</strong> plan.
                </p>
            </div>
            
            <form action="{{ route('admin.subscriptions.decline-request', $request) }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Decline (Optional)
                    </label>
                    <textarea id="admin_notes" 
                              name="admin_notes" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                              placeholder="Provide a reason for declining this request..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">This reason will be sent to the user via email.</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeDeclineModal()"
                            class="bg-gray-600 text-white px-6 py-3 rounded-lg text-base font-semibold hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to decline this subscription request?')"
                            class="bg-red-600 text-white px-6 py-3 rounded-lg text-base font-semibold hover:bg-red-700 transition-colors">
                        ❌ Decline Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openDeclineModal() {
    document.getElementById('declineModal').classList.remove('hidden');
}

function closeDeclineModal() {
    document.getElementById('declineModal').classList.add('hidden');
}
</script>
@endsection
