@extends('layouts.admin')

@section('title', 'Strengthen Business Profile - SPANZ')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Strengthen Your Business Profile</h1>
            <p class="mt-1 text-sm sm:text-base text-gray-600">
                Update or add more details about your company to receive better tender matches. You can skip this step anytime.
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif
        
        <div id="profile-toast" class="hidden fixed bottom-6 right-6 z-50 max-w-sm px-4 py-3 rounded-lg shadow-lg bg-green-600 text-white text-sm">
            <span id="profile-toast-message">Saved successfully.</span>
        </div>

        <form id="company-profile-form" method="POST" action="{{ route('company.register.store') }}" class="space-y-6">
                @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left card -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 sm:p-6 lg:p-7 space-y-4">
                    <div class="border-b border-gray-200 pb-3 mb-2">
                        <h2 class="text-lg font-semibold text-gray-900">Company & Contact Details</h2>
                        <p class="mt-1 text-xs sm:text-sm text-gray-500">Core details used across your account.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="first" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" id="first" name="first" required
                               value="{{ old('first', $firstName ?? '') }}"
                               class="w-full px-3 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('first') border-red-300 @enderror">
                        @error('first')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="last" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" id="last" name="last" required
                               value="{{ old('last', $lastName ?? '') }}"
                               class="w-full px-3 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('last') border-red-300 @enderror">
                        @error('last')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="company" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Company Name</label>
                        <input type="text" id="company" name="company" required
                               value="{{ old('company', $companyDetail->company_name ?? '') }}"
                               class="w-full px-3 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('company') border-red-300 @enderror">
                        @error('company')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="comp" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Company's Main Industry (Optional)</label>
                        <select id="comp" name="comp"
                            class="w-full px-3 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="">Select an industry</option>
                            <option value="aerospace-defense" {{ old('comp', $companyDetail->comp ?? '') == 'aerospace-defense' ? 'selected' : '' }}>Aerospace & Defense</option>
                            <option value="agriculture">Agriculture & Food</option>
                            <option value="automotive">Automotive</option>
                            <option value="chemicals">Chemicals & Materials</option>
                            <option value="construction">Construction & Real Estate</option>
                            <option value="consumer-goods">Consumer Goods</option>
                            <option value="education">Education</option>
                            <option value="electronics">Electronics & Technology</option>
                            <option value="energy">Energy & Utilities</option>
                            <option value="financial">Financial Services</option>
                            <option value="healthcare">Healthcare & Medical</option>
                            <option value="hospitality">Hospitality & Tourism</option>
                            <option value="manufacturing">Manufacturing</option>
                            <option value="media">Media & Entertainment</option>
                            <option value="mining">Mining & Metals</option>
                            <option value="retail">Retail & E-commerce</option>
                            <option value="software">Software & IT Services</option>
                            <option value="telecommunications">Telecommunications</option>
                            <option value="transportation">Transportation & Logistics</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    </div>
                    <div>
                        <label for="website" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Company Website</label>
                        <input type="text" id="website" name="website"
                               value="{{ old('website', $companyDetail->website ?? '') }}"
                               class="w-full px-3 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('website') border-red-300 @enderror">
                    @error('website')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    </div>

                    <div>
                        <label class="inline-flex items-center text-xs sm:text-sm text-gray-600">
                            <input type="checkbox" id="check" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2">I don't have a website</span>
                        </label>
                    </div>

                    <div>
                        <label for="objective" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">What is your main objective on SPANZ?</label>
                        @php
                            // We stored this in company_details.description originally
                            $savedObjective = old('objective', $companyDetail->description ?? '');
                        @endphp
                        <select id="objective" name="objective" required
                            class="w-full px-3 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('objective') border-red-300 @enderror">
                            <option value="">Select your main objective</option>
                            <option value="buy-products" {{ $savedObjective === 'buy-products' ? 'selected' : '' }}>Buy products and materials for my business</option>
                            <option value="sell-products" {{ $savedObjective === 'sell-products' ? 'selected' : '' }}>Sell my products and services</option>
                            <option value="find-suppliers" {{ $savedObjective === 'find-suppliers' ? 'selected' : '' }}>Find reliable suppliers and vendors</option>
                            <option value="expand-network" {{ $savedObjective === 'expand-network' ? 'selected' : '' }}>Expand my business network</option>
                            <option value="source-materials" {{ $savedObjective === 'source-materials' ? 'selected' : '' }}>Source raw materials and components</option>
                            <option value="market-research" {{ $savedObjective === 'market-research' ? 'selected' : '' }}>Conduct market research</option>
                            <option value="find-customers" {{ $savedObjective === 'find-customers' ? 'selected' : '' }}>Find new customers and clients</option>
                            <option value="compare-prices" {{ $savedObjective === 'compare-prices' ? 'selected' : '' }}>Compare prices and get quotes</option>
                            <option value="partnership" {{ $savedObjective === 'partnership' ? 'selected' : '' }}>Establish business partnerships</option>
                            <option value="export-import" {{ $savedObjective === 'export-import' ? 'selected' : '' }}>Explore export/import opportunities</option>
                            <option value="other" {{ $savedObjective === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('objective')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t border-gray-200 pt-4 mt-2">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Company Details</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="headquarter_location" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Headquarter / Main Office Location</label>
                                <input type="text" id="headquarter_location" name="headquarter_location"
                                       value="{{ old('headquarter_location', $companyDetail->headquarter_location ?? '') }}"
                               class="w-full px-3 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="employees_range" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Number of Employees</label>
                        <select id="employees_range" name="employees_range"
                            class="w-full px-3 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="">Select</option>
                            <option value="1-10" {{ old('employees_range', $companyDetail->employees_range ?? '') == '1-10' ? 'selected' : '' }}>1 - 10</option>
                            <option value="11-30" {{ old('employees_range', $companyDetail->employees_range ?? '') == '11-30' ? 'selected' : '' }}>11 - 30</option>
                            <option value="31-50" {{ old('employees_range', $companyDetail->employees_range ?? '') == '31-50' ? 'selected' : '' }}>31 - 50</option>
                            <option value="50-100" {{ old('employees_range', $companyDetail->employees_range ?? '') == '50-100' ? 'selected' : '' }}>50 - 100</option>
                            <option value="100-500" {{ old('employees_range', $companyDetail->employees_range ?? '') == '100-500' ? 'selected' : '' }}>100 - 500</option>
                            <option value="500-1000" {{ old('employees_range', $companyDetail->employees_range ?? '') == '500-1000' ? 'selected' : '' }}>500 - 1000</option>
                            <option value="1000-5000" {{ old('employees_range', $companyDetail->employees_range ?? '') == '1000-5000' ? 'selected' : '' }}>1000 - 5000</option>
                            <option value="5000+" {{ old('employees_range', $companyDetail->employees_range ?? '') == '5000+' ? 'selected' : '' }}>Over 5000</option>
                        </select>
                    </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4 mt-2">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Select Your Business Industries &amp; Categories</h3>
                        <p class="text-xs sm:text-sm text-gray-500 mb-3">
                            Must select at least 1 main industry and 1 subcategory. Maximum 3 main industries and 6 subcategories for each.
                        </p>

                        <div class="space-y-4">
                            <!-- Primary Industry -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Primary Industry</label>
                                    <select id="industry_1" name="main_industries[1]"
                                            class="industry-select w-full px-3 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                                            data-target="subcategories_1">
                                        <option value="">Select an industry</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Subcategories</label>
                                    <div id="subcategories_1" class="grid grid-cols-2 gap-2 text-xs sm:text-sm text-gray-700">
                                        <!-- checkboxes injected by JS -->
                                    </div>
                                </div>
                            </div>

                            <!-- Secondary Industry -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Secondary Industry (Optional)</label>
                                    <select id="industry_2" name="main_industries[2]"
                                            class="industry-select w-full px-3 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                                            data-target="subcategories_2">
                                        <option value="">Select an industry</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Subcategories</label>
                                    <div id="subcategories_2" class="grid grid-cols-2 gap-2 text-xs sm:text-sm text-gray-700">
                                        <!-- checkboxes injected by JS -->
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Industry -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Additional Industry (Optional)</label>
                                    <select id="industry_3" name="main_industries[3]"
                                            class="industry-select w-full px-3 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                                            data-target="subcategories_3">
                                        <option value="">Select an industry</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Subcategories</label>
                                    <div id="subcategories_3" class="grid grid-cols-2 gap-2 text-xs sm:text-sm text-gray-700">
                                        <!-- checkboxes injected by JS -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                <!-- Right card -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 sm:p-6 lg:p-7 space-y-4">
                    <div class="border-b border-gray-200 pb-3 mb-2">
                        <h2 class="text-lg font-semibold text-gray-900">Strengthen Your Company Profile</h2>
                        <p class="mt-1 text-xs sm:text-sm text-gray-500">These details help buyers understand your capabilities.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-700 mb-2">Company Type</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @php
                                $companyTypes = [
                                    'OEM Manufacturer',
                                    'Systems Integrator',
                                    'Wholesaler / Distributor',
                                    'Custom Manufacturer',
                                    'Services Company',
                                    'Re Manufacturer',
                                    'Manufacturer\'s Rep',
                                    'Trading Company',
                                    'Retailer',
                                ];
                            @endphp
                                @foreach ($companyTypes as $type)
                                    @php
                                        $checkedCompanyTypes = old('company_types', $selectedCompanyTypes ?? []);
                                    @endphp
                                    <label class="inline-flex items-center text-xs sm:text-sm text-gray-700">
                                        <input type="checkbox" name="company_types[]" value="{{ $type }}"
                                               class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                                               {{ in_array($type, $checkedCompanyTypes) ? 'checked' : '' }}>
                                        <span class="ml-2">{{ $type }}</span>
                                    </label>
                                @endforeach
                        </div>
                    </div>
                    <div>
                        <label for="yearly_revenue_range" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Yearly Revenue</label>
                        <select id="yearly_revenue_range" name="yearly_revenue_range"
                            class="w-full px-3 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            @php $yr = old('yearly_revenue_range', $companyDetail->yearly_revenue_range ?? ''); @endphp
                            <option value="">Select</option>
                            <option value="&lt;1M" {{ $yr == '&lt;1M' ? 'selected' : '' }}>Less than 1,000,000 AUD</option>
                            <option value="1M-5M" {{ $yr == '1M-5M' ? 'selected' : '' }}>1,000,000 – 5,000,000 AUD</option>
                            <option value="5M-10M" {{ $yr == '5M-10M' ? 'selected' : '' }}>5,000,000 – 10,000,000 AUD</option>
                            <option value="10M-30M" {{ $yr == '10M-30M' ? 'selected' : '' }}>10,000,000 – 30,000,000 AUD</option>
                            <option value="30M-50M" {{ $yr == '30M-50M' ? 'selected' : '' }}>30,000,000 – 50,000,000 AUD</option>
                            <option value="50M-100M" {{ $yr == '50M-100M' ? 'selected' : '' }}>50,000,000 – 100,000,000 AUD</option>
                            <option value="100M-500M" {{ $yr == '100M-500M' ? 'selected' : '' }}>100,000,000 – 500,000,000 AUD</option>
                            <option value="500M-1B" {{ $yr == '500M-1B' ? 'selected' : '' }}>500,000,000 – 1 Billion AUD</option>
                            <option value="&gt;1B" {{ $yr == '&gt;1B' ? 'selected' : '' }}>Over 1 Billion AUD</option>
                        </select>
                    </div>
                    </div>

                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-700 mb-2">Quality Certifications</p>
                        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                            @php
                                $certs = ['ISO 9001', 'ISO 27001', 'ISO 42001', 'ISO 14001', 'ISO 50001', 'ISO 26000', 'ISO 45001', 'ISO 22001', 'ISO 17025'];
                                $checkedCerts = old('quality_certifications', $selectedCertifications ?? []);
                            @endphp
                            @foreach ($certs as $cert)
                                <label class="inline-flex items-center text-xs sm:text-sm text-gray-700">
                                    <input type="checkbox" name="quality_certifications[]" value="{{ $cert }}"
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                                           {{ in_array($cert, $checkedCerts) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ $cert }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="brands_represented" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">List Brands Represented</label>
                            <textarea id="brands_represented" name="brands_represented" rows="3"
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('brands_represented', $companyDetail->brands_represented ?? '') }}</textarea>
                        </div>
                        <div>
                            <label for="industry_awards" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">List Industry Awards &amp; Accreditations</label>
                            <textarea id="industry_awards" name="industry_awards" rows="3"
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('industry_awards', $companyDetail->industry_awards ?? '') }}</textarea>
                        </div>
                    </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="industry_memberships" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">List Industry Memberships</label>
                        <textarea id="industry_memberships" name="industry_memberships" rows="3"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('industry_memberships', $companyDetail->industry_memberships ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="unique_value_propositions" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">List Unique Value Propositions</label>
                        <textarea id="unique_value_propositions" name="unique_value_propositions" rows="3"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Example – Faster lead times, customised solutions, same day delivery">{{ old('unique_value_propositions', $companyDetail->unique_value_propositions ?? '') }}</textarea>
                    </div>
                </div>

                <div>
                    <label for="major_projects" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">List Major Projects (Present or Past)</label>
                    <textarea id="major_projects" name="major_projects" rows="3"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('major_projects', $companyDetail->major_projects ?? '') }}</textarea>
                </div>

                <div class="border-t border-gray-200 pt-4 mt-2">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Elevate your Market Presence (Free)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-700 mb-2">Delivery Capabilities</p>
                            @php
                                $regions = ['Australia', 'MENA', 'Sub-Saharan Africa', 'New Zealand', 'Europe', 'APAC', 'North America', 'Latin America', 'Central Asia'];
                                $checkedDelivery = old('delivery_capabilities', $selectedDeliveryRegions ?? []);
                            @endphp
                            <div class="grid grid-cols-1 gap-2">
                                @foreach ($regions as $region)
                                    <label class="inline-flex items-center text-xs sm:text-sm text-gray-700">
                                        <input type="checkbox" name="delivery_capabilities[]" value="{{ $region }}"
                                               class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                                               {{ in_array($region, $checkedDelivery) ? 'checked' : '' }}>
                                        <span class="ml-2">{{ $region }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-700 mb-2">Reps &amp; Office Locations</p>
                            @php
                                $checkedOffices = old('office_locations', $selectedOfficeRegions ?? []);
                            @endphp
                            <div class="grid grid-cols-1 gap-2">
                                @foreach ($regions as $region)
                                    <label class="inline-flex items-center text-xs sm:text-sm text-gray-700">
                                        <input type="checkbox" name="office_locations[]" value="{{ $region }}"
                                               class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                                               {{ in_array($region, $checkedOffices) ? 'checked' : '' }}>
                                        <span class="ml-2">{{ $region }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-between items-center pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('dashboard') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 sm:px-7 py-3 text-sm sm:text-base font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300">
                    Skip for now
                </a>
                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg bg-[#0D6AED] px-5 sm:px-8 py-3 text-sm sm:text-base font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Save Profile
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const websiteInput = document.getElementById('website');
    const noWebsiteCheckbox = document.getElementById('check');

    if (websiteInput && noWebsiteCheckbox) {
        noWebsiteCheckbox.addEventListener('change', function() {
            if (this.checked) {
                websiteInput.value = '';
                websiteInput.disabled = true;
                websiteInput.required = false;
            } else {
                websiteInput.disabled = false;
                websiteInput.required = true;
            }
        });
    }

    // Industry → subcategory dynamic mapping
    const INDUSTRY_SUBCATEGORIES = {
        'Technology / IT': [
            'Software Development',
            'Mobile App Development',
            'Web Development',
            'Cybersecurity',
            'Cloud Services',
            'IT Consulting'
        ],
        'Retail / E-commerce': [
            'Online Store',
            'Marketplace Seller',
            'Wholesale',
            'Dropshipping',
            'Fashion Retail',
            'Electronics Retail'
        ],
        'Marketing & Advertising': [
            'Digital Marketing',
            'SEO Services',
            'Social Media Marketing',
            'Branding & Design',
            'Advertising Agency',
            'Content Marketing'
        ],
        'Healthcare': [
            'Hospitals & Clinics',
            'Medical Devices',
            'Pharmaceuticals',
            'Telemedicine',
            'Healthcare IT',
            'Diagnostics'
        ],
        'Finance & Banking': [
            'Retail Banking',
            'Corporate Banking',
            'Fintech',
            'Insurance',
            'Investment Services',
            'Accounting & Audit'
        ],
        'Manufacturing': [
            'Industrial Equipment',
            'Automotive Components',
            'Electronics Manufacturing',
            'Contract Manufacturing',
            'Packaging',
            'Machinery'
        ],
        'Logistics & Transportation': [
            'Freight Forwarding',
            'Warehousing',
            'Last-mile Delivery',
            'Air Cargo',
            'Sea Freight',
            'Road Transport'
        ]
    };

    const INDUSTRY_OPTIONS = Object.keys(INDUSTRY_SUBCATEGORIES);

    function populateIndustrySelects() {
        document.querySelectorAll('.industry-select').forEach(select => {
            // Avoid duplicating options
            if (select.dataset.initialized === '1') return;
            INDUSTRY_OPTIONS.forEach(label => {
                const opt = document.createElement('option');
                opt.value = label;
                opt.textContent = label;
                select.appendChild(opt);
            });
            select.dataset.initialized = '1';
        });
    }

    function renderSubcategories(selectEl) {
        const targetId = selectEl.getAttribute('data-target');
        const container = document.getElementById(targetId);
        if (!container) return;

        const industry = selectEl.value;
        container.innerHTML = '';

        if (!industry || !INDUSTRY_SUBCATEGORIES[industry]) {
            return;
        }

        const subs = INDUSTRY_SUBCATEGORIES[industry].slice(0, 6); // max 6
        subs.forEach((label, index) => {
            const wrapper = document.createElement('label');
            wrapper.className = 'inline-flex items-center text-xs sm:text-sm text-gray-700';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = `subcategories[${selectEl.id.split('_')[1]}][]`;
            checkbox.value = label;
            checkbox.className = 'h-4 w-4 text-blue-600 border-gray-300 rounded';

            const span = document.createElement('span');
            span.className = 'ml-2';
            span.textContent = label;

            wrapper.appendChild(checkbox);
            wrapper.appendChild(span);
            container.appendChild(wrapper);
        });
    }

    populateIndustrySelects();

    // Pre-select industries and subcategories from saved data
    const SELECTED_INDUSTRIES = @json($selectedIndustries ?? []);
    const SELECTED_SUBCATEGORIES = @json($selectedSubcategories ?? []);

    document.querySelectorAll('.industry-select').forEach(select => {
        const index = select.id.split('_')[1]; // 1,2,3
        const preSelectedIndustry = SELECTED_INDUSTRIES[index] ?? null;
        if (preSelectedIndustry) {
            select.value = preSelectedIndustry;
            renderSubcategories(select);

            const savedSubsForIndustry = SELECTED_SUBCATEGORIES[index] ?? [];
            if (Array.isArray(savedSubsForIndustry) && savedSubsForIndustry.length) {
                // After checkboxes rendered, mark them checked
                const container = document.getElementById(`subcategories_${index}`);
                if (container) {
                    container.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                        if (savedSubsForIndustry.includes(cb.value)) {
                            cb.checked = true;
                        }
                    });
                }
            }
        }

        select.addEventListener('change', function () {
            renderSubcategories(this);
        });
    });

    // AJAX submit for realtime success toast (no full page refresh)
    const form = document.getElementById('company-profile-form');
    const toast = document.getElementById('profile-toast');
    const toastMsg = document.getElementById('profile-toast-message');

    if (form && toast && toastMsg) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });

                if (!response.ok) {
                    // If validation fails, fallback to normal submit so errors show
                    form.submit();
                    return;
                }

                const data = await response.json();
                if (data && data.success) {
                    // Smooth scroll to top so user sees the whole form
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    toastMsg.textContent = data.message || 'Your business profile has been saved successfully.';
                    toast.classList.remove('hidden', 'opacity-0');
                    toast.classList.add('flex');

                    setTimeout(() => {
                        toast.classList.add('opacity-0');
                        setTimeout(() => {
                            toast.classList.add('hidden');
                            toast.classList.remove('flex');
                        }, 300);
                    }, 2500);
                }
            } catch (err) {
                // On error, fall back to normal behaviour so user isn't blocked
                form.submit();
            }
        });
    }
});
</script>
@endpush
