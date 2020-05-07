<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['name', 'description', 'start_date', 'end_date', 'start_time', 'end_time'];
    protected $dates = ['start_date', 'end_date', 'deleted_at'];

}
