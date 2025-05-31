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

    public function getCurrentWorkSchedule($date){
        $data = DB::connection('attdb')->table('user_of_run')
            ->join('num_run','num_run.NUM_RUNID','=','user_of_run.NUM_OF_RUN_ID')
            ->join('num_run_deil','num_run_deil.NUM_RUNID','=','user_of_run.NUM_OF_RUN_ID')
            ->where('USERID',$this->USERID)
             ->whereRaw("MOD(DATEDIFF('".$date."', num_run.STARTDATE), IF(num_run.UNITS=1,7,num_run.UNITS) ) BETWEEN SDAYS AND EDAYS")
            ->get();

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
