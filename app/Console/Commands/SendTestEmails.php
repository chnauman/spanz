<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionExpiringIn3DaysMail;
use App\Mail\UpgradeRequestAdminMail;
use App\Mail\DowngradeRequestAdminMail;
use App\Mail\SubscriptionApprovedMail;
use App\Mail\SubscriptionRejectedMail;
use App\Models\User;
use App\Models\Subscription;
use App\Models\UserSubscription;
use App\Models\SubscriptionRequest;
use App\Models\DowngradeRequest;
use Carbon\Carbon;

class SendTestEmails extends Command
{
    protected $signature = 'emails:test {type : expiry3|upgrade_request|downgrade_request|approved|rejected} {--to=}';

    protected $description = 'Send test emails for various scenarios to a target address';

    public function handle()
    {
        $type = $this->argument('type');
        $to = $this->option('to') ?: 'sidrarafaqat730@gmail.com';

        try {
            switch ($type) {
                case 'expiry3':
                    $this->sendExpiry3Days($to);
                    break;
                case 'upgrade_request':
                    $this->sendUpgradeRequestAdmin($to);
                    break;
                case 'downgrade_request':
                    $this->sendDowngradeRequestAdmin($to);
                    break;
                case 'approved':
                    $this->sendSubscriptionApproved($to);
                    break;
                case 'rejected':
                    $this->sendSubscriptionRejected($to);
                    break;
                default:
                    $this->error('Unknown type. Use one of: expiry3|upgrade_request|downgrade_request|approved|rejected');
                    return static::FAILURE;
            }
        } catch (\Throwable $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return static::FAILURE;
        }

        $this->info("Test email for '{$type}' sent to {$to}.");
        return static::SUCCESS;
    }

    private function fakeUser(): User
    {
        $user = new User();
        $user->name = 'Test User';
        $user->email = 'test.user@example.com';
        $user->role = 'buyer';
        return $user;
    }

    private function fakeSubscription(): Subscription
    {
        $subscription = new Subscription();
        $subscription->name = 'Pro Plan';
        $subscription->credits_per_month = 100;
        return $subscription;
    }

    private function sendExpiry3Days(string $to): void
    {
        $user = $this->fakeUser();
        $subscription = $this->fakeSubscription();

        $userSubscription = new UserSubscription();
        $userSubscription->setRelation('user', $user);
        $userSubscription->setRelation('subscription', $subscription);
        $userSubscription->expires_at = Carbon::now()->addDays(3);

        Mail::to($to)->send(new SubscriptionExpiringIn3DaysMail($userSubscription));
    }

    private function sendUpgradeRequestAdmin(string $to): void
    {
        $user = $this->fakeUser();
        $subscription = $this->fakeSubscription();

        $request = new SubscriptionRequest();
        $request->status = 'pending';
        $request->requested_at = Carbon::now();
        $request->setRelation('user', $user);
        $request->setRelation('subscription', $subscription);

        Mail::to($to)->send(new UpgradeRequestAdminMail($request));
    }

    private function sendDowngradeRequestAdmin(string $to): void
    {
        $user = $this->fakeUser();
        $current = $this->fakeSubscription();
        $current->name = 'Enterprise Plan';

        $request = new DowngradeRequest();
        $request->status = 'pending';
        $request->reason = 'Cost optimization';
        $request->requested_at = Carbon::now();
        $request->setRelation('user', $user);
        $request->setRelation('currentSubscription', $current);

        Mail::to($to)->send(new DowngradeRequestAdminMail($request));
    }

    private function sendSubscriptionApproved(string $to): void
    {
        $user = $this->fakeUser();
        $subscription = $this->fakeSubscription();

        $request = new SubscriptionRequest();
        $request->status = 'approved';
        $request->processed_at = Carbon::now();
        $request->admin_notes = 'Welcome aboard!';
        $request->setRelation('user', $user);
        $request->setRelation('subscription', $subscription);

        Mail::to($to)->send(new SubscriptionApprovedMail($request));
    }

    private function sendSubscriptionRejected(string $to): void
    {
        $user = $this->fakeUser();
        $subscription = $this->fakeSubscription();

        $request = new SubscriptionRequest();
        $request->status = 'declined';
        $request->processed_at = Carbon::now();
        $request->admin_notes = 'Incomplete company details';
        $request->setRelation('user', $user);
        $request->setRelation('subscription', $subscription);

        Mail::to($to)->send(new SubscriptionRejectedMail($request));
    }
}



