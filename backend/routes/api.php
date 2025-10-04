<?php

use App\Http\Controllers\AbsentController;
use App\Http\Controllers\LateController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveEarlyController;
use App\Http\Controllers\PresensiController;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MCUController;
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
Route::get('/late',[LateController::class,'index'])->withoutMiddleware(ThrottleRequests::class);
Route::get('/leave',[LeaveController::class,'index'])->withoutMiddleware(ThrottleRequests::class);
Route::get('/leave_early',[LeaveEarlyController::class,'index'])->withoutMiddleware(ThrottleRequests::class);
Route::get('/presensi',[PresensiController::class,'index'])->withoutMiddleware(ThrottleRequests::class);
Route::get('/absent',[AbsentController::class,'index'])->withoutMiddleware(ThrottleRequests::class);


Route::get('/search-employee', [MCUController::class, 'search_employee']);
Route::post('/mcu', [MCUController::class, 'store']);
