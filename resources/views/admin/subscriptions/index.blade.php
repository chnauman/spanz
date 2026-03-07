@extends('layouts.admin')
@section('title', 'Subscription Management - SPANZ')
@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">Subscription Management</h1>
                <a href="{{ route('admin.subscriptions.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition-colors">
                    Add New Subscription
                </a>
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

            <!-- Subscription Plans -->
            <div class="mt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($subscriptions as $subscription)
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $subscription->name }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $subscription->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $subscription->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <div class="text-3xl font-bold text-gray-900">A${{ number_format($subscription->price, 2) }}</div>
                            <div class="text-sm text-gray-500">per month (AUD)</div>
                        </div>

                        <div class="mb-4">
                            <div class="text-sm text-gray-600">
                                <strong>Credits:</strong>
                                {{ $subscription->credits_per_month == -1 ? 'Unlimited' : $subscription->credits_per_month }}
                            </div>
                            <div class="text-sm text-gray-600 mt-1">
                                <strong>Cost per View:</strong>
                                {{ $subscription->credit_cost_per_view ?? 1 }} credit{{ ($subscription->credit_cost_per_view ?? 1) > 1 ? 's' : '' }}
                            </div>
                            @if($subscription->description)
                            <div class="text-sm text-gray-600 mt-2">{{ $subscription->description }}</div>
                            @endif
                        </div>

                        <div class="flex space-x-2">
                            <a href="{{ route('admin.subscriptions.edit', $subscription) }}"
                               class="flex-1 bg-blue-600 text-white text-center px-4 py-2 rounded text-sm hover:bg-blue-700 transition-colors">
                                Edit
                            </a>
                            @if(!$subscription->userSubscriptions()->exists())
                            <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700 transition-colors"
                                        onclick="return confirm('Are you sure you want to delete this subscription?')">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('admin.subscription-requests') }}"
                       class="bg-yellow-600 text-white px-4 py-2 rounded text-sm hover:bg-yellow-700 transition-colors">
                        View Subscription Requests
                    </a>
                    <a href="{{ route('admin.subscriptions.create') }}"
                       class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700 transition-colors">
                        Create New Plan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
