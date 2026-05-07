<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPasswordNotification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'country',
        'state',
        'city',
        'phone',
        'password',
        'email_verified_at',
        'role',
        'is_approved',
        'parent_supplier_id',
        'is_buyer',
        'is_supplier',
        'interests_set',
        'notification_frequency',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
            'is_buyer' => 'boolean',
            'is_supplier' => 'boolean',
            'interests_set' => 'boolean',
        ];
    }

    // Relationships
    public function companyDetail()
    {
        return $this->hasOne(CompanyDetail::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    public function parentSupplier()
    {
        return $this->belongsTo(User::class, 'parent_supplier_id');
    }

    public function subSuppliers()
    {
        return $this->hasMany(User::class, 'parent_supplier_id');
    }

    // Note: role is stored as enum in users table, not as foreign key
    // public function role()
    // {
    //     return $this->belongsTo(Role::class);
    // }

    public function interests()
    {
        return $this->hasMany(UserInterest::class);
    }

    public function budgetRanges()
    {
        return $this->hasMany(UserBudgetRange::class);
    }

    public function tenders()
    {
        return $this->hasMany(Tender::class);
    }

    public function tenderInvitations()
    {
        return $this->hasMany(TenderInvitation::class);
    }

    public function savedTenders()
    {
        return $this->hasMany(SavedTender::class);
    }

    public function subscriptionRequests()
    {
        return $this->hasMany(SubscriptionRequest::class);
    }

    public function downgradeRequests()
    {
        return $this->hasMany(DowngradeRequest::class);
    }

    public function tenderViews()
    {
        return $this->hasMany(TenderView::class);
    }

    public function sentInvitations()
    {
        return $this->hasMany(SubSupplierInvitation::class, 'inviter_id');
    }

    public function receivedInvitations()
    {
        return $this->hasMany(SubSupplierInvitation::class, 'invitee_id');
    }

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function sentSupplierDocumentShares()
    {
        return $this->hasMany(SupplierDocumentShare::class, 'sender_id');
    }

    public function supplierDocumentShareRecipients()
    {
        return $this->hasMany(SupplierDocumentShareRecipient::class, 'recipient_user_id');
    }

    // Role-based methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }

    public function isSupplier(): bool
    {
        return $this->role === 'supplier';
    }

    public function isSubSupplier(): bool
    {
        return $this->role === 'sub_supplier';
    }

    public function isGuest(): bool
    {
        return $this->role === 'guest';
    }

    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    // Get total credits for user
    // If user is a sub supplier, return parent supplier's credits (shared pool)
    public function getTotalCredits()
    {
        // If user is a sub supplier, return parent supplier's credits
        if ($this->isSubSupplier() && $this->parentSupplier) {
            return $this->parentSupplier->credits()->sum('amount');
        }
        
        return $this->credits()->sum('amount');
    }

    // Get active subscription
    public function getActiveSubscription()
    {
        return $this->subscriptions()
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->with('subscription')
            ->first();
    }

    // Permission methods
    public function hasPermission($permission)
    {
        // For now, we'll use role-based permissions
        // This can be enhanced later to use the Role model with permissions
        $rolePermissions = $this->getRolePermissions();
        return in_array($permission, $rolePermissions);
    }

    /**
     * Get permissions for the user's role
     */
    protected function getRolePermissions()
    {
        switch ($this->role) {
            case 'admin':
                return [
                    'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
                    'view-subscriptions', 'create-subscriptions', 'edit-subscriptions', 'delete-subscriptions',
                    'view-credits', 'manage-credits',
                    'view-tenders', 'create-tenders', 'edit-tenders', 'delete-tenders', 'bid-tenders',
                    'access-admin-dashboard', 'access-buyer-dashboard', 'access-supplier-dashboard',
                ];
            case 'buyer':
                return [
                    'view-tenders', 'create-tenders', 'edit-tenders', 'delete-tenders',
                    'access-buyer-dashboard',
                ];
            case 'supplier':
                return [
                    'view-tenders', 'bid-tenders',
                    'access-supplier-dashboard',
                ];
            case 'sub_supplier':
                return [
                    'view-tenders', 'bid-tenders',
                    'access-supplier-dashboard',
                ];
            case 'guest':
            default:
                return [];
        }
    }

    // Subscription-related methods
    public function hasActiveSubscription()
    {
        return $this->getActiveSubscription() !== null;
    }

    public function hasProSubscription()
    {
        $activeSubscription = $this->getActiveSubscription();
        return $activeSubscription && $activeSubscription->subscription->name === 'Pro';
    }

    public function hasEnterpriseSubscription()
    {
        $activeSubscription = $this->getActiveSubscription();
        return $activeSubscription && $activeSubscription->subscription->name === 'Enterprise';
    }

    public function canViewTenderDetails()
    {
        // Basic users (free) cannot view tender details
        // Only Pro and Enterprise subscribers can view details
        return $this->hasProSubscription() || $this->hasEnterpriseSubscription();
    }

    // Credit-based access methods
    public function canViewTenderDetailsWithCredits($tenderId = null)
    {
        $activeSubscription = $this->getActiveSubscription();

        if (!$activeSubscription) {
            return false; // No active subscription
        }

        // Enterprise users with unlimited credits (-1) can always view
        if ($activeSubscription->subscription->credits_per_month == -1) {
            return true;
        }

        // Check if user has enough credits for this tender (if provided), otherwise fall back to subscription default.
        $totalCredits = $this->getTotalCredits();
        $creditCostPerView = $this->getTenderViewCreditCost($tenderId);

        if ($creditCostPerView === null) {
            return false;
        }

        return $totalCredits >= $creditCostPerView;
    }

    public function getCreditCostPerView()
    {
        $activeSubscription = $this->getActiveSubscription();

        if (!$activeSubscription) {
            return 0;
        }

        return $activeSubscription->subscription->credit_cost_per_view ?? 1;
    }

    /**
     * Resolve tender-view credit cost using admin-managed budget pricing rules.
     * Returns null if tender budget is missing/invalid or pricing is not configured.
     */
    public function getTenderViewCreditCost($tenderId = null)
    {
        $activeSubscription = $this->getActiveSubscription();

        if (!$activeSubscription) {
            return 0;
        }

        // Unlimited plans don't charge per view
        if ($activeSubscription->subscription->credits_per_month < 0) {
            return 0;
        }

        // If no tender provided, fall back to subscription default (used by generic summary endpoints)
        if (!$tenderId) {
            return $activeSubscription->subscription->credit_cost_per_view ?? 1;
        }

        $tender = \App\Models\Tender::find($tenderId);
        // Owner views are always free
        if ($tender && $this->id === $tender->user_id) {
            return 0;
        }

        if (!$tender || !$tender->budget || (float) $tender->budget <= 0) {
            return null;
        }

        $rule = TenderViewPricingRule::matchForBudget((float) $tender->budget);
        if (!$rule) {
            return null;
        }

        return (int) $rule->credits_cost;
    }

    public function deductCreditsForTenderView($tenderId)
    {
        $activeSubscription = $this->getActiveSubscription();

        if (!$activeSubscription) {
            return false;
        }

        // Users with unlimited credits don't need to deduct
        if ($activeSubscription->subscription->credits_per_month < 0) {
            return true;
        }

        // Check if user is the owner of this tender
        $tender = \App\Models\Tender::find($tenderId);
        if ($tender && $this->id === $tender->user_id) {
            // Owner can view their own tender for free - record view without deducting credits
            \App\Models\TenderView::recordView($this->id, $tenderId, 0, 0);
            return true;
        }

        // Check if user has already viewed this tender
        if (\App\Models\TenderView::hasUserViewedTender($this->id, $tenderId)) {
            return true; // Already viewed, no need to deduct credits again
        }

        // Tender budget is required for pricing
        if (!$tender || !$tender->budget || (float) $tender->budget <= 0) {
            return false;
        }

        $creditCostPerView = $this->getTenderViewCreditCost($tenderId);
        if ($creditCostPerView === null) {
            return false;
        }

        // Check if any team member has viewed this tender (team access)
        if ($this->hasTeamMemberViewedTender($tenderId)) {
            // Record the tender view without deducting credits (team access)
            \App\Models\TenderView::recordView($this->id, $tenderId, 0, $creditCostPerView);
            return true;
        }

        // If this tender is configured as free to view, just record it.
        if ((int) $creditCostPerView === 0) {
            \App\Models\TenderView::recordView($this->id, $tenderId, 0, 0);
            return true;
        }

        // Check if user has enough credits (will check parent if sub supplier)
        if ($this->getTotalCredits() < $creditCostPerView) {
            return false;
        }

        // Determine who owns the credits (parent supplier for sub suppliers, or user themselves)
        $creditsOwner = $this;
        if ($this->isSubSupplier() && $this->parentSupplier) {
            $creditsOwner = $this->parentSupplier;
        }

        // Build description with user name and tender title
        $tenderTitle = $tender ? $tender->cardTitle() : 'Tender #' . $tenderId;
        $userName = $this->name;
        $description = $this->isSubSupplier() 
            ? "Credits used by {$userName} (Sub Supplier) to view tender: {$tenderTitle}"
            : "Credits used by {$userName} to view tender: {$tenderTitle}";

        // Deduct credits from the owner (parent supplier for sub suppliers, or user themselves)
        $creditsOwner->credits()->create([
            'amount' => -$creditCostPerView,
            'type' => 'used',
            'description' => $description
        ]);

        // Record the tender view
        \App\Models\TenderView::recordView($this->id, $tenderId, $creditCostPerView, $creditCostPerView);

        return true;
    }

    public function getSubscriptionStatus()
    {
        if ($this->hasEnterpriseSubscription()) {
            return 'enterprise';
        } elseif ($this->hasProSubscription()) {
            return 'pro';
        } else {
            return 'basic';
        }
    }

    public function hasPendingSubscriptionRequest()
    {
        return $this->subscriptionRequests()
            ->where('status', 'pending')
            ->exists();
    }

    public function getPendingSubscriptionRequest()
    {
        return $this->subscriptionRequests()
            ->where('status', 'pending')
            ->with('subscription')
            ->first();
    }

    /**
     * Check if user has already viewed a specific tender
     */
    public function hasViewedTender($tenderId)
    {
        return \App\Models\TenderView::hasUserViewedTender($this->id, $tenderId);
    }

    /**
     * Get all team members (sub suppliers for main supplier, or parent + siblings for sub supplier)
     */
    public function getTeamMembers()
    {
        if ($this->isSupplier()) {
            // For main suppliers, return all sub suppliers
            return $this->subSuppliers()->get();
        } elseif ($this->isSubSupplier() && $this->parentSupplier) {
            // For sub suppliers, return parent and all siblings
            $teamMembers = collect([$this->parentSupplier]);
            $teamMembers = $teamMembers->merge($this->parentSupplier->subSuppliers()->get());
            return $teamMembers->unique('id');
        }

        return collect();
    }

    /**
     * Check if any team member has viewed a specific tender
     */
    public function hasTeamMemberViewedTender($tenderId)
    {
        $teamMembers = $this->getTeamMembers();

        if ($teamMembers->isEmpty()) {
            return false;
        }

        $teamMemberIds = $teamMembers->pluck('id')->toArray();

        return \App\Models\TenderView::where('tender_id', $tenderId)
            ->whereIn('user_id', $teamMemberIds)
            ->exists();
    }

    /**
     * Check if user can view tender details considering team access
     */
    public function canViewTenderDetailsWithTeamAccess($tenderId)
    {
        // First check if user has already viewed this tender
        if ($this->hasViewedTender($tenderId)) {
            return true;
        }

        // Check if any team member has viewed this tender
        if ($this->hasTeamMemberViewedTender($tenderId)) {
            return true;
        }

        // If no team member has viewed, check individual access
        return $this->canViewTenderDetailsWithCredits($tenderId);
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    /**
     * Get comprehensive subscription and credit status
     */
    public function getSubscriptionAndCreditStatus($tenderId = null)
    {
        $activeSubscription = $this->getActiveSubscription();
        $totalCredits = $this->getTotalCredits();

        if (!$activeSubscription) {
            return [
                'has_subscription' => false,
                'subscription_expired' => false,
                'total_credits' => $totalCredits,
                'credit_cost_per_view' => 0,
                'can_view' => false,
                'message' => 'No active subscription. Please subscribe to view tender details.',
                'action' => 'subscribe'
            ];
        }

        // Check if subscription has expired
        if ($activeSubscription->expires_at < now()) {
            return [
                'has_subscription' => true,
                'subscription_expired' => true,
                'total_credits' => $totalCredits,
                'credit_cost_per_view' => $activeSubscription->subscription->credit_cost_per_view ?? 1,
                'can_view' => false,
                'message' => 'Your subscription has expired. Please renew to continue viewing tender details.',
                'action' => 'renew'
            ];
        }

        $creditCostPerView = $activeSubscription->subscription->credit_cost_per_view ?? 1;

        // Users with unlimited credits (credits_per_month < 0)
        if ($activeSubscription->subscription->credits_per_month < 0) {
            return [
                'has_subscription' => true,
                'subscription_expired' => false,
                'total_credits' => -1, // Unlimited
                'credit_cost_per_view' => 0,
                'can_view' => true,
                'message' => 'You have unlimited access.',
                'action' => null
            ];
        }

        // Check if user is the owner of this tender (owner access)
        if ($tenderId) {
            $tender = \App\Models\Tender::find($tenderId);
            if ($tender && $this->id === $tender->user_id) {
                return [
                    'has_subscription' => true,
                    'subscription_expired' => false,
                    'total_credits' => $totalCredits,
                    'credit_cost_per_view' => 0,
                    'can_view' => true,
                    'message' => 'You can view your own tender for free.',
                    'action' => null,
                    'owner_access' => true
                ];
            }
        }

        // Check if any team member has viewed this tender (team access)
        if ($tenderId && $this->hasTeamMemberViewedTender($tenderId)) {
            return [
                'has_subscription' => true,
                'subscription_expired' => false,
                'total_credits' => $totalCredits,
                'credit_cost_per_view' => 0,
                'can_view' => true,
                'message' => 'A team member has already viewed this tender. You can view it for free.',
                'action' => null,
                'team_access' => true
            ];
        }

        // Resolve per-tender pricing rule (budget-based) for first-time/team unlock.
        if ($tenderId) {
            $resolved = $this->getTenderViewCreditCost($tenderId);
            if ($resolved === null) {
                return [
                    'has_subscription' => true,
                    'subscription_expired' => false,
                    'total_credits' => $totalCredits,
                    'credit_cost_per_view' => 0,
                    'can_view' => false,
                    'message' => 'Tender budget is missing or pricing is not configured for this budget.',
                    'action' => null
                ];
            }
            $creditCostPerView = $resolved;
        }

        // Check if user has enough credits
        if ($totalCredits < $creditCostPerView) {
            return [
                'has_subscription' => true,
                'subscription_expired' => false,
                'total_credits' => $totalCredits,
                'credit_cost_per_view' => $creditCostPerView,
                'can_view' => false,
                'message' => $totalCredits <= 0
                    ? 'You have used all your credits. Please upgrade your subscription to get more credits.'
                    : 'You have insufficient credits to view this tender. Please upgrade your subscription.',
                'action' => 'upgrade'
            ];
        }

        return [
            'has_subscription' => true,
            'subscription_expired' => false,
            'total_credits' => $totalCredits,
            'credit_cost_per_view' => $creditCostPerView,
            'can_view' => true,
            'message' => 'You can view this tender.',
            'action' => null
        ];
    }
}
