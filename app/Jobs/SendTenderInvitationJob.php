<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\TenderInvitationMail;
use App\Models\Tender;
use App\Models\User;

class SendTenderInvitationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tender;
    public $user;

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
    public function __construct(Tender $tender, User $user)
    {
        $this->tender = $tender;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->user->email)->send(new TenderInvitationMail($this->tender, $this->user));
        } catch (\Exception $e) {
            Log::error('Failed to send tender invitation email to ' . $this->user->email . ': ' . $e->getMessage());
            throw $e; // Re-throw to trigger retry mechanism
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Tender invitation email job failed for user ' . $this->user->email . ': ' . $exception->getMessage());
    }
}
