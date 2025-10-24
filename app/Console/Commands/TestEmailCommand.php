<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SupplierInvitationMail;
use App\Models\User;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test invitation email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info('Testing email configuration...');
        $this->info('Mail Driver: ' . config('mail.default'));
        $this->info('SMTP Host: ' . config('mail.mailers.smtp.host'));
        $this->info('SMTP Port: ' . config('mail.mailers.smtp.port'));
        $this->info('SMTP Username: ' . config('mail.mailers.smtp.username'));
        $this->info('From Address: ' . config('mail.from.address'));
        $this->info('From Name: ' . config('mail.from.name'));

        try {
            // Create test users
            $supplier = new User();
            $supplier->name = 'Test Supplier';
            $supplier->email = 'supplier@test.com';

            $subSupplier = new User();
            $subSupplier->name = 'Test Sub Supplier';
            $subSupplier->email = $email;

            // Send test email
            Mail::to($email)->send(new SupplierInvitationMail($supplier, $subSupplier, 'This is a test invitation email to verify Mailtrap configuration.'));

            $this->info('✅ Email sent successfully!');
            $this->info('Check your Mailtrap inbox for the test email.');

        } catch (\Exception $e) {
            $this->error('❌ Email sending failed:');
            $this->error($e->getMessage());
            $this->error('Please check your .env file and ensure Mailtrap credentials are correct.');
        }
    }
}
