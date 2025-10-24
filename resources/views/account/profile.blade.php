@extends('layouts.admin')

@section('title', 'Profile Settings')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">Profile Settings</h1>
                <p class="text-sm text-blue-200">Update your personal information and profile photo</p>
            </div>

            <div class="mt-6">
                <form id="profileForm" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Profile Photo Section -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-4">Profile Photo</label>
                            <div class="flex items-center space-x-6">
                                <div class="relative"
                                     ondrop="handleDrop(event)"
                                     ondragover="handleDragOver(event)"
                                     ondragenter="handleDragEnter(event)"
                                     ondragleave="handleDragLeave(event)">
                                    @php
                                        $profilePhotoUrl = null;
                                        foreach (['jpg','jpeg','png','webp'] as $ext) {
                                            $candidate = 'profile-photos/' . $user->id . '.' . $ext;
                                            if (\Storage::disk('public')->exists($candidate)) {
                                                $profilePhotoUrl = asset('storage/' . $candidate) . '?t=' . time();
                                                break;
                                            }
                                        }
                                    @endphp
                                    <img id="profileImagePreview" src="{{ $profilePhotoUrl ?: asset('spanz-img/profile.jpg') }}"
                                         alt="Profile Photo" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 transition-opacity duration-200">
                                    <input type="file" id="profilePhotoInput" name="photo" accept="image/*" class="hidden" onchange="previewImage(event)">
                                    <div id="imageStatus" class="text-xs text-gray-500 mt-1 text-center hidden">
                                        <span id="statusText">Image selected</span>
                                    </div>
                                    <div id="dragOverlay" class="absolute inset-0 bg-blue-500 bg-opacity-20 rounded-full border-4 border-blue-500 border-dashed hidden flex items-center justify-center">
                                        <span class="text-blue-600 font-medium">Drop image here</span>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" onclick="document.getElementById('profilePhotoInput').click()"
                                            class="px-4 py-2 bg-[#0D6AED] text-white rounded-lg hover:bg-[#0B5AC7] transition-colors duration-200">
                                        Change Photo
                                    </button>
                                    <p class="text-sm text-gray-500 mt-2">JPG, PNG or WEBP. Max size 2MB.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ $user->name }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0D6AED] focus:border-transparent"
                                   required>
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ $user->email }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0D6AED] focus:border-transparent"
                                   readonly>
                            <p class="text-sm text-gray-500 mt-1">Email cannot be changed. Contact support if needed.</p>
                        </div>

                        <!-- Role Field (Read-only) -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Account Type</label>
                            <input type="text" id="role" value="{{ ucfirst(str_replace('_', ' ', $user->role)) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                   readonly>
                        </div>

                        <!-- Company Name (if available) -->
                        @if($user->companyDetail)
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                            <input type="text" id="company_name" value="{{ $user->companyDetail->company_name }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                   readonly>
                        </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <button type="button" onclick="window.history.back()"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" id="saveButton"
                                class="px-6 py-2 bg-[#0D6AED] text-white rounded-lg hover:bg-[#0B5AC7] transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="saveButtonText">Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const previewElement = document.getElementById('profileImagePreview');

    console.log('previewImage called with file:', file);

    if (file && previewElement) {
        // Show loading state
        previewElement.style.opacity = '0.5';
        previewElement.alt = 'Loading...';

        // Validate file type
        if (!file.type.startsWith('image/')) {
            showErrorAlert('Invalid File Type', 'Please select an image file (JPG, PNG, WEBP, etc.).');
            event.target.value = ''; // Clear the input
            previewElement.style.opacity = '1';
            previewElement.alt = 'Profile Photo';
            return;
        }

        // Validate file size (2MB limit)
        if (file.size > 2 * 1024 * 1024) {
            showErrorAlert('File Too Large', 'File size must be less than 2MB. Please choose a smaller image.');
            event.target.value = ''; // Clear the input
            previewElement.style.opacity = '1';
            previewElement.alt = 'Profile Photo';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewElement.src = e.target.result;
            previewElement.style.opacity = '1';
            previewElement.alt = 'Profile Photo';

            // Show status indicator
            const statusElement = document.getElementById('imageStatus');
            const statusText = document.getElementById('statusText');
            if (statusElement && statusText) {
                statusText.textContent = 'New image selected';
                statusElement.classList.remove('hidden');
                statusElement.classList.add('text-green-600');
            }

            console.log('Image preview updated successfully');
        };
        reader.onerror = function() {
            console.error('Error reading file');
            showErrorAlert('File Read Error', 'Error reading the selected file. Please try again.');
            previewElement.style.opacity = '1';
            previewElement.alt = 'Profile Photo';
        };
        reader.readAsDataURL(file);
    } else {
        console.error('No file selected or preview element not found');
        if (previewElement) {
            previewElement.style.opacity = '1';
            previewElement.alt = 'Profile Photo';
        }
    }
}

