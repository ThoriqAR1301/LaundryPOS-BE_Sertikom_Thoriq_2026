<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\CustomerWebController;
use App\Http\Controllers\Web\ServiceWebController;
use App\Http\Controllers\Web\TransactionWebController;
use App\Http\Controllers\Web\ReportWebController;

Route::get('/onboarding', function () { return view('onboarding'); })->name('onboarding');

Route::get('/', function () { if (request()->cookie('laundry_onboarding_done')) { return redirect()->route('login'); } return redirect()->route('onboarding'); });

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('onboarding');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('onboarding');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/customers', CustomerWebController::class);

    Route::resource('/services', ServiceWebController::class);

    Route::get('/transactions', [TransactionWebController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionWebController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionWebController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{id}', [TransactionWebController::class, 'show'])->name('transactions.show');
    
    Route::get('/transactions/{id}/edit', [TransactionWebController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{id}', [TransactionWebController::class, 'update'])->name('transactions.update');

    Route::put('/transactions/{id}/status', [TransactionWebController::class, 'updateStatus'])->name('transactions.update-status');
    Route::post('/transactions/{id}/payment-proof', [TransactionWebController::class, 'uploadPaymentProof'])->name('transactions.payment-proof');
    Route::delete('/transactions/{id}/destroy', [TransactionWebController::class, 'destroy'])->name('transactions.destroy');

    Route::get('/transactions/{id}/print', [TransactionWebController::class, 'printStruk'])->name('transactions.print');

    Route::get('/reports', [ReportWebController::class, 'index'])->name('reports.index');

    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
});