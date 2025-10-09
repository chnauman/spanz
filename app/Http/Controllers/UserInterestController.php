<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\UserInterest;
use Illuminate\Support\Facades\Auth;

class UserInterestController extends Controller
{
    public function show()
    {
        $categories = Category::where('is_active', true)->get();
        return view('user.interests', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'interests' => 'required|array|min:1',
            'interests.*' => 'exists:categories,id'
        ]);

        $user = Auth::user();
        
        // Delete existing interests
        $user->interests()->delete();
        
        // Add new interests
        foreach ($request->interests as $categoryId) {
            UserInterest::create([
                'user_id' => $user->id,
                'category_id' => $categoryId
            ]);
        }

        // Mark user as having set interests
        $user->update(['interests_set' => true]);

        return redirect()->route('dashboard')->with('success', 'Your interests have been saved successfully!');
    }
}
