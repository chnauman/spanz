<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Show the user profile settings page
     */
    public function profile()
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    /**
     * Show the user plan settings page
     */
    public function plan()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $activeSubscription = $user->getActiveSubscription();
        $subscriptions = \App\Models\Subscription::where('is_active', true)->get();

        // Get the latest downgrade request status (only the most recent one)
        $latestDowngradeRequest = $user->downgradeRequests()->orderBy('created_at', 'desc')->first();

        $pendingDowngradeRequest = null;
        $approvedDowngradeRequest = null;
        $declinedDowngradeRequest = null;

        if ($latestDowngradeRequest) {
            switch ($latestDowngradeRequest->status) {
                case 'pending':
                    $pendingDowngradeRequest = $latestDowngradeRequest;
                    break;
                case 'approved':
                    $approvedDowngradeRequest = $latestDowngradeRequest;
                    break;
                case 'declined':
                    $declinedDowngradeRequest = $latestDowngradeRequest;
                    break;
            }
        }

        return view('account.plan', compact('user', 'activeSubscription', 'subscriptions', 'pendingDowngradeRequest', 'approvedDowngradeRequest', 'declinedDowngradeRequest'));
    }

    /**
     * Show the user credits page
     * Accessible to all authenticated users (buyers, suppliers, and sub suppliers)
     * Sub suppliers see their parent supplier's credits (shared credit pool)
     */
    public function credits()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // If user is a sub supplier, show parent supplier's credits and history
        // Otherwise, show the user's own credits
        $creditsOwner = $user;
        if ($user->isSubSupplier() && $user->parentSupplier) {
            $creditsOwner = $user->parentSupplier;
        }
        
        $totalCredits = $creditsOwner->getTotalCredits() ?? 0;
        $creditHistory = $creditsOwner->credits()->orderBy('created_at', 'desc')->get();

        return view('account.credits', compact('user', 'totalCredits', 'creditHistory', 'creditsOwner'));
    }

    /**
     * Update notification frequency settings
     */
    public function updateNotifications(Request $request)
    {
        $request->validate([
            'notification_frequency' => 'required|in:none,daily,weekly,monthly',
        ]);

        $user = Auth::user();
        $user->update([
            'notification_frequency' => $request->notification_frequency,
        ]);

        return redirect()->route('account.profile')
            ->with('notification_success', 'Notification settings updated successfully!');
    }
}
