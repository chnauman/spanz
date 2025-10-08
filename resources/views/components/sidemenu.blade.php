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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Filter Controls -->
            <div class="flex flex-wrap gap-2 mb-4">
                <button class="border border-black px-3 py-1.5 bg-white hover:bg-gray-100 rounded-sm text-sm">Collapse
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
                    <img src="{{ asset('spanz-img/plus.svg') }}" alt="Expand" class="w-4 h-4">
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
                            <input type="checkbox" aria-label="Distributor checkbox is not selected" id="North Carolina"
                                readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw"
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
                        <img src="{{ asset('spanz-img/plus.svg') }}" alt="" class="w-4 ">
                        <button class="pl-2">Show More</button>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<!-- Desktop Grid Layout -->
<!-- <div class="block lg:grid lg:grid-cols-12 w-full bg-slate-50"> -->
<div class="hidden lg:block lg:col-span-2 p-4 lg:pl-10">
    <div class="flex gap-2 items-center mb-4">
        <img src="{{ asset('spanz-img/filter.svg') }}" alt="Filter" class="w-5 h-5">
        <span class="text-sm font-medium">Filter</span>
    </div>
    <div class="flex flex-wrap gap-2 mb-4">
        <button
            class="border border-black px-3 py-1.5 bg-white hover:bg-gray-100 rounded-sm text-xs sm:text-sm">Collapse
            All</button>
        <button class="border border-black px-3 py-1.5 bg-white hover:bg-gray-100 rounded-sm text-xs sm:text-sm">Clear
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
            <img src="{{ asset('spanz-img/plus.svg') }}" alt="Expand" class="w-4 h-4">
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
                    <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="m-M" readonly=""
                        class="sda-unmasked Filter_checkbox__nQ4Qw" data-ref="srp.filter.manufacturer">
                    <label for="m-M" class="txt-body-sm  mar-l-2"><a kind="dark"
                            class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline ">Manufacturer</a></label>
                </div>
                <div class="flex align-items-center gap-3 ">
                    <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="m-D" readonly=""
                        class="sda-unmasked Filter_checkbox__nQ4Qw" data-ref="srp.filter.manufacturer">
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
                    <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="California-South"
                        readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw" data-ref="srp.filter.manufacturer">
                    <label for="California-South" class="txt-body-sm  mar-l-2"><a kind="dark"
                            class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline ">California-South</a></label>
                </div>
                <div class="flex align-items-center gap-3 ">
                    <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Colorado" readonly=""
                        class="sda-unmasked Filter_checkbox__nQ4Qw" data-ref="srp.filter.manufacturer">
                    <label for="Colorado" class="txt-body-sm  mar-l-2"><a kind="dark"
                            class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Colorado</a></label>
                </div>
                <div class="flex align-items-center gap-3 ">
                    <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Colorado" readonly=""
                        class="sda-unmasked Filter_checkbox__nQ4Qw" data-ref="srp.filter.manufacturer">
                    <label for="Colorado" class="txt-body-sm  mar-l-2"><a kind="dark"
                            class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Colorado</a></label>
                </div>
                <div class="flex align-items-center gap-3 ">
                    <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Florida" readonly=""
                        class="sda-unmasked Filter_checkbox__nQ4Qw" data-ref="srp.filter.manufacturer">
                    <label for="Florida" class="txt-body-sm  mar-l-2"><a kind="dark"
                            class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Florida</a></label>
                </div>
                <div class="flex align-items-center gap-3 ">
                    <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Iowa" readonly=""
                        class="sda-unmasked Filter_checkbox__nQ4Qw" data-ref="srp.filter.manufacturer">
                    <label for="Iowa" class="txt-body-sm  mar-l-2"><a kind="dark"
                            class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Iowa</a></label>
                </div>
                <div class="flex align-items-center gap-3 ">
                    <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Louisiana" readonly=""
                        class="sda-unmasked Filter_checkbox__nQ4Qw" data-ref="srp.filter.manufacturer">
                    <label for="Louisiana" class="txt-body-sm  mar-l-2"><a kind="dark"
                            class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Louisiana</a></label>
                </div>
                <div class="flex align-items-center gap-3 ">
                    <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Michigan" readonly=""
                        class="sda-unmasked Filter_checkbox__nQ4Qw" data-ref="srp.filter.manufacturer">
                    <label for="Michigan" class="txt-body-sm  mar-l-2"><a kind="dark"
                            class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Michigan</a></label>
                </div>
                <div class="flex align-items-center gap-3 ">
                    <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Ohio-North"
                        readonly="" class="sda-unmasked Filter_checkbox__nQ4Qw" data-ref="srp.filter.manufacturer">
                    <label for="Ohio-North" class="txt-body-sm  mar-l-2"><a kind="dark"
                            class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Ohio-North</a></label>
                </div>
                <div class="flex align-items-center gap-3 ">
                    <input type="checkbox" aria-label="Manufacturer checkbox is not selected" id="Oregon" readonly=""
                        class="sda-unmasked Filter_checkbox__nQ4Qw" data-ref="srp.filter.manufacturer">
                    <label for="Oregon" class="txt-body-sm  mar-l-2"><a kind="dark"
                            class="flex align-items-center gap-1 text-sm txt-smallest font-reg hover:underline">Oregon</a></label>
                </div>
                <div
                    class="flex mt-3 items-center rounded-sm gap-04 py-1 justify-center bg-white hover:bg-gray-100 border border-gray-300">
                    <img src="{{ asset('spanz-img/plus.svg') }}" alt="" class="w-4 ">
                    <button class="pl-2">Show More</button>
                </div>
            </section>
        </section>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mobileFilterBtn = document.getElementById('mobile-filter-btn');
        const mobileFilterModal = document.getElementById('mobile-filter-modal');
        const closeFilterModal = document.getElementById('close-filter-modal');

        if (mobileFilterBtn && mobileFilterModal && closeFilterModal) {
            mobileFilterBtn.addEventListener('click', function () {
                mobileFilterModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });

            closeFilterModal.addEventListener('click', function () {
                mobileFilterModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            });

            // Close modal when clicking outside
            mobileFilterModal.addEventListener('click', function (e) {
                if (e.target === mobileFilterModal) {
                    mobileFilterModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });
        }
    });
</script>