<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Spanz')</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
    <style>
        .menu-container {
            position: relative;
        }
        
        .menu-dropdown {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }
        
        .menu-dropdown:not(.hidden) {
            max-height: 500px; /* Adjust based on your content */
        }
        
        .menu-dropdown.hidden {
            max-height: 0;
        }
    </style>
</head>

<body>
    @yield('content')

    <script>
        // Toggle Buyer Dropdown
        function toggleDropdown() {
            const dropdown = document.getElementById('buyerDropdown');
            const arrow = document.getElementById('dropdownArrow');
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                dropdown.style.maxHeight = dropdown.scrollHeight + 'px';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.style.maxHeight = '0px';
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 300);
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Toggle Supplier Dropdown
        function toggleSupplierDropdown() {
            const dropdown = document.getElementById('supplierDropdown');
            const arrow = document.getElementById('supplierDropdownArrow');
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                dropdown.style.maxHeight = dropdown.scrollHeight + 'px';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.style.maxHeight = '0px';
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 300);
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Toggle Tender Dropdown
        function toggleTenderDropdown() {
            const dropdown = document.getElementById('tenderDropdown');
            const arrow = document.getElementById('tenderDropdownArrow');
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                dropdown.style.maxHeight = dropdown.scrollHeight + 'px';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.style.maxHeight = '0px';
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 300);
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Toggle Categories Dropdown
        function toggleCategoriesDropdown() {
            const dropdown = document.getElementById('categoriesDropdown');
            const arrow = document.getElementById('categoriesDropdownArrow');
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                dropdown.style.maxHeight = dropdown.scrollHeight + 'px';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.style.maxHeight = '0px';
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 300);
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Edit Profile Modal Functions
        function openEditModal() {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        // Preview Image Function
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('modalProfileImage').src = e.target.result;
                    document.getElementById('profileImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Save Profile Function
        function saveProfile(event) {
            event.preventDefault();
            
            // Get form data
            const formData = new FormData();
            const nameInput = document.getElementById('profileNameInput');
            const imageInput = document.getElementById('profileImageInput');
            
            // Update profile name in sidebar
            document.getElementById('profileName').textContent = nameInput.value;
            
            // Close modal
            closeEditModal();
            
            // Here you would typically send the data to your backend
            // For now, we'll just show a success message
            alert('Profile updated successfully!');
        }

        // Initialize dropdowns on page load
        document.addEventListener('DOMContentLoaded', function() {
            const buyerDropdown = document.getElementById('buyerDropdown');
            const supplierDropdown = document.getElementById('supplierDropdown');
            const tenderDropdown = document.getElementById('tenderDropdown');
            const categoriesDropdown = document.getElementById('categoriesDropdown');
            
            // Set initial max-height to 0 for smooth animations
            if (buyerDropdown) {
                buyerDropdown.style.maxHeight = '0px';
            }
            if (supplierDropdown) {
                supplierDropdown.style.maxHeight = '0px';
            }
            if (tenderDropdown) {
                tenderDropdown.style.maxHeight = '0px';
            }
            if (categoriesDropdown) {
                categoriesDropdown.style.maxHeight = '0px';
            }
        });
    </script>
</body>

</html>