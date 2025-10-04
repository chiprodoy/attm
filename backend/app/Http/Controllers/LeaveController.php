<?php

namespace App\Http\Controllers;

use App\Models\CheckLogStatus;
use App\Models\Employee;
use App\Models\EmployeeCheckLogStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeaveController extends BaseController
{
    //
    public function index(Request $request)
    {
        $query = EmployeeCheckLogStatus::with('employee');
        $startDate = Carbon::now()->toDateString();

        if ($request->filled('date')) {
            $startDate = $request->date;
        }

        if ($request->filled('status')) {
            $query->where('checklog_status', $request->status);
        }

        $query->whereDate('checklog_date', $startDate);
        $query->whereIn('checklog_status',[CheckLogStatus::IZIN,CheckLogStatus::SICK,CheckLogStatus::CUTI]);

        if($request->wantsJson()){
            $logs = $query->latest()->get();
            return $this->sendResponse($logs);
        }else{
            $logs = $query->latest()->paginate(10)->withQueryString();
            return Inertia::render('Leave/Index', [
                'logs' => $logs,
                'filters' => $request->only(['date', 'status']),
                'start_date' => $startDate
            ]);

        }
    }

    public function create()
    {
        $employees = Employee::select('USERID', 'name')->orderBy('name','asc')->get();
        return Inertia::render('Leave/Create', [
            'employees' => $employees,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
                'employee_USERID' => 'required',
                'start_date'      => 'required|date',
                'end_date'        => 'required|date|after_or_equal:start_date',
                'checklog_status' => 'required|in:7,8,9',
        ]);

        $start = \Carbon\Carbon::parse($request->start_date);
        $end = \Carbon\Carbon::parse($request->end_date);

        while ($start->lte($end)) {
            $recordCheckLogStatus = EmployeeCheckLogStatus::whereDate('checklog_date',$start->format('Y-m-d'))
                                    ->where('employee_USERID',$request->employee_USERID)
                                    ->first();

            if($recordCheckLogStatus){
                // jika status hadir tidak bisa update status hanya update status absent saja
                if(in_array($recordCheckLogStatus->checklog_status,[CheckLogStatus::ABSENT,CheckLogStatus::IZIN,CheckLogStatus::CUTI,CheckLogStatus::SICK])){
                    $recordCheckLogStatus->update(['checklog_status' => $request->checklog_status]);
                }
            }else{
                EmployeeCheckLogStatus::create([
                    'employee_USERID' => $request->employee_USERID,
                    'checklog_date'   => $start->format('Y-m-d 00:00:00'),
                    'checklog_status' => $request->checklog_status,
                ]);
            }

            $start->addDay();
        }

        return redirect()->route('leave.index')
             ->with('success', 'Data izin/cuti/sakit berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $employeeCheckLogStatus = EmployeeCheckLogStatus::find($id);
        $employees = Employee::select('USERID', 'Name')->where('USERID',$employeeCheckLogStatus->employee_USERID)->get();
        return Inertia::render('Leave/Edit', [
            'log' => $employeeCheckLogStatus,
            'employees' => $employees,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_USERID' => 'required',
            'checklog_date'   => 'required|date',
            'checklog_status' => 'required|in:7,8,9',
        ]);
        $employeeCheckLogStatus = EmployeeCheckLogStatus::find($id);
        $employeeCheckLogStatus->update($request->all());

        return redirect()->route('leave.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $employeeCheckLogStatus = EmployeeCheckLogStatus::find($id);

        $employeeCheckLogStatus->update(['checklog_status'=>CheckLogStatus::ABSENT]);

        return redirect()->route('leave.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
