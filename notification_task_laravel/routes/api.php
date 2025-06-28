<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::prefix('notifications')->group(function () {
    Route::post('/', [NotificationController::class, 'store']);

    Route::get('/recent', [NotificationController::class, 'recent']);

    Route::get('/summary', [NotificationController::class, 'summary']);
});
