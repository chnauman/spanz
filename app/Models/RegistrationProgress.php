<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationProgress extends Model
{
    use HasFactory;

    protected $table = 'registration_progress';

    protected $fillable = [
        'email',
        'password',
        'registered_business_name',
        'country',
        'business_address',
        'full_name',
        'title_position',
        'cell_mobile',
        'whatsapp_wechat',
        'email_verified',
        'email_verified_at',
        'subscription_id',
        'current_step',
        'registration_complete',
    ];

    protected $casts = [
        'email_verified' => 'boolean',
        'email_verified_at' => 'datetime',
        'registration_complete' => 'boolean',
    ];

    /**
     * Get the subscription
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Find registration progress by email
     */
    public static function findByEmail($email)
    {
        return self::where('email', $email)->first();
    }

    /**
     * Check if user can proceed to next step
     */
    public function canProceedToStep($step)
    {
        switch ($step) {
            case 2:
                return $this->current_step >= 1 && 
                       !empty($this->email) && 
                       !empty($this->password) &&
                       !empty($this->registered_business_name) &&
                       !empty($this->country) &&
                       !empty($this->business_address) &&
                       !empty($this->full_name) &&
                       !empty($this->title_position) &&
                       !empty($this->cell_mobile);
            case 3:
                return $this->current_step >= 2 && $this->email_verified;
            default:
                return false;
        }
    }
}
