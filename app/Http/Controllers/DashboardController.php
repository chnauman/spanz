<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Redirect to interests page if user hasn't set interests yet
        // if (!$user->isAdmin() && !$user->interests_set) {
        //     return redirect()->route('user.interests');
        // }
        
        $data = [
            'user' => $user,
        ];
        
        // Add company details check for non-admin users
        if (!$user->isAdmin() && !$user->companyDetail) {
            $data['needs_company_profile'] = true;
        }

        // Add recent tenders - filtered by user interests for non-admin users
        if ($user->isAdmin()) {
            // For admin, show all recent tenders
            $data['recent_tenders'] = \App\Models\Tender::where('status', 'active')
                ->where('deadline', '>', now())
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            // For non-admin users, show only tenders matching their interests
            $data['recent_tenders'] = $this->getMatchingTendersForUser($user);
        }

        // Add role-specific data
        if ($user->isAdmin()) {
            // User Statistics
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
            
            // Tender Statistics
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
            
            // Subscription Statistics
            $data['total_subscriptions'] = \App\Models\UserSubscription::where('is_active', true)->count();
            $data['total_subscription_plans'] = \App\Models\Subscription::count();
            $data['active_subscription_plans'] = \App\Models\Subscription::where('is_active', true)->count();
            $data['pending_subscription_requests'] = \App\Models\SubscriptionRequest::where('status', 'pending')->count();
            $data['pending_downgrade_requests'] = \App\Models\DowngradeRequest::where('status', 'pending')->count();
            $data['expiring_subscriptions'] = \App\Models\UserSubscription::where('is_active', true)
                ->where('expires_at', '>', now())
                ->where('expires_at', '<=', now()->addDays(30))
                ->count();
            
            // Credit Statistics
            $data['total_credits_allocated'] = \App\Models\Credit::sum('amount');
            $data['total_credits_used'] = \App\Models\TenderView::sum('credits_deducted');
            $data['total_credits_remaining'] = $data['total_credits_allocated'] - $data['total_credits_used'];
            
            // Monthly data for charts (last 6 months)
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
            
            // Recent Activity
            $data['recent_users'] = \App\Models\User::orderBy('created_at', 'desc')->limit(5)->get();
            $data['recent_subscription_requests'] = \App\Models\SubscriptionRequest::with('user', 'subscription')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            // For non-admin users, show tender-related data
            $data['my_tenders'] = $user->tenders()->count();
            $data['my_invitations'] = $user->tenderInvitations()->count();
            $data['user_interests_count'] = $user->interests()->count();
        }

        if ($user->isSupplier() || $user->isSubSupplier()) {
            $data['total_credits'] = $user->getTotalCredits();
            $data['active_subscription'] = $user->getActiveSubscription();
        }

        return view('admin.dashboard', $data);
    }

    /**
     * Get matching tenders for a user based on interests and budget ranges
     */
    private function getMatchingTendersForUser($user)
    {
        // If user hasn't set interests, return empty collection
        if (!$user->interests_set || $user->interests()->count() == 0) {
            return collect([]);
        }

        // Get user's interest category IDs
        $categoryIds = $user->interests()->pluck('category_id')->toArray();

        if (empty($categoryIds)) {
            return collect([]);
        }

        // Get tenders matching user's categories
        $tenders = \App\Models\Tender::whereIn('category_id', $categoryIds)
            ->where('status', 'active')
            ->where('deadline', '>', now())
            ->where('user_id', '!=', $user->id) // Exclude tenders created by the user
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->limit(20) // Get more to filter by budget
            ->get();

        // Filter by budget ranges if user has budget preferences
        $budgetRanges = $user->budgetRanges;
        
        if ($budgetRanges->count() > 0) {
            $tenders = $tenders->filter(function ($tender) use ($budgetRanges) {
                // Find budget range for this tender's category
                $budgetRange = $budgetRanges->firstWhere('category_id', $tender->category_id);
                
                if (!$budgetRange) {
                    // No budget range for this category, include the tender
                    return true;
                }

                // If tender has no budget, include it
                if (!$tender->budget || $tender->budget == 0) {
                    return true;
                }

                // Check if tender budget matches user's budget range
                return $this->matchesBudgetRange($tender, $budgetRange);
            });
        }

        // Return top 5 most recent matching tenders
        return $tenders->take(5);
    }

    /**
     * Check if tender budget matches user's budget range
     */
    private function matchesBudgetRange($tender, $budgetRange)
    {
        // Convert tender budget to same currency if needed (simplified - assumes same currency for now)
        $tenderBudget = $tender->budget;
        
        // If currencies don't match, we could convert here, but for simplicity, we'll just check if same currency
        if ($tender->currency !== $budgetRange->currency) {
            // For now, if currencies don't match, include the tender
            // In production, you might want to add currency conversion
            return true;
        }

        switch ($budgetRange->budget_type) {
            case 'less':
                // User wants tenders with budget less than max_budget
                if ($budgetRange->max_budget) {
                    return $tenderBudget <= $budgetRange->max_budget;
                }
                return true;

            case 'greater':
                // User wants tenders with budget greater than min_budget
                if ($budgetRange->min_budget) {
                    return $tenderBudget >= $budgetRange->min_budget;
                }
                return true;

            case 'range':
                // User wants tenders within a budget range
                $matchesMin = !$budgetRange->min_budget || $tenderBudget >= $budgetRange->min_budget;
                $matchesMax = !$budgetRange->max_budget || $tenderBudget <= $budgetRange->max_budget;
                return $matchesMin && $matchesMax;

            default:
                return true;
        }
    }
}
