<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offering extends Model
{
    use SoftDeletes;

    protected $fillable = ['other_type', 'offering_amount', 'offering_date', 'member_id', 'offering_type_id', 'transaction_id'];
    protected $date = ['deleted_at'];

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
    public function offeringType()
    {
        return $this->belongsTo('App\OfferingType');
    }
    public function member()
    {
        return $this->belongsTo('App\Member');
    }
}
