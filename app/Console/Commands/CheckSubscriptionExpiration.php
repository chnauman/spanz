<?php

namespace App\Console\Commands;

use App\Models\UserSubscription;
use App\Models\User;
use App\Mail\SubscriptionExpiringIn7DaysMail;
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
        $sevenDaysFromNow = Carbon::today()->addDays(7);
        $threeDaysFromNow = Carbon::today()->addDays(3);
        $oneDayFromNow = Carbon::today()->addDays(1);

        // Display all active subscriptions with days remaining
        $this->displaySubscriptionStatus();

        // Check subscriptions expiring in 7 days
        $this->checkExpiringIn7Days($sevenDaysFromNow);

        // Check subscriptions expiring in 3 days
        $this->checkExpiringIn3Days($threeDaysFromNow);
        
        // Check subscriptions expiring in 1 day
        $this->checkExpiringIn1Day($oneDayFromNow);
        
        // Check subscriptions that have expired today
        $this->checkExpiredToday($today);

        $this->info('Subscription expiration check completed.');
    }

    /**
     * Display subscription status with days remaining
     */
    private function displaySubscriptionStatus()
    {
        $subscriptions = UserSubscription::where('is_active', true)
            ->with(['user', 'subscription'])
            ->orderBy('expires_at', 'asc')
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->warn('No active subscriptions found.');
            return;
        }

        $this->info("\nActive Subscriptions Status:");
        $this->line(str_repeat('-', 100));

        $headers = ['User', 'Email', 'Subscription', 'Days Remaining', 'Expires At'];
        $rows = [];

        foreach ($subscriptions as $userSubscription) {
            $user = $userSubscription->user;
            $subscription = $userSubscription->subscription;
            
            $expiresAt = $userSubscription->expires_at;
            $now = Carbon::now();
            
            if ($expiresAt) {
                $daysRemaining = $now->diffInDays($expiresAt, false);
                
                if ($daysRemaining < 0) {
                    $daysRemaining = abs($daysRemaining);
                    $daysRemainingDisplay = '-' . $daysRemaining . ' (Expired)';
                } else {
                    $daysRemainingDisplay = $daysRemaining;
                }
                
                $expiresAtFormatted = $expiresAt->format('Y-m-d H:i:s');
            } else {
                $daysRemainingDisplay = 'N/A';
                $expiresAtFormatted = 'N/A';
            }

            $rows[] = [
                $user->name ?? 'N/A',
                $user->email ?? 'N/A',
                $subscription->name ?? 'N/A',
                $daysRemainingDisplay,
                $expiresAtFormatted,
            ];
        }

        $this->table($headers, $rows);

        // Summary
        $activeCount = $subscriptions->filter(fn($s) => $s->expires_at && $s->expires_at > $now)->count();
        $expiringSoon = $subscriptions->filter(function($s) {
            if (!$s->expires_at) return false;
            $days = Carbon::now()->diffInDays($s->expires_at, false);
            return $days >= 0 && $days <= 7;
        })->count();
        $expiredCount = $subscriptions->filter(fn($s) => $s->expires_at && $s->expires_at < $now)->count();

        $this->info("\nSummary:");
        $this->line("Total active subscriptions: " . $subscriptions->count());
        $this->line("Active (not expired): " . $activeCount);
        $this->line("Expiring in 7 days or less: " . $expiringSoon);
        $this->line("Expired: " . $expiredCount);
        $this->line(str_repeat('-', 100) . "\n");
    }

    /**
     * Check subscriptions expiring in 7 days
     */
    private function checkExpiringIn7Days($sevenDaysFromNow)
    {
        $expiringIn7Days = UserSubscription::where('is_active', true)
            ->whereDate('expires_at', $sevenDaysFromNow)
            ->with(['user', 'subscription'])
            ->get();

        foreach ($expiringIn7Days as $userSubscription) {
            try {
                Mail::to($userSubscription->user->email)
                    ->send(new SubscriptionExpiringIn7DaysMail($userSubscription));
                
                $this->info("Sent 'expiring in 7 days' email to: {$userSubscription->user->email}");
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$userSubscription->user->email}: " . $e->getMessage());
            }
        }
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
