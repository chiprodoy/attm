<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AttLog extends Model
{
    use HasFactory;
    protected $fillable=[
            'USERID',
            'checklog_time',
            'check_log_in',
            'check_log_out',
            'shift_in',
            'shift_out',
            'checkin_time1',
            'checkin_time2',
            'checkout_time1',
            'checkout_time2',
            'check_type',//1 = in, 2 = out
            'late_tolerance',
            'early_tolerance',// toleransi pulang cepat
            'SDAYS',
            'late', // in minutes
            'early_checkin', // in minutes
            'overtime', // lembur
            'early_checkout', // in minutes
            'check_log_status', //
            "departement_name"

    ];

    protected $casts = ['checklog_time' => 'date'];
    public function employee()
    {
        return $this->belongsTo(Employee::class,'USERID','USERID');

    }

        /**
     * Set the uid.
     *
     * @param  string  $value
     * @return void
     */
    public function getHasMcuAttribute($value)
    {

        return DB::table('mcu')
                    ->where('USERID',$this->USERID)
                    ->whereDate('mcu_date',Carbon::parse($this->checklog_time)->toDateString())
                    ->exists();
    }
}


