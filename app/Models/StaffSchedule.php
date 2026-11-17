<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffSchedule extends Model
{
    public $timestamps = false;
    protected $fillable = ['staff_id', 'weekday', 'start_time', 'end_time'];
}
