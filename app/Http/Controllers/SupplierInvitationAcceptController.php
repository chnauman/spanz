<?php

namespace App\Http\Controllers;

use App\Models\SupplierInvitation;
use Illuminate\Http\Request;

class SupplierInvitationAcceptController extends Controller
{
    public function show(string $token)
    {
        $invitation = $this->findValidInvitation($token);

        if (! $invitation) {
            return redirect()->route('register')
                ->with('error', 'This invitation link is invalid or has expired.');
        }

        $invitation->load('supplier.companyDetail');

        return view('suppliers.invitation-accept', compact('invitation'));
    }

    public function accept(Request $request, string $token)
    {
        $invitation = $this->findValidInvitation($token);

        if (! $invitation) {
            return redirect()->route('register')
                ->with('error', 'This invitation link is invalid or has expired.');
        }

        $invitation->update(['accepted_at' => now()]);

        return redirect()
            ->route('register', ['token' => $invitation->token])
            ->with('success', 'Invitation accepted! Complete your registration below.');
    }

    private function findValidInvitation(string $token): ?SupplierInvitation
    {
        return SupplierInvitation::where('token', $token)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();
    }
}
