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
     */
    public function credits()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $totalCredits = $user->getTotalCredits() ?? 0;
        $creditHistory = $user->credits()->orderBy('created_at', 'desc')->get();

        return view('account.credits', compact('user', 'totalCredits', 'creditHistory'));
    }
}
