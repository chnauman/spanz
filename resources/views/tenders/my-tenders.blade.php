@extends('layouts.admin')
@section('title', 'My Tenders - SPANZ')
@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">My Tenders</h1>
            </div>

            <!-- Add New Tender Button -->
            <!-- <div class="mt-4 sm:mt-6">
                <a href="{{ route('tenders.create') }}" 
                   class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-[#0D6AED] text-white text-sm sm:text-base font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Post a New Tender
                </a>
            </div> -->

            <!-- Content Container -->
            <div class="mt-6">

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if($tenders->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tenders as $tender)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">{{ $tender->titleHeadline() }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tender->isActive() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $tender->isActive() ? 'Active' : 'Expired' }}
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-500 mb-3">
                            Category: {{ $tender->category->name }} • {{ $tender->created_at->diffForHumans() }}
                        </p>
                        
                        <p class="text-gray-700 text-sm mb-4 line-clamp-3">{{ Str::limit($tender->description, 150) }}</p>
                        
                        <div class="space-y-2 mb-4">
                            @if($tender->budget)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Budget:</span>
                                <span class="font-medium">{{ $tender->currency }} {{ number_format($tender->budget, 2) }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Deadline:</span>
                                <span class="font-medium">{{ $tender->deadline->format('M d, Y') }}</span>
                            </div>
                            @if($tender->displayLocation() !== 'Location not specified')
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Location:</span>
                                <span class="font-medium">{{ $tender->displayLocation() }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                            <span class="text-sm text-gray-500">
                                {{ $tender->invitations()->count() }} invitations sent
                            </span>
                            <a href="{{ route('tenders.detail', $tender->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <!-- Empty State - Centered on Page -->
            <div class="flex items-center justify-center min-h-[400px]">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No tenders posted yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Start by posting your first tender to find suppliers.</p>
                    <div class="mt-6">
                        <a href="{{ route('tenders.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Post a RFX
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <div class="mt-6 flex justify-center">
                {{ $tenders->links() }}
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
