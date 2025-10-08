<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;

// Public routes
Route::get('/', [HomeController::class, 'index']);

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

Route::get('/pages/tenders', function () {
    return view('pages.tenders');
});
Route::get('/pages/home', function () {
    return view('pages.home');
});
Route::get('/pages/activetenders', function () {
    return view('pages.activetenders');
});
Route::get('/pages/tenderposting', function () {
    return view('pages.tenderposting');
});
Route::get('/pages/becomesupplier', function () {
    return view('pages.becomesupplier');
});
Route::get('/pages/pricing', function () {
    return view('pages.pricing');
});
