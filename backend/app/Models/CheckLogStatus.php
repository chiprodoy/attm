<?php

namespace App\Models;

class CheckLogStatus{
    const UNKNOWN = 0;
    const NORMAL=1;
    const LATE = 2;
    const EARLY_CHECKIN = 3;
    const EARLY_CHECKOUT = 3;
    const OVERTIME = 4;
    const ABSENT = 5;

}
