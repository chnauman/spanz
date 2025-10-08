@extends('layouts.dashlayout')
@section('title', 'Activetenders - Spanz')
@section('content')
    <div class=" flex flex-col lg:col-span-9 p-5 lg:p-10 overflow-y-auto">
        <div class="text-[#092C48] mb-5">
            <div class="text-sm sm:text-base mb-2">
                <span>Displaying </span>
                <span class="font-semibold">1 to 13 </span>
                <span>out of </span>
                <span class="font-semibold">13 </span>
                <span>suppliers </span>
            </div>
            <p class="text-xl sm:text-2xl lg:text-3xl xl:text-4xl mb-3 font-semibold leading-tight">
                Ultralight Aircraft Manufacturers and Suppliers in the USA and Canada
            </p>
        </div>
        <div class="flex justify-end mb-5 w-full">
            <button class="ml-auto bg-[#0D6AED] hover:bg-blue-700 text-white px-4 py-2 rounded-sm ">Post Tender</button>
        </div>

        <div class="bg-white border border-gray-200 rounded-sm p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
                <p class="text-[#092C48] font-semibold text-lg sm:text-xl">ShadowAir, Ltd.</p>
                <div class="flex gap-4 sm:gap-6">
                    <div class="flex items-center gap-2">
                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.75 6L7.5 5.25H16.5L17.25 6V19.3162L12 16.2051L6.75 19.3162V6ZM8.25 6.75V16.6838L12 14.4615L15.75 16.6838V6.75H8.25Z"
                                fill="#080341" />
                        </svg>
                        <p class="text-[#092C48] text-sm sm:text-lg">Save</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg width="20px" height="20px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
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
                    <h2 class="text-xl font-bold my-3">Tender name</h2>
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
                        Contact Buyer
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
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Helper to safely get element by id
            function $id(id) { return document.getElementById(id); }

            function toggleDropdown() {
                const dropdown = $id('buyerDropdown');
                const arrow = $id('dropdownArrow');
                if (!dropdown || !arrow) return;
                dropdown.classList.toggle('hidden');
                arrow.style.transform = dropdown.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            }

            function toggleSupplierDropdown() {
                const dropdown = $id('supplierDropdown');
                const arrow = $id('supplierDropdownArrow');
                if (!dropdown || !arrow) return;
                dropdown.classList.toggle('hidden');
                arrow.style.transform = dropdown.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            }

            // Expose to global so inline onclick attributes still work
            window.toggleDropdown = toggleDropdown;
            window.toggleSupplierDropdown = toggleSupplierDropdown;

            // Close dropdowns when clicking outside
            document.addEventListener('click', function (event) {
                const button = event.target.closest('button');
                const buyerDropdown = $id('buyerDropdown');
                const supplierDropdown = $id('supplierDropdown');

                // if click is not on a button that triggers our dropdowns, close them
                if (!button || (button && button.onclick && (button.onclick.toString().indexOf('toggleDropdown') === -1 && button.onclick.toString().indexOf('toggleSupplierDropdown') === -1))) {
                    if (buyerDropdown) buyerDropdown.classList.add('hidden');
                    if ($id('dropdownArrow')) $id('dropdownArrow').style.transform = 'rotate(0deg)';
                    if (supplierDropdown) supplierDropdown.classList.add('hidden');
                    if ($id('supplierDropdownArrow')) $id('supplierDropdownArrow').style.transform = 'rotate(0deg)';
                }
            });

            // Edit Profile Modal Functions
            function openEditModal() {
                const modal = $id('editModal');
                const currentImageEl = $id('profileImage');
                const currentNameEl = $id('profileName');
                if (!modal || !currentImageEl || !currentNameEl) return;

                const currentImage = currentImageEl.src;
                const currentName = currentNameEl.textContent;
                const modalImage = $id('modalProfileImage');
                const nameInput = $id('profileNameInput');
                if (modalImage) modalImage.src = currentImage;
                if (nameInput) nameInput.value = currentName;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }

            function closeEditModal() {
                const modal = $id('editModal');
                if (!modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
                const fileInput = $id('profileImageInput');
                if (fileInput) fileInput.value = '';
            }

            function previewImage(event) {
                const file = event.target && event.target.files && event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = $id('modalProfileImage');
                        if (img) img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }

            function saveProfile(event) {
                event.preventDefault();
                const nameInput = $id('profileNameInput');
                const modalImage = $id('modalProfileImage');
                if (!nameInput || !modalImage) return;
                const newName = nameInput.value.trim();
                const newImage = modalImage.src;
                if (newName === '') {
                    alert('Please enter a valid name');
                    return;
                }
                const profileName = $id('profileName');
                const profileImage = $id('profileImage');
                if (profileName) profileName.textContent = newName;
                if (profileImage) profileImage.src = newImage;
                closeEditModal();
                alert('Profile updated successfully!');
            }

            // wire file input and save form if elements exist
            const fileInputEl = $id('profileImageInput');
            if (fileInputEl) fileInputEl.addEventListener('change', previewImage);
            const saveForm = fileInputEl && fileInputEl.closest('form') ? fileInputEl.closest('form') : $id('profileImageInput') && $id('profileImageInput').form;
            // If you have an explicit form element use its submit handler, otherwise bind saveProfile to window so inline handlers work
            if (saveForm && saveForm.addEventListener) {
                saveForm.addEventListener('submit', saveProfile);
            } else {
                window.saveProfile = saveProfile;
            }

            // Close modal by clicking outside
            const editModalEl = $id('editModal');
            if (editModalEl) {
                editModalEl.addEventListener('click', function (event) {
                    if (event.target === this) closeEditModal();
                });
            }

            // Expose modal functions globally for svg onclick or other inline handlers
            window.openEditModal = openEditModal;
            window.closeEditModal = closeEditModal;
        });
    </script>
@endsection