@extends('layouts.dashlayout')
@section('title', 'dashboard - Spanz')
@section('content')
    <div class="block lg:grid lg:grid-cols-12 w-full h-screen">
        <div class="hidden lg:block lg:col-span-2 bg-gradient-to-l from-[#092C48] to-[#1b3963]">
            <div
                class="hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 h-20 transition-colors duration-300">

                <h2 class="text-4xl font-bold text-[#0D6AED] h-20 flex items-center pl-5">SPANZ</h2>
            </div>
            <hr class="border-[#657a9871]" />
            <div
                class="h-32 relative flex items-center gap-5 px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
                <img src="{{ asset('spanz-img/profile.jpg') }}" alt="" class="rounded-full w-16" id="profileImage">
                <span class="text-white" id="profileName">Amir Hamza</span>
                <!-- edit svg -->
                <svg onclick="openEditModal()" width="15px" height="15px" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    class="absolute right-5 top-5 transform -translate-y-1/2 transition-colors duration-300 hover:cursor-pointer group">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M20.8477 1.87868C19.6761 0.707109 17.7766 0.707105 16.605 1.87868L2.44744 16.0363C2.02864 16.4551 1.74317 16.9885 1.62702 17.5692L1.03995 20.5046C0.760062 21.904 1.9939 23.1379 3.39334 22.858L6.32868 22.2709C6.90945 22.1548 7.44285 21.8693 7.86165 21.4505L22.0192 7.29289C23.1908 6.12132 23.1908 4.22183 22.0192 3.05025L20.8477 1.87868ZM18.0192 3.29289C18.4098 2.90237 19.0429 2.90237 19.4335 3.29289L20.605 4.46447C20.9956 4.85499 20.9956 5.48815 20.605 5.87868L17.9334 8.55027L15.3477 5.96448L18.0192 3.29289ZM13.9334 7.3787L3.86165 17.4505C3.72205 17.5901 3.6269 17.7679 3.58818 17.9615L3.00111 20.8968L5.93645 20.3097C6.13004 20.271 6.30784 20.1759 6.44744 20.0363L16.5192 9.96448L13.9334 7.3787Z"
                        fill="#ffffff" class="group-hover:fill-[#0D6AED]" />
                </svg>
            </div>
            <hr class="border-[#657a9871]" />
            <div
                class="text-white h-14 gap-2 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M16.5 7.063C16.5 10.258 14.57 13 12 13c-2.572 0-4.5-2.742-4.5-5.938C7.5 3.868 9.16 2 12 2s4.5 1.867 4.5 5.063zM4.102 20.142C4.487 20.6 6.145 22 12 22c5.855 0 7.512-1.4 7.898-1.857a.416.416 0 0 0 .09-.317C19.9 18.944 19.106 15 12 15s-7.9 3.944-7.989 4.826a.416.416 0 0 0 .091.317z"
                        fill="#ffffff" />
                </svg>
                <h1>Dashboard</h1>
            </div>
            <hr class="border-[#657a9871]" />
            <!-- Buyer Dropdown Menu -->
            <div class="relative">
                <button onclick="toggleDropdown()"
                    class="w-full text-white h-14 gap-2 flex items-center justify-between px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
                    <div class="flex items-center gap-2">
                        <svg fill="#ffffff" width="20px" height="20px" viewBox="0 0 32 32" version="1.1"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M29.287 19.252c-0.486-0.206-1.052-0.326-1.646-0.326-0.65 0-1.267 0.144-1.82 0.402l0.027-0.011-5.121 2.301c-0.32-1.36-1.523-2.356-2.959-2.356-0.058 0-0.115 0.002-0.172 0.005l0.008-0h-3.711l-4.691-1.375c-0.104-0.032-0.225-0.051-0.349-0.051-0.001 0-0.002 0-0.003 0h-1.669v-0.257c0-0.69-0.56-1.25-1.25-1.25v0h-3.883c-0.69 0-1.25 0.56-1.25 1.25v0 12.208c0 0.69 0.56 1.25 1.25 1.25h3.883c0.69-0 1.25-0.56 1.25-1.25v-0.44c1.596 0.316 2.993 0.738 4.33 1.278l-0.159-0.057c1.209 0.432 2.603 0.682 4.056 0.682 1.676 0 3.274-0.332 4.732-0.934l-0.082 0.030c1.271-0.563 2.351-1.16 3.372-1.839l-0.083 0.052c0.334-0.207 0.668-0.412 1.004-0.611 1.648-0.977 2.973-1.832 4.17-2.699 0.595-0.424 1.115-0.843 1.608-1.29l-0.014 0.013c0.428-0.353 0.769-0.795 0.997-1.3l0.009-0.023c0.052-0.133 0.082-0.287 0.082-0.448 0-0.093-0.010-0.184-0.029-0.271l0.002 0.008c-0.176-1.17-0.885-2.144-1.868-2.68l-0.019-0.010zM4.681 28.541h-1.383v-9.709h1.383zM28.379 22.174c-0.398 0.356-0.831 0.702-1.283 1.024l-0.046 0.031c-1.131 0.818-2.395 1.635-3.975 2.57-0.352 0.209-0.697 0.424-1.045 0.639-0.833 0.557-1.791 1.091-2.793 1.547l-0.129 0.052c-1.096 0.451-2.369 0.712-3.703 0.712-1.137 0-2.229-0.19-3.247-0.54l0.070 0.021c-1.451-0.607-3.148-1.097-4.911-1.392l-0.137-0.019v-6.48h1.489l4.691 1.375c0.105 0.032 0.226 0.051 0.351 0.051h3.891c0.443 0 0.697 0.17 0.697 0.469s-0.254 0.469-0.697 0.469h-6.809c-0.69 0-1.25 0.56-1.25 1.25s0.56 1.25 1.25 1.25v0h7.781c0 0 0 0 0.001 0 0.185 0 0.361-0.040 0.519-0.113l-0.008 0.003 7.803-3.504c0.228-0.105 0.494-0.167 0.774-0.167 0.183 0 0.359 0.026 0.526 0.075l-0.013-0.003c0.185 0.113 0.326 0.282 0.4 0.484l0.002 0.007c-0.066 0.064-0.137 0.129-0.201 0.189zM16.99 18.265l0.010 0.003 0.010-0.003c4.821-0.006 8.728-3.915 8.728-8.737 0-4.825-3.912-8.737-8.737-8.737s-8.737 3.912-8.737 8.737c0 4.822 3.906 8.732 8.727 8.737h0.001zM17.001 3.729c0.51 0.579 0.933 1.251 1.238 1.985l0.018 0.048-2.505-0.012c0.323-0.776 0.744-1.444 1.257-2.029l-0.007 0.008zM18.883 8.264c0.047 0.378 0.075 0.818 0.076 1.263v0.002c0 0.077-0.009 0.145-0.010 0.221h-3.897c-0.001-0.076-0.010-0.143-0.010-0.221 0.002-0.454 0.030-0.9 0.084-1.337l-0.005 0.054zM23.236 9.529c0 0.076-0.020 0.146-0.022 0.221h-1.772c0.001-0.078 0.017-0.141 0.017-0.221 0-0.45-0.056-0.842-0.094-1.253l1.746 0.009c0.080 0.374 0.126 0.804 0.126 1.245v0zM15.395 12.25h3.21c-0.312 1.19-0.867 2.223-1.612 3.088l0.008-0.010c-0.738-0.854-1.294-1.888-1.594-3.025l-0.012-0.053zM12.558 9.75h-1.773c-0.003-0.075-0.022-0.145-0.022-0.221 0.001-0.463 0.053-0.912 0.15-1.345l-0.008 0.041 1.734 0.008c-0.051 0.377-0.086 0.825-0.097 1.279l-0 0.016c0 0.079 0.015 0.143 0.017 0.221zM11.414 12.25h1.435c0.196 0.961 0.484 1.811 0.863 2.61l-0.029-0.069c-0.974-0.626-1.748-1.487-2.252-2.504l-0.016-0.036zM20.318 14.791c0.35-0.73 0.637-1.58 0.821-2.468l0.013-0.073h1.434c-0.52 1.054-1.294 1.915-2.243 2.526l-0.025 0.015zM21.95 5.779l-1.041-0.005c-0.184-0.591-0.384-1.090-0.619-1.568l0.028 0.063c0.639 0.412 1.18 0.913 1.622 1.495l0.011 0.015zM13.682 4.269c-0.203 0.404-0.4 0.89-0.562 1.392l-0.021 0.076-1.011-0.005c0.445-0.576 0.972-1.061 1.569-1.448l0.024-0.015z">
                            </path>
                        </svg>
                        <h1>Buyer</h1>
                    </div>
                    <!-- Dropdown Arrow -->
                    <svg id="dropdownArrow" width="12px" height="12px" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="transition-transform duration-200">
                        <path d="M7 10L12 15L17 10" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="buyerDropdown"
                    class="hidden absolute left-0 w-full bg-gradient-to-l from-[#1b3963] to-[#092C48] border-t border-[#657a9871] z-10">
                    <a href="#"
                        class="block px-12 py-3 text-white text-sm hover:bg-gradient-to-l from-[#092C48] to-[#1b3963] hover:bg-opacity-50 transition-colors duration-200">
                        Browse Products
                    </a>
                    <a href="#"
                        class="block px-12 py-3 text-white text-sm hover:bg-gradient-to-l from-[#092C48] to-[#1b3963] hover:bg-opacity-50 transition-colors duration-200">
                        My Orders
                    </a>
                    <a href="#"
                        class="block px-12 py-3 text-white text-sm hover:bg-gradient-to-l from-[#092C48] to-[#1b3963] hover:bg-opacity-50 transition-colors duration-200">
                        Watchlist
                    </a>
                    <a href="#"
                        class="block px-12 py-3 text-white text-sm hover:bg-gradient-to-l from-[#092C48] to-[#1b3963] hover:bg-opacity-50 transition-colors duration-200">
                        Purchase History
                    </a>
                    <a href="#"
                        class="block px-12 py-3 text-white text-sm hover:bg-gradient-to-l from-[#092C48] to-[#1b3963] hover:bg-opacity-50 transition-colors duration-200">
                        Saved Searches
                    </a>
                </div>
            </div>
            <hr class="border-[#657a9871]" />
            <!-- Supplier Dropdown Menu -->
            <div class="relative">
                <button onclick="toggleSupplierDropdown()"
                    class="w-full text-white h-14 gap-2 flex items-center justify-between px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
                    <div class="flex items-center gap-2">
                        <svg fill="#ffffff" height="20px" width="20px" version="1.1" id="Capa_1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="0 0 297 297" xml:space="preserve">
                            <path
                                d="M276.955,114.128h-11.421c-3.076-53.545-42.247-97.577-93.496-108.064C170.499,2.498,166.953,0,162.822,0h-28.645
                                c-4.131,0-7.676,2.498-9.216,6.064C73.714,16.551,34.543,60.583,31.467,114.128H20.045c-5.542,0-10.036,4.493-10.036,10.036v30.496
                                c0,5.542,4.493,10.036,10.036,10.036h16.273v0.002c0,6.732,0.607,13.437,1.806,20.02l-15.4,8.892
                                c-2.305,1.331-3.987,3.522-4.676,6.094c-0.689,2.571-0.328,5.31,1.003,7.615l27.171,47.06c2.772,4.799,8.908,6.446,13.709,3.673
                                l15.421-8.903c10.251,8.723,22.029,15.534,34.656,20.038v17.778c0,5.542,4.493,10.036,10.036,10.036h54.341
                                c5.542,0,10.036-4.493,10.036-10.036v-17.778c12.627-4.504,24.405-11.315,34.657-20.038l15.421,8.903
                                c4.802,2.772,10.938,1.126,13.709-3.673l27.17-47.06c2.772-4.8,1.127-10.937-3.673-13.709l-15.4-8.892
                                c1.201-6.583,1.807-13.288,1.807-20.02v-0.002h18.847c5.542,0,10.036-4.493,10.036-10.036v-30.496
                                C286.991,118.622,282.497,114.128,276.955,114.128z M152.787,20.071v65.863h-8.573V20.071H152.787z M81.175,50.937V95.97
                                c0,5.542,4.493,10.036,10.036,10.036s10.036-4.493,10.036-10.036V36.055c7.128-3.984,14.812-7.081,22.896-9.176V95.97
                                c0,5.542,4.493,10.036,10.036,10.036h28.645c5.542,0,10.036-4.493,10.036-10.036V26.879c8.084,2.095,15.768,5.192,22.897,9.176
                                V95.97c0,5.542,4.493,10.036,10.036,10.036c5.542,0,10.036-4.493,10.036-10.036V50.938c16.852,16.214,27.858,38.443,29.578,63.19
                                H51.597C53.317,89.38,64.324,67.152,81.175,50.937z M238.037,164.697c0,7.638-0.95,15.229-2.823,22.562
                                c-1.114,4.358,0.81,8.926,4.705,11.175l13.056,7.538l-17.134,29.678l-13.083-7.553c-3.899-2.25-8.819-1.631-12.036,1.517
                                c-10.922,10.685-24.42,18.489-39.037,22.568c-4.338,1.21-7.338,5.163-7.338,9.666v15.079h-34.27V261.85c0-4.503-3-8.456-7.338-9.666
                                c-14.617-4.079-28.115-11.883-39.036-22.567c-3.217-3.15-8.139-3.767-12.036-1.518l-13.083,7.553L41.45,205.973l13.057-7.538
                                c3.896-2.249,5.819-6.816,4.705-11.175c-1.874-7.334-2.823-14.924-2.823-22.562v-0.002h30.86v0.002
                                c0,33.064,26.899,59.964,59.964,59.964s59.964-26.899,59.964-59.964v-0.002h30.86V164.697z M159.243,164.697
                                c0,6.634-5.397,12.031-12.031,12.031s-12.031-5.397-12.031-12.031l0-0.002h24.062L159.243,164.697z M147.212,196.799
                                c17.701,0,32.102-14.401,32.102-32.102v-0.002h7.791v0.002c0,21.997-17.896,39.892-39.893,39.892s-39.892-17.895-39.892-39.892
                                v-0.002h7.79v0.002C115.11,182.398,129.511,196.799,147.212,196.799z M266.92,144.624H30.08v-10.425H266.92V144.624z" />
                        </svg>
                        <h1>Supplier</h1>
                    </div>
                    <!-- Dropdown Arrow -->
                    <svg id="supplierDropdownArrow" width="12px" height="12px" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="transition-transform duration-200">
                        <path d="M7 10L12 15L17 10" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="supplierDropdown"
                    class="hidden absolute left-0 w-full bg-gradient-to-l from-[#1b3963] to-[#092C48] border-t border-[#657a9871] z-10">
                    <a href="#"
                        class="block px-12 py-3 text-white text-sm hover:bg-gradient-to-l from-[#092C48] to-[#1b3963] hover:bg-opacity-50 transition-colors duration-200">
                        My Products
                    </a>
                    <a href="#"
                        class="block px-12 py-3 text-white text-sm hover:bg-gradient-to-l from-[#092C48] to-[#1b3963] hover:bg-opacity-50 transition-colors duration-200">
                        Add Product
                    </a>
                    <a href="#"
                        class="block px-12 py-3 text-white text-sm hover:bg-gradient-to-l from-[#092C48] to-[#1b3963] hover:bg-opacity-50 transition-colors duration-200">
                        Order Management
                    </a>
                    <a href="#"
                        class="block px-12 py-3 text-white text-sm hover:bg-gradient-to-l from-[#092C48] to-[#1b3963] hover:bg-opacity-50 transition-colors duration-200">
                        Analytics
                    </a>
                    <a href="#"
                        class="block px-12 py-3 text-white text-sm hover:bg-gradient-to-l from-[#092C48] to-[#1b3963] hover:bg-opacity-50 transition-colors duration-200">
                        Company Profile
                    </a>
                </div>
            </div>
            <hr class="border-[#657a9871]" />
            <div
                class="text-white h-14 gap-2 flex items-center px-5 hover:bg-gradient-to-l from-[#1b3963] to-[#092C48] hover:bg-opacity-20 transition-colors duration-300">
                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M14 4L17.5 4C20.5577 4 20.5 8 20.5 12C20.5 16 20.5577 20 17.5 20H14M3 12L15 12M3 12L7 8M3 12L7 16"
                        stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h1>Logout</h1>
            </div>
            <hr class="border-[#657a9871]" />
        </div>


    </div>

    <!-- Edit Profile Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Edit Profile</h2>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <form onsubmit="saveProfile(event)" class="space-y-4">
                <!-- Profile Image Upload -->
                <div class="text-center">
                    <div class="mb-4">
                        <img id="modalProfileImage" src="{{ asset('spanz-img/profile.jpg') }}" alt="Profile"
                            class="w-20 h-20 rounded-full mx-auto object-cover">
                    </div>
                    <label for="profileImageInput" class="btn-primary btn-primary-sm cursor-pointer">
                        Change Company Logo
                    </label>
                    <input type="file" id="profileImageInput" accept="image/*" class="hidden"
                        onchange="previewImage(event)">
                </div>

                <!-- Name Input -->
                <div>
                    <label for="profileNameInput" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="profileNameInput" value="Amir Hamza"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0D6AED] focus:border-transparent">
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeEditModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary btn-primary-sm flex-1">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection