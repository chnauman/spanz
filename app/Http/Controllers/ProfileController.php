<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $user = Auth::user();

        // Update name
        $user->name = $request->input('name');
        $user->save();

        // Handle photo upload (store original file under public disk)
        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $extension = strtolower($request->file('photo')->getClientOriginalExtension() ?: 'jpg');
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                $extension = 'jpg';
            }
            $path = 'profile-photos/' . $user->id . '.' . $extension;

            // Remove old files for this user (any extension)
            foreach (['jpg','jpeg','png','webp'] as $ext) {
                $old = 'profile-photos/' . $user->id . '.' . $ext;
                if (Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
            }

            $request->file('photo')->storeAs('profile-photos', $user->id . '.' . $extension, 'public');
            $photoUrl = asset('storage/' . $path) . '?t=' . time();
        } else {
            foreach (['jpg','jpeg','png','webp'] as $ext) {
                $existingPath = 'profile-photos/' . $user->id . '.' . $ext;
                if (Storage::disk('public')->exists($existingPath)) {
                    $photoUrl = asset('storage/' . $existingPath) . '?t=' . time();
                    break;
                }
            }
        }

        return response()->json([
            'success' => true,
            'name' => $user->name,
            'photo_url' => $photoUrl,
        ]);
    }
}


