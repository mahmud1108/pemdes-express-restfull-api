<?php

use App\Http\Controllers\Admin\BumdesController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\ShipmentController;
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
    Route::get('/admin/courier/{courier_id}', [CourierController::class, 'detail']);
    Route::get('/admin/courier', [CourierController::class, 'search']);
    Route::get('/admin/courier/list/{courier_id}', [CourierController::class, 'list']);
    Route::patch('/admin/courier/{courier_id}', [CourierController::class, 'update']);

    Route::post('/admin/bumdes', [BumdesController::class, 'create']);
    Route::get('/admin/bumdes', [BumdesController::class, 'get_all']);
    Route::get('/admin/bumdes/search', [BumdesController::class, 'search']);
    Route::get('/admin/bumdes/{bumdes_id}', [BumdesController::class, 'detail']);
    Route::get('/admin/bumdes/current/{bumdes_id}', [BumdesController::class, 'current_bumdes']);
    Route::delete('/admin/bumdes/{bumdes_id}', [BumdesController::class, 'delete']);
    Route::patch('/admin/bumdes/{bumdes_id}', [BumdesController::class, 'update']);

    Route::patch('/admin/shipment/delivery_status/{no_receipts}', [ShipmentController::class, 'edit_delivery_status']);
    Route::post('/admin/shipment', [ShipmentController::class, 'create']);
    Route::get('/admin/shipment', [ShipmentController::class, 'get_all_shipment']);
    Route::get('/admin/shipment/get', [ShipmentController::class, 'get_by_delivery_status']);
    Route::delete('/admin/shipment{no_receipts}', [ShipmentController::class, 'delete']);
});
