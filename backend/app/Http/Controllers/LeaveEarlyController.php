<?php

namespace App\Http\Controllers;

use App\Models\AttLog;
use App\Models\CheckLogStatus;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveEarlyController extends BaseController
{
    //
    public function index(Request $req){
        $startDate = Carbon::now()->toDateString();

        if($req->has('start_date') && !empty($req->input('start_date'))){
            $startDate = $req->input('start_date');
        }
       //DB::enableQueryLog();

        $data=AttLog::with('employee')->whereRaw("DATE(checklog_time)=DATE('".$startDate."')")
                ->where('check_log_status',CheckLogStatus::EARLY_CHECKOUT)
                ->orderBy('checklog_time','desc')
                ->get();
       // dd(DB::getQueryLog());

        return $this->sendResponse($data);

    }
}
