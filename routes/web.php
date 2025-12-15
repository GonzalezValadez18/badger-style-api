<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});

// Rutas para restablecer la contraseña
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/update', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('password/reset/success', function () {
    return '¡Tu contraseña ha sido restablecida con éxito!';
})->name('password.reset.success');



Route::get('/run-migrations', function () {
    Artisan::call('migrate --force');
    return Artisan::output();
});

