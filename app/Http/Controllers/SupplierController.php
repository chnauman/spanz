<?php

namespace App\Http\Controllers;

use App\Mail\SupplierInvitationMail;
use App\Models\SupplierInvitation;
use App\Models\User;
use App\Rules\CompanyEmail;
use App\Support\CompanyEmail as CompanyEmailSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SupplierController extends Controller
{
    public function showInviteForm()
    {
        $user = Auth::user();

        // Allow suppliers and admins to invite sub suppliers
        if (!$user->isSupplier() && !$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Only suppliers and admins can invite colleagues.');
        }

        $companyDomain = CompanyEmailSupport::domainFrom($user->email);

        return view('suppliers.invite', compact('companyDomain'));
    }

    public function sendInvitation(Request $request)
    {
        $user = Auth::user();

        // Allow suppliers and admins to invite sub suppliers
        if (!$user->isSupplier() && !$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Only suppliers and admins can invite colleagues.');
        }

        $companyDomain = CompanyEmailSupport::domainFrom($user->email);

        if (! $companyDomain || ! CompanyEmailSupport::isCompanyEmail($user->email)) {
            return redirect()->back()->with('error', 'Your account must use a company email before you can invite colleagues.');
        }

        $request->validate([
            'email_local' => ['required', 'string', 'max:64', 'regex:/^[a-zA-Z0-9._+-]+$/'],
            'name' => 'required|string|max:255',
            'message' => 'nullable|string|max:1000',
        ], [
            'email_local.regex' => 'Please enter a valid email username (letters, numbers, dots, hyphens only).',
        ]);

        $email = CompanyEmailSupport::build($request->email_local, $companyDomain);

        $request->merge(['email' => $email]);

        $request->validate([
            'email' => ['required', 'email', 'max:255', new CompanyEmail($companyDomain)],
        ]);

        // Check if user already exists
        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            return redirect()->back()->with('error', 'A user with this email already exists.');
        }

        // Check if there's already a pending invitation for this email
        $existingInvitation = SupplierInvitation::where('email', $request->email)
            ->where('supplier_id', $user->id)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingInvitation) {
            return redirect()->back()->with('error', 'An invitation has already been sent to this email address.');
        }

        // Create invitation token
        $invitation = SupplierInvitation::createInvitation(
            $user->id,
            $request->email,
            $request->name,
            $request->message
        );

        // Send invitation email with unique URL
        Mail::to($request->email)->send(new SupplierInvitationMail($user, $invitation));

        return redirect()->back()->with('success', 'Invitation sent successfully to ' . $request->email);
    }

    public function subSuppliers()
    {
        $user = Auth::user();

        // Allow suppliers and admins to view sub suppliers
        if (!$user->isSupplier() && !$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Only suppliers and admins can view colleagues.');
        }

        $subSuppliers = $user->subSuppliers()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('suppliers.sub-suppliers', compact('subSuppliers'));
    }


    public function removeSubSupplier(User $subSupplier)
    {
        $user = Auth::user();

        // Allow suppliers and admins to remove sub suppliers
        if (!$user->isSupplier() && !$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Only suppliers and admins can remove colleagues.');
        }

        // Check if this sub supplier belongs to the current supplier
        if ($subSupplier->parent_supplier_id !== $user->id) {
            return redirect()->route('suppliers.sub-suppliers')->with('error', 'You can only remove your own colleagues.');
        }

        // Convert sub-supplier back to buyer role instead of deleting
        $subSupplier->update([
            'role' => 'buyer',
            'parent_supplier_id' => null,
            'is_approved' => true // Buyers are auto-approved
        ]);

        return redirect()->route('suppliers.sub-suppliers')->with('success', 'Colleague removed successfully. They are now a buyer.');
    }
}
