<?php

namespace App\Console\Commands;

use App\Models\AttLog;
use App\Models\CheckInOut;
use App\Models\CheckType;
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
            $workSchedule = $val->getCurrentWorkSchedule();
            $attCheckType = $this->setCheckType($val,$workSchedule);
            // cari jumlah cepat atau jumlah lambat
            //jika check type in maka cari jumlah telat
            //jika check type out maka cari jumlah pulang cepat
            if($attCheckType == CheckType::IN){
                $ct = Carbon::parse($val->CHECKTIME);

                $tIn1 = Carbon::parse($workSchedule->STARTTIME);
                $tIn1->setDate($ct->year,$ct->month,$ct->day);

                $lateAmount = $ct->diffInMinutes($tIn1);
                echo $lateAmount;

                dd($val);
            }

            dd($attCheckType);
            dd($val->getCurrentWorkSchedule());
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

    private function setCheckType($checkLog,$workSchedule){

            $ct = Carbon::parse($checkLog->CHECKTIME);

            $t1 = Carbon::parse($workSchedule->CheckInTime1);
            $t1->setDate($ct->year,$ct->month,$ct->day);

            $t2 = Carbon::parse($checkLog->CheckInTime2);
            $t2->setDate($ct->year,$ct->month,$ct->day);


            // cek apakah di range waktu masuk
            if(Carbon::parse($checkLog->CHECKTIME)->format('His.u') <= Carbon::parse($workSchedule->CheckInTime2)->format('His.u') || Carbon::parse($checkLog->CHECKTIME)->format('His.u') >= Carbon::parse($workSchedule->CheckInTime2)->format('His.u')){
                $attCheckType = CheckType::IN;
            }elseif(Carbon::parse($checkLog->CHECKTIME)->format('His.u') <= Carbon::parse($workSchedule->CheckOutTime2)->format('His.u') || Carbon::parse($checkLog->CHECKTIME)->format('His.u') >= Carbon::parse($workSchedule->CheckOutTime2)->format('His.u')){
                $attCheckType = CheckType::IN;
            }
            return $attCheckType;
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
