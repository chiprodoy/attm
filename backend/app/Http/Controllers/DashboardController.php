<?php

namespace App\Http\Controllers;

use App\Models\CheckLogStatus;
use App\Models\EmployeeCheckLogStatus;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    //
    public function index(Request $request){
        if(Auth::user()->isRole(Role::PETUGAS_MEDIS)){
            return redirect(route('mcu.index'));
        }else{

            $today = Carbon::today();

            $attendances = EmployeeCheckLogStatus::whereDate('checklog_date', $today)
                            ->whereNotIn('checklog_status',[
                                CheckLogStatus::ABSENT,
                                CheckLogStatus::CUTI,
                                CheckLogStatus::IZIN,
                                CheckLogStatus::SICK,
                                CheckLogStatus::UNKNOWN
                            ])->get();

            $summary = [
                'hadir' => $attendances->count(),
                'terlambat' => $attendances->where('checklog_status', CheckLogStatus::LATE)->count(),
                'pulang_cepat' => $attendances->where('checklog_status', CheckLogStatus::EARLY_CHECKOUT)->count(),
                'belum_presensi' => EmployeeCheckLogStatus::whereDate('checklog_date', $today)->where('checklog_status', CheckLogStatus::ABSENT)->count(), // contoh: misal total pegawai 100
            ];

            return Inertia::render('Dashboard',[
                'summary' => $summary,
                'attendances' => $attendances,
            ]);
        }
    }
}
