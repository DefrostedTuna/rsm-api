<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Ok'; // There needs to be a status route configured for Kubernetes health checks.
});

Route::post('/register')->uses('AuthController@register')->name('auth.register');
Route::post('/login')->uses('AuthController@login')->name('auth.login');
Route::get('/logout')->uses('AuthController@logout')->name('auth.logout')
    ->middleware('auth');

Route::get('/email/verify/{id}/{hash}')->uses('Auth\VerificationController@verify')->name('verification.verify')
    ->middleware(['signed', 'throttle:6,1']);
Route::post('/email/resend', 'Auth\VerificationController@resend')->name('verification.resend')
    ->middleware(['auth', 'throttle:6,1']);

Route::resource('locations', 'LocationController')->only([
    'index',
    'store',
    'show',
    'update',
    'destroy',
]);
