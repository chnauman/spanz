<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SubscriptionRequestMail;
use App\Models\SubscriptionRequest;

class SendSubscriptionRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $subscriptionRequest;
    public $type;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     */
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(SubscriptionRequest $subscriptionRequest, string $type)
    {
        $this->subscriptionRequest = $subscriptionRequest;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Load the relationship to ensure we have the user data
            $this->subscriptionRequest->load('user', 'subscription');
            
            Mail::to($this->subscriptionRequest->user->email)->send(
                new SubscriptionRequestMail($this->subscriptionRequest, $this->type)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send subscription request email to ' . $this->subscriptionRequest->user->email . ': ' . $e->getMessage());
            throw $e; // Re-throw to trigger retry mechanism
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Subscription request email job failed for user ' . $this->subscriptionRequest->user->email . ': ' . $exception->getMessage());
    }
}
