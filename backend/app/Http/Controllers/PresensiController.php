<?php

namespace App\Http\Controllers;

use App\Models\AttLog;
use App\Models\CheckInOut;
use App\Models\CheckLogStatus;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresensiController extends BaseController
{
    //
    public function index(Request $req){
        $startDate = Carbon::now()->toDateString();

        if($req->has('start_date') && !empty($req->input('start_date'))){
            $startDate = $req->input('start_date');
        }

        $cdata=CheckInOut::with('employee')->whereRaw("DATE(CHECKTIME)=DATE('".$startDate."')")
                ->orderBy('CHECKTIME','desc')
                ->limit(1)
                ->get();

        $data = [];
        foreach($cdata as $k =>$v){
            $data[$k]=(object) [
                'CHECKTIME' => $v->CHECKTIME,
                'sn'=>$v->sn,
                'Name'=>$v->employee->Name,
                'USERID'=>$v->employee->USERID,
                'BadgeNumber'=>$v->employee->BadgeNumber,
                'SSN'=>$v->employee->SSN,
                'DEFAULTDEPTID'=>$v->employee->DEFAULTDEPTID,
                'departement_name'=>$v->employee->getParentDepartementNameAttribute(),
                'has_mcu'=>$this->hasMCU($v->employee->USERID)
            ];
        }
        return $this->sendResponse($data);

    }

    private function hasMCU($userID){
        return DB::table('mcu')
                    ->where('USERID',$userID)
                    ->whereDate('mcu_date',Carbon::now()->toDateString())->exists();
    }
}
