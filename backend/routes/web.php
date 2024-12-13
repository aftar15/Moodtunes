<?php

use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::get('/', function () {
    return view('welcome');
});

// CSRF Cookie Route
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show'])
    ->middleware(['web']);
