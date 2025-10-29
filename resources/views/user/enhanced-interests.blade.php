@extends('layouts.admin')
@section('title', 'My Interests - SPANZ')
@section('content')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- FontAwesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                            <button type="button" onclick="switchTab('summary')" id="summary-tab" class="tab-button active flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200">
                                Your Interest
                            </button>
                            <button type="button" onclick="switchTab('categories')" id="categories-tab" class="tab-button flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200">
                               Add Interests
                            </button>
                        </nav>
                    </div>
                </div>

                <form method="POST" action="{{ route('user.interests.store') }}" id="interestsForm">
                    @csrf

                    <!-- Categories Tab -->
                    <div id="categories-tab-content" class="tab-content hidden">
                        <!-- Enhanced Search Bar -->
                        <div class="mb-6">
                            <div class="relative flex items-center bg-white rounded-xl shadow-lg border-2 border-blue-200 hover:border-blue-300 focus-within:border-blue-400 focus-within:shadow-xl transition-all duration-300">
                                <input type="text"
                                       id="categorySearch"
                                       placeholder="Search categories by name or description..."
                                       class="flex-1 px-4 py-4 bg-transparent border-none outline-none text-gray-700 placeholder-gray-400 rounded-l-xl"
                                       oninput="filterCategories()"
                                       onkeyup="filterCategories()">
                                <button type="button" 
                                        class="bg-[#092C48]  hover:bg-[#0a3a5a] text-white px-4 py-4 rounded-r-xl transition-all duration-200 flex items-center justify-center search-button min-w-[60px]"
                                        onclick="filterCategories()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-6" id="categories-pagination-controls">
                            <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm font-medium inline-block" id="selectionCounter">
                                <span id="selectedCount">0</span> categories selected
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <label for="pageSize" class="text-sm font-medium text-gray-700">Show:</label>
                                    <select id="pageSize" class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="changePageSize()">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span class="text-sm text-gray-600">per page</span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    Showing <span id="showingStart">1</span> to <span id="showingEnd">10</span> of <span id="totalCategories">0</span> categories
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-6" id="summary-pagination-controls">
                            <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm font-medium inline-block" id="summarySelectionCounter">
                                <span id="summarySelectedCount">0</span> interests selected
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <label for="summaryPageSize" class="text-sm font-medium text-gray-700">Show:</label>
                                    <select id="summaryPageSize" class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="changeSummaryPageSize()">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span class="text-sm text-gray-600">per page</span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    Showing <span id="summaryShowingStart">1</span> to <span id="summaryShowingEnd">10</span> of <span id="summaryTotalCategories">0</span> interests
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                                <thead class="bg-[#092C48] text-white">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Budget</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($categories as $category)
                                    @php
                                        $isSelected = false;
                                        $existingInterest = $existingInterests->where('category_id', $category->id)->first();
                                        $categoryBudgetRanges = $budgetRanges->where('category_id', $category->id);
                                        if ($existingInterest) {
                                            $isSelected = true;
                                        }
                                    @endphp
                                    <tr class="category-row hover:bg-blue-50 cursor-pointer transition-all duration-200 {{ $isSelected ? 'bg-blue-50 border-l-4 border-[#092C48]' : '' }}"
                                        data-category-name="{{ strtolower($category->name) }}"
                                        data-category-description="{{ strtolower($category->description ?? '') }}"
                                        onclick="toggleCategory({{ $category->id }}, '{{ $category->name }}', null, null, 'USD')">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">{{ $category->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-600">{{ $category->description ?? 'No description available' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-2" id="budget-tags-{{ $category->id }}">
                                                @if($categoryBudgetRanges->count() > 0)
                                                    @foreach($categoryBudgetRanges as $budgetRange)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $budgetRange->budget_type_color }} group">
                                                        {{ $budgetRange->formatted_budget_range }}
                                                        <button type="button" 
                                                                onclick="event.stopPropagation(); deleteBudgetTag({{ $budgetRange->id }}, {{ $category->id }})"
                                                                class="ml-2 text-red-500 hover:text-red-700 focus:outline-none cursor-pointer transition-all duration-200"
                                                                title="Delete this budget"
                                                                style="min-width: 24px; min-height: 24px; display: inline-flex; align-items: center; justify-content: center;">
                                                            <span style="font-size: 16px; font-weight: bold;">×</span>
                                                        </button>
                                            </span>
                                                    @endforeach
                                            @else
                                                    <span class="text-gray-400 text-xs">No budgets set</span>
                                            @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Controls -->
                        <div class="flex items-center justify-between mt-6 px-4 py-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-2">
                                <button id="prevPage" onclick="goToPreviousPage()" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                    <i class="fas fa-chevron-left mr-1"></i>Previous
                                </button>
                                <div id="pageNumbers" class="flex items-center space-x-1">
                                    <!-- Page numbers will be generated here -->
                                </div>
                                <button id="nextPage" onclick="goToNextPage()" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Next<i class="fas fa-chevron-right ml-1"></i>
                                </button>
                            </div>
                            <div class="text-sm text-gray-600">
                                Page <span id="currentPage">1</span> of <span id="totalPages">1</span>
                            </div>
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
                    <div id="summary-tab-content" class="tab-content">
                        <!-- Enhanced Summary Search Bar -->
                        <div class="mb-6">
                            <div class="relative flex items-center bg-white rounded-xl shadow-lg border-2 border-blue-200 hover:border-blue-300 focus-within:border-blue-400 focus-within:shadow-xl transition-all duration-300">
                                <input type="text"
                                       id="summarySearch"
                                       placeholder="Search your selected categories..."
                                       class="flex-1 px-4 py-4 bg-transparent border-none outline-none text-gray-700 placeholder-gray-400 rounded-l-xl"
                                       oninput="filterSummary()"
                                       onkeyup="filterSummary()">
                                <button type="button"
                                        class="bg-[#092C48]  hover:bg-[#0a3a5a] text-white px-4 py-4 rounded-r-xl transition-all duration-200 flex items-center justify-center search-button min-w-[60px]"
                                        onclick="filterSummary()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                       

                        <div class="overflow-x-auto">
                            <table class="w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                                <thead class="bg-[#092C48] text-white">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Budget</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="summary-table-body">
                                    <!-- Dynamic content will be populated here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Controls for Summary Tab -->
                        <div class="flex items-center justify-between mt-6 px-4 py-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-2">
                                <button id="summaryPrevPage" onclick="goToSummaryPreviousPage()" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                    <i class="fas fa-chevron-left mr-1"></i>Previous
                                </button>
                                <div id="summaryPageNumbers" class="flex items-center space-x-1">
                                    <!-- Page numbers will be generated here -->
                                </div>
                                <button id="summaryNextPage" onclick="goToSummaryNextPage()" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Next<i class="fas fa-chevron-right ml-1"></i>
                                </button>
                            </div>
                            <div class="text-sm text-gray-600">
                                Page <span id="summaryCurrentPage">1</span> of <span id="summaryTotalPages">1</span>
                            </div>
                        </div>

                        <!-- Enhanced No results message for Summary -->
                        <div id="summaryNoResultsMessage" class="hidden text-center py-16">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-12 border border-gray-200 shadow-sm">
                                <div class="text-gray-400 mb-6">
                                    <i class="fas fa-search text-6xl mb-4 opacity-50"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-700 mb-3">No interests found</h3>
                                <p class="text-gray-500 text-lg mb-6 max-w-md mx-auto">We couldn't find any interests matching your search. Try adjusting your search terms or browse all your interests.</p>
                                <button onclick="clearSummarySearch()" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 font-semibold">
                                    <i class="fas fa-refresh mr-2"></i>Clear Search
                                </button>
                            </div>
                        </div>

                    
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
    <div id="budgetModal" class="fixed inset-0 bg-black bg-opacity-60 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto transform transition-all duration-300 ease-out">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-[#092C48] rounded-t-2xl">
                <div class="flex items-center">
            
                    <div>
                        <h3 class="text-lg font-bold text-white" id="budgetModalTitle">Set Budget Range</h3>
                    </div>
                </div>
                <button type="button" onclick="closeBudgetModal()" class="text-white hover:text-gray-200 transition-colors p-1 hover:bg-white hover:bg-opacity-20 rounded-full">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-4">
                <!-- Category Display -->
                <div class="mb-4 p-3 bg-[#092C48] border border-[#092C48] rounded-lg">
                    <label class="block text-sm font-semibold text-white mb-1">
                        <i class="fas fa-tag mr-1"></i>Selected Category
                    </label>
                    <div class="text-sm font-bold text-white" id="selectedCategoryName"></div>
                </div>

                <!-- Budget Type Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-filter mr-1 text-blue-600"></i> Budget Type
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center p-2 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="budgetType" value="less" class="mr-3 text-[#092C48] focus:ring-[#092C48]" onchange="toggleBudgetFields()">
                            <span class="text-sm font-medium text-gray-700">Less than (Maximum amount)</span>
                        </label>
                        <label class="flex items-center p-2 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="budgetType" value="greater" class="mr-3 text-[#092C48] focus:ring-[#092C48]" onchange="toggleBudgetFields()">
                            <span class="text-sm font-medium text-gray-700">Greater than (Minimum amount)</span>
                        </label>
                        <label class="flex items-center p-2 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="budgetType" value="range" class="mr-3 text-[#092C48] focus:ring-[#092C48]" onchange="toggleBudgetFields()" checked>
                            <span class="text-sm font-medium text-gray-700">Range (Min to Max)</span>
                        </label>
                    </div>
                </div>

                <!-- Budget Inputs -->
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3" id="budgetFields">
                        <div id="minField">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                <i class="fas fa-arrow-down mr-1 text-green-600"></i> Min Budget
                            </label>
                            <div class="relative">
                                <input type="number" id="budgetMin" placeholder="0.00" step="0.01" min="0"
                                       class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-[#092C48] transition-all duration-200 text-sm font-medium">
                                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-xs font-medium" id="minCurrency">USD</span>
                                </div>
                            </div>
                        </div>

                        <div id="maxField">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                <i class="fas fa-arrow-up mr-1 text-red-600"></i> Max Budget
                            </label>
                            <div class="relative">
                                <input type="number" id="budgetMax" placeholder="1000000.00" step="0.01" min="0"
                                       class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-[#092C48] transition-all duration-200 text-sm font-medium">
                                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-xs font-medium" id="maxCurrency">USD</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

               
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 p-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                <button type="button" onclick="closeBudgetModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200 font-semibold text-sm shadow-sm hover:shadow-md">
                    <i class="fas fa-times mr-1"></i>Cancel
                </button>
                <button type="button" onclick="saveBudgetForCategory()" class="px-4 py-2 bg-[#092C48] text-white rounded-lg hover:bg-[#0a3a5a] transition-all duration-200 font-semibold shadow-lg hover:shadow-xl text-sm">
                    <i class="fas fa-save mr-1"></i>  Save
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

    /* Modal Enhancements - No animations */
    #budgetModal {
        backdrop-filter: blur(4px);
    }

    /* Enhanced input focus effects */
    #budgetMin:focus, #budgetMax:focus, #budgetCurrency:focus {
        box-shadow: 0 8px 25px -5px rgba(9, 44, 72, 0.1), 0 4px 6px -2px rgba(9, 44, 72, 0.05);
    }

    /* Enhanced search input effects */
    #categorySearch:focus, #summarySearch:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        border-color: #3b82f6;
    }

    /* Search button hover effects */
    .search-button {
        position: relative;
        overflow: hidden;
    }
    
    .search-button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .search-button:active {
        transform: scale(0.95);
    }
    
    /* Search icon styling */
    .search-button svg {
        width: 20px;
        height: 20px;
        stroke-width: 2;
    }
    
    .search-button i {
        font-size: 18px;
        line-height: 1;
        display: inline-block;
        vertical-align: middle;
        width: 18px;
        height: 18px;
        text-align: center;
    }
    
    /* Ensure proper centering */
    .search-button {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 60px;
        height: 100%;
    }
    
    /* Fallback for when FontAwesome is not loaded */
    .search-button:before {
        content: "🔍";
        font-size: 18px;
        display: none;
    }
    
    /* Show fallback icon if FontAwesome fails */
    .search-button i:not([class*="fa-"]) {
        display: none;
    }
    
    .search-button i:not([class*="fa-"]):after {
        content: "🔍";
        font-size: 18px;
    }

    /* Real-time search feedback */
    .search-loading {
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    /* Smooth transitions for search results */
    .category-row, #summary-table-body tr {
        transition: all 0.3s ease-in-out;
    }
</style>

<script>
let budgetRangeCounter = 0;

// Pagination variables
let currentPage = 1;
let pageSize = 10;
let totalCategories = 0;
let totalPages = 1;
let allCategories = [];
let filteredCategories = [];

// Summary tab pagination variables
let summaryCurrentPage = 1;
let summaryPageSize = 10;
let summaryTotalCategories = 0;
let summaryTotalPages = 1;
let allSummaryCategories = [];
let filteredSummaryCategories = [];

let paginationState = {
    categories: { currentPage: 1, pageSize: 10 },
    summary: { currentPage: 1, pageSize: 10 }
};

function switchTab(tabName) {
    // Check if switching to summary tab (Your Interest) and refresh immediately
    if (tabName === 'summary') {
        // Refresh the page when switching to summary tab
        window.location.reload();
        return;
    }

    // Save current pagination state for categories tab
    paginationState.categories.currentPage = currentPage;
    paginationState.categories.pageSize = pageSize;
    
    // Save current pagination state for summary tab
    paginationState.summary.currentPage = summaryCurrentPage;
    paginationState.summary.pageSize = summaryPageSize;

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

    // Show/hide pagination controls based on active tab
    const categoriesPaginationControls = document.getElementById('categories-pagination-controls');
    const summaryPaginationControls = document.getElementById('summary-pagination-controls');
    
    if (tabName === 'categories') {
        if (categoriesPaginationControls) categoriesPaginationControls.style.display = 'flex';
        if (summaryPaginationControls) summaryPaginationControls.style.display = 'none';
    } else if (tabName === 'summary') {
        if (categoriesPaginationControls) categoriesPaginationControls.style.display = 'none';
        if (summaryPaginationControls) summaryPaginationControls.style.display = 'flex';
    }

    // Update progress indicator
    updateProgressIndicator(tabName);

    // Handle categories tab logic
    if (tabName === 'categories') {
        // Only restore pagination state and reinitialize if we're switching TO categories tab
        // Don't call initializeCategories() if we're already on categories tab
        const currentActiveTab = document.querySelector('.tab-button.active');
        if (!currentActiveTab || currentActiveTab.id !== 'categories-tab') {
            // Restore pagination state when switching back to categories
            currentPage = paginationState.categories.currentPage;
            pageSize = paginationState.categories.pageSize;
            document.getElementById('pageSize').value = pageSize;
            initializeCategories();
        }
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

function toggleCategory(categoryId, categoryName, minBudget, maxBudget, currency) {
    // Open budget modal immediately when category is clicked
    openBudgetModal(categoryId, categoryName, minBudget, maxBudget, currency);
}

function markCategoryAsSelected(categoryId) {
    // Find the category row and mark it as selected
    const categoryRows = document.querySelectorAll('.category-row');
    categoryRows.forEach(row => {
        const onclickAttr = row.getAttribute('onclick');
        if (onclickAttr && onclickAttr.includes(`toggleCategory(${categoryId}`)) {
            row.classList.add('bg-blue-50', 'border-l-4', 'border-[#092C48]', 'selected');
        }
    });
}

function updateSelectionCounter() {
    // Count selected categories by checking rows with selected class
    const selectedRows = document.querySelectorAll('.category-row.selected');
    const counter = document.getElementById('selectedCount');
    counter.textContent = selectedRows.length;
}

// Initialize categories with sorting and pagination
function initializeCategories() {
    // Get all category rows
    const categoryRows = document.querySelectorAll('.category-row');
    allCategories = Array.from(categoryRows);
    
    // Sort categories alphabetically (case-insensitive)
    allCategories.sort((a, b) => {
        const nameA = a.querySelector('td:first-child div').textContent.toLowerCase();
        const nameB = b.querySelector('td:first-child div').textContent.toLowerCase();
        return nameA.localeCompare(nameB);
    });
    
    // Apply sorting to DOM
    const tbody = document.querySelector('tbody');
    tbody.innerHTML = '';
    allCategories.forEach(row => tbody.appendChild(row));
    
    // Initialize filtered categories as all categories
    filteredCategories = [...allCategories];
    
    // Update pagination
    updatePagination();
}

// Update pagination display and controls
function updatePagination() {
    totalCategories = filteredCategories.length;
    totalPages = Math.ceil(totalCategories / pageSize);
    
    // Ensure current page is valid
    if (currentPage > totalPages) {
        currentPage = Math.max(1, totalPages);
    }
    
    // Update display text
    document.getElementById('totalCategories').textContent = totalCategories;
    document.getElementById('currentPage').textContent = currentPage;
    document.getElementById('totalPages').textContent = totalPages;
    
    // Calculate showing range
    const startIndex = (currentPage - 1) * pageSize;
    const endIndex = Math.min(startIndex + pageSize, totalCategories);
    document.getElementById('showingStart').textContent = totalCategories > 0 ? startIndex + 1 : 0;
    document.getElementById('showingEnd').textContent = endIndex;
    
    // Update pagination buttons
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === totalPages;
    
    // Generate page numbers
    generatePageNumbers();
    
    // Show/hide categories based on current page
    showCurrentPageCategories();
}

// Generate page number buttons
function generatePageNumbers() {
    const pageNumbersContainer = document.getElementById('pageNumbers');
    pageNumbersContainer.innerHTML = '';
    
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    // Adjust start page if we're near the end
    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    // Add first page and ellipsis if needed
    if (startPage > 1) {
        addPageButton(1);
        if (startPage > 2) {
            addEllipsis();
        }
    }
    
    // Add visible page numbers
    for (let i = startPage; i <= endPage; i++) {
        addPageButton(i);
    }
    
    // Add last page and ellipsis if needed
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            addEllipsis();
        }
        addPageButton(totalPages);
    }
}

// Add a page number button
function addPageButton(pageNum) {
    const pageNumbersContainer = document.getElementById('pageNumbers');
    const button = document.createElement('button');
    button.textContent = pageNum;
    button.className = `px-3 py-2 text-sm font-medium border rounded-md ${
        pageNum === currentPage 
            ? 'bg-[#092C48] text-white border-[#092C48]' 
            : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50 hover:text-gray-700'
    }`;
    button.onclick = () => goToPage(pageNum);
    pageNumbersContainer.appendChild(button);
}

// Add ellipsis
function addEllipsis() {
    const pageNumbersContainer = document.getElementById('pageNumbers');
    const ellipsis = document.createElement('span');
    ellipsis.textContent = '...';
    ellipsis.className = 'px-2 py-2 text-sm text-gray-500';
    pageNumbersContainer.appendChild(ellipsis);
}

// Show categories for current page
function showCurrentPageCategories() {
    const startIndex = (currentPage - 1) * pageSize;
    const endIndex = startIndex + pageSize;
    
    // Hide all categories first
    allCategories.forEach(row => {
        row.style.display = 'none';
    });
    
    // Show only categories for current page
    filteredCategories.slice(startIndex, endIndex).forEach(row => {
        row.style.display = 'table-row';
    });
}

// Pagination navigation functions
function goToPage(page) {
    currentPage = page;
    updatePagination();
}

function goToPreviousPage() {
    if (currentPage > 1) {
        goToPage(currentPage - 1);
    }
}

function goToNextPage() {
    if (currentPage < totalPages) {
        goToPage(currentPage + 1);
    }
}

// Change page size
function changePageSize() {
    pageSize = parseInt(document.getElementById('pageSize').value);
    currentPage = 1; // Reset to first page
    updatePagination();
}

// Summary tab pagination functions
function changeSummaryPageSize() {
    summaryPageSize = parseInt(document.getElementById('summaryPageSize').value);
    summaryCurrentPage = 1; // Reset to first page
    updateSummaryPagination();
}

function goToSummaryPage(page) {
    summaryCurrentPage = page;
    updateSummaryPagination();
}

function goToSummaryPreviousPage() {
    if (summaryCurrentPage > 1) {
        goToSummaryPage(summaryCurrentPage - 1);
    }
}

function goToSummaryNextPage() {
    if (summaryCurrentPage < summaryTotalPages) {
        goToSummaryPage(summaryCurrentPage + 1);
    }
}

// Update summary pagination display and controls
function updateSummaryPagination() {
    summaryTotalCategories = filteredSummaryCategories.length;
    summaryTotalPages = Math.ceil(summaryTotalCategories / summaryPageSize);
    
    // Ensure current page is valid
    if (summaryCurrentPage > summaryTotalPages) {
        summaryCurrentPage = Math.max(1, summaryTotalPages);
    }
    
    // Update display text
    document.getElementById('summaryTotalCategories').textContent = summaryTotalCategories;
    document.getElementById('summaryCurrentPage').textContent = summaryCurrentPage;
    document.getElementById('summaryTotalPages').textContent = summaryTotalPages;
    
    // Calculate showing range
    const startIndex = (summaryCurrentPage - 1) * summaryPageSize;
    const endIndex = Math.min(startIndex + summaryPageSize, summaryTotalCategories);
    document.getElementById('summaryShowingStart').textContent = summaryTotalCategories > 0 ? startIndex + 1 : 0;
    document.getElementById('summaryShowingEnd').textContent = endIndex;
    
    // Update pagination buttons
    document.getElementById('summaryPrevPage').disabled = summaryCurrentPage === 1;
    document.getElementById('summaryNextPage').disabled = summaryCurrentPage === summaryTotalPages;
    
    // Generate page numbers
    generateSummaryPageNumbers();
    
    // Show/hide categories based on current page
    showCurrentSummaryPageCategories();
}

// Generate summary page number buttons
function generateSummaryPageNumbers() {
    const pageNumbersContainer = document.getElementById('summaryPageNumbers');
    pageNumbersContainer.innerHTML = '';
    
    const maxVisiblePages = 5;
    let startPage = Math.max(1, summaryCurrentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(summaryTotalPages, startPage + maxVisiblePages - 1);
    
    // Adjust start page if we're near the end
    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    // Add first page and ellipsis if needed
    if (startPage > 1) {
        addSummaryPageButton(1);
        if (startPage > 2) {
            addSummaryEllipsis();
        }
    }
    
    // Add visible page numbers
    for (let i = startPage; i <= endPage; i++) {
        addSummaryPageButton(i);
    }
    
    // Add last page and ellipsis if needed
    if (endPage < summaryTotalPages) {
        if (endPage < summaryTotalPages - 1) {
            addSummaryEllipsis();
        }
        addSummaryPageButton(summaryTotalPages);
    }
}

// Add a summary page number button
function addSummaryPageButton(pageNum) {
    const pageNumbersContainer = document.getElementById('summaryPageNumbers');
    const button = document.createElement('button');
    button.textContent = pageNum;
    button.className = `px-3 py-2 text-sm font-medium border rounded-md ${
        pageNum === summaryCurrentPage 
            ? 'bg-[#092C48] text-white border-[#092C48]' 
            : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50 hover:text-gray-700'
    }`;
    button.onclick = () => goToSummaryPage(pageNum);
    pageNumbersContainer.appendChild(button);
}

// Add summary ellipsis
function addSummaryEllipsis() {
    const pageNumbersContainer = document.getElementById('summaryPageNumbers');
    const ellipsis = document.createElement('span');
    ellipsis.textContent = '...';
    ellipsis.className = 'px-2 py-2 text-sm text-gray-500';
    pageNumbersContainer.appendChild(ellipsis);
}

// Show summary categories for current page
function showCurrentSummaryPageCategories() {
    const startIndex = (summaryCurrentPage - 1) * summaryPageSize;
    const endIndex = startIndex + summaryPageSize;
    
    // Hide all summary categories first
    allSummaryCategories.forEach(row => {
        row.style.display = 'none';
    });
    
    // Show only categories for current page
    filteredSummaryCategories.slice(startIndex, endIndex).forEach(row => {
        row.style.display = 'table-row';
    });
}

// Enhanced search functionality for categories tab
function filterCategories() {
    const searchTerm = document.getElementById('categorySearch').value.toLowerCase().trim();
    
    // Add search loading indicator
    const searchButton = document.querySelector('#categorySearch').nextElementSibling;
    if (searchButton) {
        searchButton.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
    }

    // Use setTimeout to debounce the search and show loading
    setTimeout(() => {
        // Filter categories based on search term
        if (searchTerm === '') {
            filteredCategories = [...allCategories];
        } else {
            filteredCategories = allCategories.filter(row => {
                const categoryName = row.getAttribute('data-category-name');
                const categoryDescription = row.getAttribute('data-category-description');
                return categoryName.includes(searchTerm) || categoryDescription.includes(searchTerm);
            });
        }
        
        // Reset to page 1 when searching
        currentPage = 1;
        
        // Update pagination
        updatePagination();
        
        // Show/hide no results message
        const noResultsMessage = document.getElementById('noResultsMessage');
        if (filteredCategories.length === 0 && searchTerm.length > 0) {
            noResultsMessage.classList.remove('hidden');
            noResultsMessage.style.animation = 'fadeIn 0.5s ease-in-out';
        } else {
            noResultsMessage.classList.add('hidden');
        }

        // Reset search button icon
        if (searchButton) {
            searchButton.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>';
        }
    }, 100); // Small delay for better UX
}

// Clear search function for categories
function clearSearch() {
    document.getElementById('categorySearch').value = '';
    filterCategories();
    document.getElementById('categorySearch').focus();
}

// Enhanced search functionality for summary tab
function filterSummary() {
    const searchTerm = document.getElementById('summarySearch').value.toLowerCase().trim();
    
    // Add search loading indicator
    const searchButton = document.querySelector('#summarySearch').nextElementSibling;
    if (searchButton) {
        searchButton.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
    }

    // Use setTimeout to debounce the search and show loading
    setTimeout(() => {
        // Filter summary categories based on search term
        if (searchTerm === '') {
            filteredSummaryCategories = [...allSummaryCategories];
        } else {
            filteredSummaryCategories = allSummaryCategories.filter(row => {
                const categoryName = row.querySelector('td:first-child div').textContent.toLowerCase();
                const categoryDescription = row.querySelector('td:nth-child(2) div').textContent.toLowerCase();
                return categoryName.includes(searchTerm) || categoryDescription.includes(searchTerm);
            });
        }
        
        // Reset to page 1 when searching
        summaryCurrentPage = 1;
        
        // Update pagination
        updateSummaryPagination();
        
        // Show/hide no results message
        const noResultsMessage = document.getElementById('summaryNoResultsMessage');
        if (filteredSummaryCategories.length === 0 && searchTerm.length > 0) {
            noResultsMessage.classList.remove('hidden');
            noResultsMessage.style.animation = 'fadeIn 0.5s ease-in-out';
        } else {
            noResultsMessage.classList.add('hidden');
        }

        // Reset search button icon
        if (searchButton) {
            searchButton.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>';
        }
    }, 100); // Small delay for better UX
}

// Clear search function for summary
function clearSummarySearch() {
    document.getElementById('summarySearch').value = '';
    filterSummary();
    document.getElementById('summarySearch').focus();
}

// Functions removed - no longer needed with new workflow

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

    if (budgetMinElement) budgetMinElement.value = minBudget || '';
    if (budgetMaxElement) budgetMaxElement.value = maxBudget || '';

    // Update currency display in input fields (always USD)
    updateCurrencyDisplay('USD');

    // Initialize radio buttons and fields
    initializeBudgetFields();

    // Show modal
    const modal = document.getElementById('budgetModal');
    if (modal) {
        console.log('Modal found, showing...');
        modal.classList.remove('hidden');
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

// Initialize budget fields when modal opens
function initializeBudgetFields() {
    // Set default to range if no existing budget
    const rangeRadio = document.querySelector('input[name="budgetType"][value="range"]');
    if (rangeRadio) {
        rangeRadio.checked = true;
    }
    // Trigger the toggle function to set up the initial state
    toggleBudgetFields();
}

// Toggle budget fields based on radio button selection
function toggleBudgetFields() {
    const budgetType = document.querySelector('input[name="budgetType"]:checked').value;
    const minField = document.getElementById('minField');
    const maxField = document.getElementById('maxField');
    const budgetFields = document.getElementById('budgetFields');
    
    // Clear both fields first
    document.getElementById('budgetMin').value = '';
    document.getElementById('budgetMax').value = '';
    
    if (budgetType === 'less') {
        // Show only max field
        minField.style.display = 'none';
        maxField.style.display = 'block';
        budgetFields.className = 'grid grid-cols-1 gap-3';
        // Update max field label
        maxField.querySelector('label').innerHTML = '<i class="fas fa-arrow-up mr-1 text-red-600"></i>Maximum Budget';
    } else if (budgetType === 'greater') {
        // Show only min field
        minField.style.display = 'block';
        maxField.style.display = 'none';
        budgetFields.className = 'grid grid-cols-1 gap-3';
        // Update min field label
        minField.querySelector('label').innerHTML = '<i class="fas fa-arrow-down mr-1 text-green-600"></i>Minimum Budget';
    } else if (budgetType === 'range') {
        // Show both fields
        minField.style.display = 'block';
        maxField.style.display = 'block';
        budgetFields.className = 'grid grid-cols-2 gap-3';
        // Reset labels
        minField.querySelector('label').innerHTML = '<i class="fas fa-arrow-down mr-1 text-green-600"></i>Min Budget';
        maxField.querySelector('label').innerHTML = '<i class="fas fa-arrow-up mr-1 text-red-600"></i>Max Budget';
    }
}

function saveBudgetForCategory() {
    const budgetType = document.querySelector('input[name="budgetType"]:checked').value;
    const minBudget = document.getElementById('budgetMin').value;
    const maxBudget = document.getElementById('budgetMax').value;
    const currency = 'USD'; // Default currency

    console.log('Saving budget:', {
        categoryId: currentCategoryId,
        budgetType: budgetType,
        minBudget: minBudget,
        maxBudget: maxBudget,
        currency: currency
    });

    // Validate based on budget type
    if (budgetType === 'range') {
        // For range, validate that min < max if both are provided
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
    } else if (budgetType === 'less') {
        // For less than, only max budget should be provided
        if (!maxBudget) {
            Swal.fire({
                icon: 'error',
                title: 'Missing Budget',
                text: 'Please enter a maximum budget amount.',
                confirmButtonColor: '#092C48',
                confirmButtonText: 'OK'
            });
            return;
        }
    } else if (budgetType === 'greater') {
        // For greater than, only min budget should be provided
        if (!minBudget) {
            Swal.fire({
                icon: 'error',
                title: 'Missing Budget',
                text: 'Please enter a minimum budget amount.',
                confirmButtonColor: '#092C48',
                confirmButtonText: 'OK'
            });
            return;
        }
    }

    // Show loading state
    const saveButton = document.querySelector('button[onclick="saveBudgetForCategory()"]');
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
    saveButton.disabled = true;

    // Send AJAX request to save budget and add interest
    fetch('{{ route("user.interests.save-budget") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            category_id: currentCategoryId,
            min_budget: budgetType === 'greater' || budgetType === 'range' ? minBudget || null : null,
            max_budget: budgetType === 'less' || budgetType === 'range' ? maxBudget || null : null,
            currency: currency,
            budget_type: budgetType
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            console.log('Budget saved successfully, adding tag...');
            console.log('Budget range data:', data.budget_range);
            console.log('Current category ID:', currentCategoryId);
            
            // Add new budget tag to the category first
            addBudgetTag(currentCategoryId, data.budget_range);
            
            // Mark category as selected
            markCategoryAsSelected(currentCategoryId);
            
            // Update selection counter
            updateSelectionCounter();
            
            // Close modal after a short delay to ensure tag is added
            setTimeout(() => {
            closeBudgetModal();
            }, 100);
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Budget added to your interests!',
                confirmButtonColor: '#092C48',
                confirmButtonText: 'OK'
            });
        } else {
            closeBudgetModal();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to save budget. Please try again.',
                confirmButtonColor: '#092C48',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        closeBudgetModal();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to save interest. Please try again.',
            confirmButtonColor: '#092C48',
            confirmButtonText: 'OK'
        });
    })
    .finally(() => {
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
    });
}

