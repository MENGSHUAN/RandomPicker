<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WheelController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\OptionController;

Route::get('/wheel', [WheelController::class, 'wheel']);
Route::post('/draw', [WheelController::class, 'draw']);
Route::get('/slot', [SlotController::class, 'slot']);
Route::post('/slot/draw', [SlotController::class, 'draw']);
Route::post('/slot/reset', [SlotController::class, 'resetOptions'])->name('slot.reset');
Route::get('/options', [OptionController::class, 'view'])->name('options.view');
Route::post('/options', [OptionController::class, 'store'])->name('options.store');
Route::put('/options/{option}', [OptionController::class, 'update'])->name('options.update');
Route::delete('/options/{option}', [OptionController::class, 'destroy'])->name('options.destroy');
