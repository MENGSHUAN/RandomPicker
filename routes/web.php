<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WheelController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\OptionController;

Route::get('/', [WheelController::class, 'wheel']);
Route::post('/draw', [WheelController::class, 'draw']);
Route::post('/set-options', [WheelController::class, 'setOptions']);

