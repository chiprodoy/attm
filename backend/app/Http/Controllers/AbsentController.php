<?php

namespace App\Http\Controllers;

use App\Models\CheckLogStatus;
use App\Models\EmployeeCheckLogStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsentController extends BaseController
{
    //
    public function index(Request $req){
        $startDate = Carbon::now()->toDateString();

        if($req->has('start_date') && !empty($req->input('start_date'))){
            $startDate = $req->input('start_date');
        }

        $data=EmployeeCheckLogStatus::with('employee')->whereRaw("DATE(checklog_date)=DATE('".$startDate."')")
                ->where('checklog_status',CheckLogStatus::ABSENT)
                ->get();

        return $this->sendResponse($data);

    }
}
