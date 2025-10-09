@extends('layouts.app')

@section('content')
<style>
    .interests-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .interests-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
    }
    
    .interests-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 2rem;
        text-align: center;
        position: relative;
    }
    
    .interests-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }
    
    .interests-header h4 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }
    
    .interests-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }
    
    .interests-body {
        padding: 2.5rem;
    }
    
    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .category-item {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid transparent;
        border-radius: 15px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .category-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.5s;
    }
    
    .category-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        border-color: #4facfe;
    }
    
    .category-item:hover::before {
        left: 100%;
    }
    
    .category-item.selected {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border-color: #4facfe;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(79, 172, 254, 0.3);
    }
    
    .category-checkbox {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    
    .category-label {
        display: block;
        cursor: pointer;
        margin: 0;
    }
    
    .category-name {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .category-description {
        font-size: 0.95rem;
        opacity: 0.8;
        line-height: 1.4;
    }
    
    .action-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #e9ecef;
    }
    
    .btn-skip {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
    }
    
    .btn-skip:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(108, 117, 125, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .btn-save {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
        border-radius: 10px;
        color: white;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }
    
    .selection-counter {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: inline-block;
    }
    
    @media (max-width: 768px) {
        .interests-container {
            padding: 1rem 0;
        }
        
        .interests-body {
            padding: 1.5rem;
        }
        
        .category-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 1rem;
        }
        
        .btn-skip, .btn-save {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="interests-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="interests-card">
                    <div class="interests-header">
                        <h4>🎯 Select Your Interests</h4>
                        <p>Choose the categories you're interested in to receive relevant tender notifications and stay updated with opportunities that matter to you.</p>
                    </div>
                    <div class="interests-body">
                        <form method="POST" action="{{ route('user.interests.store') }}" id="interestsForm">
                            @csrf
                            
                            <div class="selection-counter" id="selectionCounter">
                                <span id="selectedCount">0</span> categories selected
                            </div>
                            
                            <div class="category-grid">
                                @foreach($categories as $category)
                                <div class="category-item" onclick="toggleCategory({{ $category->id }})">
                                    <input class="category-checkbox" type="checkbox" name="interests[]" 
                                           value="{{ $category->id }}" id="category_{{ $category->id }}"
                                           {{ in_array($category->id, old('interests', [])) ? 'checked' : '' }}>
                                    <label class="category-label" for="category_{{ $category->id }}">
                                        <span class="category-name">{{ $category->name }}</span>
                                        @if($category->description)
                                        <span class="category-description">{{ $category->description }}</span>
                                        @endif
                                    </label>
                                </div>
                                @endforeach
                            </div>

                            @error('interests')
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                            </div>
                            @enderror

                            <div class="action-buttons">
                                <a href="{{ route('dashboard') }}" class="btn-skip">
                                    <i class="fas fa-arrow-left me-2"></i>Skip for Now
                                </a>
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-save me-2"></i>Save & Continue
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
