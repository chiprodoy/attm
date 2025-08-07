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


