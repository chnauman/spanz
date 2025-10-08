@extends('layouts.main')
@section('title', 'Featured Products Search - SPANZ')
@section('content')
        <!-- Hero main content (centered) -->
        <div class="flex flex-col lg:flex-row justify-between lg:pr-10">
            <!-- Search row: mobile stacked, sm inline -->
            <div
                class="container mx-auto mt-5 px-4 sm:px-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-0 lg:mx-0 flex-1">
                <!-- dropdown button -->
                <div class="w-full sm:w-auto">
                    <button
                        class="flex items-center justify-between w-full sm:w-40 px-3 py-3 sm:py-2 bg-gray-100 border border-gray-300 text-gray-700 text-sm">
                        Suppliers
                        <svg class="w-4 h-4 ml-2 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 011.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <!-- input -->
                <input type="search" placeholder="By Category, Company or Brand..."
                    class="w-full sm:w-96 px-3 py-3 sm:py-2 border border-gray-300 text-gray-700 focus:outline-none text-sm" />

                <!-- search button -->
                <div class="w-full sm:w-auto sm:ml-3">
                    <button class="w-full sm:w-auto px-4 py-3 sm:py-2 bg-[#0D6AED] text-white text-sm">Search</button>
                </div>
            </div>
            <div class="hidden lg:block">
                <ul class="flex space-x-2 mt-7 pl-5 ">
                    <li><a href="#" class="block text-white hover:text-blue-300 text-sm">For Buyers</a></li>
                    <li><a href="#" class="block text-white hover:text-blue-300 text-sm">Supplier Discovery</a></li>
                    <li><a href="#" class="block text-white hover:text-blue-300 text-sm">Instant Quote</a></li>
                    <li><a href="#" class="block text-white hover:text-blue-300 text-sm">Product Catalogs</a></li>
                    <li><a href="#" class="block text-white hover:text-blue-300 text-sm">CAD Models</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div
        class="flex flex-col md:flex-row bg-slate-50 justify-between items-start md:items-center p-4 sm:p-5 gap-4 md:gap-0">
        <div class="flex flex-wrap items-center text-sm flex-1">
            <span><a href="#" class="text-blue-600 hover:text-blue-300">Home</a></span>
            <span class="mx-1"><a href="#" class="text-blue-600 hover:text-blue-300">/</a></span>
            <span><a href="#" class="text-blue-600 hover:text-blue-300">Supplier Discovery</a></span>
            <span class="mx-1"><a href="#" class="text-blue-600 hover:text-blue-300">/</a></span>
            <span><a href="#" class="text-gray-900">UltraLight Aircraft Suppliers</a></span>
        </div>
        <div class="flex space-x-3 items-center flex-shrink-0">
            <img src="./spanz-img/printer.svg" alt="Print" class="w-5 h-5 cursor-pointer hover:opacity-70">
            <img src="./spanz-img/share.svg" alt="Share" class="w-5 h-5 cursor-pointer hover:opacity-70">
            <img src="./spanz-img/save.svg" alt="Save" class="w-5 h-5 cursor-pointer hover:opacity-70">
        </div>
    </div>

    <!-- Mobile Filter Button - Only visible on small screens -->
    <div class="lg:hidden bg-slate-50 p-4">
        <button id="mobile-filter-btn"
            class="flex items-center gap-2 bg-white border border-gray-300 px-4 py-2 rounded-md hover:bg-gray-50 w-full justify-center">
            <img src="./spanz-img/filter.svg" alt="Filter" class="w-5 h-5">
            <span class="text-sm font-medium">Filter & Categories</span>
        </button>
    </div>

    <!-- Mobile Filter Modal - Hidden by default -->
    <div id="mobile-filter-modal" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="fixed bottom-0 left-0 right-0 bg-white rounded-t-lg max-h-[80vh] overflow-y-auto">
            <div class="p-4">
                <!-- Modal Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-[#092C48]">Filters & Categories</h2>
                    <button id="close-filter-modal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Filter Controls -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <button
                        class="border border-black px-3 py-1.5 bg-white hover:bg-gray-100 rounded-sm text-sm">Collapse
                        All</button>
                    <button class="border border-black px-3 py-1.5 bg-white hover:bg-gray-100 rounded-sm text-sm">Clear
                        All</button>
                </div>

                <hr class="my-4 border-t border-gray-300" />

                <!-- Categories -->
                <div>
                    <h3 class="text-md font-semibold text-[#092C48] mb-3">Related Categories</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm hover:underline hover:text-blue-600 block py-1">Aircraft</a></li>
                        <li><a href="#" class="text-sm hover:underline hover:text-blue-600 block py-1">Aircraft Kits</a>
                        </li>
                        <li><a href="#" class="text-sm hover:underline hover:text-blue-600 block py-1">Aircraft
                                Stripping Equipment</a></li>
                        <li><a href="#" class="text-sm hover:underline hover:text-blue-600 block py-1">Ultralight
                                Aircraft</a></li>
                        <li><a href="#" class="text-sm hover:underline hover:text-blue-600 block py-1">Longerons</a>
                        </li>
                    </ul>
                    <div class="flex items-center gap-2 pt-4 text-[#092C48] cursor-pointer hover:text-blue-600">
                        <img src="./spanz-img/plus.svg" alt="Expand" class="w-4 h-4">
                        <span class="text-sm">View More Categories</span>
                    </div>
                    <hr class="my-4 border-t border-gray-300" />
                    <section>
                        <form action="#" method="post">
                            <h3 class="text-md font-semibold text-[#092C48] mb-3">Search Within Results</h3>
                            <input type="search" placeholder="CNC, Custom, etc."
                                class="w-full px-3 py-2 rounded-sm border border-gray-400 text-gray-700 focus:outline-none text-sm" />
                            <button
                                class="mt-2 px-4 py-2 text-[#092C48] font-medium rounded-sm border border-gray-300">Search</button>
                        </form>
                    </section>
                    <hr class="my-4 border-t border-gray-300" />
                    <section>
                        <h3 class="text-md font-semibold text-[#092C48] mb-3">Company Type</h3>
                        <div class="flex flex-col filtersContainer">
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="m-M"
                                    readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                    data-ref="srp.filter.manufacturer">
                                <label for="m-M" class="txt-body-sm  mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 txt-smallest font-reg ">Manufacturer</a></label>
                            </div>
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" aria-label="Distributor checkbox is not selected" id="m-D"
                                    readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                    data-ref="srp.filter.distributor">
                                <label for="m-D" class="txt-body-sm  mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 txt-smallest font-reg ">Distributor</a></label>
                            </div>
                        </div>
                    </section>
                    <hr class="my-4 border-t border-gray-300" />
                    <section>
                        <h3 class="text-md font-semibold text-[#092C48] mb-3">Located In</h3>
                        <div class="flex flex-col filtersContainer">
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="calsouth"
                                    readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                    data-ref="srp.filter.manufacturer">
                                <label for="calsouth" class="txt-body-sm  mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 txt-smallest font-reg ">California-South</a></label>
                            </div>
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" aria-label="Distributor checkbox is not selected" id="Colorado"
                                    readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                    data-ref="srp.filter.distributor">
                                <label for="Colorado" class="txt-body-sm  mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 txt-smallest font-reg ">Colorado</a></label>
                            </div>
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" aria-label="Distributor checkbox is not selected" id="Florida"
                                    readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                    data-ref="srp.filter.distributor">
                                <label for="Florida" class="txt-body-sm  mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 txt-smallest font-reg ">Florida</a></label>
                            </div>
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" aria-label="Distributor checkbox is not selected" id="Iowa"
                                    readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                    data-ref="srp.filter.distributor">
                                <label for="Iowa" class="txt-body-sm  mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 txt-smallest font-reg ">Iowa</a></label>
                            </div>
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" aria-label="Distributor checkbox is not selected" id="Louisiana"
                                    readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                    data-ref="srp.filter.distributor">
                                <label for="Louisiana" class="txt-body-sm  mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 txt-smallest font-reg ">Louisiana</a></label>
                            </div>
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" aria-label="Distributor checkbox is not selected" id="Michigan"
                                    readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                    data-ref="srp.filter.distributor">
                                <label for="Michigan" class="txt-body-sm  mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 txt-smallest font-reg ">Michigan</a></label>
                            </div>
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" aria-label="Distributor checkbox is not selected"
                                    id="North Carolina" readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                    data-ref="srp.filter.distributor">
                                <label for="North Carolina" class="txt-body-sm  mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 txt-smallest font-reg ">North
                                        Carolina</a></label>
                            </div>
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" aria-label="Distributor checkbox is not selected" id="Ohio-North"
                                    readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                    data-ref="srp.filter.distributor">
                                <label for="Ohio-North" class="txt-body-sm  mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 txt-smallest font-reg ">Ohio-North</a></label>
                            </div>
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" aria-label="Distributor checkbox is not selected" id="Oregon"
                                    readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                    data-ref="srp.filter.distributor">
                                <label for="Oregon" class="txt-body-sm  mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 txt-smallest font-reg ">Oregon</a></label>
                            </div>
                        </div>
                        <div
                            class="flex mt-3 items-center rounded-sm gap-04 py-1 justify-center bg-white hover:bg-gray-100 border border-gray-300">
                            <img src="./spanz-img/plus.svg" alt="" class="w-4 ">
                            <button class="pl-2">Show More</button>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Grid Layout -->
    <div class="block lg:grid lg:grid-cols-12 w-full bg-slate-50">
        <div class="hidden lg:block lg:col-span-2 p-4 lg:pl-10">
            <div class="flex gap-2 items-center mb-4">
                <img src="./spanz-img/filter.svg" alt="Filter" class="w-5 h-5">
                <span class="text-sm font-medium">Filter</span>
            </div>
            <div class="flex flex-wrap gap-2 mb-4">
                <button
                    class="border border-black px-3 py-1.5 bg-white hover:bg-gray-100 rounded-sm text-xs sm:text-sm">Collapse
                    All</button>
                <button
                    class="border border-black px-3 py-1.5 bg-white hover:bg-gray-100 rounded-sm text-xs sm:text-sm">Clear
                    All</button>
            </div>
            <hr class="my-3 border-t border-gray-400 w-[70%]" />
            <div class="mt-2">
                <h1 class="text-sm sm:text-md font-semibold text-[#092C48]">Related Categories</h1>
                <ul class="space-y-1 mt-2">
                    <li><a href="#" class="text-sm sm:text-md hover:underline block py-1">Aircraft</a></li>
                    <li><a href="#" class="text-sm sm:text-md hover:underline block py-1">Aircraft Kits</a></li>
                    <li><a href="#" class="text-sm sm:text-md hover:underline block py-1">Aircraft Stripping
                            Equipment</a></li>
                    <li><a href="#" class="text-sm sm:text-md hover:underline block py-1">Ultralight Aircraft</a></li>
                    <li><a href="#" class="text-sm sm:text-md hover:underline block py-1">Longerons</a></li>
                </ul>
                <div class="flex items-center gap-2 pt-3 text-[#092C48] cursor-pointer hover:text-blue-600">
                    <img src="./spanz-img/plus.svg" alt="Expand" class="w-4 h-4">
                    <span class="text-sm">View More Categories</span>
                </div>
            </div>
            <hr class="my-3 border-t border-gray-400 w-[70%]" />
            <div class="mt-2">
                <h1 class="text-sm sm:text-md font-semibold text-[#092C48]">Search Within Results</h1>
                <section>
                    <form action="">
                        <input type="text" placeholder="CNC, Custom, etc."
                            class="w-full px-3 py-2 rounded-sm border border-gray-400 text-gray-700 focus:outline-none text-sm mt-2" />
                        <button
                            class="border border-gray-500 rounded-sm mt-2 py-1 px-3 font-medium text-[#092C48] bg-white hover:bg-gray-100">Search</button>
                    </form>
                </section>
                <hr class="my-3 border-t border-gray-400 w-[70%]" />
                <section>
                    <h3 class="text-md font-semibold text-[#092C48] mb-3">Company Type</h3>
                    <section class="flex flex-col filtersContainer">
                        <div class="flex align-items-center gap-3 ">
                            <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="m-M"
                                readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                data-ref="srp.filter.manufacturer">
                            <label for="m-M" class="txt-body-sm  mar-l-2"><a kind="dark"
                                    class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline ">Manufacturer</a></label>
                        </div>
                        <div class="flex align-items-center gap-3 ">
                            <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="m-D"
                                readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                data-ref="srp.filter.manufacturer">
                            <label for="m-D" class="txt-body-sm  mar-l-2"><a kind="dark"
                                    class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Distributor</a></label>
                        </div>
                    </section>
                </section>
                <hr class="my-3 border-t border-gray-400 w-[70%]" />
                <section>
                    <h3 class="text-md font-semibold text-[#092C48] mb-3">Located In / Near</h3>
                    <section class="flex flex-col filtersContainer">
                        <div class="flex align-items-center gap-3 ">
                            <input type="checkbox" aria-label="Manufacturer checkbox is not selected"
                                id="California-South" readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                data-ref="srp.filter.manufacturer">
                            <label for="California-South" class="txt-body-sm  mar-l-2"><a kind="dark"
                                    class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline ">California-South</a></label>
                        </div>
                        <div class="flex align-items-center gap-3 ">
                            <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Colorado"
                                readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                data-ref="srp.filter.manufacturer">
                            <label for="Colorado" class="txt-body-sm  mar-l-2"><a kind="dark"
                                    class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Colorado</a></label>
                        </div>
                        <div class="flex align-items-center gap-3 ">
                            <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Colorado"
                                readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                data-ref="srp.filter.manufacturer">
                            <label for="Colorado" class="txt-body-sm  mar-l-2"><a kind="dark"
                                    class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Colorado</a></label>
                        </div>
                        <div class="flex align-items-center gap-3 ">
                            <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Florida"
                                readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                data-ref="srp.filter.manufacturer">
                            <label for="Florida" class="txt-body-sm  mar-l-2"><a kind="dark"
                                    class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Florida</a></label>
                        </div>
                        <div class="flex align-items-center gap-3 ">
                            <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Iowa"
                                readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                data-ref="srp.filter.manufacturer">
                            <label for="Iowa" class="txt-body-sm  mar-l-2"><a kind="dark"
                                    class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Iowa</a></label>
                        </div>
                        <div class="flex align-items-center gap-3 ">
                            <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Louisiana"
                                readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                data-ref="srp.filter.manufacturer">
                            <label for="Louisiana" class="txt-body-sm  mar-l-2"><a kind="dark"
                                    class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Louisiana</a></label>
                        </div>
                        <div class="flex align-items-center gap-3 ">
                            <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Michigan"
                                readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                data-ref="srp.filter.manufacturer">
                            <label for="Michigan" class="txt-body-sm  mar-l-2"><a kind="dark"
                                    class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Michigan</a></label>
                        </div>
                        <div class="flex align-items-center gap-3 ">
                            <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Ohio-North"
                                readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                data-ref="srp.filter.manufacturer">
                            <label for="Ohio-North" class="txt-body-sm  mar-l-2"><a kind="dark"
                                    class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Ohio-North</a></label>
                        </div>
                        <div class="flex align-items-center gap-3 ">
                            <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Oregon"
                                readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                data-ref="srp.filter.manufacturer">
                            <label for="Oregon" class="txt-body-sm  mar-l-2"><a kind="dark"
                                    class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Oregon</a></label>
                        </div>
                        <div
                            class="flex mt-3 items-center rounded-sm gap-04 py-1 justify-center bg-white hover:bg-gray-100 border border-gray-300">
                            <img src="./spanz-img/plus.svg" alt="" class="w-4 ">
                            <button class="pl-2">Show More</button>
                        </div>
                    </section>
                </section>
            </div>
        </div>
        <!-- Content area for desktop -->
        <div class="w-full lg:col-span-9 p-4 lg:p-6">
            <div class="text-[#092C48] mb-5">
                <div class="text-sm sm:text-base mb-2">
                    <span>Displaying </span>
                    <span class="font-semibold">1 to 13 </span>
                    <span>out of </span>
                    <span class="font-semibold">13 </span>
                    <span>suppliers </span>
                </div>
                <p class="text-xl sm:text-2xl lg:text-3xl xl:text-4xl font-semibold leading-tight">
                    Ultralight Aircraft Manufacturers and Suppliers in the USA and Canada
                </p>
            </div>
            <div class="bg-white border border-gray-200 rounded-sm p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
                    <p class="text-[#092C48] font-semibold text-lg sm:text-xl">ShadowAir, Ltd.</p>
                    <div class="flex gap-4 sm:gap-6">
                        <div class="flex items-center gap-2">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.75 6L7.5 5.25H16.5L17.25 6V19.3162L12 16.2051L6.75 19.3162V6ZM8.25 6.75V16.6838L12 14.4615L15.75 16.6838V6.75H8.25Z"
                                    fill="#080341" />
                            </svg>
                            <p class="text-[#092C48] text-sm sm:text-lg">Save</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg width="20px" height="20px" viewBox="0 0 32 32" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">

                                <title>plus-circle</title>
                                <desc>Created with Sketch Beta.</desc>
                                <defs>

                                </defs>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                                    sketch:type="MSPage">
                                    <g id="Icon-Set-Filled" sketch:type="MSLayerGroup"
                                        transform="translate(-466.000000, -1089.000000)" fill="#000000">
                                        <path
                                            d="M488,1106 L483,1106 L483,1111 C483,1111.55 482.553,1112 482,1112 C481.447,1112 481,1111.55 481,1111 L481,1106 L476,1106 C475.447,1106 475,1105.55 475,1105 C475,1104.45 475.447,1104 476,1104 L481,1104 L481,1099 C481,1098.45 481.447,1098 482,1098 C482.553,1098 483,1098.45 483,1099 L483,1104 L488,1104 C488.553,1104 489,1104.45 489,1105 C489,1105.55 488.553,1106 488,1106 L488,1106 Z M482,1089 C473.163,1089 466,1096.16 466,1105 C466,1113.84 473.163,1121 482,1121 C490.837,1121 498,1113.84 498,1105 C498,1096.16 490.837,1089 482,1089 L482,1089 Z"
                                            id="plus-circle" sketch:type="MSShapeGroup">

                                        </path>
                                    </g>
                                </g>
                            </svg>
                            <p class="text-[#092C48] text-sm sm:text-lg">Select</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 my-3">
                    <img src="./spanz-img/location.svg" alt="Location" class="w-4 sm:w-5">
                    <span class="text-[#092C48] font-semibold text-sm sm:text-base">Superior, CO 80027</span>
                </div>
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1 lg:w-[75%]">
                        <div class="flex items-center mb-2 gap-2">
                            <img src="./spanz-img/factory.svg" alt="Manufacturer" class="w-4 sm:w-5">
                            <span class="text-[#092C48] font-semibold text-xs sm:text-sm lg:text-base">
                                Manufacturer* . Under $1 Mil Revenue . Est.2004
                            </span>
                        </div>
                        <div>
                            <p class="text-[#092C48] text-sm sm:text-base leading-relaxed">
                                Manufacturer of ultralight aircrafts for the defense industry.
                                Features include hard points, sensor packages, situational awareness for maritime and
                                border patrols or military missions and can be upgraded to withstand repeated assaults.
                                Other aircraft systems provided include optionally-piloted
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-row lg:flex-col gap-2 lg:w-[30%]">
                        <button
                            class="bg-[#0D6AED] hover:bg-blue-700 px-3 py-2 text-white rounded-sm text-sm sm:text-base flex-1 lg:flex-none">
                            Contact Supplier
                        </button>
                        <button
                            class="bg-white hover:bg-gray-100 border border-gray-300 px-3 py-2 rounded-sm text-sm sm:text-base flex-1 lg:flex-none">
                            Request Information
                        </button>
                    </div>
                </div>
                <button class="flex text-blue-600 items-center px-2 py-2 rounded-sm hover:bg-gray-100 gap-2 mt-3">
                    <span class="font-semibold text-sm sm:text-base">More</span>
                    <svg width="16" height="16" class="sm:w-5 sm:h-5" viewBox="0 0 24 24" fill=""
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 12H20M12 4V20" stroke="#2563eb" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <div class="bg-white border border-gray-200 rounded-sm p-4 mt-5 sm:p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
                    <p class="text-[#092C48] font-semibold text-lg sm:text-xl">ShadowAir, Ltd.</p>
                    <div class="flex gap-4 sm:gap-6">
                        <div class="flex items-center gap-2">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.75 6L7.5 5.25H16.5L17.25 6V19.3162L12 16.2051L6.75 19.3162V6ZM8.25 6.75V16.6838L12 14.4615L15.75 16.6838V6.75H8.25Z"
                                    fill="#080341" />
                            </svg>
                            <p class="text-[#092C48] text-sm sm:text-lg">Save</p>
                        </div>
                        <div class="flex items-center gap-2">
                             <svg width="20px" height="20px" viewBox="0 0 32 32" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">

                                <title>plus-circle</title>
                                <desc>Created with Sketch Beta.</desc>
                                <defs>

                                </defs>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                                    sketch:type="MSPage">
                                    <g id="Icon-Set-Filled" sketch:type="MSLayerGroup"
                                        transform="translate(-466.000000, -1089.000000)" fill="#000000">
                                        <path
                                            d="M488,1106 L483,1106 L483,1111 C483,1111.55 482.553,1112 482,1112 C481.447,1112 481,1111.55 481,1111 L481,1106 L476,1106 C475.447,1106 475,1105.55 475,1105 C475,1104.45 475.447,1104 476,1104 L481,1104 L481,1099 C481,1098.45 481.447,1098 482,1098 C482.553,1098 483,1098.45 483,1099 L483,1104 L488,1104 C488.553,1104 489,1104.45 489,1105 C489,1105.55 488.553,1106 488,1106 L488,1106 Z M482,1089 C473.163,1089 466,1096.16 466,1105 C466,1113.84 473.163,1121 482,1121 C490.837,1121 498,1113.84 498,1105 C498,1096.16 490.837,1089 482,1089 L482,1089 Z"
                                            id="plus-circle" sketch:type="MSShapeGroup">

                                        </path>
                                    </g>
                                </g>
                            </svg>
                            <p class="text-[#092C48] text-sm sm:text-lg">Select</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 my-3">
                    <img src="./spanz-img/location.svg" alt="Location" class="w-4 sm:w-5">
                    <span class="text-[#092C48] font-semibold text-sm sm:text-base">Superior, CO 80027</span>
                </div>
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1 lg:w-[75%]">
                        <div class="flex items-center mb-2 gap-2">
                            <img src="./spanz-img/factory.svg" alt="Manufacturer" class="w-4 sm:w-5">
                            <span class="text-[#092C48] font-semibold text-xs sm:text-sm lg:text-base">
                                Manufacturer* . Under $1 Mil Revenue . Est.2004
                            </span>
                        </div>
                        <div>
                            <p class="text-[#092C48] text-sm sm:text-base leading-relaxed">
                                Manufacturer of ultralight aircrafts for the defense industry.
                                Features include hard points, sensor packages, situational awareness for maritime and
                                border patrols or military missions and can be upgraded to withstand repeated assaults.
                                Other aircraft systems provided include optionally-piloted
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-row lg:flex-col gap-2 lg:w-[30%]">
                        <button
                            class="bg-white hover:bg-gray-100 border border-gray-300 px-3 py-2 rounded-sm text-sm sm:text-base flex-1 lg:flex-none">
                            View Supplier
                        </button>
                    </div>
                </div>
                <button class="flex text-blue-600 items-center px-2 py-2 rounded-sm hover:bg-gray-100 gap-2 mt-3">
                    <span class="font-semibold text-sm sm:text-base">More</span>
                    <svg width="16" height="16" class="sm:w-5 sm:h-5" viewBox="0 0 24 24" fill=""
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 12H20M12 4V20" stroke="#2563eb" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Mobile filter modal toggle
    const mobileFilterBtn = document.getElementById("mobile-filter-btn");
    const mobileFilterModal = document.getElementById("mobile-filter-modal");
    const closeFilterModal = document.getElementById("close-filter-modal");

    if (mobileFilterBtn && mobileFilterModal && closeFilterModal) {
        // Open filter modal
        mobileFilterBtn.addEventListener("click", () => {
            mobileFilterModal.classList.remove("hidden");
            document.body.style.overflow = "hidden"; // Prevent background scrolling
        });

        // Close filter modal
        closeFilterModal.addEventListener("click", () => {
            mobileFilterModal.classList.add("hidden");
            document.body.style.overflow = ""; // Restore scrolling
        });

        // Close modal when clicking outside
        mobileFilterModal.addEventListener("click", (e) => {
            if (e.target === mobileFilterModal) {
                mobileFilterModal.classList.add("hidden");
                document.body.style.overflow = "";
            }
        });
    }
</script>
@endpush