<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfferingType extends Model
{
    use SoftDeletes;

    protected $fillable = ['offering_title'];
    protected $dates = ['deleted_at'];

    public function offering()
    {
        return $this->hasMany('App\Offering');
    }
}
