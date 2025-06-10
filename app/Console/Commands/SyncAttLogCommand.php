<?php

namespace App\Console\Commands;

use App\Models\AttLog;
use App\Models\CheckInOut;
use App\Models\CheckType;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
            if($workSchedule){
                $result = $this->hitungPresensi($val->CHECKTIME,$workSchedule->STARTDATE,$workSchedule);

                if($result && $result['check_type']==CheckType::OUT){
                    print_r($result);

                }
            }else{
                info('jadwal tidak ditemukan');
            }


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

    function hitungPresensi($presensiDatetimeStr, $shiftStartDateStr,$matchedShift)
    {

        $presensi_dt = new \DateTime($presensiDatetimeStr);
        $shift_start_dt = new \DateTime($shiftStartDateStr);
        $days_diff = $shift_start_dt->diff($presensi_dt)->days;
        $sdays_today = $days_diff % 12;
        $early_checkin = 0;
        $early_checkout = 0;
        $overtime = 0;
        $late=0;

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
           }elseif($late > 0 && $late > $LateMinutes){
             // jika terlambat melebihi batas tolerasni
            $early_checkin = 0;
           }
//         $terlambat = max(0, round($presensi_dt->getTimestamp() - $STARTTIME->getTimestamp()) / 60);
//         $early_checkin = min(0, round($presensi_dt->getTimestamp() - $STARTTIME->getTimestamp()) / 60);

            return [
                "tgl_presensi"=>$presensi_dt->format('Y-m-d H:i:s'),
                "shift_masuk"=>$STARTTIME->format('Y-m-d H:i:s'),
                "shift_pulang"=>$ENDTIME->format('Y-m-d H:i:s'),
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
           }elseif($overtime > 0 && $late > $LateMinutes){
             // jika terlambat melebihi batas tolerasni
            $early_checkin = 0;
           }


            return [
                "tgl_presensi"=>$presensi_dt->format('Y-m-d H:i:s'),
                "shift_masuk"=>$STARTTIME->format('Y-m-d H:i:s'),
                "shift_pulang"=>$ENDTIME->format('Y-m-d H:i:s'),
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
            ];
        } //early echeckout
        elseif ($presensi_dt >= $CheckInTime2 && $presensi_dt <= $CheckOutTime2)
        {
//2025-05-25 01:09:58 > 2025-05-25 23:00:00 2025-05-25 23:28:23 < 2025-05-25 03:00:00

            $overtime = round($presensi_dt->getTimestamp() - $ENDTIME->getTimestamp()) / 60;

          if($overtime < 0){
            $early_checkout = $overtime;
            $overtime= 0;
           }elseif($overtime > 0 && $late > $LateMinutes){
             // jika terlambat melebihi batas tolerasni
            $early_checkin = 0;
           }


            return [
                "tgl_presensi"=>$presensi_dt->format('Y-m-d H:i:s'),
                "shift_masuk"=>$STARTTIME->format('Y-m-d H:i:s'),
                "shift_pulang"=>$ENDTIME->format('Y-m-d H:i:s'),
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
            ];
        } else {

            $data = [
                "tgl_presensi"=>$presensi_dt->format('Y-m-d H:i:s'),
                "shift_masuk"=>$STARTTIME->format('Y-m-d H:i:s'),
                "shift_pulang"=>$ENDTIME->format('Y-m-d H:i:s'),
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
             ];
            info($data);
            return null;
        }
    }
    private function getEmployeeCheckType($userID,$dateTime){

    }
    private function getEmployeeLogType($userID){

    }
}
