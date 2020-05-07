<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomecellActivity extends Model
{
    use SoftDeletes;
    protected $fillable = ['activity_name', 'activity_description', 'activity_set_date', 'activity_start_time',
                            'activity_end_time', 'homecell_id'];


    public function homecell()
    {
        return $this->belongsTo('App\Homecell');
    }

    public function attendances()
    {
        return $this->hasMany('App\Attendance');
    }
}
