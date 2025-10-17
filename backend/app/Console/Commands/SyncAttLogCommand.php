<?php

namespace App\Console\Commands;

use App\Models\AttLog;
use App\Models\CheckInOut;
use App\Models\CheckLogStatus;
use App\Models\CheckType;
use App\Models\EmployeeCheckLogStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncAttLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:attlog {--date=} {--until=} {--noservice=} {--allday}';

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

    private $asService=true;

    private $isAllDay = false; // jika true maka akan sinkronisasi semua data checkinout, bukan range jam

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if($this->hasOption('noservice')){
            $this->asService = false;
        }

        if($this->option('allday')){
            $this->isAllDay = true;
        }

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
        Log::info('syncattlogcommand asService : '.$this->asService);

        // if(!$this->asService){
        //     $this->readCheckInOutData($this->startDate,$this->endDateParam);
        // }

        // while($this->asService){
            Log::info('syncattlogcommand while asService : '.$this->asService);

            $this->readCheckInOutData($this->startDate,$this->endDateParam);
        //     sleep(1);
        // }

        return Command::SUCCESS;

        //
    }
    private function setStartDate(){

        if(!$this->isAllDay){
        //if(empty($this->startDateParam)){
            // cari checklog time terakhir
           // $data=AttLog::orderBy('checklog_time','desc')
            $data=AttLog::orderBy('check_log_in','desc')
                ->first();
            if($data){
               return $data->check_log_in;
            }else{
                $data=CheckInOut::orderBy('CHECKTIME','asc')
                ->first();
               return $data->CHECKTIME;

            }

        }
            return $this->startDateParam.' 00:00:00';

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
                $result = $this->hitungPresensi($val->employee,new \DateTime($val->CHECKTIME),new \DateTime($workSchedule->STARTDATE),$workSchedule);
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

        Log::debug('savePresence begin save presensi');

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
            /*
            $recordExistOnAttlog->update([$presentData]);
            $this->info('checktype '.$presentData['check_type'].' attlog');
            $this->info('userid : '.$presentData['USERID']);
            $this->info('checklogTime : time '.$checklogTime->format('Y-m-d H:i:s'));
            $this->info('checklogTimeOnRecord : time '.$checklogTimeOnRecord->format('Y-m-d H:i:s'));
            $this->info('checklogTime : timestamp '.$checklogTime->getTimestamp());
            $this->info('checklogTimeOnRecord : timestamp '.$checklogTimeOnRecord->getTimestamp());
            */
            Log::debug('savePresence record exist sql '.$recordExistOnAttlog);
            if($recordExistOnAttlog->check_type==CheckType::IN){
                $updIn = AttLog::where('id',$recordExistOnAttlog->id)->update($presentData);
                Log::debug('savePresence update IN'.json_encode($presentData));


                /*
                if($checklogTime->getTimestamp() < $checklogTimeOnRecord->getTimestamp()){

                    $recordExistOnAttlog->update([$presentData]);
                        Log::debug('savePresence update IN'.json_encode($presentData));
                        Log::debug('savePresence checktype in update because checkin time  on machine < checktime on record');
                        Log::debug('savePresence checktype '.$presentData['check_type'].' attlog');
                        Log::debug('savePresence userid : '.$presentData['USERID']);
                        Log::debug('savePresence checklogTime : time '.$checklogTime->format('Y-m-d H:i:s'));
                        Log::debug('savePresence checklogTimeOnRecord : time '.$checklogTimeOnRecord->format('Y-m-d H:i:s'));
                        Log::debug('savePresence checklogTime : timestamp '.$checklogTime->getTimestamp());
                        Log::debug('savePresence checklogTimeOnRecord : timestamp '.$checklogTimeOnRecord->getTimestamp());
                }else{
                        Log::debug('savePresence nothing to do on checktype in because checkin time  on machine > checktime on record');
                        Log::debug('savePresence checktype '.$presentData['check_type'].' attlog');
                        Log::debug('savePresence userid : '.$presentData['USERID']);
                        Log::debug('savePresence checklogTime : time '.$checklogTime->format('Y-m-d H:i:s'));
                        Log::debug('savePresence checklogTimeOnRecord : time '.$checklogTimeOnRecord->format('Y-m-d H:i:s'));
                        Log::debug('savePresence checklogTime : timestamp '.$checklogTime->getTimestamp());
                        Log::debug('savePresence checklogTimeOnRecord : timestamp '.$checklogTimeOnRecord->getTimestamp());

                } */
            }elseif($recordExistOnAttlog->check_type==CheckType::OUT){
                //$recordExistOnAttlog->update([$presentData]);
                $updOut = AttLog::where('id',$recordExistOnAttlog->id)->update($presentData);
                Log::debug('savePresence update OUT'.json_encode($presentData));

                /*
                if($checklogTime->getTimestamp() > $checklogTimeOnRecord->getTimestamp()){
                    $recordExistOnAttlog->update([$presentData]);
                    Log::debug('savePresence update OUT'.json_encode($presentData));
                    Log::debug('savePresence checktype out update because checkout time on machine > checktime on record');
                     Log::debug('savePresence userid : '.$presentData['USERID']);
                     Log::debug('savePresence checklogTime : time '.$checklogTime->format('Y-m-d H:i:s'));
                     Log::debug('savePresence checklogTimeOnRecord : time '.$checklogTimeOnRecord->format('Y-m-d H:i:s'));
                     Log::debug('savePresence checklogTime : timestamp '.$checklogTime->getTimestamp());
                     Log::debug('savePresence checklogTimeOnRecord : timestamp '.$checklogTimeOnRecord->getTimestamp());
                }else{
                        Log::debug('savePresence nothing to do on checktype out because checkin time  on machine < checktime on record');
                        Log::debug('savePresence checktype '.$presentData['check_type'].' attlog');
                        Log::debug('savePresence userid : '.$presentData['USERID']);
                        Log::debug('savePresence checklogTime : time '.$checklogTime->format('Y-m-d H:i:s'));
                        Log::debug('savePresence checklogTimeOnRecord : time '.$checklogTimeOnRecord->format('Y-m-d H:i:s'));
                        Log::debug('savePresence checklogTime : timestamp '.$checklogTime->getTimestamp());
                        Log::debug('savePresence checklogTimeOnRecord : timestamp '.$checklogTimeOnRecord->getTimestamp());

                }
                */
            }

        }else{
            //jika record belum pernah ada insert
            Log::debug('savePresence create '.json_encode($presentData));
            Log::debug('savePresence begin create attlog');
            AttLog::create($presentData);

        }
        //dd($presentData);
        DB::connection()->enableQueryLog();
        $upd = EmployeeCheckLogStatus::whereRaw("DATE(checklog_date)=DATE('".$presentData['checklog_time']."')")
            ->where('employee_USERID',$presentData['USERID'])
            ->update([
                'checklog_status'=>$presentData['check_log_status']
            ]);
        $queries = DB::getQueryLog();
        Log::debug("SyncAttLogCommand savePresence EmployeeCheckLogStatus qry : ".json_encode($queries));
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

    function hitungPresensi($employee,\DateTime $presensiDate, \DateTime $shiftStartDate,$matchedShift){

        $shift_start_dt = $shiftStartDate;
        $days_diff = $shift_start_dt->diff($presensiDate)->days;
        $sdays_today = $days_diff % $matchedShift->CYLE; //sebelumnya bagi 12
        $early_checkin = 0;
        $early_checkout = 0;
        $overtime = 0;
        $late=0;
        $checkLogStatus = CheckLogStatus::NORMAL;
        $userID=$employee->USERID;
        $departementName = $employee->parent_departement_name;
        $check_type=0;
        $check_log_in = null;
        $check_log_out = null;

        if (!$matchedShift) {
            return "Shift tidak ditemukan untuk SDAYS = $sdays_today";
        }

        $shift = $matchedShift;
        $shift_in = new \DateTime($presensiDate->format("Y-m-d") . " " . date("H:i:s", strtotime($shift->StartTime)));
        $shift_out = new \DateTime($presensiDate->format("Y-m-d") . " " . date("H:i:s", strtotime($shift->EndTime)));
        $checkIn_time1 = new \DateTime($presensiDate->format("Y-m-d") . " " . date("H:i:s", strtotime($shift->CheckInTime1)));
        $checkIn_time2 = new \DateTime($presensiDate->format("Y-m-d") . " " . date("H:i:s", strtotime($shift->CheckInTime2)));
        $checkOut_time1 = new \DateTime($presensiDate->format("Y-m-d") . " " . date("H:i:s", strtotime($shift->CheckOutTime1)));
        $checkOut_time2 = new \DateTime($presensiDate->format("Y-m-d") . " " . date("H:i:s", strtotime($shift->CheckOutTime2)));

        //cari selisih presensi
        $timeDiffPresence = timeDiference($presensiDate,$shift_in);

        //cek apakah shift pada pergantian hari
        if($shift_out < $shift_in){
            $shift_out = $shift_out->modify("+1 day");
            $checkOut_time1 = $checkOut_time1->modify("+1 day");
            $checkOut_time2 = $checkOut_time2->modify("+1 day");
        }

        if($checkOut_time2 < $checkOut_time1){
            $checkOut_time2 = $checkOut_time2->modify("+1 day");
        }


        //cek apakah rule boleh absen pada pergantian hari,
        //jika pada pergantian hari kurangi 1 hari
        if($checkIn_time1 > $checkIn_time2){
            $checkIn_time1 = $checkIn_time1->modify("-1 day");
        }

        //check record absen masuk sudah ada di tabel attlog
        $recordExistOnAttLog = AttLog::where('USERID',$userID)
                                ->where('shift_in',$shift_in)
                                ->where('shift_out',$shift_out)
                                ->where('check_type',CheckType::IN)
                                ->exists();

        // cek record exist atau tidak di attlog
        //jika ada maka sekarang presensi pulang
        //if($recordExistOnAttLog) {
        // logika diganti dengan mencari absensi pada range waktu masuk
        // jika masuk ke range waktu pulang maka tambah record tidak absen masuk

        if(isTimeOnRange($presensiDate,$checkIn_time1,$checkOut_time1)){

            $check_type = CheckType::IN; // presensi masuk
            $check_log_in = $presensiDate->format('Y-m-d H:i:s');

            $timeDiffPresence = timeDiference($presensiDate,$shift_in);

            if($timeDiffPresence > 0){
                $late = $timeDiffPresence;
                $checkLogStatus = CheckLogStatus::LATE;
            }elseif($timeDiffPresence < 0){
                $early_checkin = $timeDiffPresence;
                $checkLogStatus = CheckLogStatus::EARLY_CHECKIN;
            }
        }
        // else range berada selain daripada range jam masuk
        else {
            $check_type = CheckType::OUT; // presensi pulang
            $check_log_out = $presensiDate->format('Y-m-d H:i:s');
            $timeDiffPresence = timeDiference($presensiDate,$shift_out);

            if($timeDiffPresence > 0){
                $overtime = $timeDiffPresence;
                $checkLogStatus = CheckLogStatus::OVERTIME;
            }elseif($timeDiffPresence < 0){
                $early_checkout = $timeDiffPresence;
                $checkLogStatus = CheckLogStatus::EARLY_CHECKOUT;
            }
        }
        // jika waktu tap lebih besar dari checkintime1

        $result = [
                "USERID"=>$userID,
                "checklog_time"=>$presensiDate->format('Y-m-d H:i:s'),
                "check_log_in"=>$check_log_in,
                "check_log_out"=>$check_log_out,
                "shift_in"=>$shift_in->format('Y-m-d H:i:s'),
                "shift_out"=>$shift_out->format('Y-m-d H:i:s'),
                'checkin_time1'=>$checkIn_time1->format('Y-m-d H:i:s'),
                'checkin_time2'=>$checkIn_time2->format('Y-m-d H:i:s'),
                'checkout_time1'=>$checkOut_time1->format('Y-m-d H:i:s'),
                'checkout_time2'=>$checkOut_time2->format('Y-m-d H:i:s'),
                "check_type" => $check_type,
                'late_tolerance' => $shift->LateMinutes,
                'early_tolerance' => $shift->EarlyMinutes,// toleransi pulang cepat
                "SDAYS" => $sdays_today,
                "late" => abs($late), // in minutes
                "early_checkin" => abs($early_checkin), // in minutes
                "overtime" => abs($overtime), // lembur
                "early_checkout" => abs($early_checkout) // in minutes
                ,"check_log_status" => $checkLogStatus
                ,"departement_name"=>$departementName

            ];
            return $result;

    }
    function hitungPresensiOld($employee,$presensiDatetimeStr, $shiftStartDateStr,$matchedShift)
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
                'late_tolerance' => abs($LateMinutes),
                'early_tolerance' => abs($EarlyMinutes),// toleransi pulang cepat
                "SDAYS" => $sdays_today,
                "late" => abs($late), // in minutes
                "early_checkin" => abs($early_checkin), // in minutes
                "overtime" => abs($overtime), // lembur
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
