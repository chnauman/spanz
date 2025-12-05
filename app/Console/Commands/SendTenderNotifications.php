<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Tender;
use App\Models\UserInterest;
use App\Models\UserBudgetRange;
use App\Mail\TenderNotificationMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendTenderNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenders:send-notifications {frequency?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send tender notifications to users based on their notification frequency preference';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $frequency = $this->argument('frequency') ?? null;

        if ($frequency && !in_array($frequency, ['daily', 'weekly', 'monthly'])) {
            $this->error('Invalid frequency. Must be: daily, weekly, or monthly');
            return 1;
        }

        $frequencies = $frequency ? [$frequency] : ['daily', 'weekly', 'monthly'];

        foreach ($frequencies as $freq) {
            $this->info("Processing {$freq} notifications...");
            $this->processFrequency($freq);
        }

        $this->info('Tender notifications sent successfully!');
        return 0;
    }

    /**
     * Process notifications for a specific frequency
     */
    private function processFrequency($frequency)
    {
        // Get users with this notification frequency and interests set
        $users = User::where('notification_frequency', $frequency)
            ->where('interests_set', true)
            ->whereNotNull('email_verified_at')
            ->with(['interests.category', 'budgetRanges'])
            ->get();

        $this->info("Found {$users->count()} users with {$frequency} notifications enabled");

        // Calculate date range based on frequency
        $dateRange = $this->getDateRange($frequency);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        $sentCount = 0;
        $skippedCount = 0;

        foreach ($users as $user) {
            // Get matching tenders for this user
            $matchingTenders = $this->getMatchingTenders($user, $startDate, $endDate);

            if ($matchingTenders->count() > 0) {
                try {
                    Mail::to($user->email)->send(
                        new TenderNotificationMail(
                            $matchingTenders,
                            $user->name,
                            $frequency,
                            $this->getPeriodDescription($frequency, $startDate, $endDate)
                        )
                    );
                    $sentCount++;
                    $this->line("  ✓ Sent notification to {$user->email} ({$matchingTenders->count()} tenders)");
                } catch (\Exception $e) {
                    $this->error("  ✗ Failed to send to {$user->email}: " . $e->getMessage());
                }
            } else {
                $skippedCount++;
                // Skip sending if no tenders (as per user requirement)
            }
        }

        $this->info("  Sent: {$sentCount}, Skipped: {$skippedCount} (no matching tenders)");
    }

    /**
     * Get date range based on frequency
     */
    private function getDateRange($frequency)
    {
        $now = Carbon::now();

        switch ($frequency) {
            case 'daily':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                ];
            case 'weekly':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek(),
                ];
            case 'monthly':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                ];
            default:
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                ];
        }
    }

    /**
     * Get matching tenders for a user based on interests and budget ranges
     */
    private function getMatchingTenders(User $user, $startDate, $endDate)
    {
        // Get user's interest category IDs
        $categoryIds = $user->interests->pluck('category_id')->toArray();

        if (empty($categoryIds)) {
            return collect([]);
        }

        // Get tenders posted in the date range matching user's categories
        $tenders = Tender::whereIn('category_id', $categoryIds)
            ->where('status', 'active')
            ->where('deadline', '>', now())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('user_id', '!=', $user->id) // Exclude tenders created by the user
            ->with('category')
            ->get();

        // Filter by budget ranges if user has budget preferences
        $budgetRanges = $user->budgetRanges;
        
        if ($budgetRanges->count() > 0) {
            $tenders = $tenders->filter(function ($tender) use ($budgetRanges, $user) {
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

        return $tenders;
    }

    /**
     * Check if tender budget matches user's budget range
     */
    private function matchesBudgetRange(Tender $tender, UserBudgetRange $budgetRange)
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

    /**
     * Get period description for email
     */
    private function getPeriodDescription($frequency, $startDate, $endDate)
    {
        switch ($frequency) {
            case 'daily':
                return $startDate->format('F j, Y');
            case 'weekly':
                return $startDate->format('F j') . ' - ' . $endDate->format('F j, Y');
            case 'monthly':
                return $startDate->format('F Y');
            default:
                return '';
        }
    }
}

