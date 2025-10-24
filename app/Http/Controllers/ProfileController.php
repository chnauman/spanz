<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'photo' => ['nullable', 'image', 'max:2048'],
            ]);

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Update name
            $user->name = $request->input('name');
            $user->save();

        // Handle photo upload (store original file under public disk)
        $photoUrl = null;
        if ($request->hasFile('photo')) {
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

            return response()->json([
                'success' => true,
                'name' => $user->name,
                'photo_url' => $photoUrl,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating your profile: ' . $e->getMessage()
            ], 500);
        }
    }
}


