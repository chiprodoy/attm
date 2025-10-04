<?php

namespace App\Models;

class CheckLogStatus{
    const UNKNOWN = 0;
    const NORMAL=1;
    const LATE = 2;
    const EARLY_CHECKIN = 3;
    const EARLY_CHECKOUT = 4;
    const OVERTIME = 5;
    const ABSENT = 6;
    const IZIN = 7;
    const CUTI = 8;
    const SICK = 9;
    const DINAS = 10;


}
