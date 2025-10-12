@extends('layouts.app')
@section('title', 'Subscription Plans - SPANZ')
@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full max-w-6xl mx-auto">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Choose Your Subscription Plan</h1>
                <p class="text-lg text-gray-600">Unlock premium features and access to detailed tender information</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Current Subscription Status -->
            @if(auth()->user()->hasActiveSubscription())
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-blue-800">Current Subscription</h3>
                            <p class="text-sm text-blue-700">
                                You are currently subscribed to the 
                                <strong>{{ auth()->user()->getActiveSubscription()->subscription->name }}</strong> plan.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Subscription Plans -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($subscriptions as $subscription)
                <div class="border border-gray-200 rounded-lg p-6 {{ $subscription->name === 'Enterprise' ? 'border-purple-300 bg-purple-50' : '' }} hover:shadow-lg transition-shadow">
                    @if($subscription->name === 'Enterprise')
                    <div class="text-center mb-4">
                        <span class="bg-purple-600 text-white px-3 py-1 rounded-full text-xs font-medium">Most Popular</span>
                    </div>
                    @endif
                    
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $subscription->name }}</h3>
                        <div class="text-4xl font-bold {{ $subscription->name === 'Enterprise' ? 'text-purple-600' : 'text-blue-600' }} mb-2">
                            ${{ number_format($subscription->price, 2) }}
                        </div>
                        <div class="text-gray-500">per month</div>
                    </div>
                    
                    <div class="mb-6">
                        <div class="text-sm text-gray-600 mb-4">
                            <strong>Credits:</strong> 
                            {{ $subscription->credits_per_month == -1 ? 'Unlimited' : $subscription->credits_per_month }}
                        </div>
                        
                        @if($subscription->description)
                        <p class="text-sm text-gray-600">{{ $subscription->description }}</p>
                        @endif
                        
                        <ul class="text-sm text-gray-600 space-y-2 mt-4">
                            <li>• View detailed tender information</li>
                            <li>• Access to premium features</li>
                            @if($subscription->name === 'Enterprise')
                            <li>• Advanced analytics</li>
                            <li>• Custom integrations</li>
                            @endif
                            <li>• Priority support</li>
                        </ul>
                    </div>
                    
                    <div class="text-center">
                        @php
                            $userRequest = auth()->user()->subscriptionRequests()
                                ->where('subscription_id', $subscription->id)
                                ->where('status', 'pending')
                                ->first();
                        @endphp
                        
                        @if($userRequest)
                            <button class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg text-sm font-medium cursor-not-allowed">
                                Request Pending
                            </button>
                        @elseif(auth()->user()->getActiveSubscription() && auth()->user()->getActiveSubscription()->subscription_id === $subscription->id)
                            <button class="w-full bg-green-600 text-white px-6 py-3 rounded-lg text-sm font-medium cursor-not-allowed">
                                Current Plan
                            </button>
                        @else
                            <form action="{{ route('subscription-requests.request', $subscription) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="w-full {{ $subscription->name === 'Enterprise' ? 'bg-purple-600 hover:bg-purple-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                                    Request Subscription
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">
                    All subscription requests are reviewed by our admin team. You'll be notified once your request is processed.
                </p>
                <div class="mt-4">
                    <a href="{{ route('subscription-requests.my-requests') }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View My Subscription Requests
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
