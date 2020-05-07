<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    protected $fillable = ["name"];

    public function Committees()
    {
        return $this->hasMnay('App\Committee');
    }
}
