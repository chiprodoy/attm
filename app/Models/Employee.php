<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    use HasFactory;

    protected $connection = 'attdb'; // Use default connection

    protected $table = 'userinfo';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'USERID';



    public function getCheckType(){
        $data = DB::connection('attdb')->table('user_of_run')
            ->join('num_run','num_run.NUM_RUNID','=','user_of_run.NUM_OF_RUN_ID')
            ->join('num_run_deil','num_run_deil.NUM_RUNID','=','user_of_run.NUM_OF_RUN_ID')
            ->where('USERID',$this->USERID)
            ->get();

        return $data;
    }

    public function getParentDepartementNameAttribute($departementID=null,$temporaryDeptName=''){

        if(empty($departementID)){
            $departementID = $this->DEFAULTDEPTID;
        }

        $data = DB::connection('attdb')->table('departments')
            ->where('DEPTID',$departementID)
            ->first();

        $temporaryDeptName='/'.$data->DEPTNAME.$temporaryDeptName;
        if($data->SUPDEPTID > 1){
            return $this->getParentDepartementNameAttribute($data->SUPDEPTID,$temporaryDeptName);
        }else{
            return substr($temporaryDeptName,1);
        }
    }
}
