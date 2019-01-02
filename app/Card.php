<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'name', 'amount', 'password', 'real_password',
    ];

    public function getAmountAttribute($value)
    {
        return $value / 10000;
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value * 10000;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
        $this->attributes['real_password'] = $value;
    }
}
