@extends('layouts.admin')
@section('title', 'Viewed Tenders - SPANZ')
@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold">Viewed Tenders</h1>
                    <p class="text-gray-300 mt-1">Tenders you have previously viewed and paid credits for.</p>
                </div>
            </div>

            @if($viewedTenders->count() > 0)
                <div class="mt-6">
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($viewedTenders as $view)
                            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border border-gray-200">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">
                                        {{ $view->tender->titleHeadline() }}
                                    </h3>
                                    <span class="text-xs text-gray-500">
                                        Viewed {{ $view->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 line-clamp-3">
                                        {{ Str::limit($view->tender->description, 150) }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <span>{{ $view->tender->category->name }}</span>
                                    <span>{{ $view->tender->displayLocation() }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="text-sm">
                                        <span class="text-gray-500">Budget:</span>
                                        <span class="font-medium text-gray-900">
                                            {{ $view->tender->currency }} {{ number_format($view->tender->budget) }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Deadline: {{ $view->tender->getFormattedDeadline('M d, Y') }}
                                    </div>
                                </div>

                                <div class="mt-4 flex space-x-2">
                                    <a href="{{ route('tenders.detail', $view->tender->id) }}"
                                       class="flex-1 bg-[#0D6AED] text-white text-center py-2 px-4 rounded hover:bg-blue-800 transition-colors">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $viewedTenders->links() }}
                    </div>
                </div>
            @else
                <div class="mt-6">
                    <div class="text-center py-12">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No viewed tenders yet</h3>
                        <p class="text-gray-500 mb-6">You haven't viewed any tenders yet. Start exploring available tenders to see them here.</p>
                        <a href="{{ route('tenders.search') }}"
                           class="bg-[#0D6AED] text-white px-6 py-2 rounded hover:bg-blue-800 transition-colors">
                            Browse Tenders
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
