@extends('layouts.admin')

@section('title', 'Post a RFX - SPANZ')

@section('content')
            <!-- Include Company Registration Modal -->
            @include('components.company-registration-modal')

            <!-- Soft modal: category % validation (replaces browser confirm/alert) -->
            <div id="percentageSoftModal" class="fixed inset-0 hidden flex items-center justify-center p-4" style="z-index: 100002;" aria-hidden="true">
                <div id="percentageSoftModalBackdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
                <div class="relative w-full max-w-md rounded-2xl shadow-2xl overflow-hidden bg-white" role="dialog" aria-modal="true" aria-labelledby="percentageSoftModalTitle">
                    <div class="bg-gradient-to-r from-[#092C48] to-[#0D6AED] text-white px-6 py-4">
                        <h3 id="percentageSoftModalTitle" class="text-lg font-bold">Confirm</h3>
                    </div>
                    <div class="px-6 py-5">
                        <p id="percentageSoftModalMessage" class="text-gray-700 text-sm leading-relaxed"></p>
                        <div id="percentageSoftModalFooterConfirm" class="mt-6 flex flex-wrap justify-end gap-3 hidden">
                            <button type="button" id="percentageSoftModalBtnCancel" class="btn-secondary btn-secondary-sm">Cancel</button>
                            <button type="button" id="percentageSoftModalBtnSubmit" class="btn-primary btn-primary-sm">Submit anyway</button>
                        </div>
                        <div id="percentageSoftModalFooterAlert" class="mt-6 flex justify-end hidden">
                            <button type="button" id="percentageSoftModalBtnOk" class="btn-primary btn-primary-sm">OK</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
                <div class="border border-gray-300 p-3 sm:p-4 lg:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                        <h1 class="text-xl sm:text-2xl font-bold">Submit your Purchasing Request (RFX)</h1>
                    </div>
        
        @if(!$hasCompany)
            <!-- Show message when company is not registered -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Company Registration Required</h3>
                <p class="text-gray-600 mb-6">Please complete your company profile to post tenders on SPANZ.</p>
                <a href="{{ route('company.register') }}" class="inline-block bg-[#0D6AED] text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Complete Company Profile
                </a>
            </div>
        @else
        <form id="tender-create-form" action="{{ route('tenders.store') }}" method="post" class="space-y-4 sm:space-y-6" enctype="multipart/form-data" onsubmit="return handleTenderFormSubmit(event)">
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
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('title') border-red-300 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">Approximate your project spent <span class="text-red-500">*</span></label>
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
                                <option value="AUD" {{ old('currency', 'AUD') == 'AUD' ? 'selected' : '' }} default>AUD (Australian Dollar)</option>
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
                        <div class="space-y-4">
                            <p class="text-sm font-medium text-gray-700">Location where service required <span class="text-red-500">*</span></p>
                            <p class="text-xs text-gray-500 -mt-2 mb-1">Select country, then city. For Australia, choose state or territory first.</p>

                            <div>
                                <label for="tender-country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                <select id="tender-country" name="country_code" required
                                    class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white @error('country_code') border-red-300 @enderror">
                                    <option value="">Select country</option>
                                    @foreach(\App\Models\Tender::locationSlugLabels() as $slug => $label)
                                        <option value="{{ $slug }}" {{ old('country_code', old('location')) == $slug ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('country_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="tender-state-wrap" class="hidden">
                                <label for="tender-state" class="block text-sm font-medium text-gray-700 mb-2">State / territory</label>
                                <select id="tender-state" name="state_id"
                                    class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white @error('state_id') border-red-300 @enderror">
                                    <option value="">Select state</option>
                                </select>
                                @error('state_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tender-city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <select id="tender-city" name="city_id" required
                                    class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white @error('city_id') border-red-300 @enderror">
                                    <option value="">Select city</option>
                                </select>
                                @error('city_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Describe your project <span class="text-red-500">*</span></label>
                            <textarea id="description" name="description" rows="4" required
                                placeholder="Describe your project — scope of work, timeline, or bullet list of what you need"
                                class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder-gray-400 resize-y min-h-[100px] @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
            @php
                // Build a JS-friendly map of { mainCategoryId: [{id, name}, ...] }
                // so subcategory checkboxes can be rendered client-side when
                // the user picks a main category.
                $subcategoriesByCategory = $categories->mapWithKeys(function ($cat) {
                    return [
                        $cat->id => $cat->subcategories->map(fn ($s) => [
                            'id' => $s->id,
                            'name' => $s->name,
                        ])->values()->all(),
                    ];
                });
            @endphp

            <!-- Dynamic Category Rows -->
            <div id="categoryRows">
                <!-- First row -->
                <div class="category-row border border-gray-200 rounded-md p-3 sm:p-4 mb-3" data-row="0">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- main category -->
                        <div class="required">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Main Category <span class="text-red-500">*</span></label>
                            <select name="categories[0][main_category]" required
                                class="main-category-select w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white"
                                data-row="0"
                                onchange="onMainCategoryChange(this)">
                                <option value="">Select main category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('categories.0.main_category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- product type / budget share -->
                        <div class="required flex items-end">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Budget Share <span class="text-red-500">*</span></label>
                                <select name="categories[0][product_type]" required
                                    class="product-type-select w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white"
                                    data-row="0"
                                    onchange="updatePercentageOptions()">
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
                                <div class="percentage-remaining text-xs text-gray-500 mt-1" data-row="0"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Subcategory checkboxes (rendered when a main category is picked) -->
                    <div class="subcategory-wrapper mt-3 hidden" data-row="0">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Subcategories <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-500 font-normal">(select up to 3)</span>
                        </label>
                        <div class="subcategory-checkboxes grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 p-3 border border-gray-200 rounded-md bg-gray-50"
                             data-row="0"></div>
                        <p class="subcategory-hint text-xs text-gray-500 mt-1">Pick the subcategories that apply to this category.</p>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="button" id="addCategoryRowBtn" onclick="addCategoryRow()" class="btn-primary btn-primary-sm">+ Add another category</button>
                <p class="text-xs text-gray-500 mt-1">You can add up to 3 main categories. Each category supports up to 3 subcategories.</p>
                <div id="percentageSummary" class="mt-2 text-sm text-gray-600"></div>
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
                            <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">Comments</label>
                            <textarea id="comments" name="requirements" rows="3" placeholder="Optional notes or comments for suppliers"
                                      class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('requirements') border-red-300 @enderror">{{ old('requirements') }}</textarea>
                            @error('requirements')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <h2 class="mb-2 mt-6 sm:mt-8 text-lg sm:text-xl font-semibold text-gray-800">Attach project files</h2>
                            <p class="text-sm text-gray-600 mb-4">You can upload <strong>multiple files</strong> at once or add more in several steps. PDF, Word, or images — max <strong>10MB per file</strong>.</p>

                            <div id="tender-drop-zone" class="border-2 border-gray-300 border-dashed rounded-md w-full flex flex-col bg-gray-50 hover:bg-gray-100 transition-colors min-h-[12rem] sm:min-h-[14rem] overflow-hidden">
                                {{-- Top strip only: invisible input so previews/buttons below stay clickable --}}
                                <div id="tender-drop-zone-trigger" class="relative shrink-0 px-4 sm:px-6 pt-5 pb-4 border-b border-dashed border-gray-300">
                                    <input type="file" name="files[]" id="tender-files-input" multiple
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.webp,application/pdf,image/*"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-[1]"
                                        aria-label="Upload project files">

                                    <div class="relative z-[2] pointer-events-none text-center px-2 max-w-lg mx-auto">
                                        <svg class="mx-auto h-8 w-8 sm:h-10 sm:w-10 text-gray-400 mb-2 sm:mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="text-gray-600 text-sm sm:text-base mb-1">
                                            <span class="font-semibold">Click to upload</span> <span class="hidden sm:inline">or drag and drop</span>
                                        </p>
                                        <p class="text-gray-500 text-xs sm:text-sm mb-3">PNG, JPG, PDF, DOC/DOCX — up to 10MB each · Multiple files allowed</p>
                                        <button type="button" id="tender-files-choose-btn"
                                            class="btn-primary btn-primary-sm pointer-events-auto">
                                            Choose files
                                        </button>
                                    </div>
                                </div>

                                {{-- Previews live inside the same dashed field --}}
                                <div id="tender-files-list-wrap" class="hidden flex-1 flex flex-col min-h-0 w-full px-4 sm:px-6 pb-4 pt-3">
                                    <p class="text-xs font-semibold text-gray-700 mb-2">
                                        <span id="tender-files-count">0</span> file(s) ready to upload
                                    </p>
                                    <div id="uploadedFilesList" class="space-y-2 max-h-52 sm:max-h-64 overflow-y-auto overscroll-contain pr-0.5"></div>
                                </div>
                            </div>

                            <p id="tender-files-error" class="mt-2 text-sm text-red-600 hidden"></p>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 sm:pt-8 border-t border-gray-200">
                <button type="reset" class="btn-secondary w-full sm:w-auto">
                    Reset Form
                </button>
                            <button type="submit" class="btn-primary w-full sm:w-auto">
                                Submit Request
                            </button>
                        </div>
                        
                    </form>
        @endif
                </div>
            </div>
        </div>
@endsection

@push('scripts')
<script>
window.SPANZ_LOCATION_DATA = @json($locationData ?? []);
const TENDER_OLD_STATE_ID = @json(old('state_id'));
const TENDER_OLD_CITY_ID = @json(old('city_id'));

function tenderFillStateOptions(slug) {
    const data = window.SPANZ_LOCATION_DATA[slug];
    const sel = document.getElementById('tender-state');
    if (!sel || !data) return;
    sel.innerHTML = '<option value="">Select state</option>';
    (data.states || []).forEach(function(s) {
        const opt = document.createElement('option');
        opt.value = String(s.id);
        opt.textContent = s.name;
        sel.appendChild(opt);
    });
}

function tenderFillCityOptions(slug, stateId) {
    const data = window.SPANZ_LOCATION_DATA[slug];
    const sel = document.getElementById('tender-city');
    if (!sel || !data) return;
    sel.innerHTML = '<option value="">Select city</option>';
    let rows = [];
    if (data.requires_state) {
        if (!stateId) {
            sel.innerHTML = '<option value="">Select state first</option>';
            return;
        }
        rows = data.cities_by_state[String(stateId)] || data.cities_by_state[stateId] || [];
    } else {
        rows = data.cities_flat || [];
    }
    rows.forEach(function(c) {
        const opt = document.createElement('option');
        opt.value = String(c.id);
        opt.textContent = (!data.requires_state && c.state_name)
            ? (c.name + ' (' + c.state_name + ')')
            : c.name;
        sel.appendChild(opt);
    });
}

function tenderSyncLocationFields(isInitial) {
    const slug = document.getElementById('tender-country')?.value || '';
    const data = window.SPANZ_LOCATION_DATA[slug];
    const stateWrap = document.getElementById('tender-state-wrap');
    const stateSel = document.getElementById('tender-state');
    const citySel = document.getElementById('tender-city');

    if (!stateWrap || !stateSel || !citySel) return;

    if (!slug || !data) {
        citySel.innerHTML = '<option value="">Select country first</option>';
        citySel.disabled = true;
        return;
    }

    citySel.disabled = false;

    if (data.requires_state) {
        stateWrap.classList.remove('hidden');
        stateSel.disabled = false;
        stateSel.setAttribute('required', 'required');
        tenderFillStateOptions(slug);
        if (isInitial && TENDER_OLD_STATE_ID) {
            stateSel.value = String(TENDER_OLD_STATE_ID);
        }
        tenderFillCityOptions(slug, stateSel.value);
        if (isInitial && TENDER_OLD_CITY_ID) {
            citySel.value = String(TENDER_OLD_CITY_ID);
        }
    } else {
        stateWrap.classList.add('hidden');
        stateSel.removeAttribute('required');
        stateSel.disabled = true;
        stateSel.innerHTML = '<option value="">—</option>';
        tenderFillCityOptions(slug, null);
        if (isInitial && TENDER_OLD_CITY_ID) {
            citySel.value = String(TENDER_OLD_CITY_ID);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const countryEl = document.getElementById('tender-country');
    const stateEl = document.getElementById('tender-state');
    if (countryEl && window.SPANZ_LOCATION_DATA) {
        countryEl.addEventListener('change', function() {
            tenderSyncLocationFields(false);
        });
        if (stateEl) {
            stateEl.addEventListener('change', function() {
                const slug = countryEl.value;
                tenderFillCityOptions(slug, stateEl.value);
            });
        }
        tenderSyncLocationFields(true);
    }
});

// Show company registration modal if company is not registered
document.addEventListener('DOMContentLoaded', function() {
    @if(!$hasCompany)
        // Show modal automatically when page loads
        setTimeout(function() {
            if (typeof openCompanyRegistrationModal === 'function') {
                openCompanyRegistrationModal();
            }
        }, 500);
    @endif
});

// Dynamic category row functionality
let categoryRowCount = 0;

// Calculate total percentage used
function getTotalPercentage() {
    let total = 0;
    document.querySelectorAll('.product-type-select').forEach(select => {
        const value = parseInt(select.value) || 0;
        total += value;
    });
    return total;
}

// Get remaining percentage
function getRemainingPercentage() {
    return 100 - getTotalPercentage();
}

// Update percentage options for all rows based on remaining percentage
function updatePercentageOptions() {
    const totalUsed = getTotalPercentage();
    const remaining = getRemainingPercentage();
    
    // Update all percentage dropdowns
    document.querySelectorAll('.product-type-select').forEach(select => {
        const currentValue = parseInt(select.value) || 0;
        const rowIndex = select.getAttribute('data-row');
        const options = select.querySelectorAll('option');
        
        // Store current selection
        let selectedValue = currentValue;
        
        // Update options based on remaining percentage (excluding current row)
        const otherRowsTotal = totalUsed - currentValue;
        const availableForThisRow = 100 - otherRowsTotal;
        
        options.forEach(option => {
            if (option.value === '') return; // Skip empty option
            
            const optionValue = parseInt(option.value);
            
            // Enable/disable options based on available percentage
            if (optionValue <= availableForThisRow) {
                option.disabled = false;
            } else {
                option.disabled = true;
                // If current selection exceeds available, clear it
                if (selectedValue === optionValue) {
                    selectedValue = '';
                }
            }
        });
        
        // If current selection is invalid, clear it
        if (selectedValue > availableForThisRow && selectedValue > 0) {
            select.value = '';
            selectedValue = 0;
        }
        
        // Update remaining percentage display
        const remainingDisplay = document.querySelector(`.percentage-remaining[data-row="${rowIndex}"]`);
        if (remainingDisplay) {
            const rowRemaining = availableForThisRow - (selectedValue || 0);
            if (rowRemaining >= 0) {
                remainingDisplay.textContent = `${rowRemaining}% remaining`;
                remainingDisplay.className = 'percentage-remaining text-xs text-gray-500 mt-1';
            } else {
                remainingDisplay.textContent = 'Exceeds limit';
                remainingDisplay.className = 'percentage-remaining text-xs text-red-500 mt-1';
            }
        }
    });
    
    // Update summary
    updatePercentageSummary();
    
    // Update add button state
    updateAddButtonState();
}

// Update percentage summary
function updatePercentageSummary() {
    const totalUsed = getTotalPercentage();
    const remaining = getRemainingPercentage();
    const summaryEl = document.getElementById('percentageSummary');
    
    if (summaryEl) {
        if (totalUsed === 0) {
            summaryEl.textContent = 'Total: 0% | Remaining: 100%';
            summaryEl.className = 'mt-2 text-sm text-gray-600';
        } else if (totalUsed === 100) {
            summaryEl.textContent = 'Total: 100% | Complete ✓';
            summaryEl.className = 'mt-2 text-sm text-green-600 font-semibold';
        } else if (totalUsed > 100) {
            summaryEl.textContent = `Total: ${totalUsed}% | Exceeds 100% by ${totalUsed - 100}%`;
            summaryEl.className = 'mt-2 text-sm text-red-600 font-semibold';
        } else {
            summaryEl.textContent = `Total: ${totalUsed}% | Remaining: ${remaining}%`;
            summaryEl.className = 'mt-2 text-sm text-gray-600';
        }
    }
}

// Maximum number of category rows allowed on a tender.
const MAX_CATEGORY_ROWS = 3;
// Maximum number of subcategory checkboxes that may be checked per row.
const MAX_SUBCATEGORIES_PER_ROW = 3;

// Subcategories grouped by main category id (rendered server-side).
const SUBCATEGORIES_BY_CATEGORY = @json($subcategoriesByCategory ?? new \stdClass());

// Update add button state. The "+ Add another category" button is hidden
// entirely once 2 categories have been added (per business rule), and is
// disabled when the running total has already reached 100% of the budget.
function updateAddButtonState() {
    const addBtn = document.getElementById('addCategoryRowBtn');
    if (!addBtn) return;

    const remaining = getRemainingPercentage();
    const rowCount = document.querySelectorAll('#categoryRows .category-row').length;

    if (rowCount >= MAX_CATEGORY_ROWS) {
        // Hide the button completely — no further rows allowed.
        addBtn.style.display = 'none';
        addBtn.disabled = true;
        return;
    }

    addBtn.style.display = '';

    if (remaining <= 0) {
        addBtn.disabled = true;
        addBtn.className = 'btn-primary btn-primary-sm is-disabled';
        addBtn.title = 'Cannot add more categories — 100% of budget already allocated';
    } else {
        addBtn.disabled = false;
        addBtn.className = 'btn-primary btn-primary-sm';
        addBtn.title = 'Add another category';
    }
}

// Render subcategory checkboxes for a given row based on its selected main
// category. If no main category is selected, the wrapper stays hidden.
function renderSubcategoriesForRow(rowEl, preCheckedNames) {
    if (!rowEl) return;
    const rowIndex = rowEl.getAttribute('data-row');
    const mainSelect = rowEl.querySelector('.main-category-select');
    const wrapper = rowEl.querySelector('.subcategory-wrapper');
    const container = rowEl.querySelector('.subcategory-checkboxes');
    if (!mainSelect || !wrapper || !container) return;

    const mainId = mainSelect.value;
    container.innerHTML = '';

    if (!mainId || !SUBCATEGORIES_BY_CATEGORY[mainId] || !SUBCATEGORIES_BY_CATEGORY[mainId].length) {
        wrapper.classList.add('hidden');
        return;
    }

    const subs = SUBCATEGORIES_BY_CATEGORY[mainId];
    const checkedSet = new Set(Array.isArray(preCheckedNames) ? preCheckedNames : []);

    subs.forEach(function (sub) {
        const label = document.createElement('label');
        label.className = 'inline-flex items-center text-xs sm:text-sm text-gray-700 cursor-pointer';

        const cb = document.createElement('input');
        cb.type = 'checkbox';
        cb.name = `categories[${rowIndex}][sub_categories][]`;
        cb.value = sub.name;
        cb.className = 'subcategory-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded';
        if (checkedSet.has(sub.name)) {
            cb.checked = true;
        }
        cb.addEventListener('change', function () {
            enforceMaxSubcategoriesForRow(rowEl, this);
        });

        const span = document.createElement('span');
        span.className = 'ml-2';
        span.textContent = sub.name;

        label.appendChild(cb);
        label.appendChild(span);
        container.appendChild(label);
    });

    wrapper.classList.remove('hidden');
}

// Cap subcategory selections per row at MAX_SUBCATEGORIES_PER_ROW.
function enforceMaxSubcategoriesForRow(rowEl, justChanged) {
    if (!rowEl) return;
    const checkboxes = rowEl.querySelectorAll('.subcategory-checkbox');
    const checked = Array.from(checkboxes).filter(cb => cb.checked);
    if (checked.length > MAX_SUBCATEGORIES_PER_ROW) {
        if (justChanged && justChanged.checked) {
            justChanged.checked = false;
        }
        openPercentageSoftModal({
            variant: 'alert',
            title: 'Subcategory limit reached',
            message: `You can select a maximum of ${MAX_SUBCATEGORIES_PER_ROW} subcategories per category.`
        });
    }
}

// Triggered when a main category dropdown changes — re-render that row's
// subcategory checkboxes.
function onMainCategoryChange(selectEl) {
    const row = selectEl.closest('.category-row');
    renderSubcategoriesForRow(row, []);
}

// Initialize with old input if available (e.g. after validation failure).
document.addEventListener('DOMContentLoaded', function() {
    // Convert old() object (PHP) which Laravel may emit as an associative
    // structure into a plain array indexed by integer keys.
    const oldCategoriesRaw = @json(old('categories', []));
    const oldCategories = Array.isArray(oldCategoriesRaw)
        ? oldCategoriesRaw
        : Object.keys(oldCategoriesRaw).sort((a, b) => Number(a) - Number(b)).map(k => oldCategoriesRaw[k]);

    // Add additional rows for any beyond the first.
    if (oldCategories.length > 1) {
        for (let i = 1; i < oldCategories.length && i < MAX_CATEGORY_ROWS; i++) {
            addCategoryRow();
            const row = document.querySelector(`.category-row[data-row="${i}"]`);
            if (!row) continue;

            const mainSelect = row.querySelector('select[name*="main_category"]');
            const productTypeSelect = row.querySelector('select[name*="product_type"]');

            if (mainSelect && oldCategories[i].main_category) {
                mainSelect.value = oldCategories[i].main_category;
            }
            if (productTypeSelect && oldCategories[i].product_type) {
                productTypeSelect.value = oldCategories[i].product_type;
            }

            // Render subcategory checkboxes with the previously-checked items
            // preserved.
            const oldSubs = Array.isArray(oldCategories[i].sub_categories)
                ? oldCategories[i].sub_categories
                : (oldCategories[i].sub_categories
                    ? Object.values(oldCategories[i].sub_categories)
                    : []);
            renderSubcategoriesForRow(row, oldSubs);
        }
    }

    // Render the FIRST row's subcategory checkboxes if a main category was
    // pre-selected (either from old input or because the dropdown had a
    // default value).
    const firstRow = document.querySelector('.category-row[data-row="0"]');
    if (firstRow) {
        const firstMainSelect = firstRow.querySelector('.main-category-select');
        if (firstMainSelect && firstMainSelect.value) {
            const oldSubs = oldCategories[0] && Array.isArray(oldCategories[0].sub_categories)
                ? oldCategories[0].sub_categories
                : (oldCategories[0] && oldCategories[0].sub_categories
                    ? Object.values(oldCategories[0].sub_categories)
                    : []);
            renderSubcategoriesForRow(firstRow, oldSubs);
        }
    }

    // Initialize percentage options/summary and add-button visibility.
    setTimeout(function() {
        updatePercentageOptions();
    }, 100);
});

let percentageModalOnConfirm = null;

function closePercentageSoftModal() {
    const wrap = document.getElementById('percentageSoftModal');
    if (!wrap) return;
    wrap.classList.add('hidden');
    wrap.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    percentageModalOnConfirm = null;
}

function openPercentageSoftModal(opts) {
    const wrap = document.getElementById('percentageSoftModal');
    const titleEl = document.getElementById('percentageSoftModalTitle');
    const msgEl = document.getElementById('percentageSoftModalMessage');
    const footConfirm = document.getElementById('percentageSoftModalFooterConfirm');
    const footAlert = document.getElementById('percentageSoftModalFooterAlert');
    if (!wrap || !titleEl || !msgEl || !footConfirm || !footAlert) return;

    titleEl.textContent = opts.title || 'Notice';
    msgEl.textContent = opts.message || '';

    if (opts.variant === 'confirm') {
        footConfirm.classList.remove('hidden');
        footAlert.classList.add('hidden');
        percentageModalOnConfirm = typeof opts.onConfirm === 'function' ? opts.onConfirm : null;
    } else {
        footConfirm.classList.add('hidden');
        footAlert.classList.remove('hidden');
        percentageModalOnConfirm = null;
    }

    wrap.classList.remove('hidden');
    wrap.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
}

function handleTenderFormSubmit(e) {
    const form = e.target;
    if (form.id !== 'tender-create-form') {
        return true;
    }

    const total = getTotalPercentage();

    if (total > 100) {
        e.preventDefault();
        openPercentageSoftModal({
            variant: 'alert',
            title: 'Total exceeds 100%',
            message: `Total percentage is ${total}%, which exceeds 100%. Please adjust your selections.`
        });
        return false;
    }

    if (total < 100) {
        e.preventDefault();
        const remaining = 100 - total;
        openPercentageSoftModal({
            variant: 'confirm',
            title: 'Incomplete category allocation',
            message: `Total percentage is ${total}%. You have ${remaining}% remaining. Do you want to submit anyway?`,
            onConfirm: function () {
                closePercentageSoftModal();
                form.submit();
            }
        });
        return false;
    }

    return true;
}

document.addEventListener('DOMContentLoaded', function () {
    const backdrop = document.getElementById('percentageSoftModalBackdrop');
    const btnCancel = document.getElementById('percentageSoftModalBtnCancel');
    const btnSubmit = document.getElementById('percentageSoftModalBtnSubmit');
    const btnOk = document.getElementById('percentageSoftModalBtnOk');

    if (btnCancel) {
        btnCancel.addEventListener('click', closePercentageSoftModal);
    }
    if (btnOk) {
        btnOk.addEventListener('click', closePercentageSoftModal);
    }
    if (btnSubmit) {
        btnSubmit.addEventListener('click', function () {
            if (typeof percentageModalOnConfirm === 'function') {
                percentageModalOnConfirm();
            } else {
                closePercentageSoftModal();
            }
        });
    }
    if (backdrop) {
        backdrop.addEventListener('click', closePercentageSoftModal);
    }
    document.addEventListener('keydown', function (ev) {
        if (ev.key === 'Escape' && document.getElementById('percentageSoftModal') && !document.getElementById('percentageSoftModal').classList.contains('hidden')) {
            closePercentageSoftModal();
        }
    });
});

function addCategoryRow() {
    const remaining = getRemainingPercentage();
    const existingRows = document.querySelectorAll('#categoryRows .category-row').length;

    // Hard cap: a tender can have at most MAX_CATEGORY_ROWS categories.
    if (existingRows >= MAX_CATEGORY_ROWS) {
        openPercentageSoftModal({
            variant: 'alert',
            title: 'Category limit reached',
            message: `You can add a maximum of ${MAX_CATEGORY_ROWS} categories per tender.`
        });
        return;
    }

    // Soft cap: don't allow adding more rows once the running total has hit 100%.
    if (remaining <= 0) {
        openPercentageSoftModal({
            variant: 'alert',
            title: '100% allocated',
            message: 'Cannot add more categories. Total percentage has reached 100%.'
        });
        return;
    }

    categoryRowCount++;
    const categoryRows = document.getElementById('categoryRows');
    const newRow = document.createElement('div');
    newRow.className = 'category-row border border-gray-200 rounded-md p-3 sm:p-4 mb-3';
    newRow.setAttribute('data-row', categoryRowCount);

    // Get available percentage options based on remaining
    const availableOptions = getAvailablePercentageOptions(remaining);

    newRow.innerHTML = `
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            <!-- main category -->
            <div class="required">
                <label class="block text-sm font-medium text-gray-700 mb-1">Main Category <span class="text-red-500">*</span></label>
                <select name="categories[${categoryRowCount}][main_category]" required
                    class="main-category-select w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white"
                    data-row="${categoryRowCount}"
                    onchange="onMainCategoryChange(this)">
                    <option value="">Select main category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- budget share -->
            <div class="required flex items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Budget Share <span class="text-red-500">*</span></label>
                    <select name="categories[${categoryRowCount}][product_type]" required
                        class="product-type-select w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white"
                        data-row="${categoryRowCount}"
                        onchange="updatePercentageOptions()">
                        <option value="">Select product type</option>
                        ${availableOptions}
                    </select>
                    <div class="percentage-remaining text-xs text-gray-500 mt-1" data-row="${categoryRowCount}">${remaining}% remaining</div>
                </div>
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
        </div>

        <!-- Subcategory checkboxes -->
        <div class="subcategory-wrapper mt-3 hidden" data-row="${categoryRowCount}">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Subcategories <span class="text-red-500">*</span>
                <span class="text-xs text-gray-500 font-normal">(select up to ${MAX_SUBCATEGORIES_PER_ROW})</span>
            </label>
            <div class="subcategory-checkboxes grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 p-3 border border-gray-200 rounded-md bg-gray-50"
                 data-row="${categoryRowCount}"></div>
            <p class="subcategory-hint text-xs text-gray-500 mt-1">Pick the subcategories that apply to this category.</p>
        </div>
    `;

    categoryRows.appendChild(newRow);

    // Refresh button state (this may hide the add button if the new row was the 2nd one)
    updatePercentageOptions();
}

// Get available percentage options HTML based on remaining percentage
function getAvailablePercentageOptions(remaining) {
    const percentages = [100, 90, 80, 70, 60, 50, 40, 30, 20, 10, 5];
    let options = '';
    
    percentages.forEach(percent => {
        if (percent <= remaining) {
            const label = percent === 5 ? 'Less than 10%' : `${percent}% of total budget`;
            options += `<option value="${percent}">${label}</option>`;
        } else {
            const label = percent === 5 ? 'Less than 10%' : `${percent}% of total budget`;
            options += `<option value="${percent}" disabled>${label}</option>`;
        }
    });
    
    return options;
}

function removeCategoryRow(button) {
    const row = button.closest('.category-row');
    row.remove();

    // Recalculate percentages and refresh add-button visibility after removal.
    updatePercentageOptions();
}

// --- Multiple file uploads: merged list synced to input for form submit ---
const MAX_FILE_BYTES = 10 * 1024 * 1024;
const ALLOWED_EXT = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'webp'];

let tenderFileList = [];
let tenderPreviewObjectUrls = [];

function escHtml(s) {
    return String(s)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function tenderFilesShowError(msg) {
    const el = document.getElementById('tender-files-error');
    if (!el) return;
    if (msg) {
        el.textContent = msg;
        el.classList.remove('hidden');
    } else {
        el.textContent = '';
        el.classList.add('hidden');
    }
}

function syncTenderFilesToInput() {
    const input = document.getElementById('tender-files-input');
    if (!input) return;
    const dt = new DataTransfer();
    tenderFileList.forEach(function(f) { dt.items.add(f); });
    input.files = dt.files;
}

function fileAllowed(file) {
    const ext = file.name.split('.').pop().toLowerCase();
    if (!ALLOWED_EXT.includes(ext)) {
        return 'Not allowed: ' + file.name + ' (use PDF, DOC/DOCX, or common images)';
    }
    if (file.size > MAX_FILE_BYTES) {
        return 'Too large: ' + file.name + ' (max 10MB per file)';
    }
    return null;
}

function addTenderFiles(newFiles) {
    tenderFilesShowError('');
    for (let i = 0; i < newFiles.length; i++) {
        const file = newFiles[i];
        const err = fileAllowed(file);
        if (err) {
            tenderFilesShowError(err);
            continue;
        }
        const dup = tenderFileList.some(function(f) {
            return f.name === file.name && f.size === file.size;
        });
        if (!dup) {
            tenderFileList.push(file);
        }
    }
    syncTenderFilesToInput();
    renderTenderFileListUI();
}

function renderTenderFileListUI() {
    const wrap = document.getElementById('tender-files-list-wrap');
    const list = document.getElementById('uploadedFilesList');
    const countEl = document.getElementById('tender-files-count');
    if (!wrap || !list) return;

    tenderPreviewObjectUrls.forEach(function(u) {
        try { URL.revokeObjectURL(u); } catch (e) {}
    });
    tenderPreviewObjectUrls = [];

    if (tenderFileList.length === 0) {
        wrap.classList.add('hidden');
        list.innerHTML = '';
        if (countEl) countEl.textContent = '0';
        return;
    }

    wrap.classList.remove('hidden');
    if (countEl) countEl.textContent = String(tenderFileList.length);
    list.innerHTML = '';

    tenderFileList.forEach(function(file, i) {
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        const fileName = escHtml(file.name);
        const fileExtension = file.name.split('.').pop().toLowerCase();

        let thumbHtml = '';
        if (isImageFile(fileExtension)) {
            const objUrl = URL.createObjectURL(file);
            tenderPreviewObjectUrls.push(objUrl);
            thumbHtml = '<img src="' + objUrl + '" alt="" class="h-14 w-14 shrink-0 rounded-lg object-cover border border-gray-200 bg-white shadow-sm" />';
        } else {
            thumbHtml = '<div class="flex-shrink-0">' + getFileIcon(fileExtension) + '</div>';
        }

        const fileItem = document.createElement('div');
        fileItem.className = 'bg-white border border-gray-200 rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow';
        fileItem.innerHTML = `
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start space-x-3 flex-1 min-w-0">
                        ${thumbHtml}
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 truncate" title="${fileName}">${fileName}</h4>
                            <div class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500">
                                <span>${fileSize} MB</span>
                                <span class="uppercase font-semibold text-blue-600">${fileExtension}</span>
                            </div>
                            <div class="mt-2 flex items-center space-x-1">
                                <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                <span class="text-xs text-green-700 font-medium">Ready to upload with your request</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1 shrink-0">
                        ${isImageFile(fileExtension) ? `
                            <button type="button" onclick="previewFile(${i})" class="text-blue-500 hover:text-blue-700 p-2 rounded-md hover:bg-blue-50 transition-colors" title="Preview">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                            </button>` : ''}
                        <button type="button" onclick="downloadFile(${i})" class="text-green-600 hover:text-green-800 p-2 rounded-md hover:bg-green-50 transition-colors" title="Download copy">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414L10 14.414 6.293 10.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                        <button type="button" onclick="removeFile(this, ${i})" class="text-red-500 hover:text-red-700 p-2 rounded-md hover:bg-red-50 transition-colors" title="Remove from list">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                </div>`;
        list.appendChild(fileItem);
    });
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
    const file = tenderFileList[index];
    if (file && isImageFile(file.name.split('.').pop().toLowerCase())) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="relative max-w-4xl max-h-full p-4">
                    <button type="button" onclick="this.closest('.fixed').remove()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <img src="${e.target.result}" alt="" class="max-w-full max-h-full rounded-lg shadow-2xl">
                    <p class="text-white text-center mt-2"></p>
                </div>
            `;
            modal.querySelector('p').textContent = file.name;
            modal.querySelector('img').alt = file.name;
            document.body.appendChild(modal);
            modal.addEventListener('click', function(ev) {
                if (ev.target === modal) modal.remove();
            });
        };
        reader.readAsDataURL(file);
    }
}

function downloadFile(index) {
    const file = tenderFileList[index];
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
    tenderFileList.splice(index, 1);
    syncTenderFilesToInput();
    renderTenderFileListUI();
}

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function highlightDropZone(e) {
    const dropArea = document.getElementById('tender-drop-zone');
    if (dropArea) dropArea.classList.add('border-blue-400', 'bg-blue-50');
}

function unhighlightDropZone(e) {
    const dropArea = document.getElementById('tender-drop-zone');
    if (dropArea) dropArea.classList.remove('border-blue-400', 'bg-blue-50');
}

function handleDrop(e) {
    const dt = e.dataTransfer;
    if (dt.files && dt.files.length) {
        addTenderFiles(Array.from(dt.files));
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('tender-files-input');
    const chooseBtn = document.getElementById('tender-files-choose-btn');
    const dropArea = document.getElementById('tender-drop-zone');
    if (chooseBtn && fileInput) {
        chooseBtn.addEventListener('click', function(ev) {
            ev.preventDefault();
            ev.stopPropagation();
            fileInput.click();
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length) {
                addTenderFiles(Array.from(this.files));
            }
        });
    }

    const tenderForm = document.getElementById('tender-create-form');
    if (tenderForm) {
        tenderForm.addEventListener('reset', function() {
            tenderFileList = [];
            syncTenderFilesToInput();
            renderTenderFileListUI();
            tenderFilesShowError('');
        });
        tenderForm.addEventListener('submit', function() {
            syncTenderFilesToInput();
        });
    }

    if (dropArea) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function(eventName) {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        ['dragenter', 'dragover'].forEach(function(eventName) {
            dropArea.addEventListener(eventName, highlightDropZone, false);
        });
        ['dragleave', 'drop'].forEach(function(eventName) {
            dropArea.addEventListener(eventName, unhighlightDropZone, false);
        });
        dropArea.addEventListener('drop', handleDrop, false);
    }
});
</script>
@endpush