@extends('layouts.admin')
@section('title', 'Manage Interests - SPANZ')
@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold">🎯 Manage Your Interests</h1>
                    <p class="text-gray-300 mt-1">Update your category interests and budget ranges to receive personalized tender notifications.</p>
                </div>
            </div>

            <div class="p-6">
                <!-- Current Interests Summary -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Interests</h3>
                    @if($existingInterests->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($existingInterests as $interest)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $interest->category->name }}</h4>
                                        @if($interest->min_budget || $interest->max_budget)
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-dollar-sign me-1"></i>
                                            Budget: {{ $interest->min_budget ? number_format($interest->min_budget, 2) : '0' }} - {{ $interest->max_budget ? number_format($interest->max_budget, 2) : '∞' }} {{ $interest->currency }}
                                        </p>
                                        @else
                                        <p class="text-sm text-gray-600 mt-1">No budget range set</p>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('user.interests.delete', $interest->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to remove this interest?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-heart text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-600">You haven't set any interests yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('user.interests') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors text-center">
                        <i class="fas fa-edit me-2"></i>Update Interests
                    </a>
                    <a href="{{ route('dashboard') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-700 transition-colors text-center">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
