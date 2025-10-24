@extends('layouts.admin')
@section('title', 'My Interests - SPANZ')
@section('content')
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
                                <span class="ml-2 bg-white text-[#092C48] px-2 py-1 rounded-full text-xs font-bold">1</span>
                            </button>
                            <button type="button" onclick="switchTab('budget')" id="budget-tab" class="tab-button flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200">
                                <i class="fas fa-dollar-sign me-3"></i>Set Budget Ranges
                                <span class="ml-2 bg-gray-200 text-gray-500 px-2 py-1 rounded-full text-xs font-bold">2</span>
                            </button>
                            <button type="button" onclick="switchTab('summary')" id="summary-tab" class="tab-button flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200">
                                <i class="fas fa-list-check me-3"></i>Summary
                                <span class="ml-2 bg-gray-200 text-gray-500 px-2 py-1 rounded-full text-xs font-bold">3</span>
                            </button>
                        </nav>
                    </div>

                    <!-- Progress indicator -->
                    <div class="mt-4 flex items-center justify-center">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-[#092C48] text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                                <div class="w-16 h-1 bg-[#092C48] mx-2"></div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-bold" id="step-2">2</div>
                                <div class="w-16 h-1 bg-gray-300 mx-2" id="step-2-line"></div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-bold" id="step-3">3</div>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('user.interests.store') }}" id="interestsForm">
                    @csrf

                    <!-- Categories Tab -->
                    <div id="categories-tab-content" class="tab-content">
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
                                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Status</th>
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
                                    <tr class="category-row hover:bg-blue-50 cursor-pointer transition-all duration-200 {{ $isSelected ? 'bg-blue-50 border-l-4 border-[#092C48]' : '' }}" onclick="toggleCategory({{ $category->id }})">
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
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @error('interests')
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 mt-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Budget Tab -->
                    <div id="budget-tab-content" class="tab-content hidden">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Set Budget Ranges for Selected Categories</h3>
                            <p class="text-gray-600 mb-6">Configure budget ranges for your selected categories. You'll receive notifications for tenders within these ranges.</p>

                            <div id="no-selected-categories" class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6 hidden">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Please select some categories first before setting budget ranges.
                            </div>
                        </div>

                        <div id="selected-categories-list" class="mb-6">
                            <!-- Selected categories will be displayed here -->
                        </div>

                        <div id="budget-ranges-container">
                            <!-- Budget ranges will be dynamically added here -->
                        </div>

                        <div class="mt-6 flex gap-4">
                            <button type="button" onclick="addBudgetRange()" id="add-budget-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus me-2"></i>Add Budget Range
                            </button>
                            <button type="button" onclick="saveBudgetRanges()" id="save-budget-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors hidden">
                                <i class="fas fa-save me-2"></i>💾 Save Budget Ranges
                            </button>
                            <button type="button" onclick="saveBudgetAndContinue()" id="save-budget-continue-btn" class="bg-[#092C48] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#0a3a5a] transition-all duration-200 shadow-md hover:shadow-lg hidden">
                                <i class="fas fa-save me-3"></i>Save & Continue to Summary
                            </button>
                        </div>

                        <!-- Success/Error Messages for Budget Ranges -->
                        <div id="budget-message" class="mt-4 hidden"></div>
                    </div>

                    <!-- Summary Tab -->
                    <div id="summary-tab-content" class="tab-content hidden">
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
    const step2Badge = document.querySelector('#budget-tab span');
    const step2Line = document.getElementById('step-2-line');
    const step3 = document.getElementById('step-3');
    const step3Badge = document.querySelector('#summary-tab span');

    if (activeTab === 'budget') {
        step2.classList.remove('bg-gray-300', 'text-gray-500');
        step2.classList.add('bg-[#092C48]', 'text-white');
        step2Badge.classList.remove('bg-gray-200', 'text-gray-500');
        step2Badge.classList.add('bg-white', 'text-[#092C48]');
        step2Line.classList.remove('bg-gray-300');
        step2Line.classList.add('bg-[#092C48]');
    } else if (activeTab === 'summary') {
        step2.classList.remove('bg-gray-300', 'text-gray-500');
        step2.classList.add('bg-[#092C48]', 'text-white');
        step2Badge.classList.remove('bg-gray-200', 'text-gray-500');
        step2Badge.classList.add('bg-white', 'text-[#092C48]');
        step2Line.classList.remove('bg-gray-300');
        step2Line.classList.add('bg-[#092C48]');
        step3.classList.remove('bg-gray-300', 'text-gray-500');
        step3.classList.add('bg-[#092C48]', 'text-white');
        step3Badge.classList.remove('bg-gray-200', 'text-gray-500');
        step3Badge.classList.add('bg-white', 'text-[#092C48]');
    } else {
        step2.classList.remove('bg-[#092C48]', 'text-white');
        step2.classList.add('bg-gray-300', 'text-gray-500');
        step2Badge.classList.remove('bg-white', 'text-[#092C48]');
        step2Badge.classList.add('bg-gray-200', 'text-gray-500');
        step2Line.classList.remove('bg-[#092C48]');
        step2Line.classList.add('bg-gray-300');
        step3.classList.remove('bg-[#092C48]', 'text-white');
        step3.classList.add('bg-gray-300', 'text-gray-500');
        step3Badge.classList.remove('bg-white', 'text-[#092C48]');
        step3Badge.classList.add('bg-gray-200', 'text-gray-500');
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
    updateBudgetTabVisibility();
}

function updateSelectionCounter() {
    const checkboxes = document.querySelectorAll('input[name="interests[]"]');
    const checkedBoxes = document.querySelectorAll('input[name="interests[]"]:checked');
    const counter = document.getElementById('selectedCount');

    counter.textContent = checkedBoxes.length;
}

function updateSelectedCategoriesList() {
    const selectedCategoriesList = document.getElementById('selected-categories-list');
    const checkedBoxes = document.querySelectorAll('input[name="interests[]"]:checked');

    if (checkedBoxes.length === 0) {
        selectedCategoriesList.innerHTML = '';
        return;
    }

    let html = '<div class="bg-blue-50 border border-blue-200 rounded-lg p-4"><h4 class="font-semibold text-blue-900 mb-3">Selected Categories:</h4><div class="flex flex-wrap gap-2">';

    checkedBoxes.forEach(checkbox => {
        const categoryId = checkbox.value;
        const categoryName = checkbox.closest('tr').querySelector('td:nth-child(2) div').textContent;
        html += `<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-tag mr-1"></i>${categoryName}
                 </span>`;
    });

    html += '</div></div>';
    selectedCategoriesList.innerHTML = html;
}

function updateBudgetTabVisibility() {
    const checkedBoxes = document.querySelectorAll('input[name="interests[]"]:checked');
    const noSelectedCategories = document.getElementById('no-selected-categories');
    const addBudgetBtn = document.getElementById('add-budget-btn');

    if (checkedBoxes.length === 0) {
        noSelectedCategories.classList.remove('hidden');
        addBudgetBtn.disabled = true;
        addBudgetBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        noSelectedCategories.classList.add('hidden');
        addBudgetBtn.disabled = false;
        addBudgetBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

function addBudgetRange() {
    budgetRangeCounter++;
    const container = document.getElementById('budget-ranges-container');

    // Get selected categories for the dropdown
    const selectedCategories = getSelectedCategories();

    const budgetRangeHtml = `
        <div class="budget-range-item bg-white border border-gray-200 rounded-lg p-6 shadow-sm" id="budget-range-${budgetRangeCounter}">
            <div class="flex justify-between items-start mb-4">
                <h4 class="font-semibold text-gray-900">Budget Range ${budgetRangeCounter}</h4>
                <button type="button" onclick="removeBudgetRange(${budgetRangeCounter})" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="budget_ranges[${budgetRangeCounter}][category_id]" class="budget-category-select w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Category</option>
                        ${selectedCategories}
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Budget</label>
                    <input type="number" name="budget_ranges[${budgetRangeCounter}][min_budget]"
                           placeholder="0.00" step="0.01" min="0"
                           class="budget-min-input w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Budget</label>
                    <input type="number" name="budget_ranges[${budgetRangeCounter}][max_budget]"
                           placeholder="1000000.00" step="0.01" min="0"
                           class="budget-max-input w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                <select name="budget_ranges[${budgetRangeCounter}][currency]" class="budget-currency-select w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="USD">USD - US Dollar</option>
                    <option value="AUD">AUD - Australian Dollar</option>
                    <option value="EUR">EUR - Euro</option>
                    <option value="GBP">GBP - British Pound</option>
                    <option value="SGD">SGD - Singapore Dollar</option>
                    <option value="NZD">NZD - New Zealand Dollar</option>
                </select>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', budgetRangeHtml);

    // Add validation for min/max budget
    const minInput = container.querySelector(`#budget-range-${budgetRangeCounter} .budget-min-input`);
    const maxInput = container.querySelector(`#budget-range-${budgetRangeCounter} .budget-max-input`);

    minInput.addEventListener('input', function() {
        const maxValue = maxInput.value;
        if (maxValue && parseFloat(this.value) >= parseFloat(maxValue)) {
            this.setCustomValidity('Minimum budget must be less than maximum budget');
            this.classList.add('border-red-500');
        } else {
            this.setCustomValidity('');
            this.classList.remove('border-red-500');
        }
    });

    maxInput.addEventListener('input', function() {
        const minValue = minInput.value;
        if (minValue && parseFloat(this.value) <= parseFloat(minValue)) {
            this.setCustomValidity('Maximum budget must be greater than minimum budget');
            this.classList.add('border-red-500');
        } else {
            this.setCustomValidity('');
            this.classList.remove('border-red-500');
        }
    });

    // Show the save button when a budget range is added
    updateSaveButtonVisibility();
}

function getSelectedCategories() {
    const checkedBoxes = document.querySelectorAll('input[name="interests[]"]:checked');
    let options = '';

    checkedBoxes.forEach(checkbox => {
        const categoryId = checkbox.value;
        const categoryName = checkbox.closest('tr').querySelector('td:nth-child(2) div').textContent;
        options += `<option value="${categoryId}">${categoryName}</option>`;
    });

    return options;
}

function removeBudgetRange(id) {
    const element = document.getElementById('budget-range-' + id);
    if (element) {
        element.remove();
    }

    // Update save button visibility after removing a budget range
    updateSaveButtonVisibility();
}

function saveBudgetRanges() {
    const budgetRanges = [];
    const budgetItems = document.querySelectorAll('.budget-range-item');
    const usedCategories = new Set();

    budgetItems.forEach((item, index) => {
        const categorySelect = item.querySelector('.budget-category-select');
        const minInput = item.querySelector('.budget-min-input');
        const maxInput = item.querySelector('.budget-max-input');
        const currencySelect = item.querySelector('.budget-currency-select');

        if (categorySelect.value) {
            // Check for duplicate categories
            if (usedCategories.has(categorySelect.value)) {
                showBudgetMessage('Error: Each category can only have one budget range. Please remove duplicates.', 'error');
                return;
            }
            usedCategories.add(categorySelect.value);

            // Validate budget range
            const minBudget = parseFloat(minInput.value) || 0;
            const maxBudget = parseFloat(maxInput.value) || null;

            if (maxBudget && minBudget > maxBudget) {
                showBudgetMessage('Error: Minimum budget cannot be greater than maximum budget.', 'error');
                return;
            }

            budgetRanges.push({
                category_id: categorySelect.value,
                min_budget: minInput.value || null,
                max_budget: maxInput.value || null,
                currency: currencySelect.value
            });
        }
    });

    // Note: Budget ranges are optional, so we don't require them
    // Users can save categories without setting budget ranges

    // Show loading state
    const saveButton = document.querySelector('button[onclick="saveBudgetRanges()"]');
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    saveButton.disabled = true;

    // Send AJAX request to save budget ranges
    fetch('{{ route("user.interests.save-budget") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            budget_ranges: budgetRanges
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showBudgetMessage('Preferences saved successfully!', 'success');
        } else {
            showBudgetMessage(data.message || 'Error saving preferences', 'error');
        }
    })
    .catch(error => {
        showBudgetMessage('Error saving budget ranges', 'error');
    })
    .finally(() => {
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
    });
}

function showBudgetMessage(message, type) {
    const messageDiv = document.getElementById('budget-message');
    messageDiv.className = `mt-4 p-4 rounded-lg ${type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'}`;
    messageDiv.textContent = message;
    messageDiv.classList.remove('hidden');

    // Hide message after 5 seconds
    setTimeout(() => {
        messageDiv.classList.add('hidden');
    }, 5000);
}

// Skip interests function
function skipInterests() {
    document.getElementById('skipForm').submit();
}

// Save categories and continue to budget tab
function saveCategoriesAndContinue() {
    const checkedBoxes = document.querySelectorAll('input[name="interests[]"]:checked');

    if (checkedBoxes.length === 0) {
        showBudgetMessage('Please select at least one category before continuing.', 'error');
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
            // Switch to budget tab
            switchTab('budget');
            // Show success message
            showBudgetMessage('Categories saved successfully! Now set your budget ranges.', 'success');
        } else {
            showBudgetMessage(data.message || 'Error saving categories. Please try again.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Fallback: use regular form submission
        showBudgetMessage('Switching to budget tab...', 'success');
        setTimeout(() => {
            switchTab('budget');
        }, 1000);
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Save budget ranges and continue to summary
function saveBudgetAndContinue() {
    const budgetRanges = [];
    const budgetItems = document.querySelectorAll('.budget-range-item');

    budgetItems.forEach(item => {
        const categorySelect = item.querySelector('select[name*="category_id"]');
        const minBudget = item.querySelector('input[name*="min_budget"]').value;
        const maxBudget = item.querySelector('input[name*="max_budget"]').value;
        const currency = item.querySelector('select[name*="currency"]').value;

        if (categorySelect && categorySelect.value) {
            // Validate min/max budget
            if (minBudget && maxBudget && parseFloat(minBudget) >= parseFloat(maxBudget)) {
                showBudgetMessage('Minimum budget must be less than maximum budget.', 'error');
                return;
            }

            budgetRanges.push({
                category_id: categorySelect.value,
                min_budget: minBudget || null,
                max_budget: maxBudget || null,
                currency: currency
            });
        }
    });

    // Show loading state
    const button = document.querySelector('button[onclick="saveBudgetAndContinue()"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-3"></i>Saving...';
    button.disabled = true;

    fetch('{{ route("user.interests.save-budget") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            budget_ranges: budgetRanges
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Switch to summary tab
            switchTab('summary');
            showBudgetMessage('Budget ranges saved successfully!', 'success');
        } else {
            showBudgetMessage(data.message || 'Error saving budget ranges', 'error');
        }
    })
    .catch(error => {
        showBudgetMessage('Error saving budget ranges', 'error');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Populate summary tab with selected categories and budget ranges
function populateSummaryTab() {
    const selectedCategories = [];
    const budgetRanges = [];

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

    // Get budget ranges
    const budgetItems = document.querySelectorAll('.budget-range-item');
    budgetItems.forEach(item => {
        const categorySelect = item.querySelector('select[name*="category_id"]');
        const minBudget = item.querySelector('input[name*="min_budget"]').value;
        const maxBudget = item.querySelector('input[name*="max_budget"]').value;
        const currency = item.querySelector('select[name*="currency"]').value;

        if (categorySelect && categorySelect.value) {
            const categoryName = categorySelect.selectedOptions[0].textContent;
            budgetRanges.push({
                categoryId: categorySelect.value,
                categoryName: categoryName,
                minBudget: minBudget,
                maxBudget: maxBudget,
                currency: currency
            });
        }
    });

    // Populate table
    const tableBody = document.getElementById('summary-table-body');
    tableBody.innerHTML = '';

    selectedCategories.forEach(category => {
        const budgetRange = budgetRanges.find(br => br.categoryId === category.id);
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';

        let budgetRangeText = 'No budget range set';
        let currencyText = '-';
        let statusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200"><i class="fas fa-minus mr-1"></i>No Budget</span>';

        if (budgetRange) {
            if (budgetRange.minBudget && budgetRange.maxBudget) {
                budgetRangeText = `${budgetRange.minBudget} - ${budgetRange.maxBudget}`;
            } else if (budgetRange.minBudget) {
                budgetRangeText = `Min: ${budgetRange.minBudget}`;
            } else if (budgetRange.maxBudget) {
                budgetRangeText = `Max: ${budgetRange.maxBudget}`;
            }
            currencyText = budgetRange.currency;
            statusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200"><i class="fas fa-dollar-sign mr-1"></i>Budget Set</span>';
        }

        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-gray-900">${category.name}</div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-600">${category.description}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${budgetRangeText}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${currencyText}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                ${statusBadge}
            </td>
        `;
        tableBody.appendChild(row);
    });

    // Update statistics
    document.getElementById('total-categories').textContent = selectedCategories.length;
    document.getElementById('categories-with-budget').textContent = budgetRanges.length;
    document.getElementById('categories-without-budget').textContent = selectedCategories.length - budgetRanges.length;
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
    updateSelectedCategoriesList();
    updateBudgetTabVisibility();

    // Load existing budget ranges
    loadExistingBudgetRanges();

    // Update save button visibility on page load
    updateSaveButtonVisibility();

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
            updateSelectedCategoriesList();
            updateBudgetTabVisibility();
        });
    }
});

function updateSaveButtonVisibility() {
    const saveButton = document.getElementById('save-budget-btn');
    const saveContinueButton = document.getElementById('save-budget-continue-btn');
    const budgetItems = document.querySelectorAll('.budget-range-item');

    if (budgetItems.length > 0) {
        saveButton.classList.remove('hidden');
        saveContinueButton.classList.remove('hidden');
    } else {
        saveButton.classList.add('hidden');
        saveContinueButton.classList.add('hidden');
    }
}

function loadExistingBudgetRanges() {
    @if($existingInterests->count() > 0)
        @foreach($existingInterests as $interest)
            @if($interest->min_budget || $interest->max_budget)
                addBudgetRangeWithData({
                    category_id: {{ $interest->category_id }},
                    min_budget: {{ $interest->min_budget ?? 'null' }},
                    max_budget: {{ $interest->max_budget ?? 'null' }},
                    currency: '{{ $interest->currency }}'
                });
            @endif
        @endforeach
    @endif
}

function addBudgetRangeWithData(data) {
    budgetRangeCounter++;
    const container = document.getElementById('budget-ranges-container');

    // Get selected categories for the dropdown
    const selectedCategories = getSelectedCategories();

    const budgetRangeHtml = `
        <div class="budget-range-item bg-white border border-gray-200 rounded-lg p-6 shadow-sm" id="budget-range-${budgetRangeCounter}">
            <div class="flex justify-between items-start mb-4">
                <h4 class="font-semibold text-gray-900">Budget Range ${budgetRangeCounter}</h4>
                <button type="button" onclick="removeBudgetRange(${budgetRangeCounter})" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="budget_ranges[${budgetRangeCounter}][category_id]" class="budget-category-select w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Category</option>
                        ${selectedCategories}
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Budget</label>
                    <input type="number" name="budget_ranges[${budgetRangeCounter}][min_budget]"
                           placeholder="0.00" step="0.01" min="0"
                           class="budget-min-input w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Budget</label>
                    <input type="number" name="budget_ranges[${budgetRangeCounter}][max_budget]"
                           placeholder="1000000.00" step="0.01" min="0"
                           class="budget-max-input w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                <select name="budget_ranges[${budgetRangeCounter}][currency]" class="budget-currency-select w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="USD">USD - US Dollar</option>
                    <option value="AUD">AUD - Australian Dollar</option>
                    <option value="EUR">EUR - Euro</option>
                    <option value="GBP">GBP - British Pound</option>
                    <option value="SGD">SGD - Singapore Dollar</option>
                    <option value="NZD">NZD - New Zealand Dollar</option>
                </select>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', budgetRangeHtml);

    // Set the values
    const newItem = document.getElementById('budget-range-' + budgetRangeCounter);
    newItem.querySelector('.budget-category-select').value = data.category_id;
    newItem.querySelector('.budget-min-input').value = data.min_budget;
    newItem.querySelector('.budget-max-input').value = data.max_budget;
    newItem.querySelector('.budget-currency-select').value = data.currency;

    // Update save button visibility
    updateSaveButtonVisibility();
}
</script>
@endsection
