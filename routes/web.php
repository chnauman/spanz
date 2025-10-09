<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\UserInterestController;
use App\Http\Controllers\TenderController;
use App\Http\Controllers\CompanyRegistrationController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password reset routes
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

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
});

// Tender Routes
Route::get('/tenders', [TenderController::class, 'index'])->name('tenders.index');
Route::get('/tenders/search', [TenderController::class, 'search'])->name('tenders.search');
Route::get('/tenders/{tender}/detail', [TenderController::class, 'detail'])->name('tenders.detail');
Route::middleware('auth')->group(function () {
    Route::get('/tenders/create', [TenderController::class, 'create'])->name('tenders.create');
    Route::post('/tenders', [TenderController::class, 'store'])->name('tenders.store');
    Route::get('/tenders/{tender}', [TenderController::class, 'show'])->name('tenders.show');
    Route::get('/my-tenders', [TenderController::class, 'myTenders'])->name('tenders.my-tenders');
    Route::get('/tender-invitations', [TenderController::class, 'invitations'])->name('tenders.invitations');
    Route::get('/tender-invitations/{invitation}/view', [TenderController::class, 'viewInvitation'])->name('tenders.invitation.view');
    
    // Save/Unsave tender routes
    Route::post('/tenders/{tender}/save', [TenderController::class, 'saveTender'])->name('tenders.save');
    Route::delete('/tenders/{tender}/unsave', [TenderController::class, 'unsaveTender'])->name('tenders.unsave');
    Route::get('/tenders/{tender}/saved-status', [TenderController::class, 'isTenderSaved'])->name('tenders.saved-status');
});

// Admin Category Management Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
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
