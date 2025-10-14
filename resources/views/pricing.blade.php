<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing Plans - Spanz</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
</head>
<body>
    <div class="bg-image bg-cover bg-center" style="background-image:url('{{ asset('spanz-img/spanz-bg.jpg') }}')">
        <!-- Navbar -->
        <nav class="bg-image bg-cover bg-center" style="background-image:url('{{ asset('spanz-img/spanz-bg.jpg') }}') absolute top-0 left-0 w-full z-50">
            <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-[#0D6AED]">Spanz</a>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex space-x-6">
                        <a href="{{ route('home') }}" class="text-white hover:text-blue-400">Home</a>
                        <a href="{{ route('tenders.search') }}" class="text-white hover:text-blue-400">Tenders</a>
                        <a href="#" class="text-white hover:text-blue-400">About</a>
                    </div>

                    <!-- Right Actions -->
                    <div class="hidden md:flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="border border-white text-white px-3 py-1 rounded hover:bg-white hover:text-black">
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="border border-white text-white px-3 py-1 rounded hover:bg-white hover:text-black">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="mt-20 min-h-screen bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Header -->
                <div class="text-center mb-16">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Plan</h1>
                    <p class="text-xl text-gray-600">Select the perfect subscription plan for your business needs</p>
                </div>

                <!-- Pricing Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    @foreach($subscriptions as $subscription)
                    <div class="bg-white rounded-lg shadow-lg p-8 {{ $subscription->name === 'Pro' ? 'ring-2 ring-blue-500 transform scale-105' : '' }}">
                        @if($subscription->name === 'Pro')
                            <div class="bg-blue-500 text-white text-center py-2 px-4 rounded-full text-sm font-semibold mb-4 -mt-8 mx-auto w-32">
                                Most Popular
                            </div>
                        @endif

                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $subscription->name }}</h3>
                            <div class="mb-4">
                                <span class="text-4xl font-bold text-gray-900">${{ number_format((float)$subscription->price, 2) }}</span>
                                @if($subscription->price > 0)
                                    <span class="text-gray-600">/month</span>
                                @endif
                            </div>
                            <p class="text-gray-600 mb-6">{{ $subscription->description }}</p>
                        </div>

                        <ul class="space-y-3 mb-8">
                            @if($subscription->credits_per_month == -1)
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Unlimited Credits</span>
                                </li>
                            @elseif($subscription->credits_per_month > 0)
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $subscription->credits_per_month }} Credits per month</span>
                                </li>
                            @else
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Basic access</span>
                                </li>
                            @endif

                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Access to supplier network</span>
                            </li>

                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Browse tenders</span>
                            </li>

                            @if($subscription->name !== 'Basic')
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>View tender details</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Save tenders</span>
                                </li>
                            @endif
                        </ul>

                        <div class="text-center">
                            @auth
                                @if($user->getActiveSubscription() && $user->getActiveSubscription()->subscription_id == $subscription->id)
                                    <button class="w-full bg-gray-400 text-white py-3 px-6 rounded-lg font-semibold cursor-not-allowed">
                                        Current Plan
                                    </button>
                                @else
                                    <a href="{{ route('subscription-requests.request', $subscription) }}"
                                       class="w-full {{ $subscription->name === 'Pro' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-600 hover:bg-gray-700' }} text-white py-3 px-6 rounded-lg font-semibold inline-block text-center transition">
                                        {{ $subscription->price == 0 ? 'Get Started' : 'Request Subscription' }}
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('register') }}"
                                   class="w-full {{ $subscription->name === 'Pro' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-600 hover:bg-gray-700' }} text-white py-3 px-6 rounded-lg font-semibold inline-block text-center transition">
                                    Sign Up
                                </a>
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- FAQ Section -->
                <div class="mt-16 max-w-3xl mx-auto">
                    <h2 class="text-3xl font-bold text-center text-gray-900 mb-8">Frequently Asked Questions</h2>
                    <div class="space-y-6">
                        <div class="bg-white rounded-lg p-6 shadow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">What are credits used for?</h3>
                            <p class="text-gray-600">Credits are used to view detailed tender information, contact buyers, and access premium features.</p>
                        </div>
                        <div class="bg-white rounded-lg p-6 shadow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I change my plan later?</h3>
                            <p class="text-gray-600">Yes, you can upgrade or downgrade your subscription at any time. Changes will be reflected in your next billing cycle.</p>
                        </div>
                        <div class="bg-white rounded-lg p-6 shadow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a free trial?</h3>
                            <p class="text-gray-600">Yes, the Basic plan is free and allows you to browse tenders. Premium features require a paid subscription.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
