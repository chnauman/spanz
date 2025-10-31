<?php

namespace App\Http\Controllers;

use App\Models\DowngradeRequest;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\DowngradeRequestAdminMail;

class DowngradeRequestController extends Controller
{
    /**
     * Show the downgrade request form
     */
    public function create()
    {
        $user = Auth::user();
        $activeSubscription = $user->getActiveSubscription();

        if (!$activeSubscription) {
            return redirect()->route('account.plan')
                ->with('error', 'You don\'t have an active subscription to downgrade.');
        }

        return view('account.downgrade-request', compact('activeSubscription'));
    }

    /**
     * Store the downgrade request
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $activeSubscription = $user->getActiveSubscription();

        if (!$activeSubscription) {
            if ($request->ajax()) {
                return response()->json(['error' => 'You don\'t have an active subscription to downgrade.'], 400);
            }
            return redirect()->route('account.plan')
                ->with('error', 'You don\'t have an active subscription to downgrade.');
        }

        // Delete any existing pending downgrade requests for this user
        // This ensures only the latest request is kept
        DowngradeRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->delete();

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $downgradeRequest = DowngradeRequest::create([
            'user_id' => $user->id,
            'current_subscription_id' => $activeSubscription->subscription_id,
            'reason' => $request->reason,
            'requested_at' => now(),
        ]);

        // Notify admin about the downgrade request
        try {
            $adminEmail = env('ADMIN_EMAIL');
            if ($adminEmail) {
                $downgradeRequest->setRelation('currentSubscription', $activeSubscription->subscription);
                $downgradeRequest->setRelation('user', $user);
                Mail::to($adminEmail)->send(new DowngradeRequestAdminMail($downgradeRequest));
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to send downgrade request admin email: ' . $e->getMessage());
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your downgrade request has been submitted successfully.',
                'request_id' => $downgradeRequest->id
            ]);
        }

        return redirect()->route('account.plan')
            ->with('success', 'Your downgrade request has been submitted successfully. You will be notified once it\'s processed.');
    }

    /**
     * Show user's downgrade requests
     */
    public function myRequests()
    {
        $requests = Auth::user()->downgradeRequests()
            ->with('currentSubscription')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('account.my-downgrade-requests', compact('requests'));
    }

    /**
     * Cancel a pending downgrade request
     */
    public function cancel(DowngradeRequest $downgradeRequest)
    {
        if ($downgradeRequest->user_id !== Auth::id() || $downgradeRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Cannot cancel this request.');
        }

        $downgradeRequest->delete();

        return redirect()->back()
            ->with('success', 'Downgrade request cancelled successfully!');
    }

    /**
     * Get current downgrade request status for AJAX
     */
    public function getStatus()
    {
        $user = Auth::user();
        $latestRequest = $user->downgradeRequests()->orderBy('created_at', 'desc')->first();

        $status = 'none';
        if ($latestRequest) {
            $status = $latestRequest->status;
        }

        return response()->json([
            'status' => $status,
            'request_id' => $latestRequest ? $latestRequest->id : null
        ]);
    }
}
