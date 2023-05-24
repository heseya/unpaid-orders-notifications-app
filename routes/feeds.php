<?php

declare(strict_types=1);

use App\Http\Controllers\FeedController;
use Heseya\Pagination\Http\Middleware\Pagination;
use Illuminate\Support\Facades\Route;

Route::prefix('feeds')->middleware('can:configure')->group(function () {
    Route::get('/', [FeedController::class, 'index'])->middleware(Pagination::class);
    Route::post('/', [FeedController::class, 'store']);
    Route::get('/{feed:id}', [FeedController::class, 'show']);
    Route::patch('/{feed:id}', [FeedController::class, 'update']);
    Route::delete('/{feed:id}', [FeedController::class, 'destroy']);
});
