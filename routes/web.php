<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas del sistema de consulta de estado de cuenta
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Identificación del contribuyente (DNI + nombre completo)
Route::get('/ingresar', [AuthController::class, 'mostrarFormulario'])->name('login');
Route::post('/ingresar', [AuthController::class, 'identificar'])->middleware('throttle:5,15')->name('login.attempt');
Route::get('/registrarse', [AuthController::class, 'mostrarRegistro'])->name('register');
Route::post('/registrarse', [AuthController::class, 'registrar'])->middleware('throttle:3,15')->name('register.store');
Route::get('/verificar-correo', [AuthController::class, 'mostrarVerificacion'])->name('verificacion.form');
Route::post('/verificar-correo', [AuthController::class, 'verificarCorreo'])->middleware('throttle:5,10')->name('verificacion.confirmar');
Route::post('/verificar-correo/reenviar', [AuthController::class, 'reenviarCodigo'])->middleware('throttle:3,10')->name('verificacion.reenviar');
Route::post('/salir', [AuthController::class, 'salir'])->name('logout');

// Consulta de estado de cuenta (protegida por identificación previa)
Route::middleware('contribuyente.identificado')->group(function () {
    Route::get('/consulta', [ConsultaController::class, 'index'])->name('consulta.index');
});
