<?php

namespace App\Http\Controllers;

use App\Models\AttLog;
use App\Models\Employee;
use Illuminate\Http\Request;

class LateController extends Controller
{
    //
    public function index($startDate){
        //AttLog::whereRaw('DATE(CHECKTIME)='.$startDate);
    }
}
