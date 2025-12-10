<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\DateController;

// Ruta para el registro de usuarios
Route::post('/register', [AuthController::class, 'register']);
// Ruta para el inicio de sesión
Route::post('/login', [AuthController::class, 'login']);
// Ruta para solicitar el restablecimiento de contraseña
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
// Ruta para procesar el restablecimiento de contraseña (el que faltaba)
Route::post('/reset-password', [AuthController::class, 'resetPassword'])
      ->name('password.reset');

Route::get('/services', [ServiceController::class, 'index']);
Route::get('/dates/{userId}', [DateController::class, 'getDatesByUser']);
Route::delete('/dates/{dateId}', [DateController::class, 'destroy']);


// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/dates', [DateController::class, 'store']);
});

// Ruta temporal para probar el envío de correos
Route::get('/test-mail', [AuthController::class, 'testMail']);
