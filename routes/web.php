<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\UserInterestController;
use App\Http\Controllers\TenderController;
use App\Http\Controllers\CompanyRegistrationController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProductController as PublicProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password reset routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Protected routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Company Registration Routes
Route::middleware('auth')->group(function () {
    Route::get('/company/register', [CompanyRegistrationController::class, 'show'])->name('company.register');
    Route::post('/company/register', [CompanyRegistrationController::class, 'store'])->name('company.register.store');
});

// User Interest Routes
Route::middleware('auth')->group(function () {
    Route::get('/user/interests', [UserInterestController::class, 'show'])->name('user.interests');
    Route::post('/user/interests', [UserInterestController::class, 'store'])->name('user.interests.store');
    Route::post('/user/interests/skip', [UserInterestController::class, 'skip'])->name('user.interests.skip');
    Route::get('/user/interests/management', [UserInterestController::class, 'management'])->name('user.interests.management');
    Route::delete('/user/interests/{interest}', [UserInterestController::class, 'delete'])->name('user.interests.delete');
    Route::post('/user/interests/save-budget', [UserInterestController::class, 'saveBudget'])->name('user.interests.save-budget');
});

// Tender Routes
Route::get('/tenders', [TenderController::class, 'index'])->name('tenders.index');
Route::get('/tenders/search', [TenderController::class, 'search'])->name('tenders.search');
Route::get('/tenders/{tender}/detail', [TenderController::class, 'detail'])->name('tenders.detail');
// Tender creation and management routes (all authenticated users except admin)
Route::middleware('auth')->group(function () {
    Route::get('/tenders/create', [TenderController::class, 'create'])->name('tenders.create');
    Route::post('/tenders', [TenderController::class, 'store'])->name('tenders.store');
    Route::get('/my-tenders', [TenderController::class, 'myTenders'])->name('tenders.my-tenders');
    Route::get('/tender-invitations', [TenderController::class, 'invitations'])->name('tenders.invitations');
    Route::get('/tender-invitations/{invitation}/view', [TenderController::class, 'viewInvitation'])->name('tenders.invitation.view');
});

// General authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/tenders/{tender}', [TenderController::class, 'detail'])->name('tenders.show');
    Route::get('/saved-tenders', [TenderController::class, 'savedTenders'])->name('tenders.saved');

    // Save/Unsave tender routes
    Route::post('/tenders/{tender}/save', [TenderController::class, 'saveTender'])->name('tenders.save');
    Route::delete('/tenders/{tender}/unsave', [TenderController::class, 'unsaveTender'])->name('tenders.unsave');
    Route::get('/tenders/{tender}/saved-status', [TenderController::class, 'isTenderSaved'])->name('tenders.saved-status');

    // View buyer details route
    Route::post('/tenders/{tender}/view-buyer-details', [TenderController::class, 'viewBuyerDetails'])->name('tenders.view-buyer-details');

    // Download attachment route
    Route::get('/tenders/{tender}/download/{filename}', [TenderController::class, 'downloadAttachment'])->name('tenders.download-attachment');

    // Subscription Request Routes
    Route::get('/subscriptions', [\App\Http\Controllers\SubscriptionRequestController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscription-requests/{subscription}', [\App\Http\Controllers\SubscriptionRequestController::class, 'request'])->name('subscription-requests.request');
    Route::post('/subscription-requests-by-id/{id}', [\App\Http\Controllers\SubscriptionRequestController::class, 'requestById'])->name('subscription-requests.request-by-id');
    Route::get('/subscription-requests', [\App\Http\Controllers\SubscriptionRequestController::class, 'myRequests'])->name('subscription-requests.my-requests');
    Route::delete('/subscription-requests/{request}', [\App\Http\Controllers\SubscriptionRequestController::class, 'cancelRequest'])->name('subscription-requests.cancel');
    Route::get('/subscription-requests/status', [\App\Http\Controllers\SubscriptionRequestController::class, 'checkStatus'])->name('subscription-requests.status');
    Route::get('/subscription-summary', [\App\Http\Controllers\SubscriptionRequestController::class, 'getSubscriptionSummary'])->name('subscription-summary');

    // Test route to check if routing is working
    Route::get('/test-subscription-route', function() {
        return response()->json(['message' => 'Route is working', 'timestamp' => now()]);
    });

    // Supplier-specific routes (only suppliers and sub-suppliers)
    Route::get('/viewed-tenders', [TenderController::class, 'viewedTenders'])->name('tenders.viewed');
});

