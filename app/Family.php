<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use SoftDeletes;

    protected $fillable = ['family_name'];

    public function members()
    {
        return $this->hasMany('App\Member');
    }
}
