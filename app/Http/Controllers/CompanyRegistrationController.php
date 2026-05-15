<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CompanyDetail;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyRegistrationController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();
        $companyDetail = $user?->companyDetail;

        if ($user && $companyDetail) {
            $contactMap = [
                'country' => $companyDetail->country,
                'state' => $companyDetail->state,
                'city' => $companyDetail->city,
                'phone' => $companyDetail->phone,
            ];
            $updates = [];
            foreach ($contactMap as $field => $value) {
                if (empty($user->{$field}) && !empty($value) && $value !== 'Not provided') {
                    $updates[$field] = $value;
                }
            }
            if (!empty($updates)) {
                $user->update($updates);
                $user->refresh();
            }
        }

        $isEditMode = $request->boolean('edit') || $request->get('mode') === 'edit';

        $firstName = '';
        $lastName = '';
        if ($user && $user->name) {
            $parts = explode(' ', $user->name, 2);
            $firstName = $parts[0] ?? '';
            $lastName = $parts[1] ?? '';
        }

        $selectedCompanyTypes = [];
        $selectedCertifications = [];
        $selectedDeliveryRegions = [];
        $selectedOfficeRegions = [];

        $selectedProfileCategories = [1 => '', 2 => '', 3 => ''];
        $selectedProfileSubcategories = [1 => [], 2 => [], 3 => []];

        if ($companyDetail) {
            if (!empty($companyDetail->company_types)) {
                $selectedCompanyTypes = json_decode($companyDetail->company_types, true) ?: [];
            }
            if (!empty($companyDetail->quality_certifications)) {
                $selectedCertifications = json_decode($companyDetail->quality_certifications, true) ?: [];
            }
            if (!empty($companyDetail->delivery_capabilities)) {
                $selectedDeliveryRegions = json_decode($companyDetail->delivery_capabilities, true) ?: [];
            }
            if (!empty($companyDetail->office_locations)) {
                $selectedOfficeRegions = json_decode($companyDetail->office_locations, true) ?: [];
            }

            $mainIds = $companyDetail->getProfileCategoryIds();
            $subIds = $companyDetail->getProfileSubcategoryIds();
            foreach ($mainIds as $i => $id) {
                $slot = $i + 1;
                if ($slot <= 3) {
                    $selectedProfileCategories[$slot] = (string) $id;
                }
            }
            if ($subIds !== []) {
                $subsByParent = Category::query()
                    ->whereIn('id', $subIds)
                    ->get(['id', 'parent_category_id'])
                    ->groupBy('parent_category_id');
                $slot = 1;
                foreach ($mainIds as $mainId) {
                    if ($slot > 3) {
                        break;
                    }
                    $selectedProfileSubcategories[$slot] = $subsByParent
                        ->get($mainId, collect())
                        ->pluck('id')
                        ->map(fn ($id) => (string) $id)
                        ->all();
                    $slot++;
                }
            }
        }

        $categories = Category::where('is_active', true)
            ->whereNull('parent_category_id')
            ->with(['subcategories' => function ($q) {
                $q->where('is_active', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $subcategoriesByCategory = $categories->mapWithKeys(function ($cat) {
            return [
                $cat->id => $cat->subcategories->map(fn ($sub) => [
                    'id' => $sub->id,
                    'name' => $sub->name,
                ])->values()->all(),
            ];
        });

        $statesByCountry = State::with('cities:id,state_id,name')
            ->orderBy('name')
            ->get(['id', 'name', 'country_name'])
            ->groupBy('country_name')
            ->map(function ($states) {
                return $states->map(function ($state) {
                    return [
                        'id' => $state->id,
                        'name' => $state->name,
                        'cities' => $state->cities->pluck('name')->values()->all(),
                    ];
                })->values()->all();
            });

        return view('company_register', compact(
            'companyDetail',
            'firstName',
            'lastName',
            'selectedCompanyTypes',
            'selectedCertifications',
            'selectedDeliveryRegions',
            'selectedOfficeRegions',
            'selectedProfileCategories',
            'selectedProfileSubcategories',
            'categories',
            'subcategoriesByCategory',
            'statesByCountry',
            'isEditMode'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first' => 'nullable|string|max:255',
            'last' => 'nullable|string|max:255',
            'company' => 'required|string|max:255',
            'website' => 'nullable|string|max:255',
            'headquarter_location' => 'nullable|string|max:255',
            'employees_range' => 'nullable|string|max:255',
            'profile_categories' => 'nullable|array',
            'profile_categories.*' => 'nullable|exists:categories,id',
            'profile_subcategories' => 'nullable|array',
            'profile_subcategories.*' => 'nullable|array',
            'profile_subcategories.*.*' => 'exists:categories,id',
            'company_types' => 'nullable|array',
            'company_types.*' => 'nullable|string|max:255',
            'yearly_revenue_range' => 'nullable|string|max:255',
            'quality_certifications' => 'nullable|array',
            'quality_certifications.*' => 'nullable|string|max:255',
            'brands_represented' => 'nullable|string',
            'industry_awards' => 'nullable|string',
            'industry_memberships' => 'nullable|string',
            'unique_value_propositions' => 'nullable|string',
            'major_projects' => 'nullable|string',
            'delivery_capabilities' => 'nullable|array',
            'delivery_capabilities.*' => 'nullable|string|max:255',
            'office_locations' => 'nullable|array',
            'office_locations.*' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to register your company.');
        }

        $profileCategoryIds = collect($request->input('profile_categories', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $profileSubcategoryIds = collect($request->input('profile_subcategories', []))
            ->flatten()
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $mainIndustryNames = [];
        if ($profileCategoryIds !== []) {
            $mainIndustryNames = Category::whereIn('id', $profileCategoryIds)->pluck('name')->all();
        }

        $subcategoriesByIndustry = [];
        if ($profileSubcategoryIds !== []) {
            $subs = Category::whereIn('id', $profileSubcategoryIds)
                ->with('parentCategory')
                ->get();
            foreach ($subs as $sub) {
                $parentName = $sub->parentCategory?->name ?? 'General';
                $subcategoriesByIndustry[$parentName] = $subcategoriesByIndustry[$parentName] ?? [];
                $subcategoriesByIndustry[$parentName][] = $sub->name;
            }
        }

        CompanyDetail::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'company_name' => $request->company,
                'website' => $request->website ?: null,
                'address' => 'Not provided',
                'city' => 'Not provided',
                'state' => 'Not provided',
                'postal_code' => '00000',
                'country' => 'Not provided',
                'phone' => 'Not provided',
                'headquarter_location' => $request->headquarter_location,
                'employees_range' => $request->employees_range,
                'profile_category_ids' => $profileCategoryIds !== [] ? json_encode($profileCategoryIds) : null,
                'profile_subcategory_ids' => $profileSubcategoryIds !== [] ? json_encode($profileSubcategoryIds) : null,
                'main_industries' => $mainIndustryNames !== [] ? json_encode(array_values($mainIndustryNames)) : null,
                'subcategories_by_industry' => $subcategoriesByIndustry !== [] ? json_encode($subcategoriesByIndustry) : null,
                'company_types' => $request->filled('company_types') ? json_encode($request->company_types) : null,
                'yearly_revenue_range' => $request->yearly_revenue_range,
                'quality_certifications' => $request->filled('quality_certifications') ? json_encode($request->quality_certifications) : null,
                'brands_represented' => $request->brands_represented,
                'industry_awards' => $request->industry_awards,
                'industry_memberships' => $request->industry_memberships,
                'unique_value_propositions' => $request->unique_value_propositions,
                'major_projects' => $request->major_projects,
                'delivery_capabilities' => $request->filled('delivery_capabilities') ? json_encode($request->delivery_capabilities) : null,
                'office_locations' => $request->filled('office_locations') ? json_encode($request->office_locations) : null,
            ]
        );

        if ($request->filled('first') && $request->filled('last')) {
            Auth::user()->update([
                'name' => trim($request->first . ' ' . $request->last),
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your business profile has been saved successfully.',
            ]);
        }

        return redirect()->route('company.register')
            ->with('success', 'Your business profile has been saved successfully.');
    }
}
