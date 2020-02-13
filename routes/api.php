<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function() {
    return 'Ok'; // There needs to be a status route configured for Kubernetes health checks.
});

Route::post('/register')->uses('AuthController@register');
Route::post('/login')->uses('AuthController@login');
Route::get('/logout')->uses('AuthController@logout')->middleware('auth');

Route::resource('locations', 'LocationController')->only([
    'index',
    'store',
    'show',
    'update',
    'destroy',
]);