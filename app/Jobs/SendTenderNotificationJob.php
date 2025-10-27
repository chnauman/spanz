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

class SendTenderNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tender;

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
        // Get all users with interests that match this tender
        $matchingUsers = $this->getMatchingUsers();

        foreach ($matchingUsers as $userData) {
            $user = $userData['user'];
            $matchReason = $userData['reason'];

            // Skip the tender creator
            if ($user->id === $this->tender->user_id) {
                continue;
            }

            // Send email notification
            Mail::to($user->email)->send(new TenderNotificationMail($this->tender, $user, $matchReason));
        }
    }

    /**
     * Get users whose interests match this tender
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

            // If no budget ranges, send notification for category match only
            if ($budgetRanges->isEmpty()) {
                $matchingUsers[] = [
                    'user' => $user,
                    'reason' => "Category: " . $this->tender->category->name
                ];
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

            // If any budget range matches, add to matching users
            if ($budgetMatchFound) {
                $reason = "Category: " . $this->tender->category->name;
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
        $tenderCurrency = $this->tender->currency;

        // If tender has no budget, don't match
        if (!$tenderBudget) {
            return false;
        }

        // Convert tender budget to budget range currency for comparison
        $convertedTenderBudget = CurrencyConversionService::convert(
            $tenderBudget,
            $tenderCurrency,
            $budgetRange->currency
        );

        switch ($budgetRange->budget_type) {
            case 'less':
                // User wants tenders less than their max budget
                return $convertedTenderBudget < $budgetRange->max_budget;

            case 'greater':
                // User wants tenders greater than their min budget
                return $convertedTenderBudget > $budgetRange->min_budget;

            case 'range':
                // User wants tenders within their budget range
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
        $tenderCurrency = $this->tender->currency;

        // Convert tender budget to budget range currency for display
        $convertedTenderBudget = CurrencyConversionService::convert(
            $tenderBudget,
            $tenderCurrency,
            $budgetRange->currency
        );

        switch ($budgetRange->budget_type) {
            case 'less':
                return sprintf(
                    "Budget %.2f %s matches your 'less than %.2f %s' preference",
                    $convertedTenderBudget,
                    $budgetRange->currency,
                    $budgetRange->max_budget,
                    $budgetRange->currency
                );

            case 'greater':
                return sprintf(
                    "Budget %.2f %s matches your 'greater than %.2f %s' preference",
                    $convertedTenderBudget,
                    $budgetRange->currency,
                    $budgetRange->min_budget,
                    $budgetRange->currency
                );

            case 'range':
                $minStr = $budgetRange->min_budget ? number_format($budgetRange->min_budget, 2) : '0';
                $maxStr = $budgetRange->max_budget ? number_format($budgetRange->max_budget, 2) : '∞';
                return sprintf(
                    "Budget %.2f %s matches your range %.2f - %.2f %s",
                    $convertedTenderBudget,
                    $budgetRange->currency,
                    $budgetRange->min_budget,
                    $budgetRange->max_budget,
                    $budgetRange->currency
                );

            default:
                return "Budget matches your preferences";
        }
    }
}
