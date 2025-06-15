<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttLog extends Model
{
    use HasFactory;
    protected $fillable=[
            'USERID',
            'checklog_time',
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

    public function employee()
    {
        return $this->belongsTo(Employee::class,'USERID','USERID');

    }
}

class CheckType{
    const IN=1;
    const OUT=2;

}
class CheckLogStatus{
    const UNKNOWN = 0;
    const NORMAL=1;
    const LATE = 2;
    const EARLY_CHECKIN = 3;
    const EARLY_CHECKOUT = 3;
    const OVERTIME = 4;
    const ABSENT = 5;

}
