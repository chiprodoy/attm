<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    use HasFactory;

    protected $connection = 'attdb'; // Use default connection

    protected $table = 'userinfo'; // Use default connection

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'USERID';

    public function getWorkSchedule(){
        $data = DB::connection('attdb')->table('user_of_run')
            ->join('num_run','num_run.NUM_RUNID','=','user_of_run.NUM_OF_RUN_ID')
            ->join('num_run_deil','num_run_deil.NUM_RUNID','=','user_of_run.NUM_OF_RUN_ID')
            ->where('USERID',$this->USERID)
            ->get();

        return $data;
    }
}
