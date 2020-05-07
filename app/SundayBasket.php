<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SundayBasket extends Model
{
    use SoftDeletes;

    protected $fillable = ['service_no', 'total_offerings', 'offering_date', 'transaction_id'];
    protected $dates = ['offering_date', 'deleted_at'];

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
}
