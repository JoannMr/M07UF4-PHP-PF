<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Rutas de Productos (protegidas por Sanctum y Roles)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    // ✅ Ruta accesible para cualquier usuario autenticado (admin y user)
    Route::get('/products', [ProductController::class, 'index']);

    // ✅ Rutas solo para admins (protección con middleware 'role:admin')
    Route::middleware('role:admin')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    });
});

use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'show']);