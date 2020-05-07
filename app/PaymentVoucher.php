<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentVoucher extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'requested_by', 'payee', 'amount', 'expenditure_details', 'expenditure_date', 'transaction_id'];
    protected $dates = ['deleted_at', 'expenditure_date'];

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
}
