<?php

namespace App\Http\Controllers;

use App\Models\AttLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

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

     $query = AttLog::query()
        ->leftJoin(
            DB::raw('marine_att.userinfo as u'),
            'u.USERID',
            '=',
            'att_logs.USERID'
        )
        ->select(
            'att_logs.*',
            'u.Name',   // ← pastikan kolom ini benar
            'u.Badgenumber'
        );
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
}
