<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Socialite\ProviderController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;





Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');
Route::get('/table', function () {
    return Inertia::render('table');
})->name('table');

Route::get('/auth/{provider}/redirect', [ProviderController::class, 'providerRedirect'])->name('auth.redirect');
Route::get('/auth/{provider}/callback', [ProviderController::class, 'providerCallback'])->name('auth.callback');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    // Route::resource('posts', PostController::class);

    // Add form page before payment
    // Route::post('/pay', [ProductController::class, 'initialize'])->name('payment.initialize');
    // Route::get('/payment/callback', [ProductController::class, 'callback'])->name('payment.callback');
    Route::post('/pay', [PaymentController::class, 'initialize'])->name('payment.initialize');
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
});

Route::middleware(['auth', 'verified', 'role:admin,superadmin'])->group(function () {
    Route::resource('posts', PostController::class);
    Route::resource('products', ProductController::class);
    // Route::post('/pay', [ProductController::class, 'initialize'])->name('payment.initialize');
    // Route::get('/payment/callback', [ProductController::class, 'callback'])->name('payment.callback');
});
Route::middleware(['auth', 'verified', 'role:superadmin'])->group(function () {
    Route::resource('products', ProductController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
