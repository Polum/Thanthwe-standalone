<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;
    protected $fillable = ['account_name', 'account_balance', 'main'];

    protected $dates = ['deleted_at'];

    public function transactions()
    {
        return $this->hasMany('App\Transactions');
    }
}