// Supplier-specific routes (only suppliers can manage sub-suppliers)
Route::middleware(['auth', 'role:supplier'])->group(function () {
    Route::get('/suppliers/invite', [\App\Http\Controllers\SupplierController::class, 'showInviteForm'])->name('suppliers.invite');
    Route::post('/suppliers/invite', [\App\Http\Controllers\SupplierController::class, 'sendInvitation'])->name('suppliers.invite.send');
    Route::get('/suppliers/sub-suppliers', [\App\Http\Controllers\SupplierController::class, 'subSuppliers'])->name('suppliers.sub-suppliers');
    Route::delete('/suppliers/{subSupplier}/remove', [\App\Http\Controllers\SupplierController::class, 'removeSubSupplier'])->name('suppliers.remove-sub-supplier');

    // Sub Supplier Invitation Routes
    Route::get('/invite-sub-suppliers', [\App\Http\Controllers\SubSupplierInvitationController::class, 'search'])->name('invite.sub-suppliers');
    Route::get('/invite-sub-suppliers/search', [\App\Http\Controllers\SubSupplierInvitationController::class, 'searchSuppliers'])->name('invite.sub-suppliers.search');
    Route::post('/invite-sub-suppliers/send', [\App\Http\Controllers\SubSupplierInvitationController::class, 'sendInvitation'])->name('invite.sub-suppliers.send');
});

// Supplier and Sub-supplier routes
Route::middleware(['auth', 'role:supplier,sub_supplier'])->group(function () {
    Route::get('/invitations', [\App\Http\Controllers\SubSupplierInvitationController::class, 'index'])->name('invitations.index');
    Route::post('/invitations/{invitation}/accept', [\App\Http\Controllers\SubSupplierInvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/{invitation}/decline', [\App\Http\Controllers\SubSupplierInvitationController::class, 'decline'])->name('invitations.decline');
    Route::delete('/invitations/{invitation}/cancel', [\App\Http\Controllers\SubSupplierInvitationController::class, 'cancel'])->name('invitations.cancel');
});

// Admin Category Management Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', AdminProductController::class)->scoped([
        'product' => 'id'
    ]);

    // User Management Routes
    Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::post('users/{user}/approve', [\App\Http\Controllers\Admin\UserController::class, 'approve'])->name('users.approve');
    Route::post('users/{user}/reject', [\App\Http\Controllers\Admin\UserController::class, 'reject'])->name('users.reject');
    Route::delete('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Subscription Management Routes
    Route::get('subscription-requests', [\App\Http\Controllers\Admin\SubscriptionController::class, 'requests'])->name('subscription-requests');
    Route::post('subscriptions/approve-request/{request}', [\App\Http\Controllers\Admin\SubscriptionController::class, 'approveRequest'])->name('subscriptions.approve-request');
    Route::post('subscriptions/decline-request/{request}', [\App\Http\Controllers\Admin\SubscriptionController::class, 'declineRequest'])->name('subscriptions.decline-request');
    Route::get('subscriptions/requests/{request}', [\App\Http\Controllers\Admin\SubscriptionController::class, 'showRequest'])->name('subscriptions.show-request');
    Route::resource('subscriptions', \App\Http\Controllers\Admin\SubscriptionController::class);

    // Downgrade Request Management
    Route::get('downgrade-requests', [\App\Http\Controllers\Admin\DowngradeRequestController::class, 'index'])->name('downgrade-requests.index');
    Route::get('downgrade-requests/{downgradeRequest}', [\App\Http\Controllers\Admin\DowngradeRequestController::class, 'show'])->name('downgrade-requests.show');
    Route::post('downgrade-requests/{downgradeRequest}/approve', [\App\Http\Controllers\Admin\DowngradeRequestController::class, 'approve'])->name('downgrade-requests.approve');
    Route::post('downgrade-requests/{downgradeRequest}/decline', [\App\Http\Controllers\Admin\DowngradeRequestController::class, 'decline'])->name('downgrade-requests.decline');
});

// Profile update (name and photo)
Route::middleware(['auth'])->group(function () {
    Route::post('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// Account Settings Routes
Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\AccountController::class, 'profile'])->name('profile');
    Route::get('/plan', [\App\Http\Controllers\AccountController::class, 'plan'])->name('plan');
    Route::get('/credits', [\App\Http\Controllers\AccountController::class, 'credits'])->name('credits');
});

// Downgrade Request Routes
Route::middleware(['auth'])->prefix('downgrade-requests')->name('downgrade-requests.')->group(function () {
    Route::get('/create', [\App\Http\Controllers\DowngradeRequestController::class, 'create'])->name('create');
    Route::post('/store', [\App\Http\Controllers\DowngradeRequestController::class, 'store'])->name('store');
    Route::get('/my-requests', [\App\Http\Controllers\DowngradeRequestController::class, 'myRequests'])->name('my-requests');
    Route::delete('/cancel/{downgradeRequest}', [\App\Http\Controllers\DowngradeRequestController::class, 'cancel'])->name('cancel');
    Route::get('/status', [\App\Http\Controllers\DowngradeRequestController::class, 'getStatus'])->name('status');
});

Route::get('/pages/product', function () {
    return view('pages.product');
});
Route::get('/pages/home', function () {
    return view('pages.home');
});
Route::get('/pages/dashboard', function () {
    return view('pages.dashboard');
});

