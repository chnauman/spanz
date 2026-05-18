<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\EmailVerificationOtp;
use App\Models\RegistrationProgress;
use App\Models\State;
use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use App\Models\SupplierInvitation;
use App\Models\User;
use App\Rules\CompanyEmail;
use App\Services\SubSupplierRegistrationService;
use App\Support\CompanyEmail as CompanyEmailSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MultiStepRegistrationController extends Controller
{
    /**
     * Show Step 1 - Business Information Form
     */
    public function showStep1(Request $request)
    {
        // Check if user is already logged in
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $invitation = SubSupplierRegistrationService::resolveInvitation($request->token);

        if ($request->has('token') && ! $invitation) {
            return redirect()->route('register')->with('error', 'Invalid or expired invitation link.');
        }

        $isSubSupplierRegistration = (bool) $invitation;

        // Check if there's existing progress
        $progress = null;
        if ($request->has('email')) {
            $progress = RegistrationProgress::findByEmail($request->email);
        } elseif ($invitation) {
            $progress = RegistrationProgress::findByEmail($invitation->email);
            $prefill = SubSupplierRegistrationService::companyPrefillFromParent($invitation->supplier);

            if (! $progress) {
                $progress = RegistrationProgress::make(array_merge([
                    'email' => $invitation->email,
                    'full_name' => $invitation->name,
                ], $prefill));
            } else {
                SubSupplierRegistrationService::applyProgressCompanyData($progress, $invitation->supplier);
                $progress->refresh();
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

        return view('auth.register-step1', compact(
            'progress',
            'invitation',
            'statesByCountry',
            'isSubSupplierRegistration'
        ));
    }

    /**
     * Handle Step 1 Submission
     */
    public function submitStep1(Request $request)
    {
        $invitation = SubSupplierRegistrationService::resolveInvitation($request->token);
        $isSubSupplierRegistration = (bool) $invitation;

        $countryHasStates = $isSubSupplierRegistration
            ? State::where('country_name', $invitation->supplier->companyDetail?->country ?? $invitation->supplier->country)->exists()
            : State::where('country_name', $request->country)->exists();

        $rules = [
            'email' => ['required', 'email', 'max:255', new CompanyEmail],
            'password' => 'required|string|min:8|confirmed',
            'full_name' => 'required|string|max:255',
            'title_position' => 'required|string|max:255',
            'cell_mobile' => 'required|string|max:255',
            'whatsapp_wechat' => 'nullable|string|max:255',
        ];

        if (! $isSubSupplierRegistration) {
            $rules = array_merge($rules, [
                'registered_business_name' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'state' => [
                    'nullable',
                    'string',
                    'max:255',
                    Rule::requiredIf($countryHasStates),
                ],
                'city' => [
                    'nullable',
                    'string',
                    'max:255',
                    Rule::requiredIf($countryHasStates),
                ],
                'business_address' => 'required|string',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        $validator->after(function ($validator) use ($request, $countryHasStates, $invitation, $isSubSupplierRegistration) {
            if ($isSubSupplierRegistration && $invitation) {
                if (strtolower($request->email) !== strtolower($invitation->email)) {
                    $validator->errors()->add('email', 'This email must match your invitation.');
                }

                $parentDomain = CompanyEmailSupport::domainFrom($invitation->supplier->email);
                if ($parentDomain && ! CompanyEmailSupport::matchesDomain($request->email, $parentDomain)) {
                    $validator->errors()->add('email', 'You must use your company email (@' . $parentDomain . ').');
                }

                return;
            }

            if (! $countryHasStates) {
                return;
            }

            $state = State::where('country_name', $request->country)
                ->where('name', $request->state)
                ->first();

            if (! $state) {
                $validator->errors()->add('state', 'Please select a valid state for the selected country.');
                return;
            }

            $cityExists = $state->cities()->where('name', $request->city)->exists();
            if (! $cityExists) {
                $validator->errors()->add('city', 'Please select a valid city for the selected state.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if email already exists in users table and is fully registered
        $user = User::where('email', $request->email)->first();
        if ($user && $user->email_verified_at) {
            return redirect()->back()
                ->withErrors(['email' => 'This email is already registered. Please login instead.'])
                ->withInput();
        }

        // Check if there's existing progress
        $progress = RegistrationProgress::findByEmail($request->email);

        $role = 'buyer';
        $isApproved = false;
        $parentSupplierId = null;

        if ($invitation) {
            $role = 'sub_supplier';
            $isApproved = true;
            $parentSupplierId = $invitation->supplier_id;
        }

        $companyData = $isSubSupplierRegistration && $invitation
            ? SubSupplierRegistrationService::companyPrefillFromParent($invitation->supplier)
            : [
                'registered_business_name' => $request->registered_business_name,
                'country' => $request->country,
                'state' => $countryHasStates ? $request->state : null,
                'city' => $countryHasStates ? $request->city : null,
                'business_address' => $request->business_address,
            ];

        $progressPayload = array_merge([
            'password' => Hash::make($request->password),
            'full_name' => $request->full_name,
            'title_position' => $request->title_position,
            'cell_mobile' => $request->cell_mobile,
            'whatsapp_wechat' => $request->whatsapp_wechat,
            'current_step' => 1,
        ], $companyData);

        if ($progress) {
            $progress->update($progressPayload);
        } else {
            $progress = RegistrationProgress::create(array_merge([
                'email' => $request->email,
            ], $progressPayload));
        }

        if (! $user) {
            $user = User::create([
                'email' => $request->email,
                'name' => $request->full_name,
                'password' => Hash::make($request->password),
                'role' => $role,
                'is_approved' => $isApproved,
                'is_supplier' => $role === 'sub_supplier',
                'parent_supplier_id' => $parentSupplierId,
                'email_verified_at' => null,
                'country' => $companyData['country'] ?? null,
                'state' => $companyData['state'] ?? null,
                'city' => $companyData['city'] ?? null,
                'phone' => $request->cell_mobile,
            ]);
        } elseif (! $user->email_verified_at) {
            $user->update([
                'name' => $request->full_name,
                'password' => Hash::make($request->password),
                'role' => $role,
                'is_approved' => $isApproved,
                'is_supplier' => $role === 'sub_supplier',
                'parent_supplier_id' => $parentSupplierId,
                'country' => $companyData['country'] ?? null,
                'state' => $companyData['state'] ?? null,
                'city' => $companyData['city'] ?? null,
                'phone' => $request->cell_mobile,
            ]);
        }

        // Create OTP
        $otpRecord = EmailVerificationOtp::createOtp($user->id);

        // Send email verification notification
        try {
            $user->notify(new \App\Notifications\EmailVerificationNotification($otpRecord->otp));
        } catch (\Exception $e) {
            \Log::error('Failed to send email verification OTP: ' . $e->getMessage());
        }

        $redirectParams = ['email' => $request->email];
        if ($request->has('token')) {
            $redirectParams['token'] = $request->token;
        }

        return redirect()->route('register.step2', $redirectParams)
            ->with('success', 'Step 1 completed! Please verify your email address.');
    }

    /**
     * Show Step 2 - Email Verification
     */
    public function showStep2(Request $request)
    {
        // Check if user is already logged in
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $email = $request->get('email');
        if (!$email) {
            return redirect()->route('register.step1')
                ->with('error', 'Please start from Step 1.');
        }

        // Pass token through if present
        $token = $request->get('token', null);

        $progress = RegistrationProgress::findByEmail($email);
        if (!$progress) {
            return redirect()->route('register.step1')
                ->with('error', 'Registration session expired. Please start again.');
        }

        // Check if step 1 is completed
        if (!$progress->canProceedToStep(2)) {
            return redirect()->route('register.step1', ['email' => $email])
                ->with('error', 'Please complete Step 1 first.');
        }

        // Get user for OTP
        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('register.step1')
                ->with('error', 'User not found. Please start again.');
        }

        $isSubSupplierRegistration = $user->isSubSupplier() && $user->parent_supplier_id;

        return view('auth.register-step2', compact('progress', 'user', 'token', 'isSubSupplierRegistration'));
    }

    /**
     * Handle Step 2 - Verify OTP
     */
    public function submitStep2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $progress = RegistrationProgress::findByEmail($request->email);
        if (!$progress) {
            return redirect()->route('register.step1')
                ->with('error', 'Registration session expired. Please start again.');
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->route('register.step1')
                ->with('error', 'User not found.');
        }

        // Clean and normalize OTP input
        $otp = trim($request->otp);
        $otp = preg_replace('/\D/', '', $otp);

        if (strlen($otp) !== 6) {
            return redirect()->back()
                ->withErrors(['otp' => 'OTP must be exactly 6 digits.'])
                ->withInput();
        }

        // Verify OTP
        $isValid = EmailVerificationOtp::verifyOtp($user->id, $otp);

        if ($isValid) {
            $user->update([
                'email_verified_at' => now(),
            ]);

            $progress->update([
                'email_verified' => true,
                'email_verified_at' => now(),
                'current_step' => 2,
            ]);

            if ($user->isSubSupplier() && $user->parent_supplier_id) {
                $invitation = SubSupplierRegistrationService::resolveInvitation($request->token);

                if (! $invitation) {
                    $invitation = SupplierInvitation::where('email', $user->email)
                        ->where('supplier_id', $user->parent_supplier_id)
                        ->where('is_used', false)
                        ->where('expires_at', '>', now())
                        ->with('supplier.companyDetail')
                        ->first();
                }

                if ($invitation) {
                    return $this->completeSubSupplierRegistration($user, $progress, $invitation);
                }
            }

            $redirectParams = ['email' => $request->email];
            if ($request->has('token')) {
                $redirectParams['token'] = $request->token;
            }

            return redirect()->route('register.step3', $redirectParams)
                ->with('success', 'Email verified successfully! Please select your subscription plan.');
        }

        return redirect()->back()
            ->withErrors(['otp' => 'Invalid or expired OTP. Please try again.'])
            ->withInput();
    }

    /**
     * Resend OTP for Step 2
     */
    public function resendOtp(Request $request)
    {
        $email = $request->get('email');
        if (!$email) {
            return redirect()->route('register.step1')
                ->with('error', 'Email is required.');
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('register.step1')
                ->with('error', 'User not found.');
        }

        // Create new OTP
        $otpRecord = EmailVerificationOtp::createOtp($user->id);

        // Send email verification notification
        try {
            $user->notify(new \App\Notifications\EmailVerificationNotification($otpRecord->otp));
            return redirect()->back()->with('success', 'A new OTP has been sent to your email address.');
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to send OTP. Please try again later.']);
        }
    }

    /**
     * Show Step 3 - Subscription Selection
     */
    public function showStep3(Request $request)
    {
        // Check if user is already logged in
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $email = $request->get('email');
        if (!$email) {
            return redirect()->route('register.step1')
                ->with('error', 'Please start from Step 1.');
        }

        // Pass token through if present
        $token = $request->get('token', null);

        $progress = RegistrationProgress::findByEmail($email);
        if (!$progress) {
            return redirect()->route('register.step1')
                ->with('error', 'Registration session expired. Please start again.');
        }

        // Check if step 2 is completed
        if (!$progress->canProceedToStep(3)) {
            return redirect()->route('register.step2', ['email' => $email])
                ->with('error', 'Please verify your email first.');
        }

        $user = User::where('email', $email)->first();
        if ($user?->isSubSupplier()) {
            return redirect()->route('dashboard')
                ->with('info', 'Your registration is already complete.');
        }

        // Get active subscriptions
        $subscriptions = Subscription::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        return view('auth.register-step3', compact('progress', 'subscriptions', 'token'));
    }

    /**
     * Handle Step 3 - Complete Registration
     */
    public function submitStep3(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'subscription_id' => 'required|exists:subscriptions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $progress = RegistrationProgress::findByEmail($request->email);
        if (!$progress) {
            return redirect()->route('register.step1')
                ->with('error', 'Registration session expired. Please start again.');
        }

        // Check if step 2 is completed
        if (!$progress->canProceedToStep(3)) {
            return redirect()->route('register.step2', ['email' => $request->email])
                ->with('error', 'Please verify your email first.');
        }

        // Get or create user
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->route('register.step1')
                ->with('error', 'User not found.');
        }

        // Check if user was registering via supplier invitation
        $invitation = null;
        $role = 'buyer';
        $isApproved = false;
        $parentSupplierId = null;

        // Check registration progress for invitation token (if stored)
        // For now, we'll check if user is already a sub_supplier
        if ($user->isSubSupplier()) {
            $role = 'sub_supplier';
            $isApproved = true;
            $parentSupplierId = $user->parent_supplier_id;
        }

        // Update user with all registration data
        // Note: country/state/city/phone are saved on the users table so they
        // auto-populate on the View Profile and Edit Profile pages without
        // requiring the user to re-enter them.
        $user->update([
            'name' => $progress->full_name,
            'password' => $progress->password, // Already hashed
            'role' => $role,
            'is_approved' => $isApproved,
            'email_verified_at' => $progress->email_verified_at,
            'parent_supplier_id' => $parentSupplierId,
            'country' => $progress->country,
            'state' => $progress->state,
            'city' => $progress->city,
            'phone' => $progress->cell_mobile,
        ]);

        // Create company details
        \App\Models\CompanyDetail::create([
            'user_id' => $user->id,
            'company_name' => $progress->registered_business_name,
            'address' => $progress->business_address,
            'city' => $progress->city ?? '',
            'state' => $progress->state ?? '',
            'postal_code' => '', // Can be added later if needed
            'country' => $progress->country,
            'phone' => $progress->cell_mobile,
        ]);

        // Create subscription request
        $subscriptionRequest = SubscriptionRequest::create([
            'user_id' => $user->id,
            'subscription_id' => $request->subscription_id,
            'requested_at' => now(),
        ]);

        // Give initial free credits (2 credits for viewing 1-2 project details)
        Credit::create([
            'user_id' => $user->id,
            'amount' => 2,
            // NOTE: `credits.type` is an enum; keep this within allowed values.
            'type' => 'admin_added',
            'description' => 'Initial free credits for new registration'
        ]);

        // Mark registration as complete
        $progress->update([
            'subscription_id' => $request->subscription_id,
            'current_step' => 3,
            'registration_complete' => true,
        ]);

        // Login the user
        Auth::login($user);

        // Clean up registration progress (optional - you might want to keep it for records)
        // $progress->delete();

        // After subscription selection, send the user to the optional
        // company profile completion page. They can complete it now
        // or skip and do it later from their dashboard sidebar.
        return redirect()->route('company.register')
            ->with('success', 'Registration completed successfully! Your subscription request is pending admin approval. You have received 2 free credits to explore the platform. You can now strengthen your business profile to get better matches or skip and do it later.');
    }

    /**
     * Resume registration from saved progress
     */
    public function resume(Request $request)
    {
        $email = $request->get('email');
        if (!$email) {
            return redirect()->route('register.step1')
                ->with('error', 'Email is required to resume registration.');
        }

        $progress = RegistrationProgress::findByEmail($email);
        if (!$progress) {
            return redirect()->route('register.step1')
                ->with('error', 'No registration progress found. Please start a new registration.');
        }

        // If registration is already complete, redirect to login
        if ($progress->registration_complete) {
            return redirect()->route('login')
                ->with('info', 'Registration already completed. Please login.');
        }

        // Redirect to appropriate step
        switch ($progress->current_step) {
            case 1:
                return redirect()->route('register.step1', ['email' => $email]);
            case 2:
                if ($progress->email_verified) {
                    return redirect()->route('register.step3', ['email' => $email]);
                }
                return redirect()->route('register.step2', ['email' => $email]);
            case 3:
                return redirect()->route('register.step3', ['email' => $email]);
            default:
                return redirect()->route('register.step1', ['email' => $email]);
        }
    }

    private function completeSubSupplierRegistration(User $user, RegistrationProgress $progress, SupplierInvitation $invitation)
    {
        $parentSupplier = $invitation->supplier;

        $user->update([
            'name' => $progress->full_name,
            'password' => $progress->password,
            'role' => 'sub_supplier',
            'is_approved' => true,
            'is_supplier' => true,
            'email_verified_at' => $progress->email_verified_at ?? now(),
            'parent_supplier_id' => $invitation->supplier_id,
            'country' => $progress->country,
            'state' => $progress->state,
            'city' => $progress->city,
            'phone' => $progress->cell_mobile,
        ]);

        SubSupplierRegistrationService::copyCompanyDetailFromParent($user, $parentSupplier);

        $invitation->markAsUsed();

        $progress->update([
            'current_step' => 3,
            'registration_complete' => true,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Welcome to SPANZ! You have joined ' . ($parentSupplier->companyDetail?->company_name ?? $parentSupplier->name) . ' as a colleague. You share your team\'s subscription and credits.');
    }
}
