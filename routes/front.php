<?php

use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

Route::get('front//{file}', [FrontController::class, 'show']);
Route::get('front/{file}', [FrontController::class, 'show']);
