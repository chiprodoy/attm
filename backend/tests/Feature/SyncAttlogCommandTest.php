<?php

namespace Tests\Feature;

use App\Models\CheckLogStatus;
use App\Models\CheckType;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SyncAttlogCommandTest extends TestCase
{

    private function initExample($time){
        return DB::connection('attdb')->table('user_of_run')
            ->join('num_run','num_run.NUM_RUNID','=','user_of_run.NUM_OF_RUN_ID')
            ->join('num_run_deil','num_run_deil.NUM_RUNID','=','user_of_run.NUM_OF_RUN_ID')
            ->join('schclass','schclass.schClassid','=','num_run_deil.SCHCLASSID')
            ->whereTime('num_run_deil.STARTTIME',$time)
            ->first();
    }



    /**
     * A basic unit test example.
     */
    public function test_pegawai_presensi_shift_pagi_tepat_waktu(): void
    {
        $example = $this->initExample('08:00');

        $checkTime  = now()->toDateString().' 08:00:00';
        $example->StartTime = new \DateTime($example->StartTime);
        $shift_in = now()->toDateString().' '.$example->StartTime->format('H:i:s');

        $data = [
            'USERID'=>$example->USERID,
	        'CHECKTIME'=> $checkTime
        ];

        DB::connection('attdb')->table('checkinout')->insert($data);
        Artisan::call('sync:attlog',['--date'=>now()->toDateString()]);

        $this->assertDatabaseHas('att_logs', [
            'USERID' => $example->USERID,
            'check_log_in' => $checkTime,
	        'shift_in'=>$shift_in,
            'check_type'=>CheckType::IN,
	        'late'=>0,
	        'early_checkin'=>0,
	        'overtime'=>0,
	        'early_checkout'=>0,
	        'check_log_status'=>CheckLogStatus::NORMAL,
        ]);

        $this->assertTrue(true);
    }
        /**
     * A basic unit test example.
     */
    public function test_pegawai_presensi_shift_pagi_datang_cepat(): void
    {
        $this->assertTrue(true);
    }

            /**
     * A basic unit test example.
     */
    public function test_pegawai_presensi_shift_pagi_terlambat(): void
    {
        $this->assertTrue(true);
    }
}