// Test routes to demonstrate role-based access control
Route::get('/test/admin-only', function () {
    return 'This is an admin-only page. You have access!';
})->middleware(['auth', 'role:admin']);

Route::get('/test/buyer-only', function () {
    return 'This is a buyer-only page. You have access!';
})->middleware(['auth', 'role:buyer']);

Route::get('/test/supplier-only', function () {
    return 'This is a supplier-only page. You have access!';
})->middleware(['auth', 'role:supplier']);

Route::get('/test/supplier-or-sub-supplier', function () {
    return 'This page is accessible to suppliers and sub-suppliers. You have access!';
})->middleware(['auth', 'role:supplier,sub_supplier']);

// Public Products
Route::get('/products', [PublicProductController::class, 'search'])->name('products.index');
Route::get('/products/search', [PublicProductController::class, 'search'])->name('products.search');
Route::get('/products/{product}', [PublicProductController::class, 'show'])->name('products.show');

// Purchase Request Routes (Public access)
Route::post('/purchase-requests', [\App\Http\Controllers\PurchaseRequestController::class, 'store'])->name('purchase-requests.store');

// Admin Purchase Request Management Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('purchase-requests', [\App\Http\Controllers\PurchaseRequestController::class, 'index'])->name('purchase-requests.index');
    Route::get('purchase-requests/{purchaseRequest}', [\App\Http\Controllers\PurchaseRequestController::class, 'show'])->name('purchase-requests.show');
    Route::put('purchase-requests/{purchaseRequest}', [\App\Http\Controllers\PurchaseRequestController::class, 'update'])->name('purchase-requests.update');
    Route::delete('purchase-requests/{purchaseRequest}', [\App\Http\Controllers\PurchaseRequestController::class, 'destroy'])->name('purchase-requests.destroy');
});

// Test storage route (remove in production)
Route::get('/test-storage', function () {
    try {
        $testContent = 'Test file content';
        $testPath = 'test-file.txt';

        // Test writing to storage
        \Storage::disk('public')->put($testPath, $testContent);

        // Test reading from storage
        $content = \Storage::disk('public')->get($testPath);

        // Clean up
        \Storage::disk('public')->delete($testPath);

        return response()->json([
            'success' => true,
            'message' => 'Storage test successful',
            'content' => $content,
            'storage_path' => storage_path('app/public'),
            'public_path' => public_path('storage')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'storage_path' => storage_path('app/public'),
            'public_path' => public_path('storage')
        ], 500);
    }
});

// Test file upload route (remove in production)
Route::post('/test-upload', function (\Illuminate\Http\Request $request) {
    try {
        if (!$request->hasFile('photo')) {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }

        $file = $request->file('photo');
        $fileName = 'test-' . time() . '.' . $file->getClientOriginalExtension();
        $targetDirectory = storage_path('app/public/profile-photos');

        // Ensure directory exists
        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        // Move file
        $moved = $file->move($targetDirectory, $fileName);

        if ($moved) {
            // Clean up test file
            unlink($targetDirectory . '/' . $fileName);
            return response()->json(['success' => true, 'message' => 'File upload test successful']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to move file'], 500);
        }
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
});

// Test upload form (remove in production)
Route::get('/test-upload-form', function () {
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Test File Upload</title>
    </head>
    <body>
        <h1>Test File Upload</h1>
        <form action="/test-upload" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="' . csrf_token() . '">
            <input type="file" name="photo" accept="image/*" required>
            <button type="submit">Test Upload</button>
        </form>
    </body>
    </html>';
});

// Test email route (remove in production)
Route::get('/test-email', function () {
    try {
        $supplier = new \App\Models\User();
        $supplier->name = 'Test Supplier';
        $supplier->email = 'supplier@test.com';

        // Create a test invitation
        $invitation = \App\Models\SupplierInvitation::createInvitation(
            $supplier->id,
            'test@example.com',
            'Test Sub Supplier',
            'Test invitation email'
        );

        \Illuminate\Support\Facades\Mail::to('test@example.com')->send(new \App\Mail\SupplierInvitationMail($supplier, $invitation));

        return response()->json([
            'success' => true,
            'message' => 'Test email sent successfully! Check your Mailtrap inbox.',
            'invitation_token' => $invitation->token,
            'registration_url' => route('register', ['token' => $invitation->token]),
            'config' => [
                'mail_driver' => config('mail.default'),
                'smtp_host' => config('mail.mailers.smtp.host'),
                'smtp_port' => config('mail.mailers.smtp.port'),
                'smtp_username' => config('mail.mailers.smtp.username'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name')
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'config' => [
                'mail_driver' => config('mail.default'),
                'smtp_host' => config('mail.mailers.smtp.host'),
                'smtp_port' => config('mail.mailers.smtp.port'),
                'smtp_username' => config('mail.mailers.smtp.username'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name')
            ]
        ], 500);
    }
});

