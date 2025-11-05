<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        // Validate request - Laravel will automatically redirect back with errors if validation fails
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:10'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:draft,active,archived'],
            'featured' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB max
        ], [
            'title.required' => 'Product title is required.',
            'status.required' => 'Product status is required.',
            'status.in' => 'Invalid status selected.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max' => 'The image may not be greater than 5MB.',
        ]);

        try {
            $slug = Str::slug($validated['title']);
            if (Product::where('slug', $slug)->exists()) {
                $slug .= '-' . uniqid();
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('products', $imageName, 'public');
            }

            $product = Product::create([
                'title' => $validated['title'],
                'slug' => $slug,
                'description' => $validated['description'] ?? null,
                'image' => $imagePath,
                'price' => $validated['price'] ?? null,
                'currency' => $validated['currency'] ?? 'USD',
                'category_id' => $validated['category_id'] ?? null,
                'status' => $validated['status'],
                'featured' => (bool)($validated['featured'] ?? false),
                'user_id' => auth()->id(),
                'published_at' => $validated['status'] === 'active' ? now() : null,
            ]);

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Check if it's a column not found error (migration not run)
            if (str_contains($e->getMessage(), 'image') || str_contains($e->getMessage(), 'SQLSTATE')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Database error: The image column may not exist. Please run: php artisan migrate');
            }
            \Log::error('Product creation database error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Database error occurred. Please check the logs or contact support.');
        } catch (\Exception $e) {
            \Log::error('Product creation error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the product: ' . $e->getMessage());
        }
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
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB max
            'remove_image' => ['sometimes', 'boolean'],
        ]);

        if ($product->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            if (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug .= '-' . uniqid();
            }
            $product->slug = $slug;
        }

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image == '1') {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = null;
        }

        // Handle image upload/replacement
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $product->image = $imagePath;
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
        // Delete associated image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}


