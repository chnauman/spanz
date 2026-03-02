<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPANZ Registration - Step 3</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
    <style>
        /* Pricing cards layout (Tailwind-independent) */
        .subscription-form {
            max-width: 820px;
            margin-left: auto;
            margin-right: auto;
        }

        @media (max-width: 1024px) {
            .subscription-form {
                max-width: 100%;
            }
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
            margin-bottom: 24px;
            max-width: 820px; /* reduce overall row width */
            margin-left: auto;
            margin-right: auto;
        }

        @media (max-width: 900px) {
            .plans-grid {
                grid-template-columns: 1fr;
            }
        }

        .subscription-card {
            --accent: #0D6AED;
            transition: all 0.3s ease;
            cursor: pointer;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-height: 420px;
            overflow: hidden;
            background: #ffffff;
            border: 2px solid #eef2f7;
            color: #111827;
        }

        .subscription-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .subscription-card.basic-card {
            --accent: #f59e0b;
        }

        .subscription-card.pro-card {
            --accent: #2f77c8;
        }

        .subscription-card.enterprise-card {
            --accent: #b2659c;
        }

        .subscription-card.selected {
            border-width: 3px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
            transform: scale(1.02);
            border-color: var(--accent);
        }

        .subscription-card.selected.pro-card {
            box-shadow: 0 12px 30px rgba(47, 119, 200, 0.28);
        }

        .subscription-card.selected.enterprise-card {
            box-shadow: 0 12px 30px rgba(178, 101, 156, 0.28);
        }

        .checkmark {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .checkmark.active {
            background-color: var(--accent);
            color: #ffffff;
        }

        .checkmark.inactive {
            background-color: #d1d5db;
            color: #9ca3af;
        }

        .plan-top {
            padding: 22px 22px 10px 22px;
        }

        .plan-body {
            padding: 0 22px 18px 22px;
        }

        .plan-footer {
            padding: 0 22px 22px 22px;
        }

        .plan-title {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.2px;
            margin-bottom: 8px;
        }

        .plan-price {
            display: flex;
            align-items: flex-end;
            gap: 6px;
            line-height: 1;
            margin-bottom: 10px;
        }

        .plan-price .amount {
            font-size: 44px;
            font-weight: 800;
            color: var(--accent);
        }

        .plan-price .per {
            font-size: 14px;
            opacity: 0.85;
            padding-bottom: 6px;
        }

        .features {
            display: grid;
            gap: 10px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
        }

        .feature.disabled {
            opacity: 0.45;
        }

        .select-btn {
            width: 100%;
            border-radius: 10px;
            padding: 12px 16px;
            font-weight: 700;
            font-size: 14px;
            transition: opacity 0.2s ease, transform 0.2s ease;
            background: var(--accent);
            color: #ffffff;
        }

        .select-btn:hover {
            opacity: 0.92;
        }

        .select-btn:active {
            transform: translateY(1px);
        }

        /* variants are kept in markup but no longer required */
    </style>
</head>
<body>
    <div class="bg-image w-full min-h-screen bg-cover bg-center bg-no-repeat flex flex-col" style="background-image:url('{{ asset('spanz-img/spanz-bg.jpg') }}')">
        <!-- Main Content Area -->
        <div class="flex-1 flex items-center justify-center px-4 py-8 sm:py-12 lg:py-16 xl:py-20">
            <div class="bg-white rounded-lg w-full max-w-4xl sm:rounded-xl shadow-xl p-6 sm:p-8">
                <!-- Progress Indicator -->
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-bold text-sm mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-500 text-center">Business Info</span>
                        </div>
                        <div class="flex-1 mx-2 h-0.5 bg-green-500 mt-[-20px]"></div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-bold text-sm mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-500 text-center">Verify Email</span>
                        </div>
                        <div class="flex-1 mx-2 h-0.5 bg-green-500 mt-[-20px]"></div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 rounded-full bg-[#0D6AED] text-white flex items-center justify-center font-bold text-sm mb-2">3</div>
                            <span class="text-xs font-medium text-[#0D6AED] text-center">Subscription</span>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-6">
                    <a href="{{ route('home') }}" class="block">
                        <h1 class="text-2xl sm:text-3xl py-3 font-bold text-[#0D6AED] mb-2 hover:text-blue-600 transition-colors cursor-pointer">SPANZ</h1>
                    </a>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">Step 3: Choose Your Subscription</h2>
                    <span class="text-sm text-gray-600">Select a subscription plan to complete your registration</span>
                </div>

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul class="text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.step3.submit') }}" id="subscriptionForm" class="subscription-form">
                    @csrf
                    <input type="hidden" name="email" value="{{ $progress->email }}">
                    <input type="hidden" name="subscription_id" id="subscription_id" required>
                    @if(isset($token) && $token)
                    <input type="hidden" name="token" value="{{ $token }}">
                    @endif
                    @if(request()->has('token'))
                    <input type="hidden" name="token" value="{{ request()->token }}">
                    @endif

                    <!-- Subscription Cards -->
                    <div class="plans-grid">
                        @foreach($subscriptions as $subscription)
                            @php
                                $cardClass = 'basic-card';
                                $textColor = 'text-gray-900';
                                $buttonVariant = 'basic';
                                $priceVariant = 'basic';
                                
                                if(strtolower($subscription->name) === 'pro') {
                                    $cardClass = 'pro-card';
                                    $textColor = 'text-white';
                                    $buttonVariant = 'pro';
                                    $priceVariant = 'colored';
                                } elseif(strtolower($subscription->name) === 'enterprise') {
                                    $cardClass = 'enterprise-card';
                                    $textColor = 'text-white';
                                    $buttonVariant = 'enterprise';
                                    $priceVariant = 'colored';
                                }
                            @endphp
                            
                            <div class="subscription-card {{ $cardClass }} relative flex flex-col h-full"
                                 data-subscription-id="{{ $subscription->id }}"
                                 onclick="selectSubscription({{ $subscription->id }})">
                                @if(strtolower($subscription->name) === 'pro')
                                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-yellow-400 text-gray-900 px-4 py-1 rounded-full text-xs font-bold z-10 whitespace-nowrap">
                                        MOST POPULAR
                                    </div>
                                @endif
                                
                                <div class="plan-top">
                                    <div class="plan-title">{{ $subscription->name }}</div>
                                    <div class="plan-price">
                                        <span class="amount">${{ number_format($subscription->price, 0) }}</span>
                                        @if($subscription->price > 0)
                                            <span class="per">/month</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="plan-body flex-grow">
                                    @php
                                        $features = [
                                            [
                                                'label' => 'View Tenders',
                                                'included' => strtolower($subscription->name) !== 'basic'
                                            ],
                                            [
                                                'label' => 'View Buyer Details',
                                                'included' => strtolower($subscription->name) !== 'basic'
                                            ],
                                            [
                                                'label' => strtolower($subscription->name) === 'enterprise' ? 'Unlimited Credits' : ($subscription->credits_per_month > 0 ? $subscription->credits_per_month . ' Credits/Month' : 'No Credits'),
                                                'included' => strtolower($subscription->name) !== 'basic'
                                            ],
                                            [
                                                'label' => 'Post Tenders',
                                                'included' => true
                                            ],
                                            [
                                                'label' => 'Email Support',
                                                'included' => strtolower($subscription->name) !== 'basic'
                                            ]
                                        ];
                                    @endphp
                                    
                                    <ul class="features">
                                        @foreach($features as $feature)
                                            <li class="feature {{ $feature['included'] ? '' : 'disabled' }}">
                                                @if($feature['included'])
                                                    <div class="checkmark active flex-shrink-0">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div class="checkmark inactive flex-shrink-0" aria-hidden="true">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <span>{{ $feature['label'] }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="plan-footer mt-auto">
                                    <button
                                        type="button"
                                        class="select-btn {{ $buttonVariant }}"
                                        onclick="selectSubscription({{ $subscription->id }}); event.stopPropagation();"
                                    >
                                        Select
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center text-sm text-gray-600 mb-4">
                        <p>💡 <strong>Note:</strong> After selecting a plan, your subscription request will be sent for admin approval. You'll receive 2 free credits to explore the platform while waiting for approval.</p>
                    </div>

                    <div class="flex justify-between items-center mt-6">
                        <a href="{{ route('register.step2', ['email' => $progress->email]) }}" class="text-gray-600 hover:text-gray-500 text-sm transition-colors">
                            ← Back to Step 2
                        </a>
                        <button type="submit" id="submitBtn" disabled class="bg-gray-400 text-white py-2 sm:py-3 px-6 rounded-md font-medium text-sm sm:text-base cursor-not-allowed transition-colors">
                            Complete Registration
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-center px-4 py-8 sm:py-12 lg:py-16 mt-8 sm:mt-12 lg:mt-16">
            <div class="bg-black bg-opacity-60 rounded-md sm:rounded-lg px-3 sm:px-4 py-2 inline-block max-w-full">
                <p class="text-white text-xs sm:text-sm leading-relaxed">
                    <span class="block sm:inline">©2025 SPANZ Publishing Company. All rights reserved.</span>
                    <span class="block sm:inline sm:ml-1 mt-1 sm:mt-0">
                        See <a href="#" class="text-blue-300 hover:text-blue-200 underline transition-colors">Terms & Conditions</a> and
                        <a href="#" class="text-blue-300 hover:text-blue-200 underline transition-colors">Privacy Statement</a>.
                    </span>
                </p>
            </div>
        </footer>
    </div>

    <script>
        let selectedSubscriptionId = null;

        function selectSubscription(subscriptionId) {
            // Remove selected class from all cards
            document.querySelectorAll('.subscription-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selected class to clicked card
            const selectedCard = document.querySelector(`[data-subscription-id="${subscriptionId}"]`);
            if (selectedCard) {
                selectedCard.classList.add('selected');
            }

            // Update hidden input
            document.getElementById('subscription_id').value = subscriptionId;
            selectedSubscriptionId = subscriptionId;

            // Enable submit button
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-[#0D6AED]', 'hover:bg-blue-700', 'cursor-pointer');
            
            // Scroll to submit button smoothly
            submitBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // Prevent form submission if no subscription is selected
        document.getElementById('subscriptionForm').addEventListener('submit', function(e) {
            if (!selectedSubscriptionId) {
                e.preventDefault();
                alert('Please select a subscription plan.');
                return false;
            }
        });
    </script>
</body>
</html>
