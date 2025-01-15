<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;


Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/employee', [AdminController::class, 'addEmployee']);
    Route::put('/customer/{customer}', [AdminController::class, 'assignCustomerToEmployee']);
    Route::prefix('admin')->group(function () {
        Route::resource('customers', CustomerController::class);
        Route::resource('actions', ActionController::class);
    });
});

Route::middleware(['auth:sanctum', 'role:employee'])->group(function () {
    Route::resource('customers', CustomerController::class)->only(['store']);
    Route::resource('actions', ActionController::class)->only(['store', 'update']);
});
