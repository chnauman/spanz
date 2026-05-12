<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPANZ Email Verification</title>
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
                    <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-800 mb-1 sm:mb-2">Verify Your Email</h2>
                    <span class="text-xs sm:text-sm md:text-base text-gray-600 leading-relaxed">
                        We've sent a 6-digit code to <strong>{{ $user->email }}</strong>
                    </span>
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

                <form method="POST" action="{{ route('email.verify') }}" class="space-y-3 sm:space-y-4">
                    @csrf
                    <div>
                        <label for="otp" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Enter code</label>
                        <input type="text" id="otp" name="otp" maxlength="6" pattern="[0-9]{6}" required
                            class="w-full px-3 py-2 sm:py-3 text-center text-2xl sm:text-3xl font-bold tracking-widest border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="000000"
                            autocomplete="off"
                            inputmode="numeric">
                        <p class="text-xs text-gray-500 mt-2">Enter the 6-digit code sent to your email</p>
                    </div>

                    <button type="submit" class="w-full mt-4 sm:mt-6 bg-[#0D6AED] text-white py-2 sm:py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium text-sm sm:text-base">
                        Verify Email
                    </button>
                </form>

                <div class="mt-4 sm:mt-6 text-center space-y-2">
                    <p class="text-xs sm:text-sm text-gray-600">
                        Didn't receive the code?
                    </p>
                    <form method="POST" action="{{ route('email.resend') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-blue-600 hover:text-blue-500 font-medium transition-colors text-xs sm:text-sm underline">
                            Resend code
                        </button>
                    </form>
                </div>

                <div class="mt-4 sm:mt-6 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-500 text-xs sm:text-sm transition-colors">
                            Sign out and use a different account
                        </button>
                    </form>
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
        // Auto-focus on OTP input
        document.addEventListener('DOMContentLoaded', function() {
            const otpInput = document.getElementById('otp');
            if (otpInput) {
                otpInput.focus();
            }
        });

        // Auto-submit when 6 digits are entered
        document.getElementById('otp')?.addEventListener('input', function(e) {
            const value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            e.target.value = value;
            
            if (value.length === 6) {
                // Optional: auto-submit after a short delay
                // setTimeout(() => {
                //     e.target.form.submit();
                // }, 500);
            }
        });
    </script>
</body>
</html>


