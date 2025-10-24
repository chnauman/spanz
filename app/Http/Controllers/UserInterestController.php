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
        $user = Auth::user();
        $existingInterests = $user->interests()->with('category')->get();
        return view('user.enhanced-interests', compact('categories', 'existingInterests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'interests' => 'required|array|min:1',
            'interests.*' => 'exists:categories,id',
            'budget_ranges' => 'nullable|array',
            'budget_ranges.*.category_id' => 'required_with:budget_ranges|exists:categories,id',
            'budget_ranges.*.min_budget' => 'nullable|numeric|min:0',
            'budget_ranges.*.max_budget' => 'nullable|numeric|min:0|gte:budget_ranges.*.min_budget',
            'budget_ranges.*.currency' => 'required_with:budget_ranges|string|in:USD,AUD,EUR,GBP,SGD,NZD'
        ]);

        $user = Auth::user();

        try {
            // Delete existing interests
            $user->interests()->delete();

            // Add new interests with budget ranges
            foreach ($request->interests as $categoryId) {
                $budgetRange = null;

                // Find matching budget range for this category
                if ($request->has('budget_ranges')) {
                    foreach ($request->budget_ranges as $range) {
                        if ($range['category_id'] == $categoryId) {
                            $budgetRange = $range;
                            break;
                        }
                    }
                }

                UserInterest::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'category_id' => $categoryId
                    ],
                    [
                        'min_budget' => $budgetRange['min_budget'] ?? null,
                        'max_budget' => $budgetRange['max_budget'] ?? null,
                        'currency' => $budgetRange['currency'] ?? 'USD'
                    ]
                );
            }

            // Mark user as having set interests
            $user->update(['interests_set' => true]);

            // Check if this is an AJAX request
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your interests have been saved successfully!'
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Your interests and budget ranges have been saved successfully!');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error saving user interests: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error saving interests. Please try again.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Error saving interests. Please try again.');
        }
    }

    public function skip()
    {
        $user = Auth::user();

        // Mark user as having skipped interests
        $user->update(['interests_set' => true]);

        return redirect()->route('dashboard')->with('info', 'You can set your interests later from your dashboard.');
    }

    public function management()
    {
        $user = Auth::user();
        $existingInterests = $user->interests()->with('category')->get();
        return view('user.interests-management', compact('existingInterests'));
    }

    public function delete(UserInterest $interest)
    {
        // Ensure the interest belongs to the authenticated user
        if ($interest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $interest->delete();

        return redirect()->route('user.interests.management')->with('success', 'Interest removed successfully.');
    }

    public function saveBudget(Request $request)
    {
        $request->validate([
            'budget_ranges' => 'nullable|array',
            'budget_ranges.*.category_id' => 'required_with:budget_ranges|exists:categories,id',
            'budget_ranges.*.min_budget' => 'nullable|numeric|min:0',
            'budget_ranges.*.max_budget' => 'nullable|numeric|min:0|gte:budget_ranges.*.min_budget',
            'budget_ranges.*.currency' => 'required_with:budget_ranges|string|in:USD,AUD,EUR,GBP,SGD,NZD'
        ]);

        $user = Auth::user();

        try {
            // Update or create budget ranges for each category (if provided)
            if ($request->has('budget_ranges') && !empty($request->budget_ranges)) {
                foreach ($request->budget_ranges as $range) {
                    UserInterest::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'category_id' => $range['category_id']
                        ],
                        [
                            'min_budget' => $range['min_budget'] ?? null,
                            'max_budget' => $range['max_budget'] ?? null,
                            'currency' => $range['currency']
                        ]
                    );
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Preferences saved successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving budget ranges: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving budget ranges: ' . $e->getMessage()
            ], 500);
        }
    }
}
