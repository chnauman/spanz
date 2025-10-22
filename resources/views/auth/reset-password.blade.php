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
            <div class="bg-white rounded-lg w-96 sm:rounded-xl shadow-xl max-w-xs sm:max-w-sm md:max-w-md p-4 sm:p-6 md:p-8">
                <div class="text-center mb-4 sm:mb-6">
                    <h1 class="text-xl sm:text-2xl md:text-3xl py-5 font-bold text-[#0D6AED] mb-1 sm:mb-2">SPANZ</h1>
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
                        <input type="password" id="password" name="password" required
                            class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror" 
                            placeholder="Enter your new password">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password_confirmation') border-red-500 @enderror" 
                            placeholder="Confirm your new password">
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
                        See <a href="#" class="text-blue-300 hover:text-blue-200 underline transition-colors">Terms & Conditions</a> and 
                        <a href="#" class="text-blue-300 hover:text-blue-200 underline transition-colors">Privacy Statement</a>.
                    </span>
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
