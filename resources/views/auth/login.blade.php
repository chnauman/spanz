<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPANZ Login</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
</head>
<body>
    <div class="bg-image w-full min-h-screen bg-cover bg-center bg-no-repeat flex flex-col" style="background-image:url('{{ asset('spanz-img/spanz-bg.jpg') }}')">
        <!-- Main Content Area -->
        <div class="flex-1 flex items-center justify-center px-4 py-8 sm:py-12 lg:py-16 xl:py-20">
            <div class="bg-white rounded-lg w-96 sm:rounded-xl shadow-xl max-w-xs sm:max-w-sm md:max-w-md p-4 sm:p-6 md:p-8 relative">
                <a href="{{ route('home') }}" class="absolute top-3 right-3 sm:top-4 sm:right-4 text-gray-400 hover:text-gray-600 transition-colors rounded-full p-1 hover:bg-gray-100" aria-label="Close">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
                <div class="text-center mb-4 sm:mb-6">
                    <a href="{{ route('home') }}" class="block">
                        <h1 class="text-xl sm:text-2xl md:text-3xl py-5 font-bold text-[#0D6AED] mb-1 sm:mb-2 hover:text-blue-600 transition-colors cursor-pointer">SPANZ</h1>
                    </a>
                    <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-800 mb-1 sm:mb-2">Welcome back</h2>
                    <span class="text-xs sm:text-sm md:text-base text-gray-600 leading-relaxed">Enter your account details to login.</span>
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
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">{{ session('status') }}</span>
                        </div>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}" class="space-y-3 sm:space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required data-password-toggle-target
                                class="w-full pr-10 px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <button type="button" class="text-gray-500 hover:text-gray-700 leading-none" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); width:20px; height:20px; display:flex; align-items:center; justify-content:center; background:transparent; border:0; padding:0;" data-password-toggle-btn aria-label="Show password">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" data-password-icon-show>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" data-password-icon-hide style="display:none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.584 10.587a3 3 0 104.243 4.243"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.88 5.09A9.77 9.77 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.72 9.72 0 01-4.125 5.208M6.228 6.228A9.72 9.72 0 002.458 12c1.274 4.057 5.065 7 9.542 7 1.61 0 3.13-.38 4.478-1.055"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex xs:flex-row xs:items-center xs:justify-between justify-between gap-2 sm:gap-0 pt-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="h-3 w-3 sm:h-4 sm:w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-xs sm:text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs sm:text-sm text-blue-600 hover:text-blue-500 transition-colors underline xs:no-underline xs:hover:underline">Forgot password?</a>
                    </div>

                    <button type="submit" class="w-full mt-4 sm:mt-6 bg-[#0D6AED] text-white py-2 sm:py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium text-sm sm:text-base">
                        Sign in
                    </button>
                </form>
                
                <div class="mt-4 sm:mt-6 text-center">
                    <p class="text-xs sm:text-sm text-gray-600">
                        Don't have an account?<br class="xs:hidden">
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 font-medium transition-colors">Join SPANZ</a>
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
        const syncPasswordToggleIcon = function (button, input) {
            const isVisible = input.type === 'text';
            const showIcon = button.querySelector('[data-password-icon-show]');
            const hideIcon = button.querySelector('[data-password-icon-hide]');

            if (showIcon) showIcon.style.display = isVisible ? 'none' : 'block';
            if (hideIcon) hideIcon.style.display = isVisible ? 'block' : 'none';
            button.setAttribute('aria-label', isVisible ? 'Hide password' : 'Show password');
        };

        document.querySelectorAll('[data-password-toggle-btn]').forEach(function (button) {
            const input = button.parentElement.querySelector('[data-password-toggle-target]');
            if (!input) return;
            syncPasswordToggleIcon(button, input);

            button.addEventListener('click', function () {
                input.type = input.type === 'password' ? 'text' : 'password';
                syncPasswordToggleIcon(button, input);
            });
        });
    });
</script>
</body>
</html>