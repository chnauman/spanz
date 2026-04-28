<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subscription;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     */
    public function index()
    {
        // Get all main categories with subcategories + tender counts (active & not expired)
        $categories = Category::query()
            ->whereNull('parent_category_id')
            ->where('is_active', true)
            ->with([
                'subcategories' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('name')
                        ->withCount([
                            'tenders' => function ($t) {
                                $t->where('status', 'active')
                                    ->where('deadline', '>', now());
                            },
                        ]);
                },
            ])
            // still load parent tenders_count (optional, but handy)
            ->withCount([
                'tenders' => function ($t) {
                    $t->where('status', 'active')
                        ->where('deadline', '>', now());
                },
            ])
            ->orderBy('name')
            ->get()
            ->each(function ($category) {
                // Requirement: parent count = sum of its subcategories’ tender counts
                $category->subcategories_tenders_total = (int) ($category->subcategories?->sum('tenders_count') ?? 0);
            });

        // Get active subscriptions for the modal (excluding Basic plan)
        $subscriptions = Subscription::where('is_active', true)
            ->where('name', '!=', 'Basic')
            ->orderBy('price', 'asc')
            ->get();

        return view('home', compact('categories', 'subscriptions'));
    }
}
