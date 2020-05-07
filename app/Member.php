<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;
    protected $fillable = ["first_name", "last_name", "sex", "dob", "marital_status", "address","phone",
    "email", "member_type", "occupation", "envelop_no",
    "previous_church", "joining_means", "join_date", "family_id", "left", "deceased", "homecell_id"];

    protected $dates = ['join_date', 'deceased'];
    protected $hidden = ['deleted_at'];

    public function family()
    {
        return $this->belongsTo('App\Family');
    }

    public function homecell()
    {
        return $this->belongsTo('App\Homecell');
    }

    public function offering()
    {
        return $this->hasMany('App\Offering');
    }

    public function attendances()
    {
        return $this->hasMany('App\Attendance');
    }

    public function committees()
    {
        return $this->hasMany('App\Committee');
    }
}
