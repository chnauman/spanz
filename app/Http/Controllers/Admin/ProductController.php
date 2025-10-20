<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderByDesc('created_at')->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:10'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:draft,active,archived'],
            'featured' => ['sometimes', 'boolean'],
        ]);

        $slug = Str::slug($validated['title']);
        if (Product::where('slug', $slug)->exists()) {
            $slug .= '-' . uniqid();
        }

        $product = Product::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'] ?? null,
            'currency' => $validated['currency'] ?? 'USD',
            'category_id' => $validated['category_id'] ?? null,
            'status' => $validated['status'],
            'featured' => (bool)($validated['featured'] ?? false),
            'user_id' => auth()->id(),
            'published_at' => $validated['status'] === 'active' ? now() : null,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:10'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:draft,active,archived'],
            'featured' => ['sometimes', 'boolean'],
        ]);

        if ($product->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            if (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug .= '-' . uniqid();
            }
            $product->slug = $slug;
        }

        $product->fill([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'] ?? null,
            'currency' => $validated['currency'] ?? 'USD',
            'category_id' => $validated['category_id'] ?? null,
            'status' => $validated['status'],
            'featured' => (bool)($validated['featured'] ?? false),
            'published_at' => $validated['status'] === 'active' ? ($product->published_at ?: now()) : null,
        ])->save();

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}


