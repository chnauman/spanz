<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPANZ Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
</head>
<body>
    <div class="bg-image w-full min-h-screen bg-cover bg-center bg-no-repeat flex flex-col" style="background-image:url('{{ asset('spanz-img/spanz-bg.jpg') }}')">
        <!-- Main Content Area -->
        <div class="flex-1 flex items-center justify-center px-4 py-8 sm:py-12 lg:py-16 xl:py-20">
            <div class="bg-white rounded-lg w-96 sm:rounded-xl shadow-xl max-w-xs sm:max-w-sm md:max-w-md p-4 sm:p-6 md:p-8 relative">
                <a href="{{ route('login') }}" class="absolute top-3 right-3 sm:top-4 sm:right-4 text-gray-400 hover:text-gray-600 transition-colors rounded-full p-1 hover:bg-gray-100" aria-label="Close">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
                <div class="text-center mb-4 sm:mb-6">
                    <a href="{{ route('home') }}" class="block"><h1 class="text-xl sm:text-2xl md:text-3xl py-5 font-bold text-[#0D6AED] mb-1 sm:mb-2">SPANZ</h1></a>
                    <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-800 mb-1 sm:mb-2">Reset your password</h2>
                    <span class="text-xs sm:text-sm md:text-base text-gray-600 leading-relaxed">Enter your new password below.</span>
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

                @if (session('status'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-3 sm:space-y-4">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div>
                        <label for="password" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">New Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required data-password-toggle-target
                                class="w-full pr-10 px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                                placeholder="Enter your new password">
                            <button type="button" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700" data-password-toggle-btn aria-label="Show password">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required data-password-toggle-target
                                class="w-full pr-10 px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password_confirmation') border-red-500 @enderror"
                                placeholder="Confirm your new password">
                            <button type="button" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700" data-password-toggle-btn aria-label="Show password">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full mt-4 sm:mt-6 bg-[#0D6AED] text-white py-2 sm:py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium text-sm sm:text-base">
                        Reset Password
                    </button>
                </form>

                <div class="mt-4 sm:mt-6 text-center">
                    <p class="text-xs sm:text-sm text-gray-600">
                        Remember your password?<br class="xs:hidden">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium transition-colors">Back to Login</a>
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
                        See <a href="{{ route('terms') }}" target="_blank" rel="noopener" class="text-blue-300 hover:text-blue-200 underline transition-colors">Terms & Conditions</a> and 
                        <a href="{{ route('privacy') }}" target="_blank" rel="noopener" class="text-blue-300 hover:text-blue-200 underline transition-colors">Privacy Statement</a>.
                    </span>
                </p>
            </div>
        </footer>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-password-toggle-btn]').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = button.parentElement.querySelector('[data-password-toggle-target]');
                if (!input) return;
                input.type = input.type === 'password' ? 'text' : 'password';
            });
        });
    });
</script>
</body>
</html>
