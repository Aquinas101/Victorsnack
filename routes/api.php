<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ✅ Midtrans Webhook - NO CSRF, NO AUTH
Route::post('/midtrans/callback', [KasirController::class, 'midtransCallback']);