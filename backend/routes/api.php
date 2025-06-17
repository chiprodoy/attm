<?php

use App\Http\Controllers\LateController;
use App\Http\Controllers\LeaveEarlyController;
use App\Http\Controllers\PresensiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/late',[LateController::class,'index']);
Route::get('/leave_early',[LeaveEarlyController::class,'index']);
Route::get('/presensi',[PresensiController::class,'index']);
