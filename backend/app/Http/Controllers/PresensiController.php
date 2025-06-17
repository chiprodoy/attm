<?php

namespace App\Http\Controllers;

use App\Models\AttLog;
use App\Models\CheckLogStatus;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PresensiController extends BaseController
{
    //
    public function index(Request $req){
        $startDate = Carbon::now()->toDateString();

        if($req->has('start_date')){
            $startDate = $req->input('start_date');
        }

        $data=AttLog::with('employee')->whereRaw("DATE(checklog_time)=DATE('".$startDate."')")
                ->orderBy('checklog_time','desc')
                ->get();
        return $this->sendResponse($data);

    }
}
