<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('parent')->orderBy('name')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_category_id')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_category_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category, Request $request)
    {
        $parentCategories = Category::whereNull('parent_category_id')
            ->where('id', '!=', $category->id)
            ->get();
        $page = $request->get('page', 1);
        return view('admin.categories.edit', compact('category', 'parentCategories', 'page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_category_id' => 'nullable|exists:categories,id',
        ]);

        $category->update($request->all());

        $page = $request->get('page', 1);
        return redirect()->route('admin.categories.index', ['page' => $page])
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Category $category)
    {
        $counts = $this->getTenderCountsForCategoryTree($category);

        // Never allow deletion if any active tender exists (even if user tries to force it).
        if ($counts['active'] > 0) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Cannot delete this category because active tenders exist under it.');
        }

        // If only inactive tenders exist, require an explicit confirmation flag.
        if ($counts['inactive'] > 0 && !$request->boolean('delete_inactive_tenders')) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'This category has inactive tenders. Please confirm deletion to remove the category and related tenders.');
        }

        // If we reached here, it is safe to delete. If inactive tenders exist, delete them first for safety.
        if ($counts['inactive'] > 0) {
            Tender::whereIn('category_id', $counts['category_ids'])->delete();
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * AJAX endpoint used by UI to decide whether delete is allowed.
     */
    public function deleteCheck(Category $category): JsonResponse
    {
        $counts = $this->getTenderCountsForCategoryTree($category);

        return response()->json([
            'category_id' => $category->id,
            'active_tenders' => $counts['active'],
            'inactive_tenders' => $counts['inactive'],
            'total_tenders' => $counts['active'] + $counts['inactive'],
        ]);
    }

    /**
     * Returns tender counts for this category + all descendants.
     *
     * "Active" is aligned with Tender::isActive(): status=active AND deadline > now().
     */
    private function getTenderCountsForCategoryTree(Category $category): array
    {
        $categoryIds = $this->getCategoryTreeIds($category);

        $activeCount = Tender::query()
            ->whereIn('category_id', $categoryIds)
            ->where('status', 'active')
            ->where('deadline', '>', now())
            ->count();

        $totalCount = Tender::query()
            ->whereIn('category_id', $categoryIds)
            ->count();

        // "Inactive" means "not active" by the above definition.
        $inactiveCount = max(0, $totalCount - $activeCount);

        return [
            'category_ids' => $categoryIds,
            'active' => $activeCount,
            'inactive' => $inactiveCount,
        ];
    }

    /**
     * Collects IDs of the category and all descendant categories.
     */
    private function getCategoryTreeIds(Category $category): array
    {
        $ids = [$category->id];

        // Iteratively collect descendants to support multi-level trees.
        $frontier = [$category->id];
        while (!empty($frontier)) {
            $children = Category::query()
                ->whereIn('parent_category_id', $frontier)
                ->pluck('id')
                ->all();

            $children = array_values(array_diff($children, $ids));
            if (empty($children)) {
                break;
            }

            $ids = array_merge($ids, $children);
            $frontier = $children;
        }

        return $ids;
    }
}
