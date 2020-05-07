<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    protected $fillable = ['expense_type', 'expense_amount', 'expense_date', 'comment', 'transaction_id'];
    protected $dates = ['deleted_at', 'expense_date'];

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
}
