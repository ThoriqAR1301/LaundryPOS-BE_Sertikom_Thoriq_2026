<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\ReportController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    Route::apiResource('/services', ServiceController::class);

    Route::apiResource('/customers', CustomerController::class);

    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    Route::put('/transactions/{id}/status', [TransactionController::class, 'updateStatus']);
    Route::post('/transactions/{id}/payment-proof', [TransactionController::class, 'uploadPaymentProof']);

    Route::get('/status-laundry', [TransactionController::class, 'statusLaundry']);

    Route::prefix('reports')->group(function () {
        Route::get('/dashboard', [ReportController::class, 'dashboard']);
        Route::get('/pendapatan-bulanan', [ReportController::class, 'pendapatanPerBulan']);
        Route::get('/transaksi-harian', [ReportController::class, 'transaksiPerHari']);
        Route::get('/layanan-populer', [ReportController::class, 'layananPopuler']);
        Route::get('/riwayat', [ReportController::class, 'riwayat']);
    });

});