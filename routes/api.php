<?php

declare(strict_types=1);

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\InstallationController;
use Illuminate\Support\Facades\Route;

Route::post('/install', [InstallationController::class, 'install']);
Route::post('/uninstall', [InstallationController::class, 'uninstall']);

Route::get('/config', [ConfigController::class, 'show'])
    ->middleware('can:unpaid_notifications_configure');
Route::post('/config', [ConfigController::class, 'store'])
    ->middleware('can:unpaid_notifications_configure');
