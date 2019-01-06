<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    static $colors = [
        0 => 'gray',
        1 => 'green',
        9 => 'red'
    ];

    static $status = [
        0 => '待处理',
        1 => '提现成功',
        9 => '提现失败',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAmountAttribute($value)
    {
        return $value / 10000;
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value * 10000;
    }
}
