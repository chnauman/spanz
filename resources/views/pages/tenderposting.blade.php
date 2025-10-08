@extends('layouts.dashlayout')
@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
    <div class="border border-gray-300 p-3 sm:p-4 lg:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
            <h1 class="text-xl sm:text-2xl font-bold">Submit your Purchasing Request (RFX)</h1>
        </div>
        <form action="#" method="post" class="space-y-4 sm:space-y-6">
            <div class="mt-6 sm:mt-8 lg:mt-10">
                <label for="comp" class="block text-sm font-medium text-gray-700 mb-2">Request Type <span
                        class="text-red-500">*</span></label>
                <select id="comp" name="comp"
                    class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                    <option value="">Select request type</option>
                    <option value="aerospace-defense">Request for Quote (RFQ)</option>
                    <option value="agriculture">Request for Tender (RFT)</option>
                    <option value="automotive">Request for Proposal (RFP)</option>
                    <option value="chemicals">Request for Expression of Interest (EOI)</option>
                </select>
            </div>
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Estimated Budget <span
                        class="text-red-500">*</span></label>
                <select id="amount" name="amount"
                    class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                    <option value="">Select amount</option>
                    <option value="1000">Less than 1,000 AUD</option>
                    <option value="5000">1,000 - 5,000 AUD</option>
                    <option value="10000">5,000 - 10,000 AUD</option>
                    <option value="30000">10,000 - 30,000 AUD</option>
                    <option value="50000">30,000 - 50,000 AUD</option>
                    <option value="100000">50,000 - 100,000 AUD</option>
                    <option value="500000">100,000 - 500,000 AUD</option>
                    <option value="1000000">500,000 - 1 million AUD</option>
                    <option value="1000001">over 1 million AUD</option>
                </select>
            </div>
            <div>
                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Location <span
                        class="text-red-500">*</span></label>
                <select id="country" name="country"
                    class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                    <option value="">Select Country</option>
                    <option value="australia">Australia</option>
                    <option value="new-zealand">New Zealand</option>
                    <option value="singapore">Singapore</option>
                    <option value="usa">United States</option>
                    <option value="uk">United Kingdom</option>
                    <option value="canada">Canada</option>
                    <option value="germany">Germany</option>
                    <option value="france">France</option>
                </select>
            </div>
            <div>
                <label for="text" class="block text-sm font-medium text-gray-700 mb-2">Products or Services <span
                        class="text-red-500">*</span></label>
                <input type="text" id="text" name="text" placeholder="Short title of Products or services required"
                    class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder-gray-400">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description <span
                        class="text-red-500">*</span></label>
                <textarea id="description" name="description" rows="4"
                    placeholder="Description or bullet list of Scope of Works and what is required"
                    class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder-gray-400 resize-y min-h-[100px]"></textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- main category -->
                <div class="mt-2 sm:mt-4 lg:mt-6 required">
                    <select id="maincategory" name="maincategory"
                        class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                        <option value="">Select main category</option>
                        <option value="australia">Aviation</option>
                        <option value="new-zealand">Construction</option>
                        <option value="singapore">Maritime</option>
                        <option value="usa">Agriculture</option>
                        <option value="uk">Engineering</option>
                        <option value="canada">Biomedical</option>
                        <option value="germany">Computers</option>
                        <option value="france">Aerospace</option>
                    </select>
                </div>
                <!-- sub category -->

                <div class="mt-2 sm:mt-4 lg:mt-6 required">
                    <select id="subcategory" name="subcategory"
                        class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                        <option value="">Select sub category</option>
                        <option value="australia">Electrical</option>
                        <option value="new-zealand">Mechanical</option>
                        <option value="singapore">Engines</option>
                        <option value="usa">Avionics</option>
                        <option value="uk">Auxiliary Power Units (APUs)</option>
                        <option value="canada">Navigation systems</option>
                        <option value="germany">Communication systems (radio, satellite)</option>
                    </select>
                </div>
                <!-- product type -->
                <div class="mt-2 sm:mt-4 lg:mt-6 required flex items-end">
                    <select id="producttype" name="producttype"
                        class="w-full px-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                        <option value="">Select product type</option>
                        <option value="australia">100% of total budget</option>
                        <option value="new-zealand">90% of total budget</option>
                        <option value="singapore">80% of total budget</option>
                        <option value="usa">70% of total budget</option>
                        <option value="uk">60% of total budget</option>
                        <option value="canada">50% of total budget</option>
                        <option value="germany">40% of total budget</option>
                        <option value="france">30% of total budget</option>
                        <option value="france">20% of total budget</option>
                        <option value="france">10% of total budget</option>
                        <option value="france">Less than 10%</option>
                    </select>
                    <button type="button"
                        class="ml-3 p-2 text-gray-500 hover:text-red-500 transition-colors flex-shrink-0"
                        title="Remove category">
                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M14 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M4 7H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M6 7H12H18V18C18 19.6569 16.6569 21 15 21H9C7.34315 21 6 19.6569 6 18V7Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <button type="button"
                    class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors text-sm sm:text-base">+
                    Add Another Line</button>
            </div>
            <div>
                <h2 class="mb-4 mt-6 sm:mt-8 text-lg sm:text-xl font-semibold text-gray-800">Attach Project Files</h2>

                <!-- Custom File Input -->
                <div
                    class="border-2 border-gray-300 border-dashed h-48 sm:h-60 rounded-md p-4 sm:p-6 w-full flex flex-col items-center justify-center bg-gray-50 hover:bg-gray-100 transition-colors relative">
                    <!-- Hidden actual file input -->
                    <input type="file" name="file" id="file" multiple
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        onchange="handleFileSelect(this)">

                    <!-- Custom button design -->
                    <div class="text-center">
                        <svg class="mx-auto h-8 w-8 sm:h-12 sm:w-12 text-gray-400 mb-3 sm:mb-4" stroke="currentColor"
                            fill="none" viewBox="0 0 48 48">
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>

                        <p class="text-gray-600 text-base sm:text-lg mb-1 sm:mb-2">
                            <span class="font-semibold">Click to upload</span> <span class="hidden sm:inline">or drag
                                and drop</span>
                        </p>
                        <p class="text-gray-500 text-xs sm:text-sm mb-3 sm:mb-4">PNG, JPG, PDF up to 10MB</p>

                        <!-- Custom Choose Files Button -->
                        <button type="button"
                            class="bg-[#0D6AED] text-white px-4 sm:px-6 py-2 sm:py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium text-sm sm:text-base">
                            Choose Files
                        </button>

                        <!-- Selected files display -->
                        <div id="selectedFiles" class="mt-4 text-left hidden">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Selected Files:</h4>
                            <div id="fileList" class="space-y-1"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Uploaded Files Display Section -->
            <div id="uploadedFilesSection" class="mt-6 sm:mt-8 hidden">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Uploaded Files</h2>
                <div id="uploadedFilesList" class="space-y-3">
                    <!-- Uploaded files will appear here -->
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 sm:pt-8 border-t border-gray-200">
                <button type="reset"
                    class="w-full sm:w-auto px-6 py-2 sm:py-3 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors text-sm sm:text-base font-medium">
                    Reset Form
                </button>
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-2 sm:py-3 bg-[#0D6AED] text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors text-sm sm:text-base font-medium">
                    Submit Request
                </button>
            </div>
        </form>
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