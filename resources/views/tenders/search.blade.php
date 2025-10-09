<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Featured Tenders Search</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
</head>

<body>
    <div class="bg-[#092C48] py-5">
        <!-- Navbar -->
        <nav>
            <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-[#0D6AED]">Spanz</a>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex space-x-6">
                        <a href="#" class="text-white hover:text-blue-400">For Buyers ▾</a>
                        <a href="#" class="text-white hover:text-blue-400">For Suppliers ▾</a>
                        <a href="{{ route('tenders.index') }}" class="text-white hover:text-blue-400">Tenders</a>
                        <a href="#" class="text-white hover:text-blue-400">About</a>
                    </div>

                    <!-- Right Actions -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('company.register') }}" class="text-white hover:text-blue-400">Claim Your Company</a>
                        <a href="#" class="text-white hover:text-blue-400">Start Advertising</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="border border-white text-white px-3 py-1 rounded hover:bg-white hover:text-black">
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="border border-white text-white px-3 py-1 rounded hover:bg-white hover:text-black">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800">
                                Register
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile Burger -->
                    <div class="md:hidden">
                        <button id="menu-btn" class="text-white focus:outline-none">
                            <!-- Icon -->
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden bg-[#092c47] text-white px-4 py-4 space-y-3">
                <a href="#" class="block hover:text-blue-300">For Buyers ▾</a>
                <a href="#" class="block hover:text-blue-300">For Suppliers ▾</a>
                <a href="{{ route('tenders.index') }}" class="block hover:text-blue-300">Tenders</a>
                <a href="#" class="block hover:text-blue-300">About</a>
                <a href="{{ route('company.register') }}" class="block hover:text-blue-300">Claim Your Company</a>
                <a href="#" class="block hover:text-blue-300">Start Advertising</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full border border-white text-white px-3 py-2 rounded hover:bg-white hover:text-black text-center block">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full bg-blue-700 text-white px-3 py-2 rounded hover:bg-blue-800 text-center block">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="w-full border border-white text-white px-3 py-2 rounded hover:bg-white hover:text-black text-center block">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="w-full bg-blue-700 text-white px-3 py-2 rounded hover:bg-blue-800 text-center block">
                        Register
                    </a>
                @endauth
            </div>
        </nav>
        <!-- Hero main content (centered) -->
        <div class="flex flex-col lg:flex-row justify-between lg:pr-10">
            <!-- Search row: mobile stacked, sm inline -->
            <div
                class="container mx-auto mt-5 px-4 sm:px-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-0 lg:mx-0 flex-1">
                <!-- dropdown button -->
                <div class="w-full sm:w-auto">
                    <button
                        class="flex items-center justify-between w-full sm:w-40 px-3 py-3 sm:py-2 bg-gray-100 border border-gray-300 text-gray-700 text-sm">
                        Tenders
                        <svg class="w-4 h-4 ml-2 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 011.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <!-- input -->
                <form method="GET" action="{{ route('tenders.search') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-0 w-full">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="By Category, Company or Brand..."
                        class="w-full sm:w-96 px-3 py-3 sm:py-2 border border-gray-300 text-gray-700 focus:outline-none text-sm" />

                    <!-- search button -->
                    <div class="w-full sm:w-auto sm:ml-3">
                        <button type="submit" class="w-full sm:w-auto px-4 py-3 sm:py-2 bg-[#0D6AED] text-white text-sm">Search</button>
                    </div>
                </form>
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
            <span><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-300">Home</a></span>
            <span class="mx-1"><a href="#" class="text-blue-600 hover:text-blue-300">/</a></span>
            <span><a href="#" class="text-blue-600 hover:text-blue-300">Tender Discovery</a></span>
            <span class="mx-1"><a href="#" class="text-blue-600 hover:text-blue-300">/</a></span>
            <span><a href="#" class="text-gray-900">Featured Tenders</a></span>
        </div>
        <div class="flex space-x-3 items-center flex-shrink-0">
            <img src="{{ asset('spanz-img/printer.svg') }}" alt="Print" class="w-5 h-5 cursor-pointer hover:opacity-70">
            <img src="{{ asset('spanz-img/share.svg') }}" alt="Share" class="w-5 h-5 cursor-pointer hover:opacity-70">
            <img src="{{ asset('spanz-img/save.svg') }}" alt="Save" class="w-5 h-5 cursor-pointer hover:opacity-70">
        </div>
    </div>

    <!-- Mobile Filter Button - Only visible on small screens -->
    <div class="lg:hidden bg-slate-50 p-4">
        <button id="mobile-filter-btn"
            class="flex items-center gap-2 bg-white border border-gray-300 px-4 py-2 rounded-md hover:bg-gray-50 w-full justify-center">
            <img src="{{ asset('spanz-img/filter.svg') }}" alt="Filter" class="w-5 h-5">
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
                        @foreach($categories as $category)
                        <li><a href="#" class="text-sm hover:underline hover:text-blue-600 block py-1">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                    <div class="flex items-center gap-2 pt-4 text-[#092C48] cursor-pointer hover:text-blue-600">
                        <img src="{{ asset('spanz-img/plus.svg') }}" alt="Expand" class="w-4 h-4">
                        <span class="text-sm">View More Categories</span>
                    </div>
                    <hr class="my-4 border-t border-gray-300" />
                    <section>
                        <form action="{{ route('tenders.search') }}" method="GET">
                            <h3 class="text-md font-semibold text-[#092C48] mb-3">Search Within Results</h3>
                            <input type="search" name="search" value="{{ request('search') }}" placeholder="CNC, Custom, etc."
                                class="w-full px-3 py-2 rounded-sm border border-gray-400 text-gray-700 focus:outline-none text-sm" />
                            <button type="submit"
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
                            @foreach($locations as $location)
                            <div class="flex align-items-center gap-3 ">
                                <input type="checkbox" aria-label="{{ $location }} checkbox is not selected" id="{{ str_replace(' ', '-', strtolower($location)) }}"
                                    readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                    data-ref="srp.filter.location">
                                <label for="{{ str_replace(' ', '-', strtolower($location)) }}" class="txt-body-sm  mar-l-2"><a kind="dark"
                                        class="flex align-items-center gap-1 txt-smallest font-reg ">{{ $location }}</a></label>
                            </div>
                            @endforeach
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
                <img src="{{ asset('spanz-img/filter.svg') }}" alt="Filter" class="w-5 h-5">
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
                    @foreach($categories as $category)
                    <li><a href="#" class="text-sm sm:text-md hover:underline block py-1">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
                <div class="flex items-center gap-2 pt-3 text-[#092C48] cursor-pointer hover:text-blue-600">
                    <img src="{{ asset('spanz-img/plus.svg') }}" alt="Expand" class="w-4 h-4">
                    <span class="text-sm">View More Categories</span>
                </div>
            </div>
            <hr class="my-3 border-t border-gray-400 w-[70%]" />
            <div class="mt-2">
                <h1 class="text-sm sm:text-md font-semibold text-[#092C48]">Search Within Results</h1>
                <section>
                    <form action="{{ route('tenders.search') }}" method="GET">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="CNC, Custom, etc."
                            class="w-full px-3 py-2 rounded-sm border border-gray-400 text-gray-700 focus:outline-none text-sm mt-2" />
                        <button type="submit"
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
                        @foreach($locations as $location)
                        <div class="flex align-items-center gap-3 ">
                            <input type="checkbox" aria-label="{{ $location }} checkbox is not selected"
                                id="{{ str_replace(' ', '-', strtolower($location)) }}" readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
                                data-ref="srp.filter.location">
                            <label for="{{ str_replace(' ', '-', strtolower($location)) }}" class="txt-body-sm  mar-l-2"><a kind="dark"
                                    class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline ">{{ $location }}</a></label>
                        </div>
                        @endforeach
                        <div
                            class="flex mt-3 items-center rounded-sm gap-04 py-1 justify-center bg-white hover:bg-gray-100 border border-gray-300">
                            <img src="{{ asset('spanz-img/plus.svg') }}" alt="" class="w-4 ">
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
                    <span class="font-semibold">1 to {{ $tenders->count() }} </span>
                    <span>out of </span>
                    <span class="font-semibold">{{ $tenders->total() }} </span>
                    <span>tenders </span>
                    @if(request('search'))
                        <span class="text-blue-600">for "{{ request('search') }}"</span>
                    @endif
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-xl sm:text-2xl lg:text-3xl xl:text-4xl font-semibold leading-tight">
                        @if(request('search'))
                            Search Results for "{{ request('search') }}"
                        @else
                            Featured Tenders and Opportunities
                        @endif
                    </p>
                    @if(request('search'))
                        <a href="{{ route('tenders.search') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-sm text-sm">
                            Clear Search
                        </a>
                    @endif
                </div>
            </div>
            
            @forelse($tenders as $tender)
            <div class="bg-white border border-gray-200 rounded-sm p-4 sm:p-6 {{ !$loop->first ? 'mt-5' : '' }}">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
                    <a href="{{ route('tenders.detail', $tender->id) }}" class="text-[#092C48] font-semibold text-lg sm:text-xl hover:text-blue-600">{{ $tender->title }}</a>
                    <div class="flex gap-4 sm:gap-6">
                        @auth
                        <div class="flex items-center gap-2 cursor-pointer" onclick="toggleSave({{ $tender->id }})" id="save-btn-{{ $tender->id }}">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.75 6L7.5 5.25H16.5L17.25 6V19.3162L12 16.2051L6.75 19.3162V6ZM8.25 6.75V16.6838L12 14.4615L15.75 16.6838V6.75H8.25Z"
                                    fill="#080341" />
                            </svg>
                            <p class="text-[#092C48] text-sm sm:text-lg" id="save-text-{{ $tender->id }}">Save</p>
                        </div>
                        @endauth
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
                    <img src="{{ asset('spanz-img/location.svg') }}" alt="Location" class="w-4 sm:w-5">
                    <span class="text-[#092C48] font-semibold text-sm sm:text-base">{{ $tender->location ?? 'Location not specified' }}</span>
                </div>
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1 lg:w-[75%]">
                        <div class="flex items-center mb-2 gap-2">
                            <img src="{{ asset('spanz-img/factory.svg') }}" alt="Category" class="w-4 sm:w-5">
                            <span class="text-[#092C48] font-semibold text-xs sm:text-sm lg:text-base">
                                {{ $tender->category->name }} . {{ $tender->budget ? $tender->currency . ' ' . number_format($tender->budget, 0) : 'Budget not specified' }} . Posted {{ $tender->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div>
                            <p class="text-[#092C48] text-sm sm:text-base leading-relaxed">
                                {{ Str::limit($tender->description, 200) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-row lg:flex-col gap-2 lg:w-[30%]">
                        <a href="{{ route('tenders.detail', $tender->id) }}"
                            class="bg-[#0D6AED] hover:bg-blue-700 px-3 py-2 text-white rounded-sm text-sm sm:text-base flex-1 lg:flex-none text-center">
                            View Details
                        </a>
                        @if($tender->requirements)
                        <button
                            class="bg-white hover:bg-gray-100 border border-gray-300 px-3 py-2 rounded-sm text-sm sm:text-base flex-1 lg:flex-none">
                            View Requirements
                        </button>
                        @endif
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
            @empty
            <div class="bg-white border border-gray-200 rounded-sm p-4 sm:p-6">
                <div class="text-center py-8">
                    @if(request('search'))
                        <h3 class="text-lg font-semibold text-[#092C48] mb-2">No Tenders Found</h3>
                        <p class="text-gray-600 mb-4">No tenders found for "{{ request('search') }}". Try different keywords or browse all tenders.</p>
                        <a href="{{ route('tenders.search') }}" class="bg-[#0D6AED] hover:bg-blue-700 text-white px-4 py-2 rounded-sm text-sm">
                            View All Tenders
                        </a>
                    @else
                        <h3 class="text-lg font-semibold text-[#092C48] mb-2">No Tenders Found</h3>
                        <p class="text-gray-600">There are currently no active tenders available.</p>
                    @endif
                </div>
            </div>
            @endforelse

            <!-- Pagination -->
            @if($tenders->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $tenders->links() }}
            </div>
            @endif
        </div>
    </div>
    <section class="bg-[#092C47] text-white py-10 px-5">
        <div class="flex flex-col md:flex-row md:justify-evenly gap-8 md:gap-0">
            <div class="space-y-3">
                <div class="font-semibold">
                    <span>For Buyers</span>
                </div>
                <div>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:underline">Supplier Discovery</a></li>
                        <li><a href="#" class="hover:underline">Product Catalogs</a></li>
                        <li><a href="#" class="hover:underline">CAD</a></li>
                        <li><a href="#" class="hover:underline">Diversity</a></li>
                        <li><a href="#" class="hover:underline">Instant Quotes</a></li>
                        <li><a href="#" class="hover:underline">Buyer & Engineer Reviews</a></li>
                    </ul>
                </div>
            </div>
            <div class="space-y-3">
                <div class="font-semibold">
                    <span>Industry Insights</span>
                </div>
                <div>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:underline">Topic</a></li>
                        <li><a href="#" class="hover:underline">SPANZ Index</a></li>
                        <li><a href="#" class="hover:underline">Guides</a></li>
                        <li><a href="#" class="hover:underline">White Papers</a></li>
                        <li><a href="#" class="hover:underline">Certification Glossary</a></li>
                        <li><a href="#" class="hover:underline">Subscribe</a></li>
                    </ul>
                </div>
            </div>
            <div class="space-y-3">
                <div class="font-semibold">
                    <span>For Business</span>
                </div>
                <div>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:underline">Advertise</a></li>
                        <li><a href="#" class="hover:underline">Content & Data Services</a></li>
                        <li><a href="#" class="hover:underline">Marketing Services</a></li>
                        <li><a href="#" class="hover:underline">SPANZ Reviews</a></li>
                        <li><a href="#" class="hover:underline">Claim Your Company Profile</a></li>
                        <li><a href="#" class="hover:underline">SPANZ Analytics</a></li>
                        <li><a href="#" class="hover:underline">Events & Webinars</a></li>
                    </ul>
                </div>
            </div>
            <div class="space-y-3">
                <div class="font-semibold">
                    <span>Site Map</span>
                </div>
                <div>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:underline">Categories</a></li>
                        <li><a href="#" class="hover:underline">Featured Companies</a></li>
                        <li><a href="#" class="hover:underline">Featured Categories</a></li>
                        <li><a href="#" class="hover:underline">Featured Products</a></li>
                        <li><a href="#" class="hover:underline">Featured Catalogs</a></li>

                    </ul>
                </div>
            </div>
            <div class="space-y-3">
                <div class="font-semibold">
                    <span>About Us</span>
                </div>
                <div>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:underline">SPANZ Brand Center</a></li>
                        <li><a href="#" class="hover:underline">Careers</a></li>
                        <li><a href="#" class="hover:underline">Press Room</a></li>
                        <li><a href="#" class="hover:underline">Sign Up</a></li>
                        <li><a href="#" class="hover:underline">Sign In</a></li>
                        <li><a href="#" class="hover:underline">Contact</a></li>
                        <li><a href="#" class="hover:underline">Help Center</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center text-sm pt-10">
            <span class="px-5">Copyright © 2025 SPANZ Publishing Company. All Rights Reserved. See Terms And Conditions,
                Privacy Statement and California Do Not Track Notice. Website Last Motified September 3, 2025.
                SPANZ Register and SPANZ Regional are part of spanz.Com. SPANZ is a registered trademark of SPANZ
                Publishing Company.
            </span>
        </div>
    </section>

    <script>
        // Mobile navigation menu toggle
        const menuBtn = document.getElementById("menu-btn");
        const mobileMenu = document.getElementById("mobile-menu");

        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener("click", () => {
                mobileMenu.classList.toggle("hidden");
            });
        }

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

        // Save/Unsave functionality
        function toggleSave(tenderId) {
            const saveBtn = document.getElementById(`save-btn-${tenderId}`);
            const saveText = document.getElementById(`save-text-${tenderId}`);
            
            // Check if already saved
            fetch(`/tenders/${tenderId}/saved-status`)
                .then(response => response.json())
                .then(data => {
                    if (data.saved) {
                        // Unsave
                        fetch(`/tenders/${tenderId}/unsave`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            saveText.textContent = 'Save';
                            saveBtn.classList.remove('text-green-600');
                            saveBtn.classList.add('text-[#092C48]');
                        })
                        .catch(error => console.error('Error:', error));
                    } else {
                        // Save
                        fetch(`/tenders/${tenderId}/save`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            saveText.textContent = 'Saved';
                            saveBtn.classList.remove('text-[#092C48]');
                            saveBtn.classList.add('text-green-600');
                        })
                        .catch(error => console.error('Error:', error));
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Check saved status on page load
        document.addEventListener('DOMContentLoaded', function() {
            @auth
            @foreach($tenders as $tender)
            fetch(`/tenders/{{ $tender->id }}/saved-status`)
                .then(response => response.json())
                .then(data => {
                    if (data.saved) {
                        const saveText = document.getElementById(`save-text-{{ $tender->id }}`);
                        const saveBtn = document.getElementById(`save-btn-{{ $tender->id }}`);
                        if (saveText) saveText.textContent = 'Saved';
                        if (saveBtn) {
                            saveBtn.classList.remove('text-[#092C48]');
                            saveBtn.classList.add('text-green-600');
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            @endforeach
            @endauth
        });

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit search on Enter key
            const searchInputs = document.querySelectorAll('input[name="search"]');
            searchInputs.forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        this.closest('form').submit();
                    }
                });
            });
        });
    </script>

</body>

</html>
