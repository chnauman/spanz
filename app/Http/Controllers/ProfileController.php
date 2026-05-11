<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        try {
            $hasFirstLast = $request->filled('first') || $request->filled('last');

            // Strip any non-digit characters from phone before validation so
            // we always store a clean digits-only value (the UI also enforces
            // digits-only via inputmode/pattern/oninput handlers).
            if ($request->filled('phone')) {
                $request->merge([
                    'phone' => preg_replace('/\D+/', '', (string) $request->input('phone')),
                ]);
            }

            $rules = [
                'photo' => ['nullable', 'image', 'max:2048'],
                'remove_photo' => ['nullable', 'boolean'],
                // Phone must be digits only (no letters/symbols/spaces).
                'phone' => ['nullable', 'string', 'regex:/^[0-9]+$/', 'max:20'],
                'country' => ['nullable', 'string', 'max:255'],
                'state' => ['nullable', 'string', 'max:255'],
                'city' => ['nullable', 'string', 'max:255'],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ];

            $messages = [
                'phone.regex' => 'Phone number must contain digits only.',
                'phone.max' => 'Phone number may not be longer than 20 digits.',
            ];

            if ($hasFirstLast) {
                $rules['first'] = ['required', 'string', 'max:255'];
                $rules['last'] = ['required', 'string', 'max:255'];
            } else {
                $rules['name'] = ['required', 'string', 'max:255'];
            }

            $request->validate($rules, $messages);

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Update name
            if ($hasFirstLast) {
                $user->name = trim(($request->input('first') ?? '') . ' ' . ($request->input('last') ?? ''));
            } else {
                $user->name = $request->input('name');
            }

            $user->country = $request->input('country');
            $user->state = $request->input('state');
            $user->city = $request->input('city');
            $user->phone = $request->input('phone');

            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }
            $user->save();

        // Handle photo upload (store original file under public disk)
        $photoUrl = null;
        if ((bool) $request->boolean('remove_photo')) {
            foreach (['jpg','jpeg','png','webp'] as $ext) {
                $old = 'profile-photos/' . $user->id . '.' . $ext;
                if (Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
            }
            $photoUrl = asset('spanz-img/profile.jpg');
        } elseif ($request->hasFile('photo')) {
            // Validate user ID exists
            if (!$user->id) {
                throw new \Exception('User ID is required for photo upload');
            }

            $photoFile = $request->file('photo');

            // Validate file exists and is valid
            if (!$photoFile || !$photoFile->isValid()) {
                throw new \Exception('Invalid photo file');
            }

            $extension = strtolower($photoFile->getClientOriginalExtension() ?: 'jpg');
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                $extension = 'jpg';
            }

            $fileName = $user->id . '.' . $extension;

            // Debug: Log all values before processing
            Log::info('Photo upload debug info', [
                'user_id' => $user->id,
                'file_name' => $fileName,
                'extension' => $extension,
                'original_name' => $photoFile->getClientOriginalName(),
                'file_size' => $photoFile->getSize(),
                'file_path' => $photoFile->getPathname(),
                'is_uploaded' => $photoFile->isValid(),
                'mime_type' => $photoFile->getMimeType()
            ]);

            // Validate path is not empty
            if (empty(trim($fileName))) {
                throw new \Exception('File name cannot be empty');
            }

            // Remove old files for this user (any extension)
            foreach (['jpg','jpeg','png','webp'] as $ext) {
                $old = 'profile-photos/' . $user->id . '.' . $ext;
                if (Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
            }

            // Ensure the directory exists
            if (!Storage::disk('public')->exists('profile-photos')) {
                Storage::disk('public')->makeDirectory('profile-photos');
            }

            // Store the new photo with error handling
            try {
                // Debug logging before storage attempt
                Log::info('Attempting to store photo', [
                    'user_id' => $user->id,
                    'file_name' => $fileName,
                    'directory' => 'profile-photos',
                    'disk' => 'public',
                    'original_name' => $photoFile->getClientOriginalName(),
                    'size' => $photoFile->getSize(),
                    'extension' => $extension
                ]);

                // Validate all parameters before calling storeAs
                if (empty($fileName)) {
                    throw new \Exception('File name cannot be empty');
                }

                if (empty('profile-photos')) {
                    throw new \Exception('Directory name cannot be empty');
                }

                // Use PHP native file functions to avoid Laravel storage issues
                $targetDirectory = storage_path('app/public/profile-photos');
                $targetPath = $targetDirectory . DIRECTORY_SEPARATOR . $fileName;

                Log::info('Native file handling', [
                    'target_directory' => $targetDirectory,
                    'target_path' => $targetPath,
                    'source_path' => $photoFile->getPathname(),
                    'file_exists' => file_exists($targetPath)
                ]);

                // Ensure target directory exists
                if (!is_dir($targetDirectory)) {
                    if (!mkdir($targetDirectory, 0755, true)) {
                        throw new \Exception('Failed to create target directory');
                    }
                }

                // Use PHP's copy function instead of Laravel's move
                $copied = copy($photoFile->getPathname(), $targetPath);

                if (!$copied) {
                    throw new \Exception('Failed to copy uploaded file');
                }

                $storedPath = 'profile-photos/' . $fileName;

                Log::info('File copied successfully', [
                    'stored_path' => $storedPath,
                    'file_exists' => file_exists($targetPath),
                    'file_size' => filesize($targetPath)
                ]);

                // Verify the file was actually stored using native PHP
                if (!file_exists($targetPath)) {
                    throw new \Exception('Photo was not stored successfully - file does not exist');
                }

                $photoUrl = asset('storage/' . $storedPath) . '?t=' . time();

            } catch (\Exception $storeException) {
                Log::error('Photo storage failed: ' . $storeException->getMessage(), [
                    'user_id' => $user->id,
                    'file_name' => $fileName,
                    'original_name' => $photoFile->getClientOriginalName(),
                    'size' => $photoFile->getSize(),
                    'exception_type' => get_class($storeException),
                    'exception_file' => $storeException->getFile(),
                    'exception_line' => $storeException->getLine()
                ]);
                throw new \Exception('Failed to store photo: ' . $storeException->getMessage());
            }
        } else {
            // Check for existing photo
            foreach (['jpg','jpeg','png','webp'] as $ext) {
                $existingPath = 'profile-photos/' . $user->id . '.' . $ext;
                if (Storage::disk('public')->exists($existingPath)) {
                    $photoUrl = asset('storage/' . $existingPath) . '?t=' . time();
                    break;
                }
            }
            // If no existing photo, use default
            if (!$photoUrl) {
                $photoUrl = asset('spanz-img/profile.jpg');
            }
        }

            // Update optional contact/location fields on company detail if it exists.
            if ($user->companyDetail) {
                $user->companyDetail->update([
                    'phone' => $request->input('phone', $user->companyDetail->phone),
                    'country' => $request->input('country', $user->companyDetail->country),
                    'state' => $request->input('state', $user->companyDetail->state),
                    'city' => $request->input('city', $user->companyDetail->city),
                    'address' => $request->input('address', $user->companyDetail->address),
                ]);
            }

            // Update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
                $user->save();
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'name' => $user->name,
                    'photo_url' => $photoUrl,
                ]);
            }

            return redirect()
                ->back()
                ->with('success', 'Your profile has been updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating your profile: ' . $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'An error occurred while updating your profile.');
        }
    }
}


