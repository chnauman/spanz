@extends('layouts.admin')
@section('title', 'My Interests - SPANZ')
@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold">🎯 Select Your Interests</h1>
                    <p class="text-gray-300 mt-1">Choose the categories you're interested in to receive relevant tender notifications and stay updated with opportunities that matter to you.</p>
                </div>
            </div>

            <div class="p-6">
                <!-- Search Bar -->
                <div class="mb-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text"
                               id="categorySearch"
                               placeholder="Search categories by name or description..."
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               onkeyup="filterCategories()">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button"
                                    id="clearSearch"
                                    onclick="clearSearch()"
                                    class="text-gray-400 hover:text-gray-600 hidden">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('user.interests.store') }}" id="interestsForm">
                    @csrf

                    <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm font-medium mb-6 inline-block" id="selectionCounter">
                        <span id="selectedCount">0</span> categories selected
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8" id="categoriesGrid">
                        @foreach($categories as $category)
                        <div class="category-item bg-gray-50 border-2 border-transparent rounded-lg p-6 transition-all duration-300 cursor-pointer hover:shadow-lg hover:border-blue-300"
                             data-category-name="{{ strtolower($category->name) }}"
                             data-category-description="{{ strtolower($category->description ?? '') }}"
                             onclick="toggleCategory({{ $category->id }})">
                            <input class="category-checkbox hidden" type="checkbox" name="interests[]"
                                   value="{{ $category->id }}" id="category_{{ $category->id }}"
                                   {{ in_array($category->id, old('interests', [])) ? 'checked' : '' }}>
                            <label class="category-label cursor-pointer" for="category_{{ $category->id }}">
                                <span class="category-name text-lg font-semibold text-gray-900 block mb-2">{{ $category->name }}</span>
                                @if($category->description)
                                <span class="category-description text-sm text-gray-600 leading-relaxed">{{ $category->description }}</span>
                                @endif
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <!-- No results message -->
                    <div id="noResultsMessage" class="hidden text-center py-12">
                        <div class="text-gray-500 text-lg mb-2">
                            <i class="fas fa-search text-4xl mb-4"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No categories found</h3>
                        <p class="text-gray-500">Try adjusting your search terms or browse all categories.</p>
                        <button onclick="clearSearch()" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Clear Search
                        </button>
                    </div>

                    @error('interests')
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                    </div>
                    @enderror

                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200">
                        <button type="button" onclick="skipInterests()" class="bg-gray-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                            <i class="fas fa-arrow-left me-2"></i>Skip for Now
                        </button>
                        <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors">
                            <i class="fas fa-save me-2"></i>Save & Continue
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
    .category-item.selected {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border-color: #4facfe;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(79, 172, 254, 0.3);
    }

    .category-item.selected .category-name {
        color: white;
    }

    .category-item.selected .category-description {
        color: rgba(255, 255, 255, 0.9);
    }
</style>

<script>
function toggleCategory(categoryId) {
    const checkbox = document.getElementById('category_' + categoryId);
    const categoryItem = checkbox.closest('.category-item');

    checkbox.checked = !checkbox.checked;

    if (checkbox.checked) {
        categoryItem.classList.add('selected');
    } else {
        categoryItem.classList.remove('selected');
    }

    updateSelectionCounter();
}

function updateSelectionCounter() {
    const checkboxes = document.querySelectorAll('input[name="interests[]"]');
    const checkedBoxes = document.querySelectorAll('input[name="interests[]"]:checked');
    const counter = document.getElementById('selectedCount');

    counter.textContent = checkedBoxes.length;
}

// Search functionality
function filterCategories() {
    const searchTerm = document.getElementById('categorySearch').value.toLowerCase();
    const categoryItems = document.querySelectorAll('.category-item');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const clearButton = document.getElementById('clearSearch');
    let visibleCount = 0;

    categoryItems.forEach(item => {
        const categoryName = item.getAttribute('data-category-name');
        const categoryDescription = item.getAttribute('data-category-description');

        if (categoryName.includes(searchTerm) || categoryDescription.includes(searchTerm)) {
            item.style.display = 'block';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    // Show/hide no results message
    if (visibleCount === 0 && searchTerm.length > 0) {
        noResultsMessage.classList.remove('hidden');
    } else {
        noResultsMessage.classList.add('hidden');
    }

    // Show/hide clear button
    if (searchTerm.length > 0) {
        clearButton.classList.remove('hidden');
    } else {
        clearButton.classList.add('hidden');
    }
}

// Clear search function
function clearSearch() {
    document.getElementById('categorySearch').value = '';
    filterCategories();
    document.getElementById('categorySearch').focus();
}

// Skip interests function
function skipInterests() {
    document.getElementById('skipForm').submit();
}

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    // Set initial state for pre-selected items
    const checkboxes = document.querySelectorAll('input[name="interests[]"]');
    checkboxes.forEach(checkbox => {
        const categoryItem = checkbox.closest('.category-item');
        if (checkbox.checked) {
            categoryItem.classList.add('selected');
        }
    });

    updateSelectionCounter();

    // Add search input event listeners
    const searchInput = document.getElementById('categorySearch');
    searchInput.addEventListener('input', filterCategories);
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Escape') {
            clearSearch();
        }
    });
});
</script>
@endsection
