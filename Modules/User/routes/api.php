<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Http\Controllers\VerifyController;
use Modules\User\Http\Middleware\DecryptTokenMiddleware;

Route::middleware([])->prefix('v1')->group(function () {
//    Route::apiResource('users', UserController::class)->names('user');
    Route::post('/check-user',[VerifyController::class,'checkUser'])->name('check.user');
    Route::post('/verificatin/send-code',[VerifyController::class,'sendCode'])->name('send-code')
        ->middleware('throttle:send-code');
    Route::post('/verificatin/verify/code',[VerifyController::class,'verifyCode'])
        ->name('send-code');
    Route::post('/register',[UserController::class,'register'])->name('register')
        ->middleware('throttle:register');
    Route::post('/login',[UserController::class,'login'])->name('login')
        ->middleware('throttle:login');

});
Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('me',[UserController::class, 'me'])
        ->name('me')
        ->middleware(DecryptTokenMiddleware::class);
});

