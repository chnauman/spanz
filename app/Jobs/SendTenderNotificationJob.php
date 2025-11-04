<?php

namespace App\Jobs;

use App\Mail\TenderNotificationMail;
use App\Models\Tender;
use App\Models\User;
use App\Models\UserInterest;
use App\Models\UserBudgetRange;
use App\Services\CurrencyConversionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendTenderNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tender;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     */
    public $timeout = 300; // 5 minutes for processing multiple users

    /**
     * Create a new job instance.
     */
    public function __construct(Tender $tender)
    {
        $this->tender = $tender;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Reload the tender with category relationship (in case it was serialized)
            $this->tender->load('category');
            
            // Get all users with interests that match this tender
            $matchingUsers = $this->getMatchingUsers();

            $sentCount = 0;
            $failedCount = 0;

            foreach ($matchingUsers as $userData) {
                $user = $userData['user'];
                $matchReason = $userData['reason'];

                // Skip the tender creator
                if ($user->id === $this->tender->user_id) {
                    continue;
                }

                try {
                    // Send email notification (TenderNotificationMail implements ShouldQueue, so it will be queued)
                    Mail::to($user->email)->send(new TenderNotificationMail($this->tender, $user, $matchReason));
                    $sentCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                    Log::error('Failed to queue tender notification email', [
                        'tender_id' => $this->tender->id,
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'error' => $e->getMessage()
                    ]);
                    // Continue processing other users even if one fails
                }
            }

            Log::info('Tender notification job completed', [
                'tender_id' => $this->tender->id,
                'total_matching_users' => count($matchingUsers),
                'emails_queued' => $sentCount,
                'failed' => $failedCount
            ]);

        } catch (\Exception $e) {
            Log::error('Tender notification job failed', [
                'tender_id' => $this->tender->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e; // Re-throw to trigger retry mechanism
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Tender notification job permanently failed', [
            'tender_id' => $this->tender->id ?? null,
            'error' => $exception->getMessage()
        ]);
    }

    /**
     * Get users whose interests match this tender
     * Both category AND budget range must match to send notification
     */
    private function getMatchingUsers(): array
    {
        $matchingUsers = [];

        // Find users interested in the tender's category
        $categoryInterests = UserInterest::where('category_id', $this->tender->category_id)
            ->with('user')
            ->get();

        foreach ($categoryInterests as $interest) {
            $user = $interest->user;

            // Get all budget ranges for this user and category
            $budgetRanges = UserBudgetRange::where('user_id', $user->id)
                ->where('category_id', $this->tender->category_id)
                ->get();

            // REQUIRE BOTH category AND budget range to match
            // If no budget ranges exist, skip this user (don't send notification)
            if ($budgetRanges->isEmpty()) {
                continue;
            }

            // Check if any budget range matches
            $budgetMatchFound = false;
            $budgetReasons = [];

            foreach ($budgetRanges as $budgetRange) {
                if ($this->checkBudgetMatch($budgetRange)) {
                    $budgetMatchFound = true;
                    $budgetReasons[] = $this->getBudgetMatchReason($budgetRange);
                }
            }

            // Only add to matching users if BOTH category AND budget match
            if ($budgetMatchFound) {
                $categoryName = $this->tender->category ? $this->tender->category->name : 'Unknown Category';
                $reason = "Category: " . $categoryName;
                if (!empty($budgetReasons)) {
                    $reason .= " | " . implode(', ', $budgetReasons);
                }

                $matchingUsers[] = [
                    'user' => $user,
                    'reason' => $reason
                ];
            }
        }

        return $matchingUsers;
    }

    /**
     * Check if a budget range matches the tender budget
     */
    private function checkBudgetMatch(UserBudgetRange $budgetRange): bool
    {
        $tenderBudget = $this->tender->budget;
        $tenderCurrency = $this->tender->currency ?? 'USD';

        // If tender has no budget or budget is 0, don't match
        if (!$tenderBudget || $tenderBudget <= 0) {
            return false;
        }

        // Ensure currency is set, default to USD
        if (!$tenderCurrency || !CurrencyConversionService::isSupported($tenderCurrency)) {
            $tenderCurrency = 'USD';
        }

        // Convert tender budget to budget range currency for comparison
        // Since both are USD, conversion will return same value
        $convertedTenderBudget = CurrencyConversionService::convert(
            $tenderBudget,
            $tenderCurrency,
            $budgetRange->currency ?? 'USD'
        );

        switch ($budgetRange->budget_type) {
            case 'less':
                // User wants tenders less than their max budget
                // Example: If max_budget is 1000, tender budget 500 should match (< 1000)
                if (!$budgetRange->max_budget) {
                    return false;
                }
                return $convertedTenderBudget < $budgetRange->max_budget;

            case 'greater':
                // User wants tenders greater than their min budget
                // Example: If min_budget is 1000, tender budget 2000 should match (> 1000)
                if (!$budgetRange->min_budget) {
                    return false;
                }
                return $convertedTenderBudget > $budgetRange->min_budget;

            case 'range':
                // User wants tenders within their budget range
                // Example: If range is 222-466789, tender budget 500 should match (>= 222 AND <= 466789)
                $minMatch = $budgetRange->min_budget ? $convertedTenderBudget >= $budgetRange->min_budget : true;
                $maxMatch = $budgetRange->max_budget ? $convertedTenderBudget <= $budgetRange->max_budget : true;
                return $minMatch && $maxMatch;

            default:
                return false;
        }
    }

    /**
     * Get the reason why a budget range matched
     */
    private function getBudgetMatchReason(UserBudgetRange $budgetRange): string
    {
        $tenderBudget = $this->tender->budget;
        $tenderCurrency = $this->tender->currency ?? 'USD';
        $budgetRangeCurrency = $budgetRange->currency ?? 'USD';

        // Ensure currency is supported
        if (!CurrencyConversionService::isSupported($tenderCurrency)) {
            $tenderCurrency = 'USD';
        }
        if (!CurrencyConversionService::isSupported($budgetRangeCurrency)) {
            $budgetRangeCurrency = 'USD';
        }

        // Convert tender budget to budget range currency for display
        $convertedTenderBudget = CurrencyConversionService::convert(
            $tenderBudget,
            $tenderCurrency,
            $budgetRangeCurrency
        );

        switch ($budgetRange->budget_type) {
            case 'less':
                return sprintf(
                    "Budget %.2f %s matches your 'less than %.2f %s' preference",
                    $convertedTenderBudget,
                    $budgetRangeCurrency,
                    $budgetRange->max_budget,
                    $budgetRangeCurrency
                );

            case 'greater':
                return sprintf(
                    "Budget %.2f %s matches your 'greater than %.2f %s' preference",
                    $convertedTenderBudget,
                    $budgetRangeCurrency,
                    $budgetRange->min_budget,
                    $budgetRangeCurrency
                );

            case 'range':
                $minStr = $budgetRange->min_budget ? number_format($budgetRange->min_budget, 2) : '0';
                $maxStr = $budgetRange->max_budget ? number_format($budgetRange->max_budget, 2) : '∞';
                return sprintf(
                    "Budget %.2f %s matches your range %.2f - %.2f %s",
                    $convertedTenderBudget,
                    $budgetRangeCurrency,
                    $budgetRange->min_budget,
                    $budgetRange->max_budget,
                    $budgetRangeCurrency
                );

            default:
                return "Budget matches your preferences";
        }
    }
}
