<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    protected $fillable = ["committee_type_id", "member_id", "department_id", "ministry_id"];

    public function departments()
    {
        return $this->hasMnay('App\Department');
    }

    public function ministries()
    {
        return $this->hasMnay('App\Ministry');
    }

    public function type()
    {
        return $this->belongsTo('App\CommitteeType');
    }

    public function member()
    {
        return $this->belongsTo('App\Member');
    }
}
