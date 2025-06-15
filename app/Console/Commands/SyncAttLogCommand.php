<?php

namespace App\Console\Commands;

use App\Models\AttLog;
use App\Models\CheckInOut;
use App\Models\CheckLogStatus;
use App\Models\CheckType;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class SyncAttLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:attlog {--date=} {--until=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $checkInOutTableSource = CheckInOut::class;

    private $startDateParam=null;

    private $endDateParam=null;

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

        if($this->hasOption('until')){
            $this->endDateParam = $this->option('until');
        }else{
            $this->endDateParam = Carbon::now()->toDateTimeString();
        }

        $this->startDate = $this->setStartDate();

        $this->info("start sinkron date: ".$this->startDate);

        $this->readCheckInOutData($this->startDate,$this->endDateParam);



        //
    }
    private function setStartDate(){
        if(empty($this->startDateParam)){
            // cari checklog time terakhir
            $data=AttLog::orderBy('checklog_time','desc')
                ->first();

            if($data){
               return $data->checklog_time;
            }else{
                $data=CheckInOut::orderBy('CHECKTIME','asc')
                ->first();
               return $data->CHECKTIME;

            }

        }else{
            return $this->startDateParam.' 00:00:00';
        }

    }

    private function setCheckType($checkLog,$workSchedule){
            $attCheckType = null;

            if($workSchedule){

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
            }
            return $attCheckType;
    }

    private function processingCheckInOutData($checkInOutData){

        foreach($checkInOutData as $key => $val){
            $this->info('Processing checktime : '.$val->CHECKTIME);
            $workSchedule = $val->getCurrentWorkSchedule();
                    // cari jumlah cepat atau jumlah lambat
                    //jika check type in maka cari jumlah telat
                    //jika check type out maka cari jumlah pulang cepat
            if($workSchedule){
                $result = $this->hitungPresensi($val->employee,$val->CHECKTIME,$workSchedule->STARTDATE,$workSchedule);
                if(!empty($result)) {
                    $this->savePresence($result);
                }else{
                    info('result tidak ditemukan :'.$val->CHECKTIME);
                }
            }else{
                info('jadwal tidak ditemukan');
                info($val);
            }

        }
       // $this->readCheckInOutData()
    }

    private function savePresence($presentData){

        $this->info('begin save presensi');
        //cari di table attlog presensi berdasarkan user, tanggalshift dan check type
        //jika ada yang sama maka update
        // jika tidak ada maka insert
        // untuk check type masuk ambil waktu paling kecil (pertama checklog)
        // untuk check type pulang ambil waktu paling besar (terakhir checklog)
        //
        $shiftIn = new \DateTime($presentData['shift_in']);
        $shiftOut = new \DateTime($presentData['shift_out']);
        $checklogTime = new \DateTime($presentData['checklog_time']);

        $recordExistOnAttlog = AttLog::where('USERID',$presentData['USERID'])
                    ->where('check_type',$presentData['check_type'])
                    ->where('shift_in',$shiftIn->format('Y-m-d H:i:s'))
                    ->where('shift_out',$shiftOut->format('Y-m-d H:i:s'))
                    ->first();

        if($recordExistOnAttlog){
            $this->info('record exist');

            $checklogTimeOnRecord = new \DateTime($recordExistOnAttlog->checklog_time);

            if($recordExistOnAttlog->check_type==CheckType::IN){
                if($checklogTime->getTimestamp() < $checklogTimeOnRecord->getTimestamp()){
                     $recordExistOnAttlog->update([$presentData]);
                     $this->info('checktype in update attlog');
                }else{
                    AttLog::create($presentData);
                     $this->info('checktype in create attlog');
                }
            }elseif($recordExistOnAttlog->check_type==CheckType::OUT){
                if($checklogTime->getTimestamp() > $checklogTimeOnRecord->getTimestamp()){
                     $recordExistOnAttlog->update([$presentData]);
                     $this->info('checktype out update attlog');
                }else{
                    AttLog::create($presentData);
                     $this->info('checktype out create update attlog');
                }
            }
        }else{
            //jika record belum pernah ada insert
            AttLog::create($presentData);

        }

    }

    private function readCheckInOutData($startDate,$endDate=null){

        $this->info("start read checkinout data for date: ".$startDate);

        $start = Carbon::parse($startDate)->toDateTimeString();

        if(empty($endDate)){
            $end = Carbon::now()->toDateTimeString();
        }else{
            $end = Carbon::parse($endDate)->toDateTimeString();
        }

        $data = $this->checkInOutTableSource::whereBetween('CHECKTIME',[$start,$end])
         ->orderBy('CHECKTIME','asc')
         ->get();
        $this->processingCheckInOutData($data);

        $this->info("end read checkinout data at date: ".$end);


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

    function hitungPresensi($employee,$presensiDatetimeStr, $shiftStartDateStr,$matchedShift)
    {

        $presensi_dt = new \DateTime($presensiDatetimeStr);
        $shift_start_dt = new \DateTime($shiftStartDateStr);
        $days_diff = $shift_start_dt->diff($presensi_dt)->days;
        $sdays_today = $days_diff % 12;
        $early_checkin = 0;
        $early_checkout = 0;
        $overtime = 0;
        $late=0;
        $checkLogStatus = CheckLogStatus::NORMAL;
        $userID=$employee->USERID;
        $departementName = $employee->parent_departement_name;

        if (!$matchedShift) {
            return "Shift tidak ditemukan untuk SDAYS = $sdays_today";
        }

        $shift = $matchedShift;

        // Ambil waktu penting
        $STARTTIME = new \DateTime($presensi_dt->format("Y-m-d") . " " . date("H:i:s", strtotime($shift->STARTTIME)));
        $ENDTIME   = new \DateTime($presensi_dt->format("Y-m-d") . " " . date("H:i:s", strtotime($shift->ENDTIME)));

        if ($ENDTIME < $STARTTIME) {
            $ENDTIME->modify("+1 day");
        }

        $CheckInTime1 = new \DateTime($presensi_dt->format("Y-m-d") . " " . date("H:i:s", strtotime($shift->CheckInTime1)));
        $CheckInTime2 = new \DateTime($presensi_dt->format("Y-m-d") . " " . date("H:i:s", strtotime($shift->CheckInTime2)));

        if ($CheckInTime2 < $CheckInTime1) {
            $CheckInTime2->modify("+1 day");
        }

        $CheckOutTime1 = new \DateTime($presensi_dt->format("Y-m-d") . " " . date("H:i:s", strtotime($shift->CheckOutTime1)));
        $CheckOutTime2 = new \DateTime($presensi_dt->format("Y-m-d") . " " . date("H:i:s", strtotime($shift->CheckOutTime2)));

        if ($CheckOutTime2 < $CheckOutTime1) {
            $CheckOutTime2->modify("+1 day");
        }

        if($presensi_dt->diff($CheckOutTime1)->h > $CheckOutTime2->diff($CheckOutTime1)->h){
            $CheckOutTime1->modify("-1 day");

        }

        $LateMinutes = (int) $shift->LateMinutes;
        $EarlyMinutes = (int) $shift->EarlyMinutes;
        //Masuk
        if ($presensi_dt >= $CheckInTime1 && $presensi_dt <= $CheckInTime2) {

           //jika selisih melebihi 24 jam modifikasi starttime ditambah satu hari
           if($presensi_dt->diff($STARTTIME)->h > $CheckInTime2->diff($CheckInTime1)->h){
                $STARTTIME->modify("+1 day");
           }

           $late = round($presensi_dt->getTimestamp() - $STARTTIME->getTimestamp()) / 60;
           // jika nilai terlambat minus maka pulang cepat
           if($late < 0){
            $early_checkin = $late;
            $late = 0;
            $checkLogStatus = CheckLogStatus::EARLY_CHECKIN;

           }elseif($late > 0 && $late > $LateMinutes){
             // jika terlambat melebihi batas tolerasni
            $early_checkin = 0;
            $checkLogStatus = CheckLogStatus::LATE;
           }
//         $terlambat = max(0, round($presensi_dt->getTimestamp() - $STARTTIME->getTimestamp()) / 60);
//         $early_checkin = min(0, round($presensi_dt->getTimestamp() - $STARTTIME->getTimestamp()) / 60);

            return [
                "USERID"=>$userID,
                "checklog_time"=>$presensi_dt->format('Y-m-d H:i:s'),
                "shift_in"=>$STARTTIME->format('Y-m-d H:i:s'),
                "shift_out"=>$ENDTIME->format('Y-m-d H:i:s'),
                'checkin_time1'=>$CheckInTime1->format('Y-m-d H:i:s'),
                'checkin_time2'=>$CheckInTime2->format('Y-m-d H:i:s'),
                'checkout_time1'=>$CheckOutTime1->format('Y-m-d H:i:s'),
                'checkout_time2'=>$CheckOutTime2->format('Y-m-d H:i:s'),
                "check_type" => CheckType::IN,
                'late_tolerance' => $LateMinutes,
                'early_tolerance' => $EarlyMinutes,// toleransi pulang cepat
                "SDAYS" => $sdays_today,
                "late" => $late, // in minutes
                "early_checkin" => abs($early_checkin), // in minutes
                "overtime" => $overtime, // lembur
                "early_checkout" => abs($early_checkout) // in minutes
                ,"check_log_status" => $checkLogStatus
                ,"departement_name"=>$departementName

            ];
        }
        //normal checkout
        //dihitung mulai dari batas akhir checkin sampai batas akhir checkout2
        elseif ($presensi_dt >= $CheckOutTime1 && $presensi_dt <= $CheckOutTime2)
        {
//2025-05-25 01:09:58 > 2025-05-25 23:00:00 2025-05-25 23:28:23 < 2025-05-25 03:00:00

            $overtime = round($presensi_dt->getTimestamp() - $ENDTIME->getTimestamp()) / 60;

          if($overtime < 0){
            $early_checkout = $overtime;
            $overtime= 0;
            $checkLogStatus = CheckLogStatus::EARLY_CHECKOUT;

           }elseif($overtime > 0 && $late > $LateMinutes){
             // jika terlambat melebihi batas tolerasni
            $early_checkin = 0;
            $checkLogStatus = CheckLogStatus::OVERTIME;
           }


            return [
                "USERID"=>$userID,
                "checklog_time"=>$presensi_dt->format('Y-m-d H:i:s'),
                "shift_in"=>$STARTTIME->format('Y-m-d H:i:s'),
                "shift_out"=>$ENDTIME->format('Y-m-d H:i:s'),
                'checkin_time1'=>$CheckInTime1->format('Y-m-d H:i:s'),
                'checkin_time2'=>$CheckInTime2->format('Y-m-d H:i:s'),
                'checkout_time1'=>$CheckOutTime1->format('Y-m-d H:i:s'),
                'checkout_time2'=>$CheckOutTime2->format('Y-m-d H:i:s'),
                "check_type" => CheckType::OUT,
                'late_tolerance' => $LateMinutes,
                'early_tolerance' => $EarlyMinutes,// toleransi pulang cepat
                "SDAYS" => $sdays_today,
                "late" => $late, // in minutes
                "early_checkin" => abs($early_checkin), // in minutes
                "overtime" => $overtime, // lembur
                "early_checkout" => abs($early_checkout) // in minutes
                ,"check_log_status" => $checkLogStatus
                ,"departement_name"=>$departementName
            ];
        } //early echeckout
        elseif ($presensi_dt >= $CheckInTime2 && $presensi_dt <= $CheckOutTime2)
        {
//2025-05-25 01:09:58 > 2025-05-25 23:00:00 2025-05-25 23:28:23 < 2025-05-25 03:00:00

            $overtime = round($presensi_dt->getTimestamp() - $ENDTIME->getTimestamp()) / 60;

          if($overtime < 0){
            $early_checkout = $overtime;
            $overtime= 0;
            $checkLogStatus = CheckLogStatus::EARLY_CHECKOUT;

           }elseif($overtime > 0 && $late > $LateMinutes){
             // jika terlambat melebihi batas tolerasni
            $early_checkin = 0;
            $checkLogStatus = CheckLogStatus::OVERTIME;

           }


            return [
                "USERID"=>$userID,
                "checklog_time"=>$presensi_dt->format('Y-m-d H:i:s'),
                "shift_in"=>$STARTTIME->format('Y-m-d H:i:s'),
                "shift_out"=>$ENDTIME->format('Y-m-d H:i:s'),
                'checkin_time1'=>$CheckInTime1->format('Y-m-d H:i:s'),
                'checkin_time2'=>$CheckInTime2->format('Y-m-d H:i:s'),
                'checkout_time1'=>$CheckOutTime1->format('Y-m-d H:i:s'),
                'checkout_time2'=>$CheckOutTime2->format('Y-m-d H:i:s'),
                "check_type" => CheckType::OUT,
                'late_tolerance' => $LateMinutes,
                'early_tolerance' => $EarlyMinutes,// toleransi pulang cepat
                "SDAYS" => $sdays_today,
                "late" => $late, // in minutes
                "early_checkin" => abs($early_checkin), // in minutes
                "overtime" => $overtime, // lembur
                "early_checkout" => abs($early_checkout) // in minutes
                ,"check_log_status" => $checkLogStatus
                ,"departement_name"=>$departementName

            ];
        } else {

            $data = [
                "USERID"=>$userID,
                "checklog_time"=>$presensi_dt->format('Y-m-d H:i:s'),
                "shift_in"=>$STARTTIME->format('Y-m-d H:i:s'),
                "shift_out"=>$ENDTIME->format('Y-m-d H:i:s'),
                'checkin_time1'=>$CheckInTime1->format('Y-m-d H:i:s'),
                'checkin_time2'=>$CheckInTime2->format('Y-m-d H:i:s'),
                'checkout_time1'=>$CheckOutTime1->format('Y-m-d H:i:s'),
                'checkout_time2'=>$CheckOutTime2->format('Y-m-d H:i:s'),
                "check_type" => CheckType::OUT,
                'late_tolerance' => $LateMinutes,
                'early_tolerance' => $EarlyMinutes,// toleransi pulang cepat
                "SDAYS" => $sdays_today,
                "late" => $late, // in minutes
                "early_checkin" => abs($early_checkin), // in minutes
                "overtime" => $overtime, // lembur
                "early_checkout" => abs($early_checkout) // in minutes
                ,"check_log_status" => $checkLogStatus
                ,"departement_name"=>$departementName

             ];

            return $data;
        }
    }
    private function getEmployeeCheckType($userID,$dateTime){

    }
    private function getEmployeeLogType($userID){

    }
}
