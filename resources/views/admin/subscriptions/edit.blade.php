@extends('layouts.admin')
@section('title', 'Edit Subscription - SPANZ')
@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">Edit Subscription Plan</h1>
                <a href="{{ route('admin.subscriptions.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded text-sm hover:bg-gray-700 transition-colors">
                    Back to Subscriptions
                </a>
            </div>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.subscriptions.update', $subscription) }}" method="POST" class="mt-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Plan Name</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $subscription->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., Pro, Enterprise"
                               required>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (AUD)</label>
                        <input type="number" 
                               id="price" 
                               name="price" 
                               step="0.01"
                               min="0"
                               value="{{ old('price', $subscription->price) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="29.99"
                               required>
                    </div>

                    <div>
                        <label for="credits_per_month" class="block text-sm font-medium text-gray-700 mb-2">Credits per Month</label>
                        <input type="number" 
                               id="credits_per_month" 
                               name="credits_per_month" 
                               value="{{ old('credits_per_month', $subscription->credits_per_month) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="20 (use -1 for unlimited)"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Use -1 for unlimited credits</p>
                    </div>

                    <div>
                        <label for="credit_cost_per_view" class="block text-sm font-medium text-gray-700 mb-2">Credit Cost per Tender View</label>
                        <input type="number" 
                               id="credit_cost_per_view" 
                               name="credit_cost_per_view" 
                               value="{{ old('credit_cost_per_view', $subscription->credit_cost_per_view ?? 1) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="1"
                               min="1"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Number of credits deducted when viewing tender details</p>
                    </div>

                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="is_active" 
                                name="is_active"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="1" {{ old('is_active', $subscription->is_active) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $subscription->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Describe the benefits of this subscription plan...">{{ old('description', $subscription->description) }}</textarea>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('admin.subscriptions.index') }}" 
                       class="bg-gray-600 text-white px-6 py-2 rounded text-sm hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded text-sm hover:bg-blue-700 transition-colors">
                        Update Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
