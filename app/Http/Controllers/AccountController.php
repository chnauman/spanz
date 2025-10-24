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

        return view('account.plan', compact('user', 'activeSubscription', 'subscriptions'));
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
