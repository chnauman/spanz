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
        $categories = Category::orderBy('name')->get();
        return view('products.search', compact('products', 'categories'));
    }
}


