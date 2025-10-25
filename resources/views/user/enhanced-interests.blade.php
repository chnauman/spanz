@extends('layouts.admin')
@section('title', 'My Interests - SPANZ')
@section('content')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="min-h-screen bg-gray-50">
    <!-- Full-width header -->
    <div class="bg-[#092C48] text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold mb-2">🎯 Manage Your Interests</h1>
                <p class="text-gray-300 text-lg">Set up your category interests and budget ranges to receive personalized tender notifications.</p>
            </div>
        </div>
    </div>

    <!-- Main content area -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6">
                <!-- Tab Navigation -->
                <div class="mb-8">
                    <div class="bg-gray-50 rounded-lg p-2">
                        <nav class="flex space-x-2">
                            <button type="button" onclick="switchTab('categories')" id="categories-tab" class="tab-button active flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200">
                                <i class="fas fa-tags me-3"></i>Select Categories
                            </button>
                            <button type="button" onclick="switchTab('summary')" id="summary-tab" class="tab-button flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200">
                                <i class="fas fa-list-check me-3"></i>Your Interest
                            </button>
                        </nav>
                    </div>
                </div>

                <form method="POST" action="{{ route('user.interests.store') }}" id="interestsForm">
                    @csrf

                    <!-- Categories Tab -->
                    <div id="categories-tab-content" class="tab-content">
                        <!-- Enhanced Search Bar -->
                        <div class="mb-6">
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
                                </div>
                                <input type="text"
                                       id="categorySearch"
                                       placeholder="🔍 Search categories by name or description..."
                                       class="w-full pl-12 pr-12 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all duration-300 bg-white shadow-sm hover:shadow-md focus:shadow-lg text-gray-700 placeholder-gray-400"
                                       onkeyup="filterCategories()">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <button type="button"
                                            id="clearSearch"
                                            onclick="clearSearch()"
                                            class="text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full p-1 transition-all duration-200 hidden group-focus-within:block">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                                <!-- Search indicator -->
                                <div class="absolute top-2 right-2 hidden" id="searchIndicator">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm font-medium mb-6 inline-block" id="selectionCounter">
                            <span id="selectedCount">0</span> categories selected
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                                <thead class="bg-[#092C48] text-white">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                                            <input type="checkbox" id="selectAllCategories" class="rounded border-gray-300 text-white focus:ring-white">
                                            <label for="selectAllCategories" class="ml-2 text-white">Select All</label>
                                        </th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Budget Range</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($categories as $category)
                                    @php
                                        $isSelected = false;
                                        $existingInterest = $existingInterests->where('category_id', $category->id)->first();
                                        if ($existingInterest) {
                                            $isSelected = true;
                                        }
                                    @endphp
                                    <tr class="category-row hover:bg-blue-50 cursor-pointer transition-all duration-200 {{ $isSelected ? 'bg-blue-50 border-l-4 border-[#092C48]' : '' }}"
                                        data-category-name="{{ strtolower($category->name) }}"
                                        data-category-description="{{ strtolower($category->description ?? '') }}"
                                        onclick="toggleCategory({{ $category->id }})">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input class="category-checkbox rounded border-gray-300 text-[#092C48] focus:ring-[#092C48]" type="checkbox" name="interests[]"
                                                   value="{{ $category->id }}" id="category_{{ $category->id }}"
                                                   {{ $isSelected ? 'checked' : '' }}>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">{{ $category->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-600">{{ $category->description ?? 'No description available' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($existingInterest && ($existingInterest->min_budget || $existingInterest->max_budget))
                                                <div class="text-sm font-medium text-gray-900">
                                                    @if($existingInterest->min_budget && $existingInterest->max_budget)
                                                        {{ number_format($existingInterest->min_budget, 2) }} - {{ number_format($existingInterest->max_budget, 2) }} {{ $existingInterest->currency }}
                                                    @elseif($existingInterest->min_budget)
                                                        Min: {{ number_format($existingInterest->min_budget, 2) }} {{ $existingInterest->currency }}
                                                    @elseif($existingInterest->max_budget)
                                                        Max: {{ number_format($existingInterest->max_budget, 2) }} {{ $existingInterest->currency }}
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-sm">No budget set</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($existingInterest && ($existingInterest->min_budget || $existingInterest->max_budget))
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                                <i class="fas fa-dollar-sign mr-1"></i>
                                                Budget Set
                                            </span>
                                            @elseif($isSelected)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#092C48] text-white">
                                                <i class="fas fa-check mr-1"></i>
                                                Selected
                                            </span>
                                            @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                                <i class="fas fa-circle mr-1"></i>
                                                Available
                                            </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($isSelected)
                                                <button type="button" onclick="event.stopPropagation(); openBudgetModal({{ $category->id }}, '{{ $category->name }}', {{ $existingInterest ? $existingInterest->min_budget ?? 'null' : 'null' }}, {{ $existingInterest ? $existingInterest->max_budget ?? 'null' : 'null' }}, '{{ $existingInterest ? $existingInterest->currency ?? 'USD' : 'USD' }}')"
                                                        class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 transition-colors">
                                                    <i class="fas fa-dollar-sign mr-1"></i>
                                                    @if($existingInterest && ($existingInterest->min_budget || $existingInterest->max_budget))
                                                        Edit Budget
                                                    @else
                                                        Set Budget
                                                    @endif
                                                </button>
                                            @else
                                                <span class="text-gray-400 text-xs">Select first</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Enhanced No results message -->
                        <div id="noResultsMessage" class="hidden text-center py-16">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-12 border border-gray-200 shadow-sm">
                                <div class="text-gray-400 mb-6">
                                    <i class="fas fa-search text-6xl mb-4 opacity-50"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-700 mb-3">No categories found</h3>
                                <p class="text-gray-500 text-lg mb-6 max-w-md mx-auto">We couldn't find any categories matching your search. Try adjusting your search terms or browse all available categories.</p>
                                <button onclick="clearSearch()" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 font-semibold">
                                    <i class="fas fa-refresh mr-2"></i>Clear Search
                                </button>
                            </div>
                        </div>

                        @error('interests')
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 mt-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                        </div>
                        @enderror
                    </div>


                    <!-- Summary Tab -->
                    <div id="summary-tab-content" class="tab-content hidden">
                        <!-- Enhanced Summary Search Bar -->
                        <div class="mb-6">
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-list-check text-gray-400 group-focus-within:text-green-500 transition-colors duration-200"></i>
                                </div>
                                <input type="text"
                                       id="summarySearch"
                                       placeholder="📋 Search your selected categories..."
                                       class="w-full pl-12 pr-12 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-green-100 focus:border-green-400 transition-all duration-300 bg-white shadow-sm hover:shadow-md focus:shadow-lg text-gray-700 placeholder-gray-400"
                                       onkeyup="filterSummary()">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <button type="button"
                                            id="clearSummarySearch"
                                            onclick="clearSummarySearch()"
                                            class="text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full p-1 transition-all duration-200 hidden group-focus-within:block">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                                <!-- Search indicator -->
                                <div class="absolute top-2 right-2 hidden" id="summarySearchIndicator">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-blue-900 mb-2">
                                <i class="fas fa-list-check me-2"></i>Your Interest Summary
                            </h3>
                            <p class="text-blue-700 text-sm">Review your selected categories and budget ranges before finalizing your preferences.</p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                                <thead class="bg-[#092C48] text-white">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Budget Range</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Currency</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="summary-table-body">
                                    <!-- Dynamic content will be populated here -->
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Summary Statistics</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div class="bg-white rounded-lg p-3 border">
                                    <div class="font-semibold text-gray-900" id="total-categories">0</div>
                                    <div class="text-gray-600">Categories Selected</div>
                                </div>
                                <div class="bg-white rounded-lg p-3 border">
                                    <div class="font-semibold text-gray-900" id="categories-with-budget">0</div>
                                    <div class="text-gray-600">With Budget Ranges</div>
                                </div>
                                <div class="bg-white rounded-lg p-3 border">
                                    <div class="font-semibold text-gray-900" id="categories-without-budget">0</div>
                                    <div class="text-gray-600">Without Budget Ranges</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" onclick="finalizePreferences()" class="bg-[#092C48] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#0a3a5a] transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-check me-2"></i>Finalize Preferences
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 border-t border-gray-200 mt-8">
                        <button type="button" onclick="skipInterests()" class="bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-arrow-left me-2"></i>Skip for Now
                        </button>
                        <button type="button" onclick="saveCategoriesAndContinue()" class="bg-[#092C48] text-white px-8 py-4 rounded-lg font-semibold hover:bg-[#0a3a5a] transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save me-3"></i>Save & Continue
                        </button>
                    </div>
                </form>

                <!-- Hidden form for skip functionality -->
                <form method="POST" action="{{ route('user.interests.skip') }}" id="skipForm" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Budget Popup Modal -->
    <div id="budgetModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-start justify-center p-4 pt-16">
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto transform transition-all my-8">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-[#092C48] to-[#0a3a5a] rounded-t-xl">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-dollar-sign text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white" id="budgetModalTitle">Set Budget Range</h3>
                        <p class="text-xs text-blue-100">Configure your budget preferences</p>
                    </div>
                </div>
                <button type="button" onclick="closeBudgetModal()" class="text-white hover:text-gray-200 transition-colors p-1 hover:bg-white hover:bg-opacity-20 rounded-full">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-4">
                <!-- Category Display -->
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <label class="block text-sm font-medium text-blue-900 mb-1">
                        <i class="fas fa-tag mr-1"></i>Selected Category
                    </label>
                    <div class="text-sm font-semibold text-blue-800" id="selectedCategoryName"></div>
                </div>

                <!-- Budget Inputs -->
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-arrow-down mr-1 text-green-600"></i>Min Budget
                            </label>
                            <div class="relative">
                                <input type="number" id="budgetMin" placeholder="0.00" step="0.01" min="0"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-[#092C48] focus:border-transparent transition-all text-sm">
                                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                    <span class="text-gray-400 text-xs" id="minCurrency">USD</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-arrow-up mr-1 text-red-600"></i>Max Budget
                            </label>
                            <div class="relative">
                                <input type="number" id="budgetMax" placeholder="1000000.00" step="0.01" min="0"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-[#092C48] focus:border-transparent transition-all text-sm">
                                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                    <span class="text-gray-400 text-xs" id="maxCurrency">USD</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-globe mr-1 text-blue-600"></i>Currency
                        </label>
                        <select id="budgetCurrency" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#092C48] focus:border-transparent transition-all text-sm">
                            <option value="USD">🇺🇸 USD - US Dollar</option>
                            <option value="AUD">🇦🇺 AUD - Australian Dollar</option>
                            <option value="EUR">🇪🇺 EUR - Euro</option>
                            <option value="GBP">🇬🇧 GBP - British Pound</option>
                            <option value="SGD">🇸🇬 SGD - Singapore Dollar</option>
                            <option value="NZD">🇳🇿 NZD - New Zealand Dollar</option>
                        </select>
                    </div>
                </div>

                <!-- Help Text -->
                <div class="mt-3 p-2 bg-gray-50 border border-gray-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2 text-xs"></i>
                        <div class="text-xs text-gray-600">
                            <p class="font-medium mb-1">Tips:</p>
                            <ul class="text-xs space-y-0.5">
                                <li>• Leave min empty for any amount above max</li>
                                <li>• Leave max empty for any amount below min</li>
                                <li>• Set both for a specific range</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 p-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                <button type="button" onclick="closeBudgetModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-medium text-sm">
                    <i class="fas fa-times mr-1"></i>Cancel
                </button>
                <button type="button" onclick="saveBudgetForCategory()" class="px-4 py-2 bg-[#092C48] text-white rounded-lg hover:bg-[#0a3a5a] transition-colors font-medium shadow-md hover:shadow-lg text-sm">
                    <i class="fas fa-save mr-1"></i>Save Budget
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .tab-button.active {
        background-color: #092C48;
        color: white;
        box-shadow: 0 2px 4px rgba(9, 44, 72, 0.2);
        transform: translateY(-1px);
    }

    .tab-button {
        background-color: transparent;
        color: #6b7280;
        border: none;
        transition: all 0.2s ease;
    }

    .tab-button:hover {
        background-color: #f3f4f6;
        color: #092C48;
        transform: translateY(-1px);
    }

    .tab-button:not(.active):hover {
        background-color: #e5e7eb;
    }

    .budget-range-item {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        transition: all 0.2s ease;
    }

    .budget-range-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }

    .budget-range-item.selected {
        border-color: #092C48;
        background: #f0f9ff;
        box-shadow: 0 4px 12px rgba(9, 44, 72, 0.1);
    }

    /* Enhanced table styling */
    .category-row:hover {
        background-color: #f8fafc !important;
        transform: translateX(2px);
    }

    .category-row.selected {
        background-color: #eff6ff !important;
        border-left: 4px solid #092C48;
    }

    /* Custom scrollbar for table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #092C48;
        border-radius: 4px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #0a3a5a;
    }

    /* Enhanced Search Bar Styles */
    .search-bar-container {
        position: relative;
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }

    .search-bar-container:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transform: translateY(-1px);
    }

    .search-bar-container:focus-within {
        box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.1), 0 10px 10px -5px rgba(59, 130, 246, 0.04);
        transform: translateY(-2px);
    }

    .search-input {
        background: transparent;
        border: none;
        outline: none;
        font-size: 16px;
        line-height: 1.5;
    }

    .search-input::placeholder {
        color: #9ca3af;
        font-weight: 400;
    }

    .search-input:focus::placeholder {
        color: #d1d5db;
    }

    .search-clear-btn {
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.2s ease;
    }

    .search-clear-btn.show {
        opacity: 1;
        transform: scale(1);
    }

    .search-clear-btn:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .search-indicator {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .search-focus-ring {
        position: absolute;
        inset: -2px;
        border-radius: 18px;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: -1;
    }

    .search-focus-ring.active {
        opacity: 0.1;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
let budgetRangeCounter = 0;

function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });

    // Show selected tab content
    document.getElementById(tabName + '-tab-content').classList.remove('hidden');
    document.getElementById(tabName + '-tab').classList.add('active');

    // Update progress indicator
    updateProgressIndicator(tabName);

    // Populate summary tab if it's being activated
    if (tabName === 'summary') {
        populateSummaryTab();
    }
}

function updateProgressIndicator(activeTab) {
    const step2 = document.getElementById('step-2');
    const step2Line = document.getElementById('step-2-line');
    const step2Badge = document.querySelector('#summary-tab span');

    if (activeTab === 'summary') {
        step2.classList.remove('bg-gray-300', 'text-gray-500');
        step2.classList.add('bg-[#092C48]', 'text-white');
        step2Badge.classList.remove('bg-gray-200', 'text-gray-500');
        step2Badge.classList.add('bg-white', 'text-[#092C48]');
        step2Line.classList.remove('bg-gray-300');
        step2Line.classList.add('bg-[#092C48]');
    } else {
        step2.classList.remove('bg-[#092C48]', 'text-white');
        step2.classList.add('bg-gray-300', 'text-gray-500');
        step2Badge.classList.remove('bg-white', 'text-[#092C48]');
        step2Badge.classList.add('bg-gray-200', 'text-gray-500');
        step2Line.classList.remove('bg-[#092C48]');
        step2Line.classList.add('bg-gray-300');
    }
}

function toggleCategory(categoryId) {
    const checkbox = document.getElementById('category_' + categoryId);
    const categoryRow = checkbox.closest('.category-row');

    checkbox.checked = !checkbox.checked;

    if (checkbox.checked) {
        categoryRow.classList.add('bg-blue-50', 'border-l-4', 'border-[#092C48]', 'selected');
    } else {
        categoryRow.classList.remove('bg-blue-50', 'border-l-4', 'border-[#092C48]', 'selected');
    }

    updateSelectionCounter();
    updateSelectedCategoriesList();
    updateBudgetButtons();
}

function updateSelectionCounter() {
    const checkboxes = document.querySelectorAll('input[name="interests[]"]');
    const checkedBoxes = document.querySelectorAll('input[name="interests[]"]:checked');
    const counter = document.getElementById('selectedCount');

    counter.textContent = checkedBoxes.length;
}

// Enhanced search functionality for categories tab
function filterCategories() {
    const searchTerm = document.getElementById('categorySearch').value.toLowerCase();
    const categoryRows = document.querySelectorAll('.category-row');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const clearButton = document.getElementById('clearSearch');
    const searchIndicator = document.getElementById('searchIndicator');
    let visibleCount = 0;

    // Show search indicator
    if (searchTerm.length > 0) {
        searchIndicator.classList.remove('hidden');
    } else {
        searchIndicator.classList.add('hidden');
    }

    categoryRows.forEach(row => {
        const categoryName = row.getAttribute('data-category-name');
        const categoryDescription = row.getAttribute('data-category-description');

        if (categoryName.includes(searchTerm) || categoryDescription.includes(searchTerm)) {
            row.style.display = 'table-row';
            row.style.animation = 'fadeIn 0.3s ease-in-out';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Show/hide no results message with animation
    if (visibleCount === 0 && searchTerm.length > 0) {
        noResultsMessage.classList.remove('hidden');
        noResultsMessage.style.animation = 'fadeIn 0.5s ease-in-out';
    } else {
        noResultsMessage.classList.add('hidden');
    }

    // Enhanced clear button animation
    if (searchTerm.length > 0) {
        clearButton.classList.remove('hidden');
        clearButton.classList.add('show');
    } else {
        clearButton.classList.add('hidden');
        clearButton.classList.remove('show');
    }
}

// Clear search function for categories
function clearSearch() {
    document.getElementById('categorySearch').value = '';
    filterCategories();
    document.getElementById('categorySearch').focus();
}

// Enhanced search functionality for summary tab
function filterSummary() {
    const searchTerm = document.getElementById('summarySearch').value.toLowerCase();
    const summaryRows = document.querySelectorAll('#summary-table-body tr');
    const clearButton = document.getElementById('clearSummarySearch');
    const searchIndicator = document.getElementById('summarySearchIndicator');
    let visibleCount = 0;

    // Show search indicator
    if (searchTerm.length > 0) {
        searchIndicator.classList.remove('hidden');
    } else {
        searchIndicator.classList.add('hidden');
    }

    summaryRows.forEach(row => {
        const categoryName = row.querySelector('td:first-child div').textContent.toLowerCase();
        const categoryDescription = row.querySelector('td:nth-child(2) div').textContent.toLowerCase();

        if (categoryName.includes(searchTerm) || categoryDescription.includes(searchTerm)) {
            row.style.display = 'table-row';
            row.style.animation = 'fadeIn 0.3s ease-in-out';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Enhanced clear button animation
    if (searchTerm.length > 0) {
        clearButton.classList.remove('hidden');
        clearButton.classList.add('show');
    } else {
        clearButton.classList.add('hidden');
        clearButton.classList.remove('show');
    }
}

// Clear search function for summary
function clearSummarySearch() {
    document.getElementById('summarySearch').value = '';
    filterSummary();
    document.getElementById('summarySearch').focus();
}

function updateSelectedCategoriesList() {
    // This function is no longer needed since we removed the budget tab
}

function updateBudgetButtons() {
    const checkedBoxes = document.querySelectorAll('input[name="interests[]"]:checked');
    const allRows = document.querySelectorAll('.category-row');

    allRows.forEach(row => {
        const checkbox = row.querySelector('input[name="interests[]"]');
        const budgetCell = row.querySelector('td:last-child');

        if (checkbox && budgetCell) {
            if (checkbox.checked) {
                const categoryId = checkbox.value;
                const categoryName = row.querySelector('td:nth-child(2) div').textContent;
                budgetCell.innerHTML = `
                    <button type="button" onclick="event.stopPropagation(); openBudgetModal(${categoryId}, '${categoryName}', null, null, 'USD')"
                            class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 transition-colors">
                        <i class="fas fa-dollar-sign mr-1"></i>Set Budget
                    </button>
                `;
    } else {
                budgetCell.innerHTML = '<span class="text-gray-400 text-xs">Select first</span>';
            }
        }
    });
}

// Budget Modal Functions
let currentCategoryId = null;

function openBudgetModal(categoryId, categoryName, minBudget, maxBudget, currency) {
    console.log('Opening budget modal for category:', categoryId, categoryName);

    currentCategoryId = categoryId;

    // Set category name
    const categoryNameElement = document.getElementById('selectedCategoryName');
    if (categoryNameElement) {
        categoryNameElement.textContent = categoryName;
    }

    // Set budget values
    const budgetMinElement = document.getElementById('budgetMin');
    const budgetMaxElement = document.getElementById('budgetMax');
    const budgetCurrencyElement = document.getElementById('budgetCurrency');

    if (budgetMinElement) budgetMinElement.value = minBudget || '';
    if (budgetMaxElement) budgetMaxElement.value = maxBudget || '';
    if (budgetCurrencyElement) budgetCurrencyElement.value = currency || 'USD';

    // Update currency display in input fields
    updateCurrencyDisplay(currency || 'USD');

    // Show modal
    const modal = document.getElementById('budgetModal');
    if (modal) {
        console.log('Modal found, showing...');
        modal.classList.remove('hidden');

        // Add entrance animation
        setTimeout(() => {
            const modalContent = modal.querySelector('.relative');
            if (modalContent) {
                modalContent.style.transform = 'scale(0.95)';
                modalContent.style.opacity = '0';
                modalContent.style.transition = 'all 0.2s ease-out';

                setTimeout(() => {
                    modalContent.style.transform = 'scale(1)';
                    modalContent.style.opacity = '1';
                }, 10);
            }
        }, 10);
        } else {
        console.error('Budget modal not found!');
    }
}

function closeBudgetModal() {
    document.getElementById('budgetModal').classList.add('hidden');
    currentCategoryId = null;
}

function updateCurrencyDisplay(currency) {
    document.getElementById('minCurrency').textContent = currency;
    document.getElementById('maxCurrency').textContent = currency;
}

function saveBudgetForCategory() {
    const minBudget = document.getElementById('budgetMin').value;
    const maxBudget = document.getElementById('budgetMax').value;
    const currency = document.getElementById('budgetCurrency').value;

            // Validate budget range
    if (minBudget && maxBudget && parseFloat(minBudget) >= parseFloat(maxBudget)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Budget Range',
            text: 'Minimum budget must be less than maximum budget.',
            confirmButtonColor: '#092C48',
            confirmButtonText: 'OK'
        });
        return;
    }

    // Show loading state
    const saveButton = document.querySelector('button[onclick="saveBudgetForCategory()"]');
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
    saveButton.disabled = true;

    // Send AJAX request to save budget
    fetch('{{ route("user.interests.save-budget") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            category_id: currentCategoryId,
            min_budget: minBudget || null,
            max_budget: maxBudget || null,
            currency: currency
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeBudgetModal();
            // Update the budget button to show "Edit Budget"
            updateBudgetButtonForCategory(currentCategoryId, minBudget, maxBudget, currency);
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Budget saved successfully!',
                confirmButtonColor: '#092C48',
                confirmButtonText: 'OK'
            });
        } else {
            closeBudgetModal();
            window.location.reload();
        }
    })
    .catch(error => {
        window.location.reload();
    })
    .finally(() => {
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
    });
}

function updateBudgetButtonForCategory(categoryId, minBudget, maxBudget, currency = 'USD') {
    const row = document.querySelector(`input[value="${categoryId}"]`).closest('.category-row');
    const budgetRangeCell = row.querySelector('td:nth-child(4)'); // Budget Range column
    const actionCell = row.querySelector('td:last-child'); // Actions column
    const categoryName = row.querySelector('td:nth-child(2) div').textContent;

    // Update budget range display
    if (budgetRangeCell) {
        if (minBudget && maxBudget) {
            budgetRangeCell.innerHTML = `<div class="text-sm font-medium text-gray-900">${parseFloat(minBudget).toFixed(2)} - ${parseFloat(maxBudget).toFixed(2)} ${currency}</div>`;
        } else if (minBudget) {
            budgetRangeCell.innerHTML = `<div class="text-sm font-medium text-gray-900">Min: ${parseFloat(minBudget).toFixed(2)} ${currency}</div>`;
        } else if (maxBudget) {
            budgetRangeCell.innerHTML = `<div class="text-sm font-medium text-gray-900">Max: ${parseFloat(maxBudget).toFixed(2)} ${currency}</div>`;
        } else {
            budgetRangeCell.innerHTML = '<span class="text-gray-400 text-sm">No budget set</span>';
        }
    }

    // Update action button
    if (actionCell) {
        actionCell.innerHTML = `
            <button type="button" onclick="event.stopPropagation(); openBudgetModal(${categoryId}, '${categoryName}', ${minBudget || 'null'}, ${maxBudget || 'null'}, '${currency}')"
                    class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700 transition-colors">
                <i class="fas fa-edit mr-1"></i>Edit Budget
            </button>
        `;
    }
}

// Old budget functions removed - now using modal approach

// Old budget functions removed - now using modal approach

// Skip interests function
function skipInterests() {
    document.getElementById('skipForm').submit();
}

// Save categories and continue to summary tab
function saveCategoriesAndContinue() {
    const checkedBoxes = document.querySelectorAll('input[name="interests[]"]:checked');

    if (checkedBoxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Categories Selected',
            text: 'Please select at least one category before continuing.',
            confirmButtonColor: '#092C48',
            confirmButtonText: 'OK'
        });
        return;
    }

    // Show loading state
    const button = document.querySelector('button[onclick="saveCategoriesAndContinue()"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    button.disabled = true;

    // Save categories via AJAX
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    checkedBoxes.forEach(checkbox => {
        formData.append('interests[]', checkbox.value);
    });

    fetch('{{ route("user.interests.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Switch to summary tab
            switchTab('summary');
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Categories saved successfully!',
                confirmButtonColor: '#092C48',
                confirmButtonText: 'OK'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error saving categories. Please try again.',
                confirmButtonColor: '#092C48',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Fallback: use regular form submission
        Swal.fire({
            icon: 'info',
            title: 'Switching to Summary',
            text: 'Switching to summary...',
            confirmButtonColor: '#092C48',
            confirmButtonText: 'OK'
        });
        setTimeout(() => {
            switchTab('summary');
        }, 1000);
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Old budget functions removed - now using modal approach

// Populate summary tab with selected categories
function populateSummaryTab() {
    const selectedCategories = [];

    // Get selected categories
    const checkboxes = document.querySelectorAll('input[name="interests[]"]:checked');
    checkboxes.forEach(checkbox => {
        const categoryId = checkbox.value;
        const categoryRow = checkbox.closest('.category-row');
        const categoryName = categoryRow.querySelector('td:nth-child(2) div').textContent;
        const categoryDescription = categoryRow.querySelector('td:nth-child(3) div').textContent;

        selectedCategories.push({
            id: categoryId,
            name: categoryName,
            description: categoryDescription
        });
    });

    // Populate table
    const tableBody = document.getElementById('summary-table-body');
    tableBody.innerHTML = '';

    selectedCategories.forEach(category => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';

        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-gray-900">${category.name}</div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-600">${category.description}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">-</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">-</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#092C48] text-white">
                    <i class="fas fa-check mr-1"></i>Selected
                </span>
            </td>
        `;
        tableBody.appendChild(row);
    });

    // Update statistics
    document.getElementById('total-categories').textContent = selectedCategories.length;
    document.getElementById('categories-with-budget').textContent = '0';
    document.getElementById('categories-without-budget').textContent = selectedCategories.length;
}

// Finalize preferences
function finalizePreferences() {
    const button = document.querySelector('button[onclick="finalizePreferences()"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Finalizing...';
    button.disabled = true;

    // Submit the form
    document.getElementById('interestsForm').submit();
}

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    // Set initial state for pre-selected items
    const checkboxes = document.querySelectorAll('input[name="interests[]"]');
    checkboxes.forEach(checkbox => {
        const categoryRow = checkbox.closest('.category-row');
        if (checkbox.checked) {
            categoryRow.classList.add('bg-blue-50', 'border-l-4', 'border-[#092C48]', 'selected');
        }
    });

    updateSelectionCounter();
    updateBudgetButtons();

    // Add currency change listener
    const currencySelect = document.getElementById('budgetCurrency');
    if (currencySelect) {
        currencySelect.addEventListener('change', function() {
            updateCurrencyDisplay(this.value);
        });
    }

    // Add click outside to close modal
    const modal = document.getElementById('budgetModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeBudgetModal();
            }
        });
    }

    // Add select all functionality
    const selectAllCheckbox = document.getElementById('selectAllCategories');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="interests[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                const categoryRow = checkbox.closest('.category-row');
                if (this.checked) {
                    categoryRow.classList.add('bg-blue-50', 'border-l-4', 'border-[#092C48]', 'selected');
                } else {
                    categoryRow.classList.remove('bg-blue-50', 'border-l-4', 'border-[#092C48]', 'selected');
                }
            });
            updateSelectionCounter();
            updateBudgetButtons();
        });
    }

    // Add search input event listeners
    const categorySearchInput = document.getElementById('categorySearch');
    if (categorySearchInput) {
        categorySearchInput.addEventListener('input', filterCategories);
        categorySearchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Escape') {
                clearSearch();
            }
        });
    }

    const summarySearchInput = document.getElementById('summarySearch');
    if (summarySearchInput) {
        summarySearchInput.addEventListener('input', filterSummary);
        summarySearchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Escape') {
                clearSummarySearch();
            }
        });
    }
});

// Old budget functions removed - now using modal approach
</script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
