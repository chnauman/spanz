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
        ]);

        // Update user's first and last name
        Auth::user()->update([
            'name' => $request->first . ' ' . $request->last,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Company profile created successfully! You can now post tenders and participate in the platform.');
    }
}
