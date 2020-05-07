<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;

    protected $fillable = ['homecell_activity_id', 'member_id'];

    public function homecellActivity()
    {
        return $this->belongsTo('App\HomecellActivity');
    }

    public function member()
    {
        return $this->belongsTo('App\Member');
    }
}
