<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Test for the embeded auth from the mobile
Route::prefix('oauth')->group(function () {
    Route::get("callback", [OAuthController::class, 'callback'])->name('oauth.callback');
    Route::get("redirect", [OAuthController::class, 'redirect'])->name('oauth.redirect');
});


// this one should be protected
Route::get("/dashboard", function () {
    return "dashbord";
});
