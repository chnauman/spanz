@extends("layouts.app")

@section("content")
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-indigo-600">Spanz</h1>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Tender Management System</h2>
            <p class="mt-2 text-sm text-gray-600">Connect buyers and suppliers across Australia and beyond</p>
        </div>
    </div>
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <div class="space-y-6">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900">Get Started</h3>
                    <p class="mt-2 text-sm text-gray-600">Choose your account type to begin</p>
                </div>
                <div class="space-y-4">
                    <a href="{{ route("register") }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create Account</a>
                    <a href="{{ route("login") }}" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Sign In</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
