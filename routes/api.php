<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TwitterAuthController;

Route::middleware('web')->group(function () {
    Route::post('/send-otp', [TwitterAuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [TwitterAuthController::class, 'verifyOtp']);
});


