<?php

use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\EmployeeController;
use Illuminate\Support\Facades\Route;

// Ruta especial transaccional Unit of Work
Route::post('companias/con-empleados', [CompanyController::class, 'createWithEmployees']);

// Listar empleados de una compañía específica
Route::get('companias/{id}/empleados', [CompanyController::class, 'getEmployees']);

// Rutas de CRUD estándar
Route::apiResource('companias', CompanyController::class);
Route::apiResource('empleados', EmployeeController::class);
