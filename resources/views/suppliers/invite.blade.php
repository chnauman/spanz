@extends('layouts.admin')
@section('title', 'Invite Sub Supplier - SPANZ')
@section('content')
@php
    $companyDomain = $companyDomain ?? null;
@endphp
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold">Invite Sub Supplier</h1>
                    <p class="text-gray-300 mt-1">Invite a colleague from your company to join as a sub-supplier on SPANZ</p>
                </div>
                <a href="{{ route('suppliers.sub-suppliers') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Sub Suppliers
                </a>
            </div>

            @if(!$companyDomain)
                <div class="mt-6 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded">
                    Your account must use a company email address before you can invite sub-suppliers.
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 mt-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 mt-6">
                    {{ session('error') }}
                </div>
            @endif

            @if($companyDomain)
            <div class="mt-6">
                <form method="POST" action="{{ route('suppliers.invite.send') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Sub Supplier Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0D6AED] focus:border-transparent @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Company Email Address <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-500 mb-2">Sub-suppliers must use the same company domain as you (@{{ $companyDomain }}).</p>
                        <div class="flex items-stretch">
                            <input type="text"
                                   id="email_local"
                                   name="email_local"
                                   value="{{ old('email_local') }}"
                                   placeholder="firstname"
                                   autocomplete="off"
                                   class="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-[#0D6AED] focus:border-transparent @error('email_local') border-red-500 @enderror @error('email') border-red-500 @enderror"
                                   required>
                            <span class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-100 text-gray-700 text-sm rounded-r-md whitespace-nowrap">@{{ $companyDomain }}</span>
                        </div>
                        @error('email_local')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Personal Message (Optional)
                        </label>
                        <textarea id="message"
                                  name="message"
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0D6AED] focus:border-transparent @error('message') border-red-500 @enderror"
                                  placeholder="Add a personal message to your invitation...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-6">
                        <button type="submit"
                                class="bg-[#0D6AED] text-white px-6 py-2 rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-[#0D6AED] focus:ring-offset-2 font-medium">
                            Send Invitation
                        </button>
                    </div>
                </form>

                <div class="mt-8 bg-blue-50 border border-blue-200 rounded-md p-4">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">What happens next?</h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Your colleague receives an email invitation from you</li>
                        <li>• They accept the invitation and complete a short registration</li>
                        <li>• They share your company profile and subscription on SPANZ</li>
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
