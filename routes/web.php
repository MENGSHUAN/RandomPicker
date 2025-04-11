<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WheelController;

Route::get('/wheel', [WheelController::class, 'wheel']);
Route::post('/draw', [WheelController::class, 'draw']);
