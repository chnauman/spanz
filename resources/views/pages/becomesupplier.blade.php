@extends('layouts.dashlayout')
@section('title', 'Becomesupplier')
@section('content')
    <div class="bg-gray-100">
        <div class="text-center py-6 sm:py-8 lg:py-10 px-4 sm:px-6 lg:px-8 text-[#092C48]">
            <h1 class="font-bold text-2xl sm:text-3xl lg:text-4xl xl:text-5xl leading-tight mb-3 sm:mb-4">Transparent
                Pricing for All Procurement Needs</h1>
            <span class="text-sm sm:text-base lg:text-lg max-w-3xl mx-auto block leading-relaxed">Choose the plan that's
                right for your business. All plans include access to our core platform
                features.</span>
        </div>
        <div class="flex justify-center px-4 mb-6 sm:mb-8 lg:mb-10">
            <div class="inline-flex rounded-lg border border-blue-600 overflow-hidden">
                <button
                    class="bg-blue-600 text-white border-r border-blue-600 text-sm sm:text-lg lg:text-xl font-semibold px-3 sm:px-4 lg:px-6 py-2 hover:bg-blue-700 transition-colors">Monthly</button>
                <button
                    class="text-sm sm:text-lg lg:text-xl font-semibold border-blue-600 text-[#1E3A8A] px-3 sm:px-4 lg:px-6 py-2 hover:bg-blue-50 transition-colors">
                    <span class="block sm:inline">Annual</span>
                    <span class="block sm:inline text-xs sm:text-sm lg:text-base">(save 15%)</span>
                </button>
            </div>
        </div>
        <!-- Pricing Cards -->
        <div class="px-4 sm:px-6 lg:px-8 pb-12 sm:pb-16 lg:pb-20">
            <div class="flex flex-col md:flex-row md:justify-center gap-6 lg:gap-8 max-w-7xl mx-auto">

                <!-- Professional Card -->
                <div
                    class="w-full max-w-sm mx-auto relative rounded-[20px] sm:rounded-[30px] border border-blue-400 hover:scale-105 transition-all duration-300 bg-white shadow-[0px_4px_4px_0px_rgba(0,0,0,0.25)]">
                    <div class="flex justify-center">
                        <img src="{{ asset('./spanz-img/most-popular.svg') }}" alt="" class="absolute -top-2 sm:-top-3 h-6 sm:h-auto">
                    </div>
                    <div class="px-5 sm:px-6">

                        <h1 class="text-xl sm:text-2xl text-blue-800 font-bold pt-4 sm:pt-5">Professional</h1>
                        <p class="text-xs sm:text-sm text-blue-800 pb-8 sm:pb-12 leading-relaxed">Tailored for growth
                            the Professional Package
                            offers advanced features, scalability, and
                            priority support 24/7.</p>
                        <h2 class="text-2xl sm:text-3xl text-blue-800 font-bold">$120/mon</h2>
                        <p class="text-xs sm:text-sm text-blue-800 mb-4 sm:mb-5">Billed Monthly</p>
                        <h3 class="text-sm sm:text-md text-blue-800 font-bold">It includes</h3>
                        <div class="space-y-2 sm:space-y-3 mt-3">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <img src="{{ asset('./spanz-img/check-mark 1.png') }}" alt="" class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0">
                                <span class="text-blue-800 text-xs sm:text-sm">3 Month package duration</span>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3">
                                <img src="{{ asset('./spanz-img/check-mark 1.png') }}" alt="" class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0">
                                <span class="text-blue-800 text-xs sm:text-sm">3 Featured task allowed</span>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3">
                                <img src="{{ asset('./spanz-img/check-mark 1.png') }}" alt="" class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0">
                                <span class="text-blue-800 text-xs sm:text-sm">0 Credits required to apply to job</span>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3">
                                <img src="{{ asset('./spanz-img/check-mark 1.png') }}" alt="" class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0">
                                <span class="text-blue-800 text-xs sm:text-sm">10 Days featured task duration</span>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3">
                                <img src="{{ asset('./spanz-img/check-mark 1.png') }}" alt="" class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0">
                                <span class="text-blue-800 text-xs sm:text-sm">0 Credits required to apply to job</span>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3">
                                <img src="{{ asset('./spanz-img/check-mark 1.png') }}" alt="" class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0">
                                <span class="text-blue-800 text-xs sm:text-sm">10 Days featured task duration</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center pb-4 sm:pb-6">
                        <button
                            class="bg-gray-300 hover:bg-[#092C47] hover:text-white mt-4 sm:mt-7 mb-2 sm:mb-3 text-blue-800 font-bold py-2 px-4 sm:px-6 rounded-md text-sm sm:text-base transition-colors">Select
                            Plan</button>
                    </div>
                </div>
                <!-- Enterprise Card -->
                <div
                    class="w-full max-w-sm mx-auto rounded-[20px] sm:rounded-[30px] hover:scale-105 transition-all duration-300 bg-white shadow-[0px_4px_4px_0px_rgba(0,0,0,0.25)] md:col-span-2 xl:col-span-1">
                    <div class="px-5 sm:px-7">

                        <h1 class="text-xl sm:text-2xl text-blue-800 font-bold pt-4 sm:pt-5">Enterprise</h1>
                        <p class="text-xs sm:text-sm text-blue-800 pb-8 sm:pb-12 leading-relaxed">Ultimate for large
                            organizations, offering
                            cutting-edge tech, dedicated support, and
                            advanced security features.</p>
                        <h2 class="text-2xl sm:text-3xl text-blue-800 font-bold">$299/mon</h2>
                        <p class="text-xs sm:text-sm text-blue-800 mb-4 sm:mb-5">Billed Monthly</p>
                        <h3 class="text-sm sm:text-md text-blue-800 font-bold">It includes</h3>
                        <div class="space-y-2 sm:space-y-3 mt-3">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <img src="{{ asset('./spanz-img/check-mark 1.png') }}" alt="" class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0">
                                <span class="text-blue-800 text-xs sm:text-sm">1 Year package duration</span>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3">
                                <img src="{{ asset('./spanz-img/check-mark 1.png') }}" alt="" class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0">
                                <span class="text-blue-800 text-xs sm:text-sm">50 Tasks to post</span>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3">
                                <img src="{{ asset('./spanz-img/check-mark 1.png') }}" alt="" class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0">
                                <span class="text-blue-800 text-xs sm:text-sm">20 Featured task allowed</span>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3">
                                <img src="{{ asset('./spanz-img/check-mark 1.png') }}" alt="" class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0">
                                <span class="text-blue-800 text-xs sm:text-sm">Task plans allowed</span>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3">
                                <img src="{{ asset('./spanz-img/check-mark 1.png') }}" alt="" class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0">
                                <span class="text-blue-800 text-xs sm:text-sm">0 Credits required to apply to job</span>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3">
                                <img src="{{ asset('./spanz-img/check-mark 1.png') }}" alt="" class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0">
                                <span class="text-blue-800 text-xs sm:text-sm">30 Days featured task duration</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center pb-4 sm:pb-6">
                        <button
                            class="bg-gray-300 hover:bg-blue-600 hover:text-white mt-4 sm:mt-7 mb-2 sm:mb-3 text-blue-800 font-bold py-2 px-4 sm:px-6 rounded-md text-sm sm:text-base transition-colors">Select
                            Plan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- All plans include -->
    <div class="bg-gray-100 py-8 sm:py-12 lg:py-16">
        <div class="text-center mb-8 sm:mb-12">
            <h1 class="text-[#092C48] font-bold text-xl sm:text-2xl lg:text-3xl">All plans include</h1>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-10 lg:gap-12">
                <!-- Feature 1 -->
                <div class="flex flex-col items-center text-center">
                    <div
                        class="flex justify-center bg-[#416D94] w-16 h-16 sm:w-20 sm:h-20 rounded-full items-center mb-4 sm:mb-6">
                        <svg width="40px" height="40px" class="sm:w-[60px] sm:h-[60px]" viewBox="0 0 64 64" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_14_1955)">
                                <path
                                    d="M47.18 55.997V51.627C47.1803 49.7477 46.8103 47.8868 46.0913 46.1505C45.3722 44.4142 44.3181 42.8366 42.9893 41.5077C41.6604 40.1789 40.0828 39.1248 38.3465 38.4058C36.6102 37.6867 34.7493 37.3167 32.87 37.317H31.13C29.2507 37.3167 27.3898 37.6867 25.6535 38.4058C23.9172 39.1248 22.3396 40.1789 21.0107 41.5077C19.6819 42.8366 18.6278 44.4142 17.9087 46.1505C17.1897 47.8868 16.8197 49.7477 16.82 51.627V55.997"
                                    stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M32.003 37.317C37.1624 37.317 41.345 33.1344 41.345 27.975C41.345 22.8156 37.1624 18.633 32.003 18.633C26.8436 18.633 22.661 22.8156 22.661 27.975C22.661 33.1344 26.8436 37.317 32.003 37.317Z"
                                    stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M52.003 7.99699H12.003C10.9421 7.99699 9.92472 8.41842 9.17457 9.16857C8.42443 9.91871 8.003 10.9361 8.003 11.997V51.997C8.003 53.0579 8.42443 54.0753 9.17457 54.8254C9.92472 55.5756 10.9421 55.997 12.003 55.997H52.003C53.0639 55.997 54.0813 55.5756 54.8314 54.8254C55.5816 54.0753 56.003 53.0579 56.003 51.997V11.997C56.003 10.9361 55.5816 9.91871 54.8314 9.16857C54.0813 8.41842 53.0639 7.99699 52.003 7.99699V7.99699Z"
                                    stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_14_1955">
                                    <rect width="52" height="52" fill="white" transform="translate(6 6)" />
                                </clipPath>
                            </defs>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg lg:text-xl font-bold mb-3 sm:mb-4 text-[#092C48]">Detailed
                            tender information</h2>
                        <p class="text-sm sm:text-base text-gray-700 leading-relaxed">Know who issued the tender, the
                            location, closing date and the full tender
                            description. we link you directly to the tender portal where the opportunity was initially
                            advertised, so you don't have to go hunting for it.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="flex flex-col items-center text-center">
                    <div
                        class="flex justify-center bg-[#416D94] w-16 h-16 sm:w-20 sm:h-20 rounded-full items-center mb-4 sm:mb-6">
                        <svg fill="#ffffff" width="60px" height="60px" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10,21h4a2,2,0,0,1-4,0ZM3.076,18.383a1,1,0,0,1,.217-1.09L5,15.586V10a7.006,7.006,0,0,1,6-6.92V2a1,1,0,0,1,2,0V3.08A7.006,7.006,0,0,1,19,10v5.586l1.707,1.707A1,1,0,0,1,20,19H4A1,1,0,0,1,3.076,18.383ZM6.414,17H17.586l-.293-.293A1,1,0,0,1,17,16V10A5,5,0,0,0,7,10v6a1,1,0,0,1-.293.707Z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg lg:text-xl font-bold mb-3 sm:mb-4 text-[#092C48]">Tender alerts
                        </h2>
                        <p class="text-sm sm:text-base text-gray-700 leading-relaxed">We notify you when tender
                            opportunities get released in your industry. Your account manager will work with you to find
                            the right filters and ensure you never miss an open tender.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="flex flex-col items-center text-center md:col-span-2 lg:col-span-1">
                    <div
                        class="flex justify-center bg-[#416D94] w-16 h-16 sm:w-20 sm:h-20 rounded-full items-center mb-4 sm:mb-6">
                        <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_15_200)">
                                <rect width="24" height="24" fill="none" />
                                <circle cx="12" cy="13" r="2" stroke="#ffffff" stroke-linejoin="round" />
                                <path
                                    d="M12 7.5C7.69517 7.5 4.47617 11.0833 3.39473 12.4653C3.14595 12.7832 3.14595 13.2168 3.39473 13.5347C4.47617 14.9167 7.69517 18.5 12 18.5C16.3048 18.5 19.5238 14.9167 20.6053 13.5347C20.8541 13.2168 20.8541 12.7832 20.6053 12.4653C19.5238 11.0833 16.3048 7.5 12 7.5Z"
                                    stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_15_200">
                                    <rect width="24" height="24" fill="none" />
                                </clipPath>
                            </defs>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg lg:text-xl font-bold mb-3 sm:mb-4 text-[#092C48]">Watchlist</h2>
                        <p class="text-sm sm:text-base text-gray-700 leading-relaxed">Keep track of tenders you are
                            interested in with our tender watchlist feature. We will let you know if any details of the
                            opportunity change through our email notifications.</p>
                    </div>
                </div>
                <!-- Feature 4 -->
                <div class="flex flex-col items-center text-center md:col-span-2 lg:col-span-1">
                    <div
                        class="flex justify-center bg-[#416D94] w-16 h-16 sm:w-20 sm:h-20 rounded-full items-center mb-4 sm:mb-6">
                        <svg fill="#ffffff" width="60px" height="60px" viewBox="0 0 32 32"
                            style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"
                            version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                            xmlns:serif="http://www.serif.com/" xmlns:xlink="http://www.w3.org/1999/xlink">

                            <g id="Icon">

                                <path
                                    d="M4.96,27.999l0.051,0.001l0.043,-0.004c0.191,-0.024 0.957,-0.171 0.957,-0.996c-0,-4.971 4.029,-9 9,-9c0.661,-0 1.327,-0 1.988,-0c4.97,-0 9,4.029 9,9l-0,0c-0,0.021 0,0.041 0.002,0.061c0.015,0.325 0.153,0.537 0.323,0.676c0.178,0.164 0.415,0.263 0.675,0.263c-0,0 1,-0.057 1,-1c-0,-6.075 -4.925,-11 -11,-11c-0.661,-0 -1.327,-0 -1.988,-0c-6.075,-0 -11,4.925 -11,11c-0,-0.05 0.003,-0.092 0.008,-0.127c-0.005,0.041 -0.008,0.084 -0.008,0.127c-0,0.535 0.42,0.972 0.949,0.999Z" />

                                <path
                                    d="M15.994,3.988c-2.763,-0 -5.006,2.243 -5.006,5.006c-0,2.763 2.243,5.006 5.006,5.006c2.763,0 5.006,-2.243 5.006,-5.006c0,-2.763 -2.243,-5.006 -5.006,-5.006Zm-0,2c1.659,-0 3.006,1.347 3.006,3.006c0,1.659 -1.347,3.006 -3.006,3.006c-1.659,0 -3.006,-1.347 -3.006,-3.006c-0,-1.659 1.347,-3.006 3.006,-3.006Z" />

                            </g>

                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg lg:text-xl font-bold mb-3 sm:mb-4 text-[#092C48]">Account
                            manager</h2>
                        <p class="text-sm sm:text-base text-gray-700 leading-relaxed">You'll get an account manager that
                            is there to support you through the tender search process. They can help you set up tender
                            alerts, direct you to helpful tender writers, and ensure you're getting the most out of your
                            subscription.</p>
                    </div>
                </div>
                <!-- Feature 5 -->
                <div class="flex flex-col items-center text-center md:col-span-2 lg:col-span-1">
                    <div
                        class="flex justify-center bg-[#416D94] w-16 h-16 sm:w-20 sm:h-20 rounded-full items-center mb-4 sm:mb-6">
                        <svg width="60px" height="60px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M15 1.25H10.9436C9.10583 1.24998 7.65019 1.24997 6.51098 1.40314C5.33856 1.56076 4.38961 1.89288 3.64124 2.64124C2.89288 3.38961 2.56076 4.33856 2.40314 5.51098C2.24997 6.65019 2.24998 8.10582 2.25 9.94357V16C2.25 17.8722 3.62205 19.424 5.41551 19.7047C5.55348 20.4687 5.81753 21.1208 6.34835 21.6517C6.95027 22.2536 7.70814 22.5125 8.60825 22.6335C9.47522 22.75 10.5775 22.75 11.9451 22.75H15.0549C16.4225 22.75 17.5248 22.75 18.3918 22.6335C19.2919 22.5125 20.0497 22.2536 20.6517 21.6517C21.2536 21.0497 21.5125 20.2919 21.6335 19.3918C21.75 18.5248 21.75 17.4225 21.75 16.0549V10.9451C21.75 9.57754 21.75 8.47522 21.6335 7.60825C21.5125 6.70814 21.2536 5.95027 20.6517 5.34835C20.1208 4.81753 19.4687 4.55348 18.7047 4.41551C18.424 2.62205 16.8722 1.25 15 1.25ZM17.1293 4.27117C16.8265 3.38623 15.9876 2.75 15 2.75H11C9.09318 2.75 7.73851 2.75159 6.71085 2.88976C5.70476 3.02502 5.12511 3.27869 4.7019 3.7019C4.27869 4.12511 4.02502 4.70476 3.88976 5.71085C3.75159 6.73851 3.75 8.09318 3.75 10V16C3.75 16.9876 4.38624 17.8265 5.27117 18.1293C5.24998 17.5194 5.24999 16.8297 5.25 16.0549V10.9451C5.24998 9.57754 5.24996 8.47522 5.36652 7.60825C5.48754 6.70814 5.74643 5.95027 6.34835 5.34835C6.95027 4.74643 7.70814 4.48754 8.60825 4.36652C9.47522 4.24996 10.5775 4.24998 11.9451 4.25H15.0549C15.8297 4.24999 16.5194 4.24998 17.1293 4.27117ZM7.40901 6.40901C7.68577 6.13225 8.07435 5.9518 8.80812 5.85315C9.56347 5.75159 10.5646 5.75 12 5.75H15C16.4354 5.75 17.4365 5.75159 18.1919 5.85315C18.9257 5.9518 19.3142 6.13225 19.591 6.40901C19.8678 6.68577 20.0482 7.07435 20.1469 7.80812C20.2484 8.56347 20.25 9.56458 20.25 11V16C20.25 17.4354 20.2484 18.4365 20.1469 19.1919C20.0482 19.9257 19.8678 20.3142 19.591 20.591C19.3142 20.8678 18.9257 21.0482 18.1919 21.1469C17.4365 21.2484 16.4354 21.25 15 21.25H12C10.5646 21.25 9.56347 21.2484 8.80812 21.1469C8.07435 21.0482 7.68577 20.8678 7.40901 20.591C7.13225 20.3142 6.9518 19.9257 6.85315 19.1919C6.75159 18.4365 6.75 17.4354 6.75 16V11C6.75 9.56458 6.75159 8.56347 6.85315 7.80812C6.9518 7.07435 7.13225 6.68577 7.40901 6.40901Z"
                                fill="#ffffff" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg lg:text-xl font-bold mb-3 sm:mb-4 text-[#092C48]">Resources</h2>
                        <p class="text-sm sm:text-base text-gray-700 leading-relaxed">We have tons of resources to help
                            you not only find tenders but win them too. We have training programs to teach you the ropes
                            and templates for the tender response process. Plus, our newsletter and blog keep you up to
                            date on all things procurement!</p>
                    </div>
                </div>
                <!-- Feature 6 -->
                <div class="flex flex-col items-center text-center md:col-span-2 lg:col-span-1">
                    <div
                        class="flex justify-center bg-[#416D94] w-16 h-16 sm:w-20 sm:h-20 rounded-full items-center mb-4 sm:mb-6">
                        <svg width="60px" height="60px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#ffffff"
                                d="M480 896V702.08A256.256 256.256 0 0 1 264.064 512h-32.64a96 96 0 0 1-91.968-68.416L93.632 290.88a76.8 76.8 0 0 1 73.6-98.88H256V96a32 32 0 0 1 32-32h448a32 32 0 0 1 32 32v96h88.768a76.8 76.8 0 0 1 73.6 98.88L884.48 443.52A96 96 0 0 1 792.576 512h-32.64A256.256 256.256 0 0 1 544 702.08V896h128a32 32 0 1 1 0 64H352a32 32 0 1 1 0-64h128zm224-448V128H320v320a192 192 0 1 0 384 0zm64 0h24.576a32 32 0 0 0 30.656-22.784l45.824-152.768A12.8 12.8 0 0 0 856.768 256H768v192zm-512 0V256h-88.768a12.8 12.8 0 0 0-12.288 16.448l45.824 152.768A32 32 0 0 0 231.424 448H256z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg lg:text-xl font-bold mb-3 sm:mb-4 text-[#092C48]">Awarded
                            tenders</h2>
                        <p class="text-sm sm:text-base text-gray-700 leading-relaxed">Know who is winning tenders in
                            your industry, what value and date the tender was awarded. This information can help you
                            understand your competitors, know when contracts are up for tender again, and how you need
                            to price to win!</p>
                    </div>
                </div>
                <!-- Feature 7 -->
                <div class="flex flex-col items-center text-center md:col-span-2 lg:col-span-1">
                    <div
                        class="flex justify-center bg-[#416D94] w-16 h-16 sm:w-20 sm:h-20 rounded-full items-center mb-4 sm:mb-6">
                        <svg width="50px" height="50px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">

                            <defs>

                                <style>
                                    .cls-1,
                                    .cls-2 {
                                        fill: none;
                                        stroke: #ffffff;
                                        stroke-miterlimit: 10;
                                        stroke-width: 1.91px;
                                    }

                                    .cls-1 {
                                        stroke-linecap: square;
                                    }
                                </style>

                            </defs>

                            <g id="calendar">

                                <rect class="cls-1" x="1.5" y="2.43" width="21" height="4.77"
                                    transform="translate(24 9.64) rotate(180)" />

                                <line class="cls-2" x1="17.73" y1="0.52" x2="17.73" y2="4.34" />

                                <line class="cls-2" x1="6.27" y1="0.52" x2="6.27" y2="4.34" />

                                <polygon class="cls-1"
                                    points="22.5 11.98 22.5 7.21 1.5 7.21 1.5 22.48 22.5 22.48 22.5 15.79 22.5 11.98" />

                                <line class="cls-2" x1="9.14" y1="11.02" x2="11.05" y2="11.02" />

                                <line class="cls-2" x1="12.95" y1="11.02" x2="14.86" y2="11.02" />

                                <line class="cls-2" x1="16.77" y1="11.02" x2="18.68" y2="11.02" />

                                <line class="cls-2" x1="9.14" y1="14.84" x2="11.05" y2="14.84" />

                                <line class="cls-2" x1="5.32" y1="14.84" x2="7.23" y2="14.84" />

                                <line class="cls-2" x1="12.95" y1="14.84" x2="14.86" y2="14.84" />

                                <line class="cls-2" x1="16.77" y1="14.84" x2="18.68" y2="14.84" />

                                <line class="cls-2" x1="9.14" y1="18.66" x2="11.05" y2="18.66" />

                                <line class="cls-2" x1="5.32" y1="18.66" x2="7.23" y2="18.66" />

                                <line class="cls-2" x1="12.95" y1="18.66" x2="14.86" y2="18.66" />

                            </g>

                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg lg:text-xl font-bold mb-3 sm:mb-4 text-[#092C48]">Future tenders
                        </h2>
                        <p class="text-sm sm:text-base text-gray-700 leading-relaxed">Stay ahead by knowing what's
                            coming up in your industry! Keeping informed of upcoming tenders gives you the opportunity
                            to plan ahead, allocate resources, and prepare a winning bid..</p>
                    </div>
                </div>
                <!-- Feature 8 -->
                <div class="flex flex-col items-center text-center md:col-span-2 lg:col-span-1">
                    <div
                        class="flex justify-center bg-[#416D94] w-16 h-16 sm:w-20 sm:h-20 rounded-full items-center mb-4 sm:mb-6">
                        <svg width="60px" height="60px" viewBox="0 0 48 48" id="Layer_2" data-name="Layer 2"
                            xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <style>
                                    .cls-1 {
                                        fill: none;
                                        stroke: #ffffff;
                                        stroke-linecap: round;
                                        stroke-linejoin: round;
                                    }
                                </style>
                            </defs>
                            <path class="cls-1"
                                d="M10.35,4.5a2,2,0,0,0-1.95,2v35.1a2,2,0,0,0,1.95,2h27.3a2,2,0,0,0,2-2V6.45a2,2,0,0,0-2-1.95h-2v8.82L31.79,9.41l-3.88,3.91V4.5Zm5.84,20H32.81M16.19,36.18H28.88M16.19,30.33h9.74" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg lg:text-xl font-bold mb-3 sm:mb-4 text-[#092C48]">Tender archive
                        </h2>
                        <p class="text-sm sm:text-base text-gray-700 leading-relaxed">Access and search a comprehensive
                            archive
                            of past tenders, projects and contracts dating back 10 years.</p>
                    </div>
                </div>
                <!-- Feature 9 -->
                <div class="flex flex-col items-center text-center md:col-span-2 lg:col-span-1">
                    <div
                        class="flex justify-center bg-[#416D94] w-16 h-16 sm:w-20 sm:h-20 rounded-full items-center mb-4 sm:mb-6">
                        <svg fill="#ffffff" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="60px" height="60px"
                            viewBox="0 0 31.725 31.725" xml:space="preserve">
                            <g>
                                <path d="M30.611,18.54v-3.817c0.708-0.605,1.113-1.479,1.113-2.415c0-1.323-0.832-2.521-2.071-2.982L18.212,5.068
                                            c-1.55-0.577-3.276-0.574-4.822,0.007L2.063,9.328C0.83,9.791,0,10.988,0,12.307c0,1.317,0.83,2.514,2.063,2.976L6.3,16.875
                                            l0.009,5.655c0.197,2.99,4.928,4.557,9.499,4.557s9.302-1.564,9.5-4.571l0.008-5.612l3.679-1.371v3.009
                                            c-0.545,0.395-0.892,1.165-0.892,2.015c0,1.123,0,2.284,1.699,2.284c1.698,0,1.698-1.161,1.698-2.284
                                            C31.503,19.706,31.157,18.935,30.611,18.54z M8.778,17.806l4.612,1.732c1.545,0.582,3.274,0.584,4.825,0.009l4.627-1.725v4.521
                                            c0,0.773-2.738,2.257-7.032,2.257c-4.292,0-7.031-1.481-7.031-2.257L8.778,17.806L8.778,17.806z M14.625,16.98L5.168,13.43
                                            c-0.085-0.032-0.19-0.068-0.308-0.107c-0.542-0.181-1.552-0.518-1.552-1.014c0-0.506,0.964-0.822,1.539-1.012
                                            c0.123-0.04,0.232-0.077,0.321-0.109l9.455-3.553c0.381-0.144,0.78-0.215,1.185-0.215c0.404,0,0.799,0.07,1.177,0.211l9.551,3.556
                                            c0.077,0.029,0.172,0.062,0.278,0.096c0.52,0.179,1.603,0.544,1.603,1.026c0,0.194-0.317,0.543-1.882,1.122l-9.549,3.554
                                            C16.229,17.267,15.384,17.265,14.625,16.98z" />
                            </g>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg lg:text-xl font-bold mb-3 sm:mb-4 text-[#092C48]">Tender
                            training</h2>
                        <p class="text-sm sm:text-base text-gray-700 leading-relaxed">All plans give you access to
                            exclusive discounts on training courses with Tender Training College. Their tailored
                            training helps you master procurement at any level and provides you with the skills and
                            knowledge to bid with confidence.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection