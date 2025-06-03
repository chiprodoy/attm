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
            $this->hitungPresensi($val->CHECKTIME,$workSchedule->STARTDATE,$workSchedule);
            if($key==10){
            dd($attCheckType);
            dd($val->getCurrentWorkSchedule());
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

    function hitungPresensi($presensiDatetimeStr, $shiftStartDateStr,$matchedShift)
    {

        // Tanggal awal shift dan tanggal presensi
        $startShift = new \DateTime($shiftStartDateStr);
        $presensi = new \DateTime($presensiDatetimeStr);

        $daysDiff = $startShift->diff($presensi)->days;

        // Jika SDAYS dimulai dari 0
        $sdaysToday = $daysDiff % 12;

        if (!$matchedShift) {
            echo "Shift tidak ditemukan untuk SDAYS = $sdaysToday\n";
            return;
        }

        // Bangun waktu shift berdasarkan tanggal presensi
        $shiftMasuk = new \DateTime($presensi->format('Y-m-d') . ' ' . date('H:i:s', strtotime($matchedShift->STARTTIME)));
        $shiftPulang = new \DateTime($presensi->format('Y-m-d') . ' ' . date('H:i:s', strtotime($matchedShift->ENDTIME)));

        if ($shiftPulang < $shiftMasuk) {
            $shiftPulang->modify('+1 day');
        }

        $lateMinutes = (int)$matchedShift->LateMinutes;
        $earlyMinutes = (int)$matchedShift->EarlyMinutes;

        $maxToleransiMasuk = clone $shiftMasuk;
        $maxToleransiMasuk->modify("+$lateMinutes minutes");

        $minToleransiPulang = clone $shiftPulang;
        $minToleransiPulang->modify("-$earlyMinutes minutes");

        echo "Presensi: {$presensi->format('Y-m-d H:i:s')}\n";
        echo "Shift Masuk: {$shiftMasuk->format('H:i')} | Pulang: {$shiftPulang->format('H:i')}\n";

        if ($presensi <= $maxToleransiMasuk) {
            echo "➡️ Presensi Masuk: ";
            if ($presensi > $shiftMasuk) {
                $late = round(($presensi->getTimestamp() - $shiftMasuk->getTimestamp()) / 60);
                echo "Terlambat $late menit.\n";
            } else {
                echo "Tepat waktu.\n";
            }
        } elseif ($presensi >= $shiftPulang) {
            echo "➡️ Presensi Pulang: ";
            if ($presensi < $minToleransiPulang) {
                $early = round(($minToleransiPulang->getTimestamp() - $presensi->getTimestamp()) / 60);
                echo "Pulang cepat $early menit.\n";
            } else {
                echo "Tepat waktu.\n";
            }
        } else {
            echo "Presensi di tengah shift, tidak bisa dipastikan masuk atau pulang.\n";
        }
    }
    private function getEmployeeCheckType($userID,$dateTime){

    }
    private function getEmployeeLogType($userID){

    }
}
