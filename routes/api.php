<?php

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\InstallationController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InfoController::class, 'index']);

Route::post('/install', [InstallationController::class, 'install']);
Route::post('/uninstall', [InstallationController::class, 'uninstall']);

Route::get('/config', [ConfigController::class, 'show']);
Route::post('/config', [ConfigController::class, 'store'])
    ->middleware('can:config');

Route::get('/products', [ProductsController::class, 'show']);
Route::get('/products-private', [ProductsController::class, 'showPrivate']);

Route::get('/orders', [OrdersController::class, 'show']);

Route::get('/items', [ItemsController::class, 'show']);
