<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Api\GrnController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// API Routes for Procurement
// Invoice API
Route::get('/invoices', [InvoiceController::class, 'index']);

// Purchase Order API
Route::get('/purchase-orders', [PurchaseOrderController::class, 'index']);

// GRN API
Route::get('/grns', [GrnController::class, 'index']);
