<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\SupplierInvitation;
use App\Mail\SupplierInvitationMail;

class SupplierController extends Controller
{
    public function showInviteForm()
    {
        $user = Auth::user();

        // Allow suppliers and admins to invite sub suppliers
        if (!$user->isSupplier() && !$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Only suppliers and admins can invite sub suppliers.');
        }

        return view('suppliers.invite');
    }

    public function sendInvitation(Request $request)
    {
        $user = Auth::user();

        // Allow suppliers and admins to invite sub suppliers
        if (!$user->isSupplier() && !$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Only suppliers and admins can invite sub suppliers.');
        }

        $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'required|string|max:255',
            'message' => 'nullable|string|max:1000',
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
            return redirect()->route('dashboard')->with('error', 'Only suppliers and admins can view sub suppliers.');
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
            return redirect()->route('dashboard')->with('error', 'Only suppliers and admins can remove sub suppliers.');
        }

        // Check if this sub supplier belongs to the current supplier
        if ($subSupplier->parent_supplier_id !== $user->id) {
            return redirect()->route('suppliers.sub-suppliers')->with('error', 'You can only remove your own sub suppliers.');
        }

        // Convert sub-supplier back to buyer role instead of deleting
        $subSupplier->update([
            'role' => 'buyer',
            'parent_supplier_id' => null,
            'is_approved' => true // Buyers are auto-approved
        ]);

        return redirect()->route('suppliers.sub-suppliers')->with('success', 'Sub supplier removed successfully. They are now a buyer.');
    }
}
