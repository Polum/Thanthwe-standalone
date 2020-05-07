<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deposit extends Model
{
    use SoftDeletes;

    protected $fillable = ['deposit_type', 'deposit_source', 'deposit_amount', 'deposit_details', 'deposit_date', 'transaction_id'];
    protected $dates = ['deleted_at'];

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
}
