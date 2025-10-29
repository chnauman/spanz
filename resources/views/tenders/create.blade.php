@extends('layouts.admin')

@section('title', 'Post a Tender - SPANZ')

@section('content')
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
                <div class="border border-gray-300 p-3 sm:p-4 lg:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                        <h1 class="text-xl sm:text-2xl font-bold">Submit your Purchasing Request (RFX)</h1>
                    </div>
        <form action="{{ route('tenders.store') }}" method="post" class="space-y-4 sm:space-y-6" enctype="multipart/form-data">
            @csrf
            
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                    {{ session('error') }}
                </div>
            @endif
                        <div class="mt-6 sm:mt-8 lg:mt-10">
                <label for="request_type" class="block text-sm font-medium text-gray-700 mb-2">Request Type <span class="text-red-500">*</span></label>
                <select id="request_type" name="request_type" required
                    class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white @error('request_type') border-red-300 @enderror">
                    <option value="">Select request type</option>
                    <option value="rfq" {{ old('request_type') == 'rfq' ? 'selected' : '' }}>Request for Quote (RFQ)</option>
                    <option value="rft" {{ old('request_type') == 'rft' ? 'selected' : '' }}>Request for Tender (RFT)</option>
                    <option value="rfp" {{ old('request_type') == 'rfp' ? 'selected' : '' }}>Request for Proposal (RFP)</option>
                    <option value="eoi" {{ old('request_type') == 'eoi' ? 'selected' : '' }}>Request for Expression of Interest (EOI)</option>
                </select>
                @error('request_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">Estimated Budget <span class="text-red-500">*</span></label>
                <select id="budget" name="budget" required
                                class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white @error('budget') border-red-300 @enderror">
                                <option value="">Select amount</option>
                                <option value="1000" {{ old('budget') == '1000' ? 'selected' : '' }}>Less than 1,000</option>
                                <option value="5000" {{ old('budget') == '5000' ? 'selected' : '' }}>1,000 - 5,000</option>
                                <option value="10000" {{ old('budget') == '10000' ? 'selected' : '' }}>5,000 - 10,000</option>
                                <option value="30000" {{ old('budget') == '30000' ? 'selected' : '' }}>10,000 - 30,000</option>
                                <option value="50000" {{ old('budget') == '50000' ? 'selected' : '' }}>30,000 - 50,000</option>
                                <option value="100000" {{ old('budget') == '100000' ? 'selected' : '' }}>50,000 - 100,000</option>
                                <option value="500000" {{ old('budget') == '500000' ? 'selected' : '' }}>100,000 - 500,000</option>
                                <option value="1000000" {{ old('budget') == '1000000' ? 'selected' : '' }}>500,000 - 1 million</option>
                                <option value="1000001" {{ old('budget') == '1000001' ? 'selected' : '' }}>over 1 million</option>
                            </select>
                            @error('budget')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency <span class="text-red-500">*</span></label>
                            <select id="currency" name="currency" required
                                class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white @error('currency') border-red-300 @enderror">
                                <option value="">Select currency</option>
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }} default>USD (US Dollar)</option>
                            </select>
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">Submission Deadline <span class="text-red-500">*</span></label>
                            <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}" required
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('deadline') border-red-300 @enderror">
                            @error('deadline')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location <span class="text-red-500">*</span></label>
                <select id="location" name="location" required
                                class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white @error('location') border-red-300 @enderror">
                                <option value="">Select Country</option>
                                <option value="australia" {{ old('location') == 'australia' ? 'selected' : '' }}>Australia</option>
                                <option value="new-zealand" {{ old('location') == 'new-zealand' ? 'selected' : '' }}>New Zealand</option>
                                <option value="singapore" {{ old('location') == 'singapore' ? 'selected' : '' }}>Singapore</option>
                                <option value="usa" {{ old('location') == 'usa' ? 'selected' : '' }}>United States</option>
                                <option value="uk" {{ old('location') == 'uk' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="canada" {{ old('location') == 'canada' ? 'selected' : '' }}>Canada</option>
                                <option value="germany" {{ old('location') == 'germany' ? 'selected' : '' }}>Germany</option>
                                <option value="france" {{ old('location') == 'france' ? 'selected' : '' }}>France</option>
                            </select>
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Products or Services <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Short title of Products or services required" required
                    class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder-gray-400 @error('title') border-red-300 @enderror">
                @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                            <textarea id="description" name="description" rows="4" required
                                placeholder="Description or bullet list of Scope of Works and what is required"
                                class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder-gray-400 resize-y min-h-[100px] @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
            <!-- Dynamic Category Rows -->
            <div id="categoryRows">
                <!-- First row with plus icon -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 category-row" data-row="0">
                    <!-- main category -->
                    <div class="mt-2 sm:mt-4 lg:mt-6 required">
                        <select name="categories[0][main_category]" required
                            class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                            <option value="">Select main category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('categories.0.main_category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- sub category -->
                    <div class="mt-2 sm:mt-4 lg:mt-6 required">
                        <select name="categories[0][sub_category]" required
                            class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                            <option value="">Select sub category</option>
                            <option value="electrical" {{ old('categories.0.sub_category') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                            <option value="mechanical" {{ old('categories.0.sub_category') == 'mechanical' ? 'selected' : '' }}>Mechanical</option>
                            <option value="engines" {{ old('categories.0.sub_category') == 'engines' ? 'selected' : '' }}>Engines</option>
                            <option value="avionics" {{ old('categories.0.sub_category') == 'avionics' ? 'selected' : '' }}>Avionics</option>
                            <option value="apus" {{ old('categories.0.sub_category') == 'apus' ? 'selected' : '' }}>Auxiliary Power Units (APUs)</option>
                            <option value="navigation" {{ old('categories.0.sub_category') == 'navigation' ? 'selected' : '' }}>Navigation systems</option>
                            <option value="communication" {{ old('categories.0.sub_category') == 'communication' ? 'selected' : '' }}>Communication systems (radio, satellite)</option>
                        </select>
                    </div>
                    <!-- product type -->
                    <div class="mt-2 sm:mt-4 lg:mt-6 required flex items-end">
                        <select name="categories[0][product_type]" required
                            class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                            <option value="">Select product type</option>
                            <option value="100" {{ old('categories.0.product_type') == '100' ? 'selected' : '' }}>100% of total budget</option>
                            <option value="90" {{ old('categories.0.product_type') == '90' ? 'selected' : '' }}>90% of total budget</option>
                            <option value="80" {{ old('categories.0.product_type') == '80' ? 'selected' : '' }}>80% of total budget</option>
                            <option value="70" {{ old('categories.0.product_type') == '70' ? 'selected' : '' }}>70% of total budget</option>
                            <option value="60" {{ old('categories.0.product_type') == '60' ? 'selected' : '' }}>60% of total budget</option>
                            <option value="50" {{ old('categories.0.product_type') == '50' ? 'selected' : '' }}>50% of total budget</option>
                            <option value="40" {{ old('categories.0.product_type') == '40' ? 'selected' : '' }}>40% of total budget</option>
                            <option value="30" {{ old('categories.0.product_type') == '30' ? 'selected' : '' }}>30% of total budget</option>
                            <option value="20" {{ old('categories.0.product_type') == '20' ? 'selected' : '' }}>20% of total budget</option>
                            <option value="10" {{ old('categories.0.product_type') == '10' ? 'selected' : '' }}>10% of total budget</option>
                            <option value="5" {{ old('categories.0.product_type') == '5' ? 'selected' : '' }}>Less than 10%</option>
                        </select>
                        <button type="button" onclick="addCategoryRow()" class="ml-3 p-2 text-gray-500 hover:text-blue-500 transition-colors flex-shrink-0" title="Add another category">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                            </div>
            </div>
            
            <div>
                <button type="button" onclick="addCategoryRow()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors text-sm sm:text-base">+ Add Another Line</button>
                        </div>
                        
                        <!-- Additional fields for contact information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                                <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email') }}"
                                       class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('contact_email') border-red-300 @enderror">
                                @error('contact_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                                <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}"
                                       class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('contact_phone') border-red-300 @enderror">
                                @error('contact_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">Requirements</label>
                            <textarea id="requirements" name="requirements" rows="3"
                                      class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('requirements') border-red-300 @enderror">{{ old('requirements') }}</textarea>
                            @error('requirements')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <h2 class="mb-4 mt-6 sm:mt-8 text-lg sm:text-xl font-semibold text-gray-800">Attach Project Files</h2>
                            
                            <!-- Custom File Input -->
                            <div class="border-2 border-gray-300 border-dashed h-48 sm:h-60 rounded-md p-4 sm:p-6 w-full flex flex-col items-center justify-center bg-gray-50 hover:bg-gray-100 transition-colors relative">
                                <!-- Hidden actual file input -->
                                <input type="file" name="files[]" id="file" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(this)">
                                
                                <!-- Custom button design -->
                                <div class="text-center">
                                    <svg class="mx-auto h-8 w-8 sm:h-12 sm:w-12 text-gray-400 mb-3 sm:mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    
                                    <p class="text-gray-600 text-base sm:text-lg mb-1 sm:mb-2">
                                        <span class="font-semibold">Click to upload</span> <span class="hidden sm:inline">or drag and drop</span>
                                    </p>
                                    <p class="text-gray-500 text-xs sm:text-sm mb-3 sm:mb-4">PNG, JPG, PDF up to 10MB</p>
                                    
                                    <!-- Custom Choose Files Button -->
                                    <button type="button" class="bg-[#0D6AED] text-white px-4 sm:px-6 py-2 sm:py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium text-sm sm:text-base">
                                        Choose Files
                                    </button>
                                    
                                    <!-- Selected files display -->
                                    <div id="selectedFiles" class="mt-4 text-left hidden">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Selected Files:</h4>
                                        <div id="fileList" class="space-y-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Uploaded Files Display Section -->
                        <div id="uploadedFilesSection" class="mt-6 sm:mt-8 hidden">
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Uploaded Files</h2>
                            <div id="uploadedFilesList" class="space-y-3">
                                <!-- Uploaded files will appear here -->
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 sm:pt-8 border-t border-gray-200">
                <button type="reset" class="w-full sm:w-auto px-6 py-2 sm:py-3 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors text-sm sm:text-base font-medium">
                    Reset Form
                </button>
                            <button type="submit" class="w-full sm:w-auto px-6 py-2 sm:py-3 bg-[#0D6AED] text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors text-sm sm:text-base font-medium">
                                Submit Request
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
<script>
// Dynamic category row functionality
let categoryRowCount = 0;

// Initialize with old input if available
document.addEventListener('DOMContentLoaded', function() {
    const oldCategories = @json(old('categories', []));
    if (oldCategories.length > 1) {
        // Add additional rows for old input
        for (let i = 1; i < oldCategories.length; i++) {
            addCategoryRow();
            // Fill the row with old data
            const row = document.querySelector(`[data-row="${i}"]`);
            if (row) {
                const mainCategorySelect = row.querySelector('select[name*="main_category"]');
                const subCategorySelect = row.querySelector('select[name*="sub_category"]');
                const productTypeSelect = row.querySelector('select[name*="product_type"]');
                
                if (mainCategorySelect && oldCategories[i].main_category) {
                    mainCategorySelect.value = oldCategories[i].main_category;
                }
                if (subCategorySelect && oldCategories[i].sub_category) {
                    subCategorySelect.value = oldCategories[i].sub_category;
                }
                if (productTypeSelect && oldCategories[i].product_type) {
                    productTypeSelect.value = oldCategories[i].product_type;
                }
            }
        }
    }
});

function addCategoryRow() {
    categoryRowCount++;
    const categoryRows = document.getElementById('categoryRows');
    const newRow = document.createElement('div');
    newRow.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 category-row';
    newRow.setAttribute('data-row', categoryRowCount);
    
    newRow.innerHTML = `
        <!-- main category -->
        <div class="mt-2 sm:mt-4 lg:mt-6 required">
            <select name="categories[${categoryRowCount}][main_category]" required
                class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                <option value="">Select main category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <!-- sub category -->
        <div class="mt-2 sm:mt-4 lg:mt-6 required">
            <select name="categories[${categoryRowCount}][sub_category]" required
                class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                <option value="">Select sub category</option>
                <option value="electrical">Electrical</option>
                <option value="mechanical">Mechanical</option>
                <option value="engines">Engines</option>
                <option value="avionics">Avionics</option>
                <option value="apus">Auxiliary Power Units (APUs)</option>
                <option value="navigation">Navigation systems</option>
                <option value="communication">Communication systems (radio, satellite)</option>
            </select>
        </div>
        <!-- product type -->
        <div class="mt-2 sm:mt-4 lg:mt-6 required flex items-end">
            <select name="categories[${categoryRowCount}][product_type]" required
                class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                <option value="">Select product type</option>
                <option value="100">100% of total budget</option>
                <option value="90">90% of total budget</option>
                <option value="80">80% of total budget</option>
                <option value="70">70% of total budget</option>
                <option value="60">60% of total budget</option>
                <option value="50">50% of total budget</option>
                <option value="40">40% of total budget</option>
                <option value="30">30% of total budget</option>
                <option value="20">20% of total budget</option>
                <option value="10">10% of total budget</option>
                <option value="5">Less than 10%</option>
            </select>
            <button type="button" onclick="removeCategoryRow(this)" class="ml-3 p-2 text-gray-500 hover:text-red-500 transition-colors flex-shrink-0" title="Remove category">
                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M14 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M4 7H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M6 7H12H18V18C18 19.6569 16.6569 21 15 21H9C7.34315 21 6 19.6569 6 18V7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    `;
    
    categoryRows.appendChild(newRow);
}

function removeCategoryRow(button) {
    const row = button.closest('.category-row');
    row.remove();
}

// File handling functionality
function handleFileSelect(input) {
    const files = input.files;
    const uploadedFilesSection = document.getElementById('uploadedFilesSection');
    const uploadedFilesList = document.getElementById('uploadedFilesList');
    
    if (files.length > 0) {
        // Show the uploaded files section
        uploadedFilesSection.classList.remove('hidden');
        uploadedFilesList.innerHTML = ''; // Clear previous files
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const fileSize = (file.size / 1024 / 1024).toFixed(2); // Size in MB
            const fileName = file.name;
            const fileExtension = fileName.split('.').pop().toLowerCase();
            const uploadTime = new Date().toLocaleString();
            
            // Create enhanced file display card
            const fileItem = document.createElement('div');
            fileItem.className = 'bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow';
            fileItem.innerHTML = `
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3 flex-1">
                        <!-- File type icon -->
                        <div class="flex-shrink-0">
                            ${getFileIcon(fileExtension)}
                        </div>
                        
                        <!-- File details -->
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 truncate" title="${fileName}">
                                ${fileName}
                            </h4>
                            <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                                <span class="flex items-center space-x-1">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 110-2h4a1 1 0 011 1v4a1 1 0 11-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>${fileSize} MB</span>
                                </span>
                                <span class="flex items-center space-x-1">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>${uploadTime}</span>
                                </span>
                                <span class="uppercase font-semibold text-blue-600">${fileExtension}</span>
                            </div>
                            
                            <!-- Upload status -->
                            <div class="mt-2 flex items-center space-x-1">
                                <svg class="h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs text-green-600 font-medium">Successfully Uploaded</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-center space-x-2 ml-4">
                        ${isImageFile(fileExtension) ? `
                            <button type="button" onclick="previewFile(${i})" class="text-blue-500 hover:text-blue-700 p-2 rounded-md hover:bg-blue-50 transition-colors" title="Preview">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        ` : ''}
                        <button type="button" onclick="downloadFile(${i})" class="text-green-500 hover:text-green-700 p-2 rounded-md hover:bg-green-50 transition-colors" title="Download">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414L10 14.414 6.293 10.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <button type="button" onclick="removeFile(this, ${i})" class="text-red-500 hover:text-red-700 p-2 rounded-md hover:bg-red-50 transition-colors" title="Remove">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            uploadedFilesList.appendChild(fileItem);
        }
    } else {
        uploadedFilesSection.classList.add('hidden');
    }
}

function getFileIcon(extension) {
    const iconClass = "h-10 w-10";
    
    switch(extension) {
        case 'pdf':
            return `<div class="bg-red-100 p-2 rounded-lg">
                <svg class="${iconClass} text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                </svg>
            </div>`;
        case 'doc':
        case 'docx':
            return `<div class="bg-blue-100 p-2 rounded-lg">
                <svg class="${iconClass} text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                </svg>
            </div>`;
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'webp':
            return `<div class="bg-green-100 p-2 rounded-lg">
                <svg class="${iconClass} text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                </svg>
            </div>`;
        case 'xlsx':
        case 'xls':
            return `<div class="bg-emerald-100 p-2 rounded-lg">
                <svg class="${iconClass} text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                </svg>
            </div>`;
        case 'zip':
        case 'rar':
        case '7z':
            return `<div class="bg-yellow-100 p-2 rounded-lg">
                <svg class="${iconClass} text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                </svg>
            </div>`;
        default:
            return `<div class="bg-gray-100 p-2 rounded-lg">
                <svg class="${iconClass} text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                </svg>
            </div>`;
    }
}

function isImageFile(extension) {
    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
    return imageExtensions.includes(extension);
}

function previewFile(index) {
    const fileInput = document.getElementById('file');
    const file = fileInput.files[index];
    
    if (file && isImageFile(file.name.split('.').pop().toLowerCase())) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Create modal for image preview
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="relative max-w-4xl max-h-full p-4">
                    <button onclick="this.parentElement.parentElement.remove()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <img src="${e.target.result}" alt="${file.name}" class="max-w-full max-h-full rounded-lg shadow-2xl">
                    <p class="text-white text-center mt-2">${file.name}</p>
                </div>
            `;
            document.body.appendChild(modal);
            
            // Close on click outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.remove();
                }
            });
        };
        reader.readAsDataURL(file);
    }
}

function downloadFile(index) {
    const fileInput = document.getElementById('file');
    const file = fileInput.files[index];
    
    if (file) {
        const url = URL.createObjectURL(file);
        const a = document.createElement('a');
        a.href = url;
        a.download = file.name;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
}

function removeFile(button, index) {
    const fileInput = document.getElementById('file');
    const dt = new DataTransfer();
    const files = fileInput.files;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }
    
    fileInput.files = dt.files;
    handleFileSelect(fileInput);
}

// Add drag and drop functionality
const dropArea = document.querySelector('#file').parentElement;

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropArea.classList.add('border-blue-400', 'bg-blue-50');
}

function unhighlight(e) {
    dropArea.classList.remove('border-blue-400', 'bg-blue-50');
}

dropArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    const fileInput = document.getElementById('file');
    fileInput.files = files;
    handleFileSelect(fileInput);
}
</script>
@endpush