// Drag and drop functions
function handleDragOver(event) {
    event.preventDefault();
}

function handleDragEnter(event) {
    event.preventDefault();
    document.getElementById('dragOverlay').classList.remove('hidden');
}

function handleDragLeave(event) {
    event.preventDefault();
    document.getElementById('dragOverlay').classList.add('hidden');
}

function handleDrop(event) {
    event.preventDefault();
    document.getElementById('dragOverlay').classList.add('hidden');

    const files = event.dataTransfer.files;
    if (files.length > 0) {
        const file = files[0];
        if (file.type.startsWith('image/')) {
            // Create a fake event object for the previewImage function
            const fakeEvent = {
                target: {
                    files: [file],
                    value: ''
                }
            };
            previewImage(fakeEvent);

            // Update the file input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.getElementById('profilePhotoInput').files = dataTransfer.files;
        } else {
            showErrorAlert('Invalid File', 'Please drop an image file.');
        }
    }
}

document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitButton = document.getElementById('saveButton');
    const buttonText = document.getElementById('saveButtonText');
    const originalText = buttonText.textContent;

    // Show loading state
    buttonText.textContent = 'Saving...';
    submitButton.disabled = true;

    // Show loading alert
    showLoadingAlert('Saving Profile', 'Please wait while we update your profile...');

    fetch('{{ route("profile.update") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update the profile image in the sidebar
            const sidebarImage = document.getElementById('profileImage');
            const sidebarName = document.getElementById('profileName');

            if (sidebarImage && data.photo_url) {
                sidebarImage.src = data.photo_url;
            }
            if (sidebarName && data.name) {
                sidebarName.textContent = data.name;
            }

            // Update the preview image if a new photo was uploaded
            if (data.photo_url) {
                document.getElementById('profileImagePreview').src = data.photo_url;
            }

            // Clear the status indicator
            const statusElement = document.getElementById('imageStatus');
            if (statusElement) {
                statusElement.classList.add('hidden');
                statusElement.classList.remove('text-green-600');
            }

            // Clear the file input
            document.getElementById('profilePhotoInput').value = '';

            // Show success message
            Swal.close(); // Close loading alert
            showSuccessAlert('Profile Updated!', 'Your profile has been updated successfully.');
        } else {
            // Handle validation errors
            Swal.close(); // Close loading alert
            if (data.errors) {
                let errorMessage = 'Please fix the following errors:\n';
                for (const field in data.errors) {
                    errorMessage += `• ${data.errors[field].join(', ')}\n`;
                }
                showErrorAlert('Validation Error', errorMessage);
            } else {
                throw new Error(data.message || 'Failed to update profile');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.close(); // Close loading alert
        showErrorAlert('Update Failed', 'Error updating profile: ' + error.message);
    })
    .finally(() => {
        // Reset button state
        buttonText.textContent = originalText;
        submitButton.disabled = false;
    });
});
</script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Replace all alert() calls with SweetAlert2
function showAlert(title, text, type = 'info') {
    Swal.fire({
        title: title,
        text: text,
        icon: type,
        confirmButtonText: 'OK',
        confirmButtonColor: '#0D6AED'
    });
}

function showSuccessAlert(title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'success',
        confirmButtonText: 'Great!',
        confirmButtonColor: '#10B981'
    });
}

function showErrorAlert(title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'error',
        confirmButtonText: 'Try Again',
        confirmButtonColor: '#EF4444'
    });
}

function showLoadingAlert(title, text) {
    Swal.fire({
        title: title,
        text: text,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}
</script>
@endsection
