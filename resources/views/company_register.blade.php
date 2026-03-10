<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Registration - Spanz</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
</head>
<body>
<div class="bg-image w-full bg-cover bg-center bg-no-repeat flex flex-col min-h-screen" style="background-image:url('{{ asset('spanz-img/spanz-bg.jpg') }}')">
    <!-- Main Content Area -->
    <div class="flex-1 flex items-center justify-center px-4 py-6 sm:py-8 lg:py-12">
        <div class="bg-white rounded-lg w-full max-w-md sm:max-w-lg md:max-w-xl lg:max-w-2xl mx-auto p-4 sm:p-6 md:p-8">
            <div class="text-center mb-4 sm:mb-6">
                <h1 class="text-2xl sm:text-2xl md:text-3xl py-3 sm:py-5 font-bold text-[#0D6AED] mb-1 sm:mb-2">SPANZ</h1>
                <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-800 mb-2 sm:mb-2">Complete Your Profile</h2>
                <span class="text-sm sm:text-sm md:text-base text-gray-600 leading-relaxed block px-2 sm:px-0">Great news, your account has been created! Tell us a little bit more about yourself and your company to ensure you get the most out of our platform.</span>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <form class="space-y-4 sm:space-y-4" method="post" action="{{ route('company.register.store') }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label for="first" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">First Name</label>
                        <input type="text" id="first" name="first" required 
                        class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('first') border-red-300 @enderror">
                        @error('first')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="last" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Last Name</label>
                        <input type="text" id="last" name="last" required
                        class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('last') border-red-300 @enderror">
                        @error('last')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label for="company" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Company Name</label>
                        <input type="text" id="company" name="company" required
                        class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('company') border-red-300 @enderror">
                        @error('company')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="comp" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Company's Main Industry (Optional)</label>
                        <select id="comp" name="comp"
                            class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                            <option value="">Select an industry</option>
                            <option value="aerospace-defense">Aerospace & Defense</option>
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
                    <label for="website" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Company Website</label>
                    <input type="text" id="website" name="website" 
                    class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('website') border-red-300 @enderror">
                    @error('website')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="pt-2">
                    <label class="flex items-center">
                        <input type="checkbox" id="check" class="h-4 w-4 sm:h-4 sm:w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm sm:text-sm text-gray-600">I don't have a website</span>
                    </label>                        
                </div>
                
                <div>
                    <label for="objective" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">What is your main objective on SPANZ?</label>
                    <select id="objective" name="objective" required size="1"
                        class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white @error('objective') border-red-300 @enderror"
                        style="max-height: 200px; overflow-y: auto;">
                        <option value="">Select your main objective</option>
                        <option value="buy-products">Buy products and materials for my business</option>
                        <option value="sell-products">Sell my products and services</option>
                        <option value="find-suppliers">Find reliable suppliers and vendors</option>
                        <option value="expand-network">Expand my business network</option>
                        <option value="source-materials">Source raw materials and components</option>
                        <option value="market-research">Conduct market research</option>
                        <option value="find-customers">Find new customers and clients</option>
                        <option value="compare-prices">Compare prices and get quotes</option>
                        <option value="partnership">Establish business partnerships</option>
                        <option value="export-import">Explore export/import opportunities</option>
                        <option value="other">Other</option>
                    </select>
                    @error('objective')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Additional company information from client requirements -->
                <hr class="my-4 sm:my-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-2">Company Details</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label for="headquarter_location" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Headquarter / Main Office Location</label>
                        <input type="text" id="headquarter_location" name="headquarter_location"
                            class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label for="employees_range" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Number of Employees</label>
                        <select id="employees_range" name="employees_range"
                            class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                            <option value="">Select</option>
                            <option value="1-10">1 - 10</option>
                            <option value="11-30">11 - 30</option>
                            <option value="31-50">31 - 50</option>
                            <option value="50-100">50 - 100</option>
                            <option value="100-500">100 - 500</option>
                            <option value="500-1000">500 - 1000</option>
                            <option value="1000-5000">1000 - 5000</option>
                            <option value="5000+">Over 5000</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 sm:mt-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-2">Select Your Business Industries &amp; Categories</h3>
                    <p class="text-xs sm:text-sm text-gray-500 mb-3">Must select at least 1 main industry and 1 subcategory. Maximum 3 main industries and 6 subcategories for each.</p>

                    <div class="space-y-3">
                        @for ($i = 1; $i <= 3; $i++)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Select – DDM – Industry {{ $i }}</label>
                                    <input type="text" name="main_industries[]" placeholder="Industry {{ $i }}"
                                        class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Select – DDM subcategory</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        @for ($j = 1; $j <= 6; $j++)
                                            <label class="inline-flex items-center text-xs sm:text-sm text-gray-700">
                                                <input type="checkbox" name="subcategories[{{ $i }}][]" value="Sub-category {{ $j }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                                <span class="ml-2">Sub-category {{ $j }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <hr class="my-4 sm:my-6">

                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-2">Strengthen Your Company Profile</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-700 mb-2">Company Type</p>
                        <div class="grid grid-cols-1 gap-2">
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
                                <label class="inline-flex items-center text-xs sm:text-sm text-gray-700">
                                    <input type="checkbox" name="company_types[]" value="{{ $type }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    <span class="ml-2">{{ $type }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label for="yearly_revenue_range" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Yearly Revenue</label>
                        <select id="yearly_revenue_range" name="yearly_revenue_range"
                            class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                            <option value="">Select</option>
                            <option value="&lt;1M">Less than 1,000,000 AUD</option>
                            <option value="1M-5M">1,000,000 – 5,000,000 AUD</option>
                            <option value="5M-10M">5,000,000 – 10,000,000 AUD</option>
                            <option value="10M-30M">10,000,000 – 30,000,000 AUD</option>
                            <option value="30M-50M">30,000,000 – 50,000,000 AUD</option>
                            <option value="50M-100M">50,000,000 – 100,000,000 AUD</option>
                            <option value="100M-500M">100,000,000 – 500,000,000 AUD</option>
                            <option value="500M-1B">500,000,000 – 1 Billion AUD</option>
                            <option value="&gt;1B">Over 1 Billion AUD</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 sm:mt-6">
                    <p class="text-xs sm:text-sm font-medium text-gray-700 mb-2">Quality Certifications</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @php
                            $certs = ['ISO 9001', 'ISO 27001', 'ISO 42001', 'ISO 14001', 'ISO 50001', 'ISO 26000', 'ISO 45001', 'ISO 22001', 'ISO 17025'];
                        @endphp
                        @foreach ($certs as $cert)
                            <label class="inline-flex items-center text-xs sm:text-sm text-gray-700">
                                <input type="checkbox" name="quality_certifications[]" value="{{ $cert }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <span class="ml-2">{{ $cert }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 sm:mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label for="brands_represented" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">List Brands Represented</label>
                        <textarea id="brands_represented" name="brands_represented" rows="3"
                            class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                    </div>
                    <div>
                        <label for="industry_awards" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">List Industry Awards &amp; Accreditations</label>
                        <textarea id="industry_awards" name="industry_awards" rows="3"
                            class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                    </div>
                </div>

                <div class="mt-4 sm:mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label for="industry_memberships" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">List Industry Memberships</label>
                        <textarea id="industry_memberships" name="industry_memberships" rows="3"
                            class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                    </div>
                    <div>
                        <label for="unique_value_propositions" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">List Unique Value Propositions</label>
                        <textarea id="unique_value_propositions" name="unique_value_propositions" rows="3"
                            class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Example – Faster lead times, customised solutions, same day delivery"></textarea>
                    </div>
                </div>

                <div class="mt-4 sm:mt-6">
                    <label for="major_projects" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">List Major Projects (Present or Past)</label>
                    <textarea id="major_projects" name="major_projects" rows="3"
                        class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                </div>

                <div class="mt-4 sm:mt-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-2">Elevate your Market Presence (Free)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-700 mb-2">Delivery Capabilities</p>
                            @php
                                $regions = ['Australia', 'MENA', 'Sub-Saharan Africa', 'New Zealand', 'Europe', 'APAC', 'North America', 'Latin America', 'Central Asia'];
                            @endphp
                            <div class="grid grid-cols-1 gap-2">
                                @foreach ($regions as $region)
                                    <label class="inline-flex items-center text-xs sm:text-sm text-gray-700">
                                        <input type="checkbox" name="delivery_capabilities[]" value="{{ $region }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <span class="ml-2">{{ $region }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-700 mb-2">Reps &amp; Office Locations</p>
                            <div class="grid grid-cols-1 gap-2">
                                @foreach ($regions as $region)
                                    <label class="inline-flex items-center text-xs sm:text-sm text-gray-700">
                                        <input type="checkbox" name="office_locations[]" value="{{ $region }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <span class="ml-2">{{ $region }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full mt-6 sm:mt-6 bg-[#0D6AED] text-white py-3 sm:py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium text-base sm:text-base">
                    Complete Profile
                </button>
            </form>
            
            <div class="mt-6 sm:mt-6 text-center">                
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-500 transition-colors text-sm sm:text-base">Skip for now</a>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="text-center px-4 py-3 sm:py-4">
        <div class="bg-black bg-opacity-60 rounded-md sm:rounded-lg px-3 sm:px-4 py-2 inline-block max-w-full">
            <p class="text-white text-xs sm:text-sm leading-relaxed">
                <span class="block sm:inline">©2025 SPANZ Publishing Company. All rights reserved.</span>
                <span class="block sm:inline sm:ml-1 mt-1 sm:mt-0">
                    See <a href="#" class="text-blue-300 hover:text-blue-200 underline transition-colors">Terms & Conditions</a> and 
                    <a href="#" class="text-blue-300 hover:text-blue-200 underline transition-colors">Privacy Statement</a>.
                </span>
            </p>
        </div>
    </footer>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const websiteInput = document.getElementById('website');
    const noWebsiteCheckbox = document.getElementById('check');
    
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
});
</script>
</body>
</html>