<?php

use App\Http\Controllers\FeedController;
use Illuminate\Support\Facades\Route;

Route::prefix('feeds')->middleware('can:configure')->group(function () {
    Route::get('/', [FeedController::class, 'show']);
    Route::post('/', [FeedController::class, 'store']);
    Route::patch('/{feed:id}', [FeedController::class, 'update']);
    Route::delete('/{feed:id}', [FeedController::class, 'destroy']);
});
