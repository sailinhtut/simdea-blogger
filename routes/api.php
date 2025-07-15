<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;



Route::controller(PostController::class)->group(function () {
    Route::get('/posts', 'index');
    Route::get('/posts/{slug}', 'show');
    Route::post('/posts', 'store')->middleware('auth:sanctum');
    Route::post('/posts/{slug}', 'update')->middleware('auth:sanctum');
    Route::delete('/posts/{slug}', 'destroy')->middleware('auth:sanctum');
});



// With Email Verified Middleware
// Route::middleware(['auth:sanctum', 'verified'])->group(function () {
//     Route::controller(PostController::class)->group(function () {
//         Route::get('/posts', 'index');
//         Route::get('/posts/{slug}', 'show');
//         Route::post('/posts', 'store');
//         Route::post('/posts/{slug}', 'update');
//         Route::delete('/posts/{slug}', 'destroy');
//     });
// });

Route::controller(AuthController::class)->group(function () {
    Route::post('/sign-up', 'signUp');
    Route::post('/sign-in',  'signIn');
    Route::get('/sign-out', 'signOut')->middleware('auth:sanctum');
    Route::post('/send-reset-password-email', 'sendResetPasswordEmail');

    Route::post('/reset-password', 'resetPassword');

    Route::post('/user/send-verify-email', 'sendVerificationEmail')->middleware('auth:sanctum');
    Route::post('/user/update-email', 'updateEmail')->middleware('auth:sanctum');
    Route::post('/user/update-password', 'updatePassword')->middleware('auth:sanctum');
    Route::get('/user/{id}', 'getUser')->middleware('auth:sanctum');
    Route::post('/user/{id}', 'updateUser')->middleware('auth:sanctum');
    Route::delete('/user/{id}', 'deleteUser')->middleware('auth:sanctum');

    Route::get('/user/verify-email/{id}', 'verifyEmail')
        ->name('verification.verify');
});