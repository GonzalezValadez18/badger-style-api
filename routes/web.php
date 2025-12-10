<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Esta ruta muestra el formulario de restablecimiento de contraseña.
// El enlace en el correo electrónico del usuario debe apuntar aquí.
// Ejemplo: https://tu-dominio.com/reset-password/el-token-aqui?email=usuario@correo.com
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->name('password.reset.form');
