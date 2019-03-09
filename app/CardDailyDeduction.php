<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardDailyDeduction extends Model
{

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function getTotalChargedAmountAttribute($value)
    {
        return $value / 10000;
    }
}
