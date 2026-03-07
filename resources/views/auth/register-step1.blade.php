<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPANZ Registration - Step 1</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
</head>
<body>
    <div class="bg-image w-full min-h-screen bg-cover bg-center bg-no-repeat flex flex-col" style="background-image:url('{{ asset('spanz-img/spanz-bg.jpg') }}')">
        <!-- Main Content Area -->
        <div class="flex-1 flex items-center justify-center px-4 py-8 sm:py-12 lg:py-16 xl:py-20">
            <div class="bg-white rounded-lg w-full max-w-3xl sm:rounded-xl shadow-xl p-6 sm:p-8 relative">
                <a href="{{ route('home') }}" class="absolute top-3 right-3 sm:top-4 sm:right-4 text-gray-400 hover:text-gray-600 transition-colors rounded-full p-1 hover:bg-gray-100" aria-label="Close">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>

                <div class="text-center mb-2">
                    <a href="{{ route('home') }}" class="block">
                        <h1 class="text-2xl sm:text-3xl py-2 font-bold text-[#0D6AED] mb-1 hover:text-blue-600 transition-colors cursor-pointer">SPANZ</h1>
                    </a>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-1"> Business Information</h2>
                    <span class="text-sm text-gray-600">Enter your business details to get started.</span>
                </div>

                <!-- Progress Indicator – spaced below header -->
                <div class="mt-8 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 rounded-full bg-[#0D6AED] text-white flex items-center justify-center font-bold text-sm mb-2">1</div>
                            <span class="text-xs font-medium text-[#0D6AED] text-center">Business Info</span>
                        </div>
                        <div class="flex-1 mx-2 h-0.5 bg-gray-200 mt-[-20px]">
                            <div class="h-0.5 bg-gray-200"></div>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold text-sm mb-2">2</div>
                            <span class="text-xs font-medium text-gray-500 text-center">Verify Email</span>
                        </div>
                        <div class="flex-1 mx-2 h-0.5 bg-gray-200 mt-[-20px]">
                            <div class="h-0.5 bg-gray-200"></div>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold text-sm mb-2">3</div>
                            <span class="text-xs font-medium text-gray-500 text-center">Subscription</span>
                        </div>
                    </div>
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

                @if(isset($invitation) && $invitation)
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 text-blue-800 rounded">
                    <p class="text-sm font-medium">You're invited by {{ $invitation->supplier->name }} to join as a Sub Supplier!</p>
                    <p class="text-xs mt-1">This invitation expires on {{ $invitation->expires_at->format('M d, Y \a\t g:i A') }}.</p>
                </div>
                @endif

                <form method="POST" action="{{ route('register.step1.submit') }}">
                    @csrf
                    @if(isset($invitation) && $invitation)
                    <input type="hidden" name="token" value="{{ $invitation->token }}">
                    @endif
                    @if(request()->has('token'))
                    <input type="hidden" name="token" value="{{ request()->token }}">
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Card 1: Account & Business -->
                        <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 sm:p-5 space-y-4">
                            <h3 class="text-sm font-semibold text-gray-800 border-b border-gray-200 pb-2">Account & Business</h3>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email', $progress->email ?? '') }}" required
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                                <input type="password" id="password" name="password" required
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                                <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password <span class="text-red-500">*</span></label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                            </div>
                            <div>
                                <label for="registered_business_name" class="block text-sm font-medium text-gray-700 mb-1.5">Registered Business Name <span class="text-red-500">*</span></label>
                                <input type="text" id="registered_business_name" name="registered_business_name" value="{{ old('registered_business_name', $progress->registered_business_name ?? '') }}" required
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                            </div>
                            <div>
                                <label for="business_address" class="block text-sm font-medium text-gray-700 mb-1.5">Business Address <span class="text-red-500">*</span></label>
                                <textarea id="business_address" name="business_address" rows="3" required
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none bg-white">{{ old('business_address', $progress->business_address ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Card 2: Contact -->
                        <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 sm:p-5 space-y-4">
                            <h3 class="text-sm font-semibold text-gray-800 border-b border-gray-200 pb-2">Contact Details</h3>
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-1.5">Select Country <span class="text-red-500">*</span></label>
                                <select id="country" name="country" required
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                                    <option value="">Select Country</option>
                                    <option value="Australia" {{ old('country', $progress->country ?? '') == 'Australia' ? 'selected' : '' }}>Australia</option>
                                    <option value="New Zealand" {{ old('country', $progress->country ?? '') == 'New Zealand' ? 'selected' : '' }}>New Zealand</option>
                                    <option value="Singapore" {{ old('country', $progress->country ?? '') == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                    <option value="United States" {{ old('country', $progress->country ?? '') == 'United States' ? 'selected' : '' }}>United States</option>
                                    <option value="United Kingdom" {{ old('country', $progress->country ?? '') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="Canada" {{ old('country', $progress->country ?? '') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                    <option value="Germany" {{ old('country', $progress->country ?? '') == 'Germany' ? 'selected' : '' }}>Germany</option>
                                    <option value="France" {{ old('country', $progress->country ?? '') == 'France' ? 'selected' : '' }}>France</option>
                                    <option value="China" {{ old('country', $progress->country ?? '') == 'China' ? 'selected' : '' }}>China</option>
                                    <option value="Japan" {{ old('country', $progress->country ?? '') == 'Japan' ? 'selected' : '' }}>Japan</option>
                                    <option value="India" {{ old('country', $progress->country ?? '') == 'India' ? 'selected' : '' }}>India</option>
                                    <option value="Other" {{ old('country', $progress->country ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1.5">Your Full Name – Point of Contact <span class="text-red-500">*</span></label>
                                <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $progress->full_name ?? '') }}" required
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                            </div>
                            <div>
                                <label for="title_position" class="block text-sm font-medium text-gray-700 mb-1.5">Title / Position <span class="text-red-500">*</span></label>
                                <input type="text" id="title_position" name="title_position" value="{{ old('title_position', $progress->title_position ?? '') }}" required
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                            </div>
                            <div>
                                <label for="cell_mobile" class="block text-sm font-medium text-gray-700 mb-1.5">Cell / Mobile – Country Code and Number <span class="text-red-500">*</span></label>
                                <input type="text" id="cell_mobile" name="cell_mobile" value="{{ old('cell_mobile', $progress->cell_mobile ?? '') }}" placeholder="+61 400 000 000" required
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                            </div>
                            <div>
                                <label for="whatsapp_wechat" class="block text-sm font-medium text-gray-700 mb-1.5">WhatsApp or WeChat</label>
                                <input type="text" id="whatsapp_wechat" name="whatsapp_wechat" value="{{ old('whatsapp_wechat', $progress->whatsapp_wechat ?? '') }}"
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 bg-[#0D6AED] text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium text-sm">
                        Next >
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium transition-colors">Login</a>
                    </p>
                </div>
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
</body>
</html>
