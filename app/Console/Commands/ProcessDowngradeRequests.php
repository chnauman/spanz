<?php

namespace App\Console\Commands;

use App\Models\DowngradeRequest;
use App\Models\UserSubscription;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessDowngradeRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'downgrade:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process approved downgrade requests and make users free after subscription expires';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing downgrade requests...');

        // Get all approved downgrade requests
        $approvedRequests = DowngradeRequest::where('status', 'approved')
            ->with(['user', 'currentSubscription'])
            ->get();

        $processedCount = 0;

        foreach ($approvedRequests as $request) {
            $user = $request->user;
            $activeSubscription = $user->getActiveSubscription();

            // Check if the user's subscription has expired
            if ($activeSubscription && $activeSubscription->expires_at <= now()) {
                // Mark the subscription as inactive
                $activeSubscription->update(['is_active' => false]);

                // Log the downgrade completion
                Log::info("User {$user->email} has been downgraded to free plan after subscription expiration", [
                    'user_id' => $user->id,
                    'subscription_id' => $activeSubscription->subscription_id,
                    'expired_at' => $activeSubscription->expires_at
                ]);

                $processedCount++;
                $this->line("Processed downgrade for user: {$user->email}");
            }
        }

        $this->info("Processed {$processedCount} downgrade requests.");

        return Command::SUCCESS;
    }
}
