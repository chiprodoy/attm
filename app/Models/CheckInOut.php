<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckInOut extends Model
{
    use HasFactory;

    protected $connection = 'attdb'; // Use default connection
    protected $table = 'checkinout'; // Use default connection

}
