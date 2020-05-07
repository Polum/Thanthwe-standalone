<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ministry extends Model
{
    protected $fillable = ["name"];

    public function Committees()
    {
        return $this->hasMnay('App\Committee');
    }
}
