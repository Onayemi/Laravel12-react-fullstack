<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    // Route::resource('posts', PostController::class);
});

Route::middleware(['auth', 'verified', 'role:admin,superadmin'])->group(function () {
    Route::resource('posts', PostController::class);
    Route::resource('products', ProductController::class);
});
Route::middleware(['auth', 'verified', 'role:superadmin'])->group(function () {
    Route::resource('products', ProductController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
