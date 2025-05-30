<?php

namespace App\Console\Commands;

use App\Models\AttLog;
use App\Models\CheckInOut;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncAttLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:attlog {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $checkInOutTableSource = CheckInOut::class;

    private $startDateParam;

    private $startDate;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if($this->hasOption('date')){
            $this->startDateParam = $this->option('date');
        }else{
            $this->startDateParam = Carbon::now()->toDateString();
        }

        $this->startDate = $this->setStartDate();

        echo $this->startDate;

        $checkInOutData = $this->readCheckInOutData($this->startDate);

        foreach($checkInOutData as $key => $val){
            dd($val->getWorkSchedule());
            // $this->setAttLogTable($val->USERID,
            // $val->CHECKTIME,
            // $attCheckType,
            // $attLogType,
            // $lateEearlyAmount,
            // $workSchedule);
        }

        //
    }
    private function setStartDate(){
        $data=AttLog::whereRaw('DATE(att_log_time)='.$this->startDateParam)
        ->orderBy('att_log_time','desc')
        ->first();
        if($data){
            return $data->att_log_time;
        }else{
            return $this->startDateParam.' 00:00:00';
        }

    }
    private function readCheckInOutData($date){

        $date= Carbon::parse($date);
        $start = $date->toDateTimeString();
        $end = $date->endOfDay()->toDateTimeString();

        $data = $this->checkInOutTableSource::whereBetween('CHECKTIME',[$start,$end])
         ->orderBy('CHECKTIME','desc')
         ->get();

         return $data;

    }

    private function setAttLogTable($userID,$attLogTime,$attCheckType,$attLogType,$lateEearlyAmount,$workSchedule)
    {
        AttLog::create([
            'USERID'=>$userID,
            'att_log_time'=>$attLogTime,
            'att_check_type'=>$attCheckType,
            'att_log_type'=>$attLogType,
            'late_early_amount'=>$lateEearlyAmount,
            'work_schedule'=>$workSchedule
        ]);
    }

    private function getEmployeeCheckType($userID,$dateTime){

    }
    private function getEmployeeLogType($userID){

    }
}
