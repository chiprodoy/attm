<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckInOut extends Model
{
    use HasFactory;

    protected $connection = 'attdb'; // Use default connection
    protected $table = 'checkinout'; // Use default connection

    static function readCheckInOutData($date){

        $date= Carbon::parse($date);
        $start = $date->toDateTimeString();
        $end = $date->endOfDay()->toDateTimeString();

        $data = $this->whereBetween('CHECKTIME',[$start,$end])
         ->orderBy('CHECKTIME','desc')
         ->get();

         return $data;

    }
}
