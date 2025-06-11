<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;

// Endpoint untuk ESP32
Route::post('/sensor-data', [SensorController::class, 'store']);

// Endpoint untuk aplikasi web/mobile
Route::get('/sensor/latest', [SensorController::class, 'latest']); // Semua device
Route::get('/sensor/latest/{device_id}', [SensorController::class, 'latest']); // By device ID