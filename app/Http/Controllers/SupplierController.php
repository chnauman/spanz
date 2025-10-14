<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
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

        // Create the sub supplier user
        $subSupplier = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'sub_supplier',
            'parent_supplier_id' => $user->id,
            'is_approved' => false, // Needs approval
            'password' => bcrypt('temp_password_' . time()), // Temporary password
        ]);

        // Send invitation email
        Mail::to($request->email)->send(new SupplierInvitationMail($user, $subSupplier, $request->message));

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

    public function approveSubSupplier(User $subSupplier)
    {
        $user = Auth::user();

        // Allow suppliers and admins to approve sub suppliers
        if (!$user->isSupplier() && !$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Only suppliers and admins can approve sub suppliers.');
        }

        // Check if this sub supplier belongs to the current supplier
        if ($subSupplier->parent_supplier_id !== $user->id) {
            return redirect()->route('suppliers.sub-suppliers')->with('error', 'You can only approve your own sub suppliers.');
        }

        $subSupplier->update(['is_approved' => true]);

        return redirect()->route('suppliers.sub-suppliers')->with('success', 'Sub supplier approved successfully.');
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

        $subSupplier->delete();

        return redirect()->route('suppliers.sub-suppliers')->with('success', 'Sub supplier removed successfully.');
    }
}
