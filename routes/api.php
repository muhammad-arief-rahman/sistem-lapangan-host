<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(API\BookingController::class)->group(function () {
    Route::get('/field/get-available', 'getFieldAvailability')->name('api.fields.get-available');
    Route::get('/services/get-available', 'getAvailableServices')->name('api.services.get-available');
});
