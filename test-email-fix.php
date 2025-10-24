<?php

/**
 * Test Email Fix for SPANZ
 * This script tests if the htmlspecialchars error is fixed
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
    echo "Testing email fix...\n";

    // Create test users
    $supplier = new User();
    $supplier->name = 'Test Supplier';
    $supplier->email = 'supplier@test.com';

    $subSupplier = new User();
    $subSupplier->name = 'Test Sub Supplier';
    $subSupplier->email = 'test@example.com';

    // Test email sending with message
    echo "Sending test email with message...\n";
    Mail::to('test@example.com')->send(new SupplierInvitationMail($supplier, $subSupplier, 'This is a test message to verify the fix.'));

    echo "✅ Email sent successfully!\n";
    echo "The htmlspecialchars error should be fixed now.\n";
    echo "Check your Mailtrap inbox for the test email.\n";

} catch (Exception $e) {
    echo "❌ Error occurred:\n";
    echo $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
