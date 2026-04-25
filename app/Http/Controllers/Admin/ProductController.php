<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            'currency' => ['nullable', 'string', 'in:AUD'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:draft,active,archived'],
            'featured' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB max
            'gallery_images' => ['nullable', 'array', 'max:12'],
            'gallery_images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB each
        ], [
            'title.required' => 'Product title is required.',
            'status.required' => 'Product status is required.',
            'status.in' => 'Invalid status selected.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max' => 'The image may not be greater than 5MB.',
            'gallery_images.array' => 'Gallery images must be a list of files.',
            'gallery_images.max' => 'You can upload up to 12 gallery images.',
            'gallery_images.*.image' => 'Each gallery file must be an image.',
            'gallery_images.*.mimes' => 'Gallery images must be: jpeg, png, jpg, gif, webp.',
            'gallery_images.*.max' => 'Each gallery image may not be greater than 5MB.',
        ]);

        $hasImageColumn = Schema::hasColumn('products', 'image');
        $hasImagesColumn = Schema::hasColumn('products', 'images');

        if ($request->hasFile('image') && !$hasImageColumn) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'The products.image column is missing. Please run: php artisan migrate');
        }

        if ($request->hasFile('gallery_images') && !$hasImagesColumn) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'The products.images column is missing. Please run: php artisan migrate');
        }

        $storedPaths = [];

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
                $storedPaths[] = $imagePath;
            }

            $galleryPaths = [];
            if ($request->hasFile('gallery_images')) {
                foreach ((array) $request->file('gallery_images') as $img) {
                    if (!$img) continue;
                    $imageName = time() . '_' . Str::random(10) . '.' . $img->getClientOriginalExtension();
                    $path = $img->storeAs('products/gallery', $imageName, 'public');
                    $galleryPaths[] = $path;
                    $storedPaths[] = $path;
                }
            }

            DB::transaction(function () use ($validated, $slug, $imagePath, $galleryPaths, $hasImageColumn, $hasImagesColumn) {
                $payload = [
                    'title' => $validated['title'],
                    'slug' => $slug,
                    'description' => $validated['description'] ?? null,
                    'price' => $validated['price'] ?? null,
                    'currency' => $validated['currency'] ?? 'AUD',
                    'category_id' => $validated['category_id'] ?? null,
                    'status' => $validated['status'],
                    'featured' => (bool)($validated['featured'] ?? false),
                    'user_id' => auth()->id(),
                    'published_at' => $validated['status'] === 'active' ? now() : null,
                ];

                if ($hasImageColumn) {
                    $payload['image'] = $imagePath;
                }

                if ($hasImagesColumn) {
                    $payload['images'] = !empty($galleryPaths) ? $galleryPaths : null;
                }

                Product::create($payload);
            });

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
            foreach ($storedPaths as $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
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
            'currency' => ['nullable', 'string', 'in:AUD'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:draft,active,archived'],
            'featured' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB max
            'remove_image' => ['sometimes', 'boolean'],
            'gallery_images' => ['nullable', 'array', 'max:12'],
            'gallery_images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB each
            'clear_gallery' => ['sometimes', 'boolean'],
            'remove_gallery_images' => ['nullable', 'array'],
            'remove_gallery_images.*' => ['string'],
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

        // Handle gallery clear
        if ($request->boolean('clear_gallery')) {
            $existing = is_array($product->images) ? $product->images : [];
            foreach ($existing as $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
            $product->images = null;
        }

        // Handle per-image removals (only from existing gallery)
        $toRemove = $request->input('remove_gallery_images', []);
        if (!empty($toRemove) && !$request->boolean('clear_gallery')) {
            $existing = is_array($product->images) ? $product->images : [];
            $existingSet = array_flip($existing);

            foreach ($toRemove as $path) {
                if (!is_string($path) || $path === '') continue;
                if (!isset($existingSet[$path])) continue; // only allow removal of existing images
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
                unset($existingSet[$path]);
            }

            $remaining = array_values(array_keys($existingSet));
            $product->images = !empty($remaining) ? $remaining : null;
        }

        // Handle gallery image uploads (append)
        if ($request->hasFile('gallery_images')) {
            $existing = is_array($product->images) ? $product->images : [];
            $newPaths = [];
            foreach ((array) $request->file('gallery_images') as $img) {
                if (!$img) continue;
                $imageName = time() . '_' . Str::random(10) . '.' . $img->getClientOriginalExtension();
                $newPaths[] = $img->storeAs('products/gallery', $imageName, 'public');
            }
            $merged = array_values(array_filter(array_merge($existing, $newPaths)));
            $product->images = !empty($merged) ? $merged : null;
        }

        $product->fill([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'] ?? null,
            'currency' => $validated['currency'] ?? 'AUD',
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

        // Delete gallery images if exist
        $gallery = is_array($product->images) ? $product->images : [];
        foreach ($gallery as $path) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}


