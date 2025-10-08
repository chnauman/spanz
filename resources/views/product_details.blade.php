@extends('layouts.main')
@section('title', 'Product Details - SPANZ')
@section('content')

    <!-- project overview -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        <div class="border border-gray-300 rounded-sm p-3 sm:p-4 lg:p-6">
            <div>
                <h3 class="text-lg sm:text-xl font-semibold">Project Overview</h3>
            </div>
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">Tender</h1>
                <span class="text-sm sm:text-base">Estimated Budget: $10,000</span>
            </div>
            <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row sm:justify-between space-y-4 sm:space-y-0">
                <h3 class="text-base sm:text-lg font-semibold">Tender Details</h3>
                <div class="flex flex-col sm:items-end space-y-3 sm:space-y-4">
                    <button
                        class="bg-[#0D6AED] rounded-sm text-white px-4 sm:px-6 py-2 text-sm sm:text-base w-full sm:w-auto">View
                        buyer details</button>
                    <div class="flex items-center text-[#6C6C6C] space-x-2">
                        <svg width="16px" height="16px" class="sm:w-5 sm:h-5" viewBox="-4 0 32 32" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">

                            <title>location</title>
                            <desc>Created with Sketch Beta.</desc>
                            <defs>

                            </defs>
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                                sketch:type="MSPage">
                                <g id="Icon-Set" sketch:type="MSLayerGroup"
                                    transform="translate(-104.000000, -411.000000)" fill="#6C6C6C">
                                    <path
                                        d="M116,426 C114.343,426 113,424.657 113,423 C113,421.343 114.343,420 116,420 C117.657,420 119,421.343 119,423 C119,424.657 117.657,426 116,426 L116,426 Z M116,418 C113.239,418 111,420.238 111,423 C111,425.762 113.239,428 116,428 C118.761,428 121,425.762 121,423 C121,420.238 118.761,418 116,418 L116,418 Z M116,440 C114.337,440.009 106,427.181 106,423 C106,417.478 110.477,413 116,413 C121.523,413 126,417.478 126,423 C126,427.125 117.637,440.009 116,440 L116,440 Z M116,411 C109.373,411 104,416.373 104,423 C104,428.018 114.005,443.011 116,443 C117.964,443.011 128,427.95 128,423 C128,416.373 122.627,411 116,411 L116,411 Z"
                                        id="location" sketch:type="MSShapeGroup">

                                    </path>
                                </g>
                            </g>
                        </svg>
                        <span class="text-sm sm:text-base">Melbourne, Australia</span>
                    </div>

                </div>

            </div>
            <div class="flex flex-col lg:flex-row lg:gap-8 mt-6 sm:mt-8">
                <div class="flex-1">
                    <div class="mt-6 sm:mt-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 max-w-sm sm:max-w-md">
                            <p class="text-sm sm:text-base font-medium text-gray-700">Tender #:</p>
                            <p class="text-sm sm:text-base">00000012</p>
                            <p class="text-sm sm:text-base font-medium text-gray-700">Publish Date:</p>
                            <p class="text-sm sm:text-base">26 September 2025</p>
                        </div>
                    </div>
                    <div class="mt-6 sm:mt-8">
                        <h3 class="font-semibold mb-3 text-base sm:text-lg">Indicative Budget Break Down :</h3>
                        <div class="space-y-1 text-sm sm:text-base">
                            <p>Electronic parts 20%</p>
                            <p>Electrical Services 60%</p>
                            <p>Civil Works 20%</p>
                        </div>
                    </div>
                    <div class="mt-6 sm:mt-8">
                        <h3 class="font-semibold mb-3 text-base sm:text-lg">Project Title:</h3>
                        <p class="text-sm sm:text-base">Water storage construction works</p>
                    </div>
                    <div class="mt-8">
                        <h3 class="font-semibold mb-3">Description:</h3>
                        <h4 class="mb-2 font-semibold">1. Introduction:</h4>
                        <p>Spanz invites qualified contractors to submit sealed bids for the construction of Water
                            storage
                            construction works
                            “Residential Building at Sydney”. This tender aims to ensure transparency, competitiveness,
                            and
                            quality in project delivery.
                        </p>
                    </div>
                    <div class="mt-6 sm:mt-8">
                        <h3 class="font-semibold mb-3 text-base sm:text-lg">2. Scope of Work:</h3>
                        <p class="mb-3 text-sm sm:text-base"> The project includes but is not limited to:</p>
                        <ul class="list-disc ml-4 sm:ml-6 space-y-1 text-sm sm:text-base">
                            <li>Site preparation and earthworks</li>
                            <li>Construction of water storage tanks</li>
                            <li>Installation of plumbing and electrical systems</li>
                            <li>Landscaping and site restoration</li>
                        </ul>
                    </div>
                    <div class="mt-6 sm:mt-8">
                        <h3 class="font-semibold mb-3 text-base sm:text-lg">3. Eligibility Criteria:</h3>
                        <p class="mb-3 text-sm sm:text-base"> Bidders must:</p>
                        <ul class="list-disc ml-4 sm:ml-6 space-y-1 text-sm sm:text-base">
                            <li>Be registered construction firms with valid licenses.</li>
                            <li>Have at least 5 years of experience in similar projects.</li>
                            <li>Provide proof of financial stability and technical capacity</li>
                            <li>Submit references and portfolio of completed projects.</li>
                        </ul>
                    </div>
                </div>
                <!-- view buyer details -->
                <div class="w-full lg:w-96 lg:flex-shrink-0 mt-8 lg:mt-0">
                    <div class="border border-gray-300 rounded-sm p-4 sm:p-5 bg-white shadow-sm">
                        <h2 class="font-bold text-lg sm:text-xl mb-4 sm:mb-5 text-gray-800">Download Project Documents</h2>
                        
                        <div class="space-y-3 sm:space-y-4">
                            <div class="flex flex-col sm:flex-row sm:gap-x-6">
                                <span class="font-medium text-gray-700 text-sm sm:text-base">Project Location:</span>
                                <p class="text-gray-600 text-sm sm:text-base">Melbourne, Australia</p>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:gap-x-6">
                                <span class="font-medium text-gray-700 text-sm sm:text-base">Project Category:</span>
                                <p class="text-gray-600 text-sm sm:text-base">Construction</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex flex-col mb-3 sm:flex-row sm:gap-x-6">
                                <h3 class="font-semibold sm:text-lg  text-gray-800">Requested By:</h3>
                                <p>SPANZ</p>
                                <a href="#" class="text-blue-600 hover:text-blue-800 underline text-sm sm:text-base mb-4 block">
                            </div>
                                User Business Name Here (Link to user profile)
                            </a>
                            
                            <div class="space-y-3">
                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">ABN/ACN or Registration Number:</span>
                                    <span class="text-gray-600 text-sm ">123456789</span>
                                </div>
                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">Business Phone:</span>
                                    <span class="text-gray-600 text-sm ">+61422010631</span>
                                </div>
                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">Name:</span>
                                    <span class="text-gray-600 text-sm ">Amir Hamza</span>
                                </div>
                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">Email:</span>
                                    <span class="text-gray-600 text-sm ">amir.hamza@example.com</span>
                                </div>
                                <div class="flex gap-x-4">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm block mb-1">No of Employees:</span>
                                    <span class="text-gray-600 text-sm ">50</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection