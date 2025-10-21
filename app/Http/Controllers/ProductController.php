<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('status', 'active');
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        $products = $query->orderByDesc('published_at')->paginate(12)->appends($request->all());
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if ($product->status !== 'active') {
            abort(404);
        }
        $product->load('category');
        return view('products.show', compact('product'));
    }

    public function search(Request $request)
    {
        $query = Product::with('category')->where('status', 'active');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qq) use ($q) {
                $qq->where('title', 'like', "%$q%")->orWhere('description', 'like', "%$q%");
            });
        }
        $products = $query->orderByDesc('published_at')->paginate(12)->appends($request->all());
        
        // Get all categories for display (we'll handle pagination in JavaScript)
        $allCategories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Handle pagination for categories (10 per page)
        $categoryPage = $request->get('category_page', 1);
        $categoriesPerPage = 10;
        $startIndex = ($categoryPage - 1) * $categoriesPerPage;
        $categories = $allCategories->slice($startIndex, $categoriesPerPage);
        $hasMoreCategories = $allCategories->count() > ($categoryPage * $categoriesPerPage);

        return view('products.search', compact('products', 'categories', 'hasMoreCategories', 'allCategories'));
    }
}


