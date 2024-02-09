<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\userController;
use App\Http\Controllers\API\RouteController;
use App\Http\Controllers\API\TrainController;
use App\Http\Controllers\API\stationController;

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


Route::post('/api/users', [userController::class, 'userFunction']);
Route::post('/api/stations', [stationController::class, 'station']);
Route::post('/trains', [TrainController::class, 'store']);
Route::get('/api/stations', [stationController::class,'index']);
Route::get('/api/stations/{stationId}/trains', [TrainController::class, 'getByStation']);
Route::get('/api/wallets/{id}', [userController::class,'show']);
Route::put('/api/wallets/{wallet_id}', [userController::class,'update']);
Route::get('/api/routes', [RouteController::class,'getOptimalRoute']);


