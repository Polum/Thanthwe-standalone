<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'address', 'employee',
        'occupation', 'role_id', 'level', 'denomination_id', 'homecell_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // attributes that should be mutated to dates.
    protected $dates = ['deleted_at'];

    public function role(){
        return $this->belongsTo('App\Role');
    }

    public function denomination(){
        return $this->belongsTo('App\denomination');
    }

    public function homecell()
    {
        return $this->belongsTo('App\Homcell');
    }
}
