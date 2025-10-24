<?php

/**
 * Test Email Configuration for SPANZ
 * This script tests if the Mailtrap configuration is working properly
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Mail;
use App\Mail\SupplierInvitationMail;
use App\Models\User;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Create test users
    $supplier = new User();
    $supplier->name = 'Test Supplier';
    $supplier->email = 'supplier@test.com';

    $subSupplier = new User();
    $subSupplier->name = 'Test Sub Supplier';
    $subSupplier->email = 'subsupplier@test.com';

    // Test email sending
    echo "Testing email configuration...\n";
    echo "Mail Driver: " . config('mail.default') . "\n";
    echo "SMTP Host: " . config('mail.mailers.smtp.host') . "\n";
    echo "SMTP Port: " . config('mail.mailers.smtp.port') . "\n";
    echo "SMTP Username: " . config('mail.mailers.smtp.username') . "\n";
    echo "From Address: " . config('mail.from.address') . "\n";
    echo "From Name: " . config('mail.from.name') . "\n";

    // Send test email
    Mail::to('test@example.com')->send(new SupplierInvitationMail($supplier, $subSupplier, 'Test message'));

    echo "\n✅ Email configuration is working!\n";
    echo "Check your Mailtrap inbox for the test email.\n";

} catch (Exception $e) {
    echo "\n❌ Email configuration error:\n";
    echo $e->getMessage() . "\n";
    echo "\nPlease check your .env file and ensure Mailtrap credentials are correct.\n";
}
