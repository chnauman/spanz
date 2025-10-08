@extends('layouts.master')
@section('title', 'Product - Spanz')
@section('content')
@include('components.headermid')

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
            <img src="{{ asset('spanz-img/printer.svg') }}" alt="Print" class="w-5 h-5 cursor-pointer hover:opacity-70">
            <img src="{{ asset('spanz-img/share.svg') }}" alt="Share" class="w-5 h-5 cursor-pointer hover:opacity-70">
            <img src="{{ asset('spanz-img/save.svg') }}" alt="Save" class="w-5 h-5 cursor-pointer hover:opacity-70">
        </div>
    </div>
    <div class="block lg:grid lg:grid-cols-12 w-full bg-slate-50">
        @include('components.sidemenu')
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
                    <img src="{{ asset('spanz-img/location.svg') }}" alt="Location" class="w-4 sm:w-5">
                    <span class="text-[#092C48] font-semibold text-sm sm:text-base">Superior, CO 80027</span>
                </div>
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1 lg:w-[75%]">
                        <div class="flex items-center mb-2 gap-2">
                            <img src="{{ asset('spanz-img/factory.svg') }}" alt="Manufacturer" class="w-4 sm:w-5">
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
                    <img src="{{ asset('spanz-img/location.svg') }}" alt="Location" class="w-4 sm:w-5">
                    <span class="text-[#092C48] font-semibold text-sm sm:text-base">Superior, CO 80027</span>
                </div>
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1 lg:w-[75%]">
                        <div class="flex items-center mb-2 gap-2">
                            <img src="{{ asset('spanz-img/factory.svg') }}" alt="Manufacturer" class="w-4 sm:w-5">
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