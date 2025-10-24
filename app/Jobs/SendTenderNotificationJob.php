<?php

namespace App\Jobs;

use App\Mail\TenderNotificationMail;
use App\Models\Tender;
use App\Models\User;
use App\Models\UserInterest;
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

            // Check if budget matches (if budget range is set)
            $budgetMatches = true;
            $budgetReason = '';

            if ($interest->min_budget || $interest->max_budget) {
                $tenderBudget = $this->tender->budget;
                if ($tenderBudget) {
                    $budgetMatches = false;
                    if ($interest->min_budget && $interest->max_budget) {
                        // Range check
                        if ($tenderBudget >= $interest->min_budget && $tenderBudget <= $interest->max_budget) {
                            $budgetMatches = true;
                            $budgetReason = "Budget matches your range: " . number_format($interest->min_budget, 2) . " - " . number_format($interest->max_budget, 2) . " " . ($interest->currency ?? 'USD');
                        }
                    } elseif ($interest->min_budget) {
                        // Minimum budget check
                        if ($tenderBudget >= $interest->min_budget) {
                            $budgetMatches = true;
                            $budgetReason = "Budget meets your minimum: " . number_format($interest->min_budget, 2) . " " . ($interest->currency ?? 'USD');
                        }
                    } elseif ($interest->max_budget) {
                        // Maximum budget check
                        if ($tenderBudget <= $interest->max_budget) {
                            $budgetMatches = true;
                            $budgetReason = "Budget within your maximum: " . number_format($interest->max_budget, 2) . " " . ($interest->currency ?? 'USD');
                        }
                    }
                }
            }

            if ($budgetMatches) {
                $reason = "Category: " . $this->tender->category->name;
                if ($budgetReason) {
                    $reason .= " | " . $budgetReason;
                }

                $matchingUsers[] = [
                    'user' => $user,
                    'reason' => $reason
                ];
            }
        }

        return $matchingUsers;
    }
}
