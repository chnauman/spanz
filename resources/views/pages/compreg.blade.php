@extends('layouts.bg')
@section('title', 'Company Registration')
@section('content')

<div class="flex-1 flex items-center justify-center px-4 py-6 sm:py-8 lg:py-12">
            <div class="bg-white rounded-lg w-full max-w-md sm:max-w-lg md:max-w-xl lg:max-w-2xl mx-auto p-4 sm:p-6 md:p-8">
                <div class="text-center mb-4 sm:mb-6">
                    <h1 class="text-2xl sm:text-2xl md:text-3xl py-3 sm:py-5 font-bold text-[#0D6AED] mb-1 sm:mb-2">SPANZ</h1>
                    <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-800 mb-2 sm:mb-2">Complete Your Profile</h2>
                    <span class="text-sm sm:text-sm md:text-base text-gray-600 leading-relaxed block px-2 sm:px-0">Great news, your account has been created! Tell us a little bit more about yourself and your company to ensure you get the most out of our platform.</span>
                </div>
                
                <form class="space-y-4 sm:space-y-4" method="post" action="#">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="first" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">First Name</label>
                            <input type="text" id="first" name="first" required 
                            class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label for="last" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Last Name</label>
                            <input type="text" id="last" name="last" required
                            class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="company" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Company Name</label>
                            <input type="text" id="company" name="company" required
                            class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
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
                        <input type="text" id="website" name="website" required
                        class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
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
                            class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white"
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
                    </div>
                    <button type="submit" class="w-full mt-6 sm:mt-6 bg-[#0D6AED] text-white py-3 sm:py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium text-base sm:text-base">
                        Complete Profile
                    </button>
                </form>
                
                <div class="mt-6 sm:mt-6 text-center">                
                        <a href="#" class="text-blue-600 hover:text-blue-500 transition-colors text-sm sm:text-base">Skip for now</a>
                    </p>
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
        @endsection