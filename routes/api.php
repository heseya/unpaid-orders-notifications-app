<?php

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\InstallationController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

Route::post('/install', [InstallationController::class, 'install']);
Route::post('/uninstall', [InstallationController::class, 'uninstall']);

Route::get('/config', [ConfigController::class, 'show']);
Route::post('/config', [ConfigController::class, 'store'])
    ->middleware('can:configure');

Route::get('/products', [ProductsController::class, 'show'])
    ->middleware('can:show_products');
Route::get('/products-private', [ProductsController::class, 'showPrivate'])
    ->middleware('can:show_products_private');

Route::get('/orders', [OrdersController::class, 'show'])
    ->middleware('can:show_orders');

Route::get('/items', [ItemsController::class, 'show'])
    ->middleware('can:show_items');
