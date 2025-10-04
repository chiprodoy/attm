<?php

namespace App\Console\Commands;

use App\Models\CheckLogStatus;
use App\Models\EmployeeCheckLogStatus;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InitCheckLogStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecls:init {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateParam = ($this->hasArgument('date')) ? new DateTime($this->argument('date')) : new DateTime();
        //cari seluruh shift pegawai kemarin
        $shiftDatas = $this->getWorkSchedule($dateParam);

        foreach($shiftDatas as $k => $v){
            //simpan status absent di employee_checklog_statuses
            // cek apakah sudah ada record di employee_checklog_statuses
            // jika tidak ada record maka insert

            $employeeChecklogStatusExist = EmployeeCheckLogStatus::where('checklog_date',$dateParam->format('Y-m-d'))
                ->where('employee_USERID',$v->USERID)->exists();

            if(!$employeeChecklogStatusExist){
                EmployeeCheckLogStatus::create([
                    'checklog_date'=>$dateParam,
                    'employee_USERID'=>$v->USERID,
                    'checklog_status'=>CheckLogStatus::ABSENT
                ]);
            }
            //check di attlog apakah hadir

        }

    }

    public function getWorkSchedule(\DateTime $dateTime){
        $data = DB::connection('attdb')->table('user_of_run')
            ->select([ 'userinfo.USERID',
			            'userinfo.Badgenumber',
			            'userinfo.SSN',
			            'userinfo.Name',
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
             ->join('userinfo','userinfo.USERID','=','user_of_run.USERID')
            ->where('num_run.ENDDATE','>=',$dateTime->format('Y-m-d'))
            ->whereRaw("MOD(DATEDIFF('".$dateTime->format('Y-m-d')."', num_run.STARTDATE), IF(num_run.UNITS=1,7,num_run.CYLE) ) BETWEEN SDAYS AND EDAYS")
            ->get();

        return $data;
    }
}
