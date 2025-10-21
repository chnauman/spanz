<?php

namespace App\Http\Controllers;

use App\Models\SubSupplierInvitation;
use App\Models\User;
use App\Jobs\SendSubSupplierInvitationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubSupplierInvitationController extends Controller
{
    /**
     * Display the invitation search page
     */
    public function search()
    {
        return view('suppliers.invite-search');
    }

    /**
     * Search for suppliers to invite
     */
    public function searchSuppliers(Request $request)
    {
        $query = $request->get('query');
        $currentUserId = Auth::id();

        $suppliers = User::where('role', 'supplier')
            ->where('id', '!=', $currentUserId)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->with('companyDetail')
            ->get();

        // Get existing invitations for these suppliers
        $invitedSupplierIds = SubSupplierInvitation::where('inviter_id', $currentUserId)
            ->whereIn('invitee_id', $suppliers->pluck('id'))
            ->pluck('invitee_id')
            ->toArray();

        // Add invitation status to each supplier
        $suppliers->transform(function ($supplier) use ($invitedSupplierIds) {
            $supplier->already_invited = in_array($supplier->id, $invitedSupplierIds);
            return $supplier;
        });

        return response()->json([
            'data' => $suppliers,
            'total' => $suppliers->count()
        ]);
    }

    /**
     * Send invitation to a supplier
     */
    public function sendInvitation(Request $request)
    {
        $request->validate([
            'invitee_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:500'
        ]);

        $inviterId = Auth::id();
        $inviteeId = $request->invitee_id;

        // Check if invitation already exists
        $existingInvitation = SubSupplierInvitation::where('inviter_id', $inviterId)
            ->where('invitee_id', $inviteeId)
            ->first();

        if ($existingInvitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation already sent to this supplier.'
            ], 400);
        }

        // Create invitation
        $invitation = SubSupplierInvitation::create([
            'inviter_id' => $inviterId,
            'invitee_id' => $inviteeId,
            'message' => $request->message,
            'sent_at' => now(),
        ]);

        // Send email notification via job
        SendSubSupplierInvitationJob::dispatch($invitation);

        return response()->json([
            'success' => true,
            'message' => 'Invitation sent successfully!'
        ]);
    }

    /**
     * Display invitations management page
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $filter = $request->get('filter', 'all');
        $type = $request->get('type', 'received'); // sent or received

        $query = $type === 'sent'
            ? SubSupplierInvitation::sentBy($userId)
            : SubSupplierInvitation::receivedBy($userId);

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $invitations = $query->with(['inviter', 'invitee'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('suppliers.invitations', compact('invitations', 'filter', 'type'));
    }

    /**
     * Accept an invitation
     */
    public function accept($id)
    {
        $invitation = SubSupplierInvitation::findOrFail($id);

        // Check if user is authorized to accept this invitation
        if ($invitation->invitee_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($invitation->isPending()) {
            $invitation->accept();

            return redirect()->route('invitations.index')
                ->with('success', 'Invitation accepted successfully!');
        }

        return redirect()->route('invitations.index')
            ->with('error', 'This invitation has already been processed.');
    }

    /**
     * Decline an invitation
     */
    public function decline($id)
    {
        $invitation = SubSupplierInvitation::findOrFail($id);

        // Check if user is authorized to decline this invitation
        if ($invitation->invitee_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($invitation->isPending()) {
            $invitation->decline();

            return redirect()->route('invitations.index')
                ->with('success', 'Invitation declined successfully!');
        }

        return redirect()->route('invitations.index')
            ->with('error', 'This invitation has already been processed.');
    }

    /**
     * Cancel a sent invitation
     */
    public function cancel($id)
    {
        $invitation = SubSupplierInvitation::findOrFail($id);

        // Check if user is authorized to cancel this invitation
        if ($invitation->inviter_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($invitation->isPending()) {
            $invitation->delete();

            return redirect()->route('invitations.index', ['type' => 'sent'])
                ->with('success', 'Invitation cancelled successfully!');
        }

        return redirect()->route('invitations.index', ['type' => 'sent'])
            ->with('error', 'This invitation has already been processed.');
    }
}
