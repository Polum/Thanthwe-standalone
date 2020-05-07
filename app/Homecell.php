<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Homecell extends Model
{
    use SoftDeletes;
    protected $fillable = ['zone_id', 'name', 'location'];

    public function members()
    {
        return $this->hasMany('App\Member');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function activities()
    {
        return $this->hasMany('App\HomecellActivity');
    }

    public function zone()
    {
        return $this->belongsTo('App\Zone');
    }
}
