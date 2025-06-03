<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CheckInOut extends Model
{
    use HasFactory;

    protected $connection = 'attdb'; // Use default connection
    protected $table = 'checkinout'; // Use default connection

        /**

     * Get the phone associated with the user.

     */

    public function employee()
    {
        return $this->belongsTo(Employee::class,'USERID','USERID');

    }

    public function getWorkSchedule(){
        $data = DB::connection('attdb')->table('user_of_run')
            ->join('num_run','num_run.NUM_RUNID','=','user_of_run.NUM_OF_RUN_ID')
            ->join('num_run_deil','num_run_deil.NUM_RUNID','=','user_of_run.NUM_OF_RUN_ID')
            ->where('USERID',$this->USERID)
            ->get();

        return $data;
    }

    public function getCurrentWorkSchedule(){
        $data = DB::connection('attdb')->table('user_of_run')
            ->select([
                        'schclass.schName',
                        'schclass.StartTime',
                        'schclass.EndTime',
                        'schclass.LateMinutes',
                        'schclass.EarlyMinutes',
                        'schclass.CheckIn',
                        'schclass.CheckOut',
                        'schclass.CheckInTime1',
                        'schclass.CheckInTime2',
                        'schclass.CheckOutTime1',
                        'schclass.CheckOutTime2',
                        'num_run.NUM_RUNID',
                        'num_run.NAME',
                        'num_run.STARTDATE',
                        'num_run.ENDDATE',
                        'num_run.CYLE',
                        'num_run.UNITS',
                        'num_run_deil.STARTTIME',
                        'num_run_deil.ENDTIME',
                        'num_run_deil.SDAYS',
                        'num_run_deil.EDAYS',
                        'num_run_deil.SCHCLASSID',
                        'num_run_deil.OverTime'])
            ->join('num_run','num_run.NUM_RUNID','=','user_of_run.NUM_OF_RUN_ID')
            ->join('num_run_deil','num_run_deil.NUM_RUNID','=','user_of_run.NUM_OF_RUN_ID')
            ->join('schclass','schclass.schClassid','=','num_run_deil.SCHCLASSID')
            ->where('USERID',$this->USERID)
            ->where('num_run.ENDDATE','>=',$this->CHECKTIME)
            ->whereRaw("MOD(DATEDIFF('".$this->CHECKTIME."', num_run.STARTDATE), IF(num_run.UNITS=1,7,num_run.CYLE) ) BETWEEN SDAYS AND EDAYS")
            ->first();

        return $data;
    }
    static function readCheckInOutData($date){

        // $date= Carbon::parse($date);
        // $start = $date->toDateTimeString();
        // $end = $date->endOfDay()->toDateTimeString();

        // $data = $this->whereBetween('CHECKTIME',[$start,$end])
        //  ->orderBy('CHECKTIME','desc')
        //  ->get();

        //  return $data;

    }
}
