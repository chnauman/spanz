<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Redirect to interests page if user hasn't set interests yet
        if (!$user->isAdmin() && !$user->interests_set) {
            return redirect()->route('user.interests');
        }
        
        $data = [
            'user' => $user,
        ];
        
        // Add company details check for non-admin users
        if (!$user->isAdmin() && !$user->companyDetail) {
            $data['needs_company_profile'] = true;
        }

        // Add recent tenders for all users
        $data['recent_tenders'] = \App\Models\Tender::where('status', 'active')
            ->where('deadline', '>', now())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Add role-specific data
        if ($user->isAdmin()) {
            $data['pending_approvals'] = \App\Models\User::where('is_approved', false)
                ->whereIn('role', ['supplier', 'sub_supplier'])
                ->count();
            $data['total_users'] = \App\Models\User::count();
            $data['total_subscriptions'] = \App\Models\UserSubscription::where('is_active', true)->count();
            $data['total_tenders'] = \App\Models\Tender::count();
            $data['active_tenders'] = \App\Models\Tender::where('status', 'active')->count();
        } else {
            // For non-admin users, show tender-related data
            $data['my_tenders'] = $user->tenders()->count();
            $data['my_invitations'] = $user->tenderInvitations()->count();
        }

        if ($user->isSupplier() || $user->isSubSupplier()) {
            $data['total_credits'] = $user->getTotalCredits();
            $data['active_subscription'] = $user->getActiveSubscription();
        }

        return view('admin.dashboard', $data);
    }
}