// Old budget functions removed - now using modal approach

// Old budget functions removed - now using modal approach

// Old budget functions removed - now using modal approach

// Skip interests function
function skipInterests() {
    document.getElementById('skipForm').submit();
}

// Add budget tag to category
function addBudgetTag(categoryId, budgetRange) {
    console.log('Adding budget tag:', { categoryId, budgetRange });
    
    // Try to find the tags container
    const tagsContainer = document.getElementById(`budget-tags-${categoryId}`);
    console.log('Tags container found:', tagsContainer);
    
    if (tagsContainer) {
        // Remove "No budgets set" text if it exists
        const noBudgetsText = tagsContainer.querySelector('.text-gray-400');
        if (noBudgetsText) {
            noBudgetsText.remove();
            console.log('Removed "No budgets set" text');
        }
        
        // Create the tag element
        const tagElement = document.createElement('span');
        tagElement.className = `inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border ${budgetRange.budget_type_color} group`;
        tagElement.innerHTML = `
            ${budgetRange.formatted_range}
            <button type="button" 
                    onclick="event.stopPropagation(); deleteBudgetTag(${budgetRange.id}, ${categoryId})"
                    class="ml-2 text-red-500 hover:text-red-700 focus:outline-none cursor-pointer transition-all duration-200"
                    title="Delete this budget"
                    style="min-width: 24px; min-height: 24px; display: inline-flex; align-items: center; justify-content: center;">
                <span style="font-size: 16px; font-weight: bold;">×</span>
            </button>
        `;
        
        // Add the tag to the container
        tagsContainer.appendChild(tagElement);
        console.log('Tag added successfully');
        
        // Verify the tag was added
        const addedTag = tagsContainer.querySelector(`button[onclick="deleteBudgetTag(${budgetRange.id}, ${categoryId})"]`);
        console.log('Tag verification:', addedTag ? 'Success' : 'Failed');
        
        // Force a visual update
        tagsContainer.style.display = 'none';
        tagsContainer.offsetHeight; // Trigger reflow
        tagsContainer.style.display = 'flex';
        
    } else {
        console.error('Tags container not found for category:', categoryId);
        console.error('Available containers:', document.querySelectorAll('[id^="budget-tags-"]'));
        
        // Try alternative approach - find by category row
        const categoryRow = document.querySelector(`tr[onclick*="toggleCategory(${categoryId}"]`);
        if (categoryRow) {
            console.log('Found category row, looking for tags container...');
            const alternativeContainer = categoryRow.querySelector('[id^="budget-tags-"]');
            if (alternativeContainer) {
                console.log('Found alternative container:', alternativeContainer);
                // Use the alternative container
                const tagElement = document.createElement('span');
                tagElement.className = `inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border ${budgetRange.budget_type_color} group`;
                tagElement.innerHTML = `
                    ${budgetRange.formatted_range}
                    <button type="button" 
                            onclick="event.stopPropagation(); deleteBudgetTag(${budgetRange.id}, ${categoryId})"
                            class="ml-2 text-red-500 hover:text-red-700 focus:outline-none cursor-pointer transition-all duration-200"
                            title="Delete this budget"
                            style="min-width: 24px; min-height: 24px; display: inline-flex; align-items: center; justify-content: center;">
                        <span style="font-size: 16px; font-weight: bold;">×</span>
                    </button>
                `;
                alternativeContainer.appendChild(tagElement);
                console.log('Tag added via alternative method');
            }
        }
    }
}

