<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttLog extends Model
{
    use HasFactory;
}

class CheckType{
    const IN=1;
    const OUT=2;

}
class AttLogType{
    const normal=1;
    const late = 2;
    const early = 3;
    const overtime = 4;
    const absent = 5;

}
