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
                <form method="POST" action="{{ route('user.interests.store') }}" id="interestsForm">
                    @csrf

                    <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm font-medium mb-6 inline-block" id="selectionCounter">
                        <span id="selectedCount">0</span> categories selected
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @foreach($categories as $category)
                        <div class="category-item bg-gray-50 border-2 border-transparent rounded-lg p-6 transition-all duration-300 cursor-pointer hover:shadow-lg hover:border-blue-300" onclick="toggleCategory({{ $category->id }})">
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
});
</script>
@endsection
