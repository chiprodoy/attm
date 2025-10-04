<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeCheckLogStatus extends Model
{
    use HasFactory;

           /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $casts = [
                    'checklog_status' => 'integer',
               ];


    protected $fillable = [
        'checklog_date','employee_USERID','checklog_status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_USERID','USERID');

    }
}
