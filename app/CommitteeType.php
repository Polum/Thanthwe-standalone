<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommitteeType extends Model
{
    protected $fillable = ["name"];

    public function committees()
    {
        return $this->hasMnay('App\Committee');
    }
}
