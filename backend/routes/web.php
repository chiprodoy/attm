<?php

use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\iclockController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\MCUController;
use App\Http\Controllers\MDBUploadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TVController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});



Route::middleware('auth')->group(function () {
    Route::get('/mcu', [MCUController::class,'index'])->name('mcu.index');
    Route::resource('/leave',LeaveController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('users', UserController::class);

    /* MDB Upload Routes */
    Route::post('/upload-mdb', [MDBUploadController::class, 'store'])
    ->name('mdb.upload.store');

    Route::get('/upload-mdb', [MDBUploadController::class, 'index'])
        ->name('mdb.upload.index');

    Route::get('/reports/attendance', [AttendanceReportController::class, 'index'])
    ->name('reports.attendance');
});

Route::get('tv',[TVController::class,'index']);

// handshake
Route::middleware(['log.iclockrequest'])->get('/iclock/cdata', [iclockController::class, 'handshake']);
// request dari device
Route::middleware(['log.iclockrequest'])->post('/iclock/cdata', [iclockController::class, 'receiveRecords']);

Route::get('/iclock/test', [iclockController::class, 'test']);
Route::middleware(['log.iclockrequest'])->get('/iclock/getrequest', [iclockController::class, 'getrequest']);
// routing cetak laporan
Route::get('/report/attendance/print', [AttendanceReportController::class, 'print'])
    ->name('attendance.report.print');



require __DIR__.'/auth.php';

