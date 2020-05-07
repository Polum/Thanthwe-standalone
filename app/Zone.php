<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = ["name", "areas"];

    public function homecells()
    {
        return $this->hasMany('App\Homecell');
    }
}
