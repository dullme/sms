<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'name', 'amount', 'password','user_id'
    ];

    public function getAmountAttribute($value)
    {
        return $value / 10000;
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value * 10000;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
