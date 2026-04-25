<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\UserInterest;
use App\Models\UserBudgetRange;
use Illuminate\Support\Facades\Auth;

class UserInterestController extends Controller
{
    public function show()
    {
        // Get parent categories (null parent_category_id) with their subcategories
        // Parent categories sorted alphabetically, subcategories in their original order (not sorted)
        $parentCategories = Category::where('is_active', true)
            ->whereNull('parent_category_id')
            ->with(['subcategories' => function($query) {
                $query->where('is_active', true); // No orderBy - keep original order
            }])
            ->orderBy('name') // Only sort parent categories alphabetically
            ->get();
        
        // Get all categories (flat list) for backward compatibility and search
        $categories = Category::where('is_active', true)->get();
        
        $user = Auth::user();
        $existingInterests = $user->interests()->with('category')->get();
        $budgetRanges = $user->budgetRanges()->with('category')->get();
        
        return view('user.enhanced-interests', compact('parentCategories', 'categories', 'existingInterests', 'budgetRanges'));
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
            'budget_ranges.*.currency' => 'required_with:budget_ranges|string|in:AUD'
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
                        'currency' => $budgetRange['currency'] ?? 'AUD'
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
        \Log::info('Save budget request received:', $request->all());
        
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'min_budget' => 'nullable|numeric|min:0',
            'max_budget' => 'nullable|numeric|min:0',
            'currency' => 'required|string|in:AUD',
            'budget_type' => 'required|string|in:less,greater,range'
        ]);

        $user = Auth::user();

        // Test if UserBudgetRange model can be instantiated
        try {
            $testModel = new UserBudgetRange();
            \Log::info('UserBudgetRange model instantiated successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to instantiate UserBudgetRange model: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Model error: ' . $e->getMessage()
            ], 500);
        }

        // Custom validation for budget ranges
        if ($request->budget_type === 'range' && $request->min_budget && $request->max_budget) {
            if ($request->min_budget >= $request->max_budget) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimum budget must be less than maximum budget for range type.'
                ], 422);
            }
        }

        try {
            \Log::info('Creating interest for user:', ['user_id' => $user->id, 'category_id' => $request->category_id]);
            
            // Create or update the interest first
            $interest = UserInterest::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'category_id' => $request->category_id
                ],
                [
                    'min_budget' => null, // We'll store budgets in separate table now
                    'max_budget' => null,
                    'currency' => 'AUD'
                ]
            );

            \Log::info('Creating budget range:', [
                'user_id' => $user->id,
                'category_id' => $request->category_id,
                'min_budget' => $request->min_budget,
                'max_budget' => $request->max_budget,
                'currency' => $request->currency,
                'budget_type' => $request->budget_type
            ]);

            // Create new budget range
            \Log::info('Attempting to create UserBudgetRange with data:', [
                'user_id' => $user->id,
                'category_id' => $request->category_id,
                'min_budget' => $request->min_budget,
                'max_budget' => $request->max_budget,
                'currency' => $request->currency,
                'budget_type' => $request->budget_type
            ]);
            
            $budgetRange = UserBudgetRange::create([
                'user_id' => $user->id,
                'category_id' => $request->category_id,
                'min_budget' => $request->min_budget,
                'max_budget' => $request->max_budget,
                'currency' => $request->currency,
                'budget_type' => $request->budget_type
            ]);

            \Log::info('Budget range created successfully:', ['budget_range_id' => $budgetRange->id]);

            return response()->json([
                'success' => true,
                'message' => 'Budget saved successfully!',
                'budget_range' => [
                    'id' => $budgetRange->id,
                    'formatted_range' => $budgetRange->formatted_budget_range,
                    'budget_type_color' => $budgetRange->budget_type_color,
                    'budget_type' => $budgetRange->budget_type
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving budget: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error saving budget: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteBudget(Request $request, $budgetRangeId)
    {
        $user = Auth::user();
        
        \Log::info('Delete budget request received:', [
            'user_id' => $user->id,
            'budget_range_id' => $budgetRangeId,
            'request_data' => $request->all()
        ]);
        
        try {
            $budgetRange = UserBudgetRange::where('id', $budgetRangeId)
                ->where('user_id', $user->id)
                ->first();

            if (!$budgetRange) {
                \Log::warning('Budget range not found:', [
                    'user_id' => $user->id,
                    'budget_range_id' => $budgetRangeId
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Budget range not found.'
                ], 404);
            }

            \Log::info('Found budget range to delete:', [
                'budget_range_id' => $budgetRange->id,
                'category_id' => $budgetRange->category_id,
                'user_id' => $budgetRange->user_id
            ]);

            $categoryId = $budgetRange->category_id;
            $budgetRange->delete();

            \Log::info('Budget range deleted successfully');

            // Check if this was the last budget for this category
            $remainingBudgets = UserBudgetRange::where('user_id', $user->id)
                ->where('category_id', $categoryId)
                ->count();

            \Log::info('Remaining budgets for category:', [
                'category_id' => $categoryId,
                'remaining_budgets' => $remainingBudgets
            ]);

            // If no budgets left, remove the interest
            if ($remainingBudgets === 0) {
                UserInterest::where('user_id', $user->id)
                    ->where('category_id', $categoryId)
                    ->delete();
                \Log::info('Interest removed as no budgets remain');
            }

            return response()->json([
                'success' => true,
                'message' => 'Budget deleted successfully!',
                'category_id' => $categoryId,
                'remaining_budgets' => $remainingBudgets
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting budget: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'budget_range_id' => $budgetRangeId
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error deleting budget: ' . $e->getMessage()
            ], 500);
        }
    }
}