// Delete budget tag
function deleteBudgetTag(budgetRangeId, categoryId) {
    console.log('Delete budget tag requested:', { budgetRangeId, categoryId });
    
    Swal.fire({
        title: 'Delete Budget?',
        text: 'Are you sure you want to delete this budget range?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('User confirmed deletion, sending request...');
            console.log('Budget Range ID:', budgetRangeId);
            console.log('Category ID:', categoryId);
            
            const deleteUrl = `{{ route("user.interests.delete-budget", ":budgetRangeId") }}`.replace(':budgetRangeId', budgetRangeId);
            console.log('Delete URL:', deleteUrl);
            
            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Delete response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Delete response data:', data);
                if (data.success) {
                    // Remove the tag from UI - improved element selection
                    const deleteButton = document.querySelector(`button[onclick*="deleteBudgetTag(${budgetRangeId}, ${categoryId})"]`);
                    console.log('Found delete button:', deleteButton);
                    
                    if (deleteButton && deleteButton.parentElement) {
                        console.log('Removing tag element:', deleteButton.parentElement);
                        deleteButton.parentElement.remove();
                    } else {
                        console.error('Could not find delete button or its parent element');
                        // Fallback: try to find by budget range ID in a different way
                        const allButtons = document.querySelectorAll('button[onclick*="deleteBudgetTag"]');
                        console.log('All delete buttons found:', allButtons);
                        
                        for (let button of allButtons) {
                            if (button.getAttribute('onclick').includes(`deleteBudgetTag(${budgetRangeId}`)) {
                                console.log('Found matching button via fallback:', button);
                                if (button.parentElement) {
                                    button.parentElement.remove();
                                    break;
                                }
                            }
                        }
                    }
                    
                    // If no budgets left, unselect the category
                    if (data.remaining_budgets === 0) {
                        console.log('No budgets left, unselecting category');
                        const categoryRow = document.querySelector(`tr[onclick*="toggleCategory(${categoryId}"]`);
                        console.log('Found category row:', categoryRow);
                        
                        if (categoryRow) {
                            categoryRow.classList.remove('bg-blue-50', 'border-l-4', 'border-[#092C48]', 'selected');
                            console.log('Category row unselected');
                        }
                        updateSelectionCounter();
                        
                        // Add "No budgets set" text back
                        const tagsContainer = document.getElementById(`budget-tags-${categoryId}`);
                        console.log('Tags container for adding "No budgets set":', tagsContainer);
                        
                        if (tagsContainer) {
                            const noBudgetsSpan = document.createElement('span');
                            noBudgetsSpan.className = 'text-gray-400 text-xs';
                            noBudgetsSpan.textContent = 'No budgets set';
                            tagsContainer.appendChild(noBudgetsSpan);
                            console.log('Added "No budgets set" text');
                        }
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Budget range has been deleted.',
                        confirmButtonColor: '#092C48',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to delete budget range.',
                        confirmButtonColor: '#092C48',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error deleting budget:', error);
                console.error('Error details:', {
                    message: error.message,
                    stack: error.stack,
                    budgetRangeId: budgetRangeId,
                    categoryId: categoryId
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: `An error occurred while deleting the budget range: ${error.message}`,
                    confirmButtonColor: '#092C48',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

// Save categories and continue to summary tab
function saveCategoriesAndContinue() {
    const selectedRows = document.querySelectorAll('.category-row.selected');

    if (selectedRows.length === 0) {
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

    // Collect selected category IDs
    const selectedCategoryIds = [];
    selectedRows.forEach(row => {
        const onclickAttr = row.getAttribute('onclick');
        const categoryIdMatch = onclickAttr.match(/toggleCategory\((\d+)/);
        if (categoryIdMatch) {
            selectedCategoryIds.push(categoryIdMatch[1]);
        }
    });

    // Save categories via AJAX
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    selectedCategoryIds.forEach(categoryId => {
        formData.append('interests[]', categoryId);
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

    // Get selected categories by checking rows with selected class
    const selectedRows = document.querySelectorAll('.category-row.selected');
    selectedRows.forEach(row => {
        const categoryName = row.querySelector('td:first-child div').textContent;
        const categoryDescription = row.querySelector('td:nth-child(2) div').textContent;
        
        // Extract category ID from onclick attribute
        const onclickAttr = row.getAttribute('onclick');
        const categoryIdMatch = onclickAttr.match(/toggleCategory\((\d+)/);
        const categoryId = categoryIdMatch ? categoryIdMatch[1] : null;

        // Get budget tags for this category
        const budgetTagsContainer = row.querySelector(`#budget-tags-${categoryId}`);
        let budgetTagsHtml = '';
        if (budgetTagsContainer) {
            // Clone the budget tags container content
            const budgetTagsClone = budgetTagsContainer.cloneNode(true);
            // Remove delete buttons from summary view
            const deleteButtons = budgetTagsClone.querySelectorAll('button[onclick*="deleteBudgetTag"]');
            deleteButtons.forEach(button => button.remove());
            budgetTagsHtml = budgetTagsClone.innerHTML;
        }

        if (categoryId) {
        selectedCategories.push({
            id: categoryId,
            name: categoryName,
            description: categoryDescription,
            budgetTags: budgetTagsHtml || '<span class="text-gray-400 text-xs">No budgets set</span>'
        });
        }
    });

    // Sort categories alphabetically (case-insensitive)
    selectedCategories.sort((a, b) => {
        return a.name.toLowerCase().localeCompare(b.name.toLowerCase());
    });

    // Populate table
    const tableBody = document.getElementById('summary-table-body');
    tableBody.innerHTML = '';

    // Store all summary categories for pagination
    allSummaryCategories = [];

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
            <td class="px-6 py-4">
                <div class="flex flex-wrap gap-2">${category.budgetTags}</div>
            </td>
        `;
        tableBody.appendChild(row);
        allSummaryCategories.push(row);
    });

    // Initialize filtered categories as all categories
    filteredSummaryCategories = [...allSummaryCategories];
    
    // Update pagination
    updateSummaryPagination();
    
    // Update statistics
    document.getElementById('summarySelectedCount').textContent = selectedCategories.length;
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
    // Initialize categories with sorting and pagination
    initializeCategories();
    
    // Set initial state for pre-selected items
    const categoryRows = document.querySelectorAll('.category-row');
    categoryRows.forEach(row => {
        if (row.classList.contains('bg-blue-50')) {
            row.classList.add('selected');
        }
    });

    updateSelectionCounter();
    
    // Populate summary tab on page load since it's now the default tab
    populateSummaryTab();

    // Initialize pagination controls visibility
    const categoriesPaginationControls = document.getElementById('categories-pagination-controls');
    const summaryPaginationControls = document.getElementById('summary-pagination-controls');
    
    // Since summary tab is default, hide categories pagination controls
    if (categoriesPaginationControls) categoriesPaginationControls.style.display = 'none';
    if (summaryPaginationControls) summaryPaginationControls.style.display = 'flex';

    // Currency is now fixed to USD, no listener needed

    // Add click outside to close modal
    const modal = document.getElementById('budgetModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeBudgetModal();
            }
        });
    }

    // Add search input event listeners for real-time search
    const categorySearchInput = document.getElementById('categorySearch');
    if (categorySearchInput) {
        // Real-time search on every keystroke
        categorySearchInput.addEventListener('input', filterCategories);
        categorySearchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Escape') {
                clearSearch();
            } else {
                filterCategories(); // Also trigger on keyup for better responsiveness
            }
        });
        categorySearchInput.addEventListener('paste', function() {
            // Handle paste events with a slight delay
            setTimeout(filterCategories, 50);
        });
    }

    const summarySearchInput = document.getElementById('summarySearch');
    if (summarySearchInput) {
        // Real-time search on every keystroke
        summarySearchInput.addEventListener('input', filterSummary);
        summarySearchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Escape') {
                clearSummarySearch();
            } else {
                filterSummary(); // Also trigger on keyup for better responsiveness
            }
        });
        summarySearchInput.addEventListener('paste', function() {
            // Handle paste events with a slight delay
            setTimeout(filterSummary, 50);
        });
    }
});

// Old budget functions removed - now using modal approach
</script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
