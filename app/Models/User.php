<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'password',
        'role',
        'is_approved',
        'parent_supplier_id',
        'is_buyer',
        'is_supplier',
        'interests_set',
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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function interests()
    {
        return $this->hasMany(UserInterest::class);
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

    public function tenderViews()
    {
        return $this->hasMany(TenderView::class);
    }

    // Role-based methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isBuyer()
    {
        return $this->role === 'buyer';
    }

    public function isSupplier()
    {
        return $this->role === 'supplier';
    }

    public function isSubSupplier()
    {
        return $this->role === 'sub_supplier';
    }

    public function isGuest()
    {
        return $this->role === 'guest';
    }

    public function isApproved()
    {
        return $this->is_approved;
    }

    // Get total credits for user
    public function getTotalCredits()
    {
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
        if ($this->role) {
            return $this->role->hasPermission($permission);
        }
        return false;
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
    public function canViewTenderDetailsWithCredits()
    {
        $activeSubscription = $this->getActiveSubscription();
        
        if (!$activeSubscription) {
            return false; // No active subscription
        }

        // Enterprise users with unlimited credits (-1) can always view
        if ($activeSubscription->subscription->credits_per_month == -1) {
            return true;
        }

        // Check if user has enough credits
        $totalCredits = $this->getTotalCredits();
        $creditCostPerView = $activeSubscription->subscription->credit_cost_per_view ?? 1;
        
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

        $creditCostPerView = $activeSubscription->subscription->credit_cost_per_view ?? 1;
        
        // Check if user has already viewed this tender
        if (\App\Models\TenderView::hasUserViewedTender($this->id, $tenderId)) {
            return true; // Already viewed, no need to deduct credits again
        }
        
        // Check if user has enough credits
        if ($this->getTotalCredits() < $creditCostPerView) {
            return false;
        }

        // Deduct credits
        $this->credits()->create([
            'amount' => -$creditCostPerView,
            'type' => 'used',
            'description' => 'Credits used for viewing tender details'
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
     * Get comprehensive subscription and credit status
     */
    public function getSubscriptionAndCreditStatus()
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
