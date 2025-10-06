<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

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

        return view('home', compact('categories'));
    }
}
