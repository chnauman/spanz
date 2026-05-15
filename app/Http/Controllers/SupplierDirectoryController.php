<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CompanyDetail;
use App\Models\Subscription;
use App\Models\Tender;
use App\Models\User;
use Illuminate\Http\Request;

class SupplierDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->with(['companyDetail', 'parentSupplier.companyDetail'])
            ->whereIn('role', ['supplier', 'sub_supplier'])
            ->where('is_approved', true)
            ->whereHas('companyDetail');

        if ($request->filled('category')) {
            $selected = $request->input('category');
            $selectedIds = is_array($selected) ? $selected : [$selected];
            $selectedIds = collect($selectedIds)
                ->filter(fn ($v) => $v !== null && $v !== '')
                ->map(fn ($v) => (int) $v)
                ->filter(fn ($v) => $v > 0)
                ->values();

            if ($selectedIds->isNotEmpty()) {
                $selectedCategories = Category::whereIn('id', $selectedIds->all())
                    ->with(['children' => function ($q) {
                        $q->select(['id', 'parent_category_id'])
                            ->where('is_active', true);
                    }])
                    ->get(['id']);

                $childIds = $selectedCategories
                    ->flatMap(fn ($c) => $c->children->pluck('id'))
                    ->unique()
                    ->values();

                $filterIds = $selectedIds->merge($childIds)->unique()->values()->all();

                $query->whereHas('companyDetail', function ($q) use ($filterIds) {
                    foreach ($filterIds as $id) {
                        $q->orWhereJsonContains('profile_category_ids', $id)
                            ->orWhereJsonContains('profile_subcategory_ids', $id);
                    }
                });
            }
        }

        if ($request->filled('location')) {
            $locations = is_array($request->location) ? $request->location : [$request->location];
            $slugKeys = array_keys(Tender::locationSlugLabels());
            $query->where(function ($q) use ($locations, $slugKeys) {
                foreach ($locations as $loc) {
                    if (! in_array($loc, $slugKeys, true)) {
                        continue;
                    }
                    $q->orWhereHas('companyDetail', function ($cd) use ($loc) {
                        $cd->whereMatchesTenderLocationSlug($loc);
                    });
                }
            });
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $matchingCategoryIds = Category::query()
                ->where('name', 'like', '%' . $searchTerm . '%')
                ->pluck('id');

            $query->where(function ($q) use ($searchTerm, $matchingCategoryIds) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('companyDetail', function ($cd) use ($searchTerm, $matchingCategoryIds) {
                        $cd->where('company_name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('description', 'like', '%' . $searchTerm . '%')
                            ->orWhere('headquarter_location', 'like', '%' . $searchTerm . '%');
                        foreach ($matchingCategoryIds as $catId) {
                            $cd->orWhereJsonContains('profile_category_ids', $catId)
                                ->orWhereJsonContains('profile_subcategory_ids', $catId);
                        }
                    });
            });
        }

        $suppliers = $query->orderBy('name')
            ->paginate(12)
            ->appends($request->query());

        $allCategories = Category::where('is_active', true)
            ->whereNull('parent_category_id')
            ->with(['children' => function ($q) {
                $q->where('is_active', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $locationFilterOptions = $this->buildSupplierLocationFilterOptions();
        $allLocationSlugs = array_keys($locationFilterOptions);

        $categories = $allCategories;

        $locationPage = (int) $request->get('location_page', 1);
        $locationPerPage = 12;
        $locationOffset = ($locationPage - 1) * $locationPerPage;
        $paginatedLocationSlugs = array_slice($allLocationSlugs, $locationOffset, $locationPerPage);
        $locationHasMore = count($allLocationSlugs) > $locationOffset + $locationPerPage;

        return view('suppliers.directory', compact(
            'suppliers',
            'categories',
            'locationFilterOptions',
            'paginatedLocationSlugs',
            'locationPage',
            'locationHasMore'
        ));
    }

    private function buildSupplierLocationFilterOptions(): array
    {
        $labels = Tender::locationSlugLabels();
        $counts = [];

        $details = CompanyDetail::query()
            ->whereHas('user', function ($q) {
                $q->whereIn('role', ['supplier', 'sub_supplier'])
                    ->where('is_approved', true);
            })
            ->get();

        foreach ($labels as $slug => $label) {
            $counts[$slug] = $details->filter(function (CompanyDetail $detail) use ($slug) {
                return in_array($slug, CompanyDetail::resolveLocationSlugsForRow($detail), true);
            })->count();
        }

        return array_filter($labels, fn ($label, $slug) => ($counts[$slug] ?? 0) > 0, ARRAY_FILTER_USE_BOTH);
    }
}
