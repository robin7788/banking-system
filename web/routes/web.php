<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\UserRequestController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'twofactor', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
    Route::resource('verify', TwoFactorController::class)->only(['index', 'store']);
});

Route::middleware(['auth', 'twofactor'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        // Routes for user
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::get('/user/{user}/transaction', [UserController::class, 'transaction'])->name('user.transaction');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::get('/user/{user}', [UserController::class, 'edit'])->name('user.edit');
        Route::patch('/user/{user}', [UserController::class, 'update'])->name('user.update');
    });



    // Routes for general users
    Route::get('/transactions', [UserRequestController::class, 'transactions'])->name('user.transactions');
    Route::get('fund/transfer', [UserRequestController::class, 'fund_transfer'])->name('user.fund.transfer');
    Route::post('fund/transfer/detail', [UserRequestController::class, 'get_fund_detail'])->name('user.fund.transfer.detail');
    Route::post('fund/transfer/confirm', [UserRequestController::class, 'get_fund_confirm'])->name('user.fund.transfer.confirm');
        
   
   
});

require __DIR__.'/auth.php';
