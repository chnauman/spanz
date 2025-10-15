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
        // Get all main categories with their subcategories
        $categories = Category::whereNull('parent_category_id')
            ->where('is_active', true)
            ->with(['subcategories' => function($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();

        // Get active subscriptions for the modal (excluding Basic plan)
        $subscriptions = Subscription::where('is_active', true)
            ->where('name', '!=', 'Basic')
            ->orderBy('price', 'asc')
            ->get();

        return view('home', compact('categories', 'subscriptions'));
    }
}
