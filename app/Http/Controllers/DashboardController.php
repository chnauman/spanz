<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $data = [
            'user' => $user,
        ];

        // Add role-specific data
        if ($user->isAdmin()) {
            $data['pending_approvals'] = \App\Models\User::where('is_approved', false)
                ->whereIn('role', ['supplier', 'sub_supplier'])
                ->count();
            $data['total_users'] = \App\Models\User::count();
            $data['total_subscriptions'] = \App\Models\UserSubscription::where('is_active', true)->count();
        }

        if ($user->isSupplier() || $user->isSubSupplier()) {
            $data['total_credits'] = $user->getTotalCredits();
            $data['active_subscription'] = $user->getActiveSubscription();
        }

        return view('dashboard', $data);
    }
}
