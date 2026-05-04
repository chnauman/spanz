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
            ->with(['companyDetail', 'interests.category', 'parentSupplier.companyDetail'])
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

                $filterIds = $selectedIds->merge($childIds)->unique()->values();

                $query->whereHas('interests', function ($q) use ($filterIds) {
                    $q->whereIn('category_id', $filterIds->all());
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
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('companyDetail', function ($cd) use ($searchTerm) {
                        $cd->where('company_name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('description', 'like', '%' . $searchTerm . '%')
                            ->orWhere('headquarter_location', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('interests.category', function ($cq) use ($searchTerm) {
                        $cq->where('name', 'like', '%' . $searchTerm . '%');
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
        $locationsPerPage = 5;
        $locations = collect($allLocationSlugs)
            ->slice(($locationPage - 1) * $locationsPerPage, $locationsPerPage)
            ->mapWithKeys(fn ($slug) => [$slug => $locationFilterOptions[$slug]])
            ->all();
        $hasMoreLocations = count($allLocationSlugs) > ($locationPage * $locationsPerPage);

        $subscriptions = Subscription::where('is_active', true)
            ->where('name', '!=', 'Basic')
            ->orderBy('price', 'asc')
            ->get();

        $canShareDocuments = auth()->check()
            && (auth()->user()->isBuyer() || auth()->user()->isSupplier() || auth()->user()->isSubSupplier());

        if ($request->ajax() || $request->wantsJson()) {
            $html = view('suppliers.partials.directory-results', compact('suppliers', 'canShareDocuments'))->render();

            return response()->json(['html' => $html]);
        }

        return view('suppliers.directory', compact(
            'suppliers',
            'categories',
            'locations',
            'hasMoreLocations',
            'allCategories',
            'subscriptions',
            'canShareDocuments'
        ))->with([
            'allLocations' => $allLocationSlugs,
            'locationFilterOptions' => $locationFilterOptions,
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function buildSupplierLocationFilterOptions(): array
    {
        $labels = Tender::locationSlugLabels();
        $userIds = User::query()
            ->whereIn('role', ['supplier', 'sub_supplier'])
            ->where('is_approved', true)
            ->pluck('id');

        $slugs = CompanyDetail::whereIn('user_id', $userIds)
            ->get(['country', 'state', 'headquarter_location'])
            ->flatMap(fn (CompanyDetail $d) => CompanyDetail::resolveLocationSlugsForRow($d));

        $out = [];
        foreach ($slugs->unique()->sort() as $slug) {
            if (isset($labels[$slug])) {
                $out[$slug] = $labels[$slug];
            }
        }

        return $out;
    }
}
