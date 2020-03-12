<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Ok'; // There needs to be a status route configured for Kubernetes health checks.
});

Route::post('/register')->uses('Auth\RegisterController@register')->name('auth.register');
Route::post('/login')->uses('Auth\LoginController@login')->name('auth.login');
Route::get('/logout')->uses('Auth\LoginController@logout')->name('auth.logout')
    ->middleware('auth');

Route::get('/email/verify/{id}/{hash}')->uses('Auth\VerificationController@verify')->name('verification.verify')
    ->middleware(['signed', 'throttle:6,1']);
Route::post('/email/resend')->uses('Auth\VerificationController@resend')->name('verification.resend')
    ->middleware(['auth', 'throttle:6,1']);

Route::post('/password/email')->uses('Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email')
    ->middleware(['guest', 'throttle:6,1']);
Route::post('/password/reset')->uses('Auth\ResetPasswordController@reset')->name('password.reset')
    ->middleware(['guest', 'throttle:6,1']);

Route::resource('locations', 'LocationController')->only([
    'index',
    'store',
    'show',
    'update',
    'destroy',
]);

Route::post('/locations/{location_id}/ratings')->uses('RatingController@store')->name('locations.ratings.store')
    ->middleware('auth');
