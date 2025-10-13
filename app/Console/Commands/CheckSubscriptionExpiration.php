<?php

namespace App\Console\Commands;

use App\Models\UserSubscription;
use App\Models\User;
use App\Mail\SubscriptionExpiringIn3DaysMail;
use App\Mail\SubscriptionExpiringIn1DayMail;
use App\Mail\SubscriptionExpiredMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CheckSubscriptionExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for subscription expirations and send email notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking subscription expirations...');

        $today = Carbon::today();
        $threeDaysFromNow = Carbon::today()->addDays(3);
        $oneDayFromNow = Carbon::today()->addDays(1);

        // Check subscriptions expiring in 3 days
        $this->checkExpiringIn3Days($threeDaysFromNow);
        
        // Check subscriptions expiring in 1 day
        $this->checkExpiringIn1Day($oneDayFromNow);
        
        // Check subscriptions that have expired today
        $this->checkExpiredToday($today);

        $this->info('Subscription expiration check completed.');
    }

    /**
     * Check subscriptions expiring in 3 days
     */
    private function checkExpiringIn3Days($threeDaysFromNow)
    {
        $expiringIn3Days = UserSubscription::where('is_active', true)
            ->whereDate('expires_at', $threeDaysFromNow)
            ->with(['user', 'subscription'])
            ->get();

        foreach ($expiringIn3Days as $userSubscription) {
            try {
                Mail::to($userSubscription->user->email)
                    ->send(new SubscriptionExpiringIn3DaysMail($userSubscription));
                
                $this->info("Sent 'expiring in 3 days' email to: {$userSubscription->user->email}");
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$userSubscription->user->email}: " . $e->getMessage());
            }
        }
    }

    /**
     * Check subscriptions expiring in 1 day
     */
    private function checkExpiringIn1Day($oneDayFromNow)
    {
        $expiringIn1Day = UserSubscription::where('is_active', true)
            ->whereDate('expires_at', $oneDayFromNow)
            ->with(['user', 'subscription'])
            ->get();

        foreach ($expiringIn1Day as $userSubscription) {
            try {
                Mail::to($userSubscription->user->email)
                    ->send(new SubscriptionExpiringIn1DayMail($userSubscription));
                
                $this->info("Sent 'expiring in 1 day' email to: {$userSubscription->user->email}");
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$userSubscription->user->email}: " . $e->getMessage());
            }
        }
    }

    /**
     * Check subscriptions that have expired today
     */
    private function checkExpiredToday($today)
    {
        $expiredToday = UserSubscription::where('is_active', true)
            ->whereDate('expires_at', $today)
            ->where('expires_at', '<', now())
            ->with(['user', 'subscription'])
            ->get();

        foreach ($expiredToday as $userSubscription) {
            try {
                // Mark subscription as inactive
                $userSubscription->update(['is_active' => false]);
                
                Mail::to($userSubscription->user->email)
                    ->send(new SubscriptionExpiredMail($userSubscription));
                
                $this->info("Sent 'expired' email to: {$userSubscription->user->email}");
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$userSubscription->user->email}: " . $e->getMessage());
            }
        }
    }
}
