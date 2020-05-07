<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = ['transaction_type', 'transaction_source', 'transaction_amount', 'transaction_date', 'account_id', 'reverse'];

    protected $dates = ['deleted_at'];

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function offering()
    {
        return $this->hasOne('App\Offering');
    }
    public function deposit()
    {
        return $this->hasOne('App\Deposit');
    }
    public function paymentVoucher()
    {
        return $this->hasOne('App\PaymentVoucher');
    }
    public function sundayBasket()
    {
        return $this->hasOne('App\SundayBasket');
    }
    public function expense()
    {
        return $this->hasOne('App\Expense');
    }
}
