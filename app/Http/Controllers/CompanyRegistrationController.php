<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyRegistrationController extends Controller
{
    public function show()
    {
        // Check if user already has company details
        if (Auth::check() && Auth::user()->companyDetail) {
            return redirect()->route('dashboard')->with('info', 'You already have a company profile.');
        }
        
        return view('company_register');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first' => 'required|string|max:255',
            'last' => 'required|string|max:255',
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

        // Check if user already has company details
        if (Auth::user()->companyDetail) {
            return redirect()->route('dashboard')->with('info', 'You already have a company profile.');
        }

        // Create company details
        $companyDetail = CompanyDetail::create([
            'user_id' => Auth::id(),
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

        // Update user's first and last name
        Auth::user()->update([
            'name' => $request->first . ' ' . $request->last,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Company profile created successfully! You can now post tenders and participate in the platform.');
    }
}
