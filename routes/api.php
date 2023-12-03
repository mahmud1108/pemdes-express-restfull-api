<?php

use App\Http\Controllers\Admin\BumdesController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Bumdes\BumdesController as BumdesBumdesController;
use App\Http\Controllers\Courier\CourierController as CourierCourierController;
use App\Http\Controllers\GuestController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\BumdesMiddleware;
use App\Http\Middleware\CourierMiddleware;
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
Route::get('/check_cost/{village_id}', [GuestController::class, 'check_cost']);

Route::post('/bumdes/login', [BumdesBumdesController::class, 'login']);
Route::post('/admin', [AdminController::class, 'login']);
Route::post('/courier', [CourierCourierController::class, 'login']);
Route::post('/courier/register', [CourierCourierController::class, 'register']);

Route::middleware(AdminMiddleware::class)->prefix('/admin')->group(function () {
    Route::patch('/', [AdminController::class, 'update']);
    Route::get('/', [AdminController::class, 'current']);
    Route::delete('/', [AdminController::class, 'logout']);

    Route::post('/courier', [CourierController::class, 'create']);
    Route::delete('/courier/{courier_id}', [CourierController::class, 'delete']);
    Route::get('/courier/{courier_id}', [CourierController::class, 'detail']);
    Route::get('/courier', [CourierController::class, 'search']);
    Route::get('/courier/list/{courier_id}', [CourierController::class, 'list']);
    Route::patch('/courier/{courier_id}', [CourierController::class, 'update']);

    Route::post('/bumdes', [BumdesController::class, 'create']);
    Route::get('/bumdes', [BumdesController::class, 'get_all']);
    Route::get('/bumdes/search', [BumdesController::class, 'search']);
    Route::get('/bumdes/{bumdes_id}', [BumdesController::class, 'detail']);
    Route::get('/bumdes/current/{bumdes_id}', [BumdesController::class, 'current_bumdes']);
    Route::delete('/bumdes/{bumdes_id}', [BumdesController::class, 'delete']);
    Route::patch('/bumdes/{bumdes_id}', [BumdesController::class, 'update']);

    Route::patch('/shipment/delivery_status/{no_receipts}', [ShipmentController::class, 'edit']);
    Route::post('/shipment', [ShipmentController::class, 'create']);
    Route::get('/shipment', [ShipmentController::class, 'get_all_shipment']);
    Route::get('/shipment/get', [ShipmentController::class, 'get_by_delivery_status']);
    Route::delete('/shipment/{no_receipts}', [ShipmentController::class, 'delete']);
});

Route::middleware(CourierMiddleware::class)->prefix('courier')->group(function () {
    Route::patch('/', [CourierCourierController::class, 'update']);
    Route::get('/shipment', [CourierCourierController::class, 'get_all_shipment']);
    Route::post('/shipment/{no_receipts}', [CourierCourierController::class, 'update_shipment']);
    Route::delete('/logout', [CourierCourierController::class, 'logout']);
});

Route::middleware(BumdesMiddleware::class)->prefix('bumdes')->group(function () {
    Route::patch('/', [BumdesBumdesController::class, 'update']);
    Route::get('/shipment', [BumdesBumdesController::class, 'shipment']);
    Route::patch('/shipment/{no_receipts}', [BumdesBumdesController::class, 'update_current_bumdes']);
    Route::get('/shipment/{no_receipts}', [BumdesBumdesController::class, 'detail']);
    Route::delete('/logout', [BumdesBumdesController::class, 'logout']);
});
