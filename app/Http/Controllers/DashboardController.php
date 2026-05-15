<?php

namespace App\Http\Controllers;

use App\Models\UserTenderNotification;
use App\Services\TenderProfileMatchingService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $data = [
            'user' => $user,
        ];

        if (!$user->isAdmin() && !$user->companyDetail) {
            $data['needs_company_profile'] = true;
        }

        if ($user->isAdmin()) {
            $data['recent_tenders'] = \App\Models\Tender::where('status', 'active')
                ->where('deadline', '>', now())
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            $data['recent_tenders'] = $this->getMatchingTendersForUser($user);
        }

        if ($user->isAdmin()) {
            $data['total_users'] = \App\Models\User::count();
            $data['total_buyers'] = \App\Models\User::where('role', 'buyer')->count();
            $data['total_suppliers'] = \App\Models\User::where('role', 'supplier')->count();
            $data['total_sub_suppliers'] = \App\Models\User::where('role', 'sub_supplier')->count();
            $data['pending_approvals'] = \App\Models\User::where('is_approved', false)
                ->whereIn('role', ['supplier', 'sub_supplier'])
                ->count();
            $data['new_users_this_month'] = \App\Models\User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $data['new_users_last_month'] = \App\Models\User::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count();
            $data['approved_users'] = \App\Models\User::where('is_approved', true)
                ->whereIn('role', ['supplier', 'sub_supplier'])
                ->count();

            $data['total_tenders'] = \App\Models\Tender::count();
            $data['active_tenders'] = \App\Models\Tender::where('status', 'active')
                ->where('deadline', '>', now())
                ->count();
            $data['closed_tenders'] = \App\Models\Tender::where('status', 'closed')->count();
            $data['expired_tenders'] = \App\Models\Tender::where('deadline', '<', now())
                ->where('status', '!=', 'closed')
                ->count();
            $data['tenders_this_month'] = \App\Models\Tender::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $data['tenders_last_month'] = \App\Models\Tender::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count();
            $data['total_tender_views'] = \App\Models\TenderView::count();
            $data['total_invitations'] = \App\Models\TenderInvitation::count();

            $data['total_subscriptions'] = \App\Models\UserSubscription::where('is_active', true)->count();
            $data['total_subscription_plans'] = \App\Models\Subscription::count();
            $data['active_subscription_plans'] = \App\Models\Subscription::where('is_active', true)->count();
            $data['pending_subscription_requests'] = \App\Models\SubscriptionRequest::where('status', 'pending')->count();
            $data['pending_downgrade_requests'] = \App\Models\DowngradeRequest::where('status', 'pending')->count();
            $data['expiring_subscriptions'] = \App\Models\UserSubscription::where('is_active', true)
                ->where('expires_at', '>', now())
                ->where('expires_at', '<=', now()->addDays(30))
                ->count();

            $data['total_credits_allocated'] = \App\Models\Credit::sum('amount');
            $data['total_credits_used'] = \App\Models\TenderView::sum('credits_deducted');
            $data['total_credits_remaining'] = $data['total_credits_allocated'] - $data['total_credits_used'];

            $months = [];
            $userCounts = [];
            $tenderCounts = [];
            $subscriptionCounts = [];

            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $months[] = $date->format('M Y');
                $userCounts[] = \App\Models\User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                $tenderCounts[] = \App\Models\Tender::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                $subscriptionCounts[] = \App\Models\UserSubscription::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('is_active', true)
                    ->count();
            }

            $data['chart_months'] = $months;
            $data['chart_user_counts'] = $userCounts;
            $data['chart_tender_counts'] = $tenderCounts;
            $data['chart_subscription_counts'] = $subscriptionCounts;

            $data['recent_users'] = \App\Models\User::orderBy('created_at', 'desc')->limit(5)->get();
            $data['recent_subscription_requests'] = \App\Models\SubscriptionRequest::with('user', 'subscription')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            $data['my_tenders'] = $user->tenders()->count();
            $data['unread_rfx_count'] = \App\Models\UserTenderNotification::query()
                ->where('user_id', $user->id)
                ->whereNull('read_at')
                ->count();
            $detail = $user->companyDetail;
            $data['profile_categories_count'] = $detail
                ? count($detail->getProfileCategoryIds())
                : 0;
        }

        if ($user->isSupplier() || $user->isSubSupplier()) {
            $data['total_credits'] = $user->getTotalCredits();
            $data['active_subscription'] = $user->getActiveSubscription();
        }

        return view('admin.dashboard', $data);
    }

    private function getMatchingTendersForUser($user)
    {
        $detail = $user->companyDetail;
        if (!$detail || ($detail->getProfileCategoryIds() === [] && $detail->getProfileSubcategoryIds() === [])) {
            return collect([]);
        }

        $matcher = app(TenderProfileMatchingService::class);

        $tenders = \App\Models\Tender::query()
            ->where('status', 'active')
            ->where('deadline', '>', now())
            ->where('user_id', '!=', $user->id)
            ->with('category')
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        return $tenders->filter(function ($tender) use ($matcher, $detail) {
            return $matcher->userMatchesTender(
                $detail,
                $matcher->tenderMainCategoryIds($tender),
                $matcher->tenderSubcategoryIds($tender)
            );
        })->take(5)->values();
    }
}
