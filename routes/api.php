<?php

use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\GuestController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/check_receipts/{no_receipts}', [GuestController::class, 'check_receipts']);
Route::get('/check_cost/{id_bumdes}', [GuestController::class, 'check_cost']);

Route::post('/admin/login', [AdminController::class, 'login']);

Route::middleware(AdminMiddleware::class)->group(function () {
    Route::patch('/admin/update', [AdminController::class, 'update']);
    Route::get('/admin/current', [AdminController::class, 'current']);
    Route::delete('/admin/logout', [AdminController::class, 'logout']);

    Route::post('/admin/courier', [CourierController::class, 'create']);
    Route::delete('/admin/courier/{courier_id}', [CourierController::class, 'delete']);
    Route::get('/admin/courier/{courier_id}', [CourierController::class, 'search']);
    Route::patch('/admin/courier/{courier_id}', [CourierController::class, 'update']);
});
