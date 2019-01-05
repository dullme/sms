<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recharge extends Model
{

    public function getAmountAttribute($value)
    {
        return $value / 10000;
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
