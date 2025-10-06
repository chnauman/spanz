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
}
