<?php

namespace App\Http\Controllers;

use App\Models\AttLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
// SELECT * FROM employee_check_log_statuses
// 	INNER JOIN marine_att.userinfo ON userinfo.USERID = employee_check_log_statuses.employee_USERID
// 	LEFT JOIN att_logs ON att_logs.USERID=employee_check_log_statuses.employee_USERID
// 		AND DATE(employee_check_log_statuses.checklog_date) = att_logs.checklog_time
// 		 WHERE checklog_time = '2025-10-03'
class AttendanceReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only([
            'start_date',
            'end_date',
            'check_type',
            'check_log_status',
            'employee',
            'department',
        ]);

        $query = DB::table('employee_check_log_statuses')
                ->join(DB::raw('marine_att.userinfo as u'), 'u.USERID', '=', 'employee_check_log_statuses.employee_USERID')
                ->leftJoin(DB::raw('att_logs'), function ($join) {
                    $join->on('att_logs.USERID', '=', 'employee_check_log_statuses.employee_USERID')
                         ->whereRaw('DATE(employee_check_log_statuses.checklog_date) = att_logs.checklog_time');
                })->select(
                    'att_logs.*',
                    'u.Name as employee_name',   // ← pastikan kolom ini benar
                    'u.Badgenumber'
                );
    //  $query = AttLog::query()
    //     ->leftJoin(
    //         DB::raw('marine_att.userinfo as u'),
    //         'u.USERID',
    //         '=',
    //         'att_logs.USERID'
    //     )
    //     ->select(
    //         'att_logs.*',
    //         'u.Name as employee_name',   // ← pastikan kolom ini benar
    //         'u.Badgenumber'
    //     );
        // ===================== FILTER =====================
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('checklog_time', [
                $request->start_date,
                $request->end_date
            ]);
        }

        if ($request->filled('check_type')) {
            $query->where('check_type', $request->check_type);
        }

        if ($request->filled('check_log_status')) {
            $query->where('check_log_status', $request->check_log_status);
        }

        if ($request->filled('employee')) {
            $query->where('u.Name', 'like', '%' . trim($request->employee) . '%');
        }

        if ($request->filled('department')) {
            $query->where('departement_name', 'like', '%' . $request->department . '%');
        }

        $logs = $query
            ->orderByDesc('checklog_time')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Report/AttendanceReport', [
            'logs' => $logs,
            'filters' => $filters,
        ]);
    }

    public function print(Request $request)
    {
        $query = DB::table('employee_check_log_statuses')
                ->join(DB::raw('marine_att.userinfo as u'), 'u.USERID', '=', 'employee_check_log_statuses.employee_USERID')
                ->leftJoin(DB::raw('att_logs'), function ($join) {
                    $join->on('att_logs.USERID', '=', 'employee_check_log_statuses.employee_USERID')
                         ->whereRaw('DATE(employee_check_log_statuses.checklog_date) = att_logs.checklog_time');
                })->select(
                    'att_logs.*',
                    'u.Name as employee_name',   // ← pastikan kolom ini benar
                    'u.Badgenumber'
                );

    // ===== FILTER (SAMA DENGAN INDEX) =====
    if ($request->filled(['start_date', 'end_date'])) {
        $query->whereBetween('checklog_time', [
            $request->start_date,
            $request->end_date
        ]);
    }

    if ($request->filled('check_type')) {
        $query->where('check_type', $request->check_type);
    }

    if ($request->filled('check_log_status')) {
        $query->where('check_log_status', $request->check_log_status);
    }

    if ($request->filled('employee')) {
        $query->where('u.NAME', 'like', '%' . $request->employee . '%');
    }

    if ($request->filled('department')) {
        $query->where('departement_name', 'like', '%' . $request->department . '%');
    }

    $logs = $query
        ->orderBy('checklog_time')
        ->get();

    return Inertia::render('Report/AttendanceReportPrint', [
        'logs' => $logs,
        'filters' => $request->all(),
        'printedAt' => now()->format('d-m-Y H:i'),
    ]);
}

}
