<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'level'];
    protected $hiddent = ['level'];

    public function users(){
        return $this->hasMany('APP\User');
    }

}