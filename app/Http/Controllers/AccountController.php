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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Backfill location/phone from company_details for accounts created
        // before these fields started being saved on the users table at
        // registration time. This is a one-time, automatic sync so the View
        // Profile page can display the values the user already entered.
        $this->backfillUserContactFromCompanyDetail($user);

        return view('account.profile', compact('user'));
    }

    /**
     * Copy country/state/city/phone from the user's company_detail row onto
     * the users table when the user-level columns are still empty. Values
     * equal to the legacy placeholder "Not provided" are ignored.
     */
    protected function backfillUserContactFromCompanyDetail(\App\Models\User $user): void
    {
        $companyDetail = $user->companyDetail;
        if (!$companyDetail) {
            return;
        }

        $updates = [];
        $map = [
            'country' => $companyDetail->country,
            'state' => $companyDetail->state,
            'city' => $companyDetail->city,
            'phone' => $companyDetail->phone,
        ];

        foreach ($map as $field => $value) {
            if (empty($user->{$field}) && !empty($value) && $value !== 'Not provided') {
                $updates[$field] = $value;
            }
        }

        if (!empty($updates)) {
            $user->update($updates);
        }
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
