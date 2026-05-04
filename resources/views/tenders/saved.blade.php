<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Saved Tenders - SPANZ</title>
    @php($tendersSavedCssQuery = is_file(public_path('css/output.css')) ? filemtime(public_path('css/output.css')) : time())
    <link rel="stylesheet" href="{{ asset('css/output.css') }}?v={{ $tendersSavedCssQuery }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .tender-card {
            transition: all 0.3s ease;
            min-height: 400px;
        }
        .tender-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Include Admin Layout -->
    @extends('layouts.admin')

    @section('title', 'My Saved Tenders - SPANZ')

    @section('content')
    <div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
        <div class="w-full">
            <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold">My Saved Tenders</h1>
                        <p class="text-gray-300 mt-1">Manage your saved tenders and opportunities</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-300">
                            {{ $savedTenders->total() }} saved tender{{ $savedTenders->total() !== 1 ? 's' : '' }}
                        </span>
                        <a href="{{ route('tenders.search') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm transition-colors">
                            Browse More Tenders
                        </a>
                    </div>
                </div>

                <!-- Saved Tenders Grid -->
                @if($savedTenders->count() > 0)
                    <div class="mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($savedTenders as $savedTender)
                                @php
                                    $tender = $savedTender->tender;
                                @endphp
                                <div class="tender-card bg-white rounded-lg border border-gray-200 p-6 shadow-sm flex flex-col" data-saved-tender-id="{{ $savedTender->id }}">
                                    <!-- Tender Header -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-[#092C48] mb-2 line-clamp-2">
                                                <a href="{{ route('tenders.detail', $tender->id) }}"
                                                   class="hover:text-blue-600 transition-colors duration-200">
                                                    {{ $tender->titleHeadline() }}
                                                </a>
                                            </h3>
                                            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                                                <img src="{{ asset('spanz-img/location.svg') }}" alt="Location" class="w-4 h-4">
                                                <span>{{ $tender->displayLocation() }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                                <img src="{{ asset('spanz-img/factory.svg') }}" alt="Category" class="w-4 h-4">
                                                <span>{{ $tender->category->name }}</span>
                                                @if($tender->budget)
                                                    <span class="text-[#0D6AED] font-medium">
                                                        • {{ $tender->currency }} {{ number_format($tender->budget, 0) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tender Description -->
                                    <div class="mb-4 flex-1">
                                        <p class="text-gray-700 text-sm leading-relaxed line-clamp-3">
                                            {{ Str::limit($tender->description, 150) }}
                                        </p>
                                    </div>

                                    <!-- Tender Meta Info -->
                                    <div class="mb-4 text-xs text-gray-500">
                                        <div class="flex items-center justify-between">
                                            <span>Posted {{ $tender->created_at->diffForHumans() }}</span>
                                            <span>Deadline: {{ $tender->deadline ? \Carbon\Carbon::parse($tender->deadline)->format('M d, Y') : 'Not specified' }}</span>
                                        </div>
                                    </div>

                                    <!-- Action Buttons - Fixed Position -->
                                    <div class="flex items-center gap-3 mt-auto">
                                        <a href="{{ route('tenders.detail', $tender->id) }}"
                                           class="flex-1 bg-[#0D6AED] hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors duration-200">
                                            View Details
                                        </a>
                                        <button onclick="unsaveTender({{ $tender->id }}, {{ $savedTender->id }})"
                                                class="bg-orange-50 hover:bg-orange-100 text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200"
                                                title="Unsave Tender">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9l-6 6m0-6l6 6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($savedTenders->hasPages())
                            <div class="mt-8 flex justify-center">
                                {{ $savedTenders->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="mt-6">
                        <div class="text-center py-12">
                            <div class="mb-6">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Saved Tenders</h3>
                            <p class="text-gray-600 mb-6">You haven't saved any tenders yet. Start browsing to find opportunities that interest you.</p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('tenders.search') }}"
                                   class="bg-[#0D6AED] hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                                    Browse Tenders
                                </a>
                                <a href="{{ route('dashboard') }}"
                                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                                    Go to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function unsaveTender(tenderId, savedTenderId) {
            Swal.fire({
                title: 'Unsave Tender?',
                text: 'Are you sure you want to remove this tender from your saved list?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f97316',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Unsave',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/tenders/${tenderId}/unsave`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the card from the DOM
                            const card = document.querySelector(`[data-saved-tender-id="${savedTenderId}"]`);
                            if (card) {
                                card.style.transition = 'all 0.3s ease';
                                card.style.transform = 'scale(0.95)';
                                card.style.opacity = '0';
                                setTimeout(() => {
                                    card.remove();

                                    // Check if this was the last tender
                                    const remainingCards = document.querySelectorAll('.tender-card');
                                    if (remainingCards.length === 0) {
                                        location.reload(); // Reload to show empty state
                                    }
                                }, 300);
                            }

                            // Show success message with SweetAlert
                            Swal.fire({
                                title: 'Success!',
                                text: 'Tender removed from saved list',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to remove tender from saved list',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while removing the tender',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        }

        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    </script>
    @endsection
</body>
</html>
