<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyDetail;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyRegistrationController extends Controller
{
    public function show(Request $request)
    {
        // User must be logged in (route already has auth middleware)
        $user = Auth::user();
        $companyDetail = $user?->companyDetail;

        // When arriving from "Edit Profile" we render the page in edit mode.
        $isEditMode = $request->boolean('edit') || $request->get('mode') === 'edit';

        // Derive first / last name from user's name for convenience
        $firstName = '';
        $lastName = '';
        if ($user && $user->name) {
            $parts = explode(' ', $user->name, 2);
            $firstName = $parts[0] ?? '';
            $lastName = $parts[1] ?? '';
        }

        // Decode JSON fields into arrays for easier use in the view
        $selectedIndustries = [];
        $selectedSubcategories = [];
        $selectedCompanyTypes = [];
        $selectedCertifications = [];
        $selectedDeliveryRegions = [];
        $selectedOfficeRegions = [];

        if ($companyDetail) {
            if (!empty($companyDetail->main_industries)) {
                $selectedIndustries = json_decode($companyDetail->main_industries, true) ?: [];
            }
            if (!empty($companyDetail->subcategories_by_industry)) {
                $selectedSubcategories = json_decode($companyDetail->subcategories_by_industry, true) ?: [];
            }
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
        }

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

        // Always show the profile form so the user can create or update their business profile.
        return view('company_register', compact(
            'companyDetail',
            'firstName',
            'lastName',
            'selectedIndustries',
            'selectedSubcategories',
            'selectedCompanyTypes',
            'selectedCertifications',
            'selectedDeliveryRegions',
            'selectedOfficeRegions',
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
            'comp' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'objective' => 'required|string|max:255',

            // New profile fields from client requirements
            'headquarter_location' => 'nullable|string|max:255',
            'employees_range' => 'nullable|string|max:255',
            'main_industries' => 'nullable|array',
            'main_industries.*' => 'nullable|string|max:255',
            'subcategories' => 'nullable|array',
            'subcategories.*' => 'nullable|array',
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

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to register your company.');
        }

        // Create or update company details for this user
        $companyDetail = CompanyDetail::updateOrCreate(
            ['user_id' => Auth::id()],
            [
            'company_name' => $request->company,
            'website' => $request->website ?: null,
            'description' => $request->objective,
            // Set default values for required fields that aren't in the form
            'address' => 'Not provided',
            'city' => 'Not provided',
            'state' => 'Not provided',
            'postal_code' => '00000',
            'country' => 'Not provided',
            'phone' => 'Not provided',

            // New structured profile data
            'headquarter_location' => $request->headquarter_location,
            'employees_range' => $request->employees_range,
            'main_industries' => $request->filled('main_industries') ? json_encode($request->main_industries) : null,
            'subcategories_by_industry' => $request->filled('subcategories') ? json_encode($request->subcategories) : null,
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
        ]);

        // Name is managed in the profile section; only update here if explicitly sent.
        if ($request->filled('first') && $request->filled('last')) {
            Auth::user()->update([
                'name' => trim($request->first . ' ' . $request->last),
            ]);
        }

        // For AJAX requests, return JSON so the frontend can show a toast
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your business profile has been saved successfully.',
            ]);
        }

        // Fallback for normal form posts – stay on page with flash message
        return redirect()->route('company.register')
            ->with('success', 'Your business profile has been saved successfully.');
    }
}
