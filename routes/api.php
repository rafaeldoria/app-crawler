<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\GoutteController;
use App\Http\Controllers\PhanterController;
use App\Http\Controllers\CrawllerController;
use App\Http\Controllers\CurrencyController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/data', [CurrencyController::class, 'get']);

Route::get('/goutte', [GoutteController::class, 'index']);

// Route::get('/phanter', [PhanterController::class, 'index']);

