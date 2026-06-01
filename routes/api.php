<?php
use App\Http\Controllers\Parameter\ParameterController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController; // Import Controller kamu

// Endpoint untuk ESP32 kirim data sensor
Route::post('/sensor/store', [SensorController::class, 'store']);

// Endpoint untuk ESP32 ambil parameter
Route::get('/sensor/{id}/parameter', [ParameterController::class, 'getForDevice']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/v1/send-data', [SensorController::class, 'store']);