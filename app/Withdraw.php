<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $fillable= [
        'user_id',
        'amount',
        'handling_fee',
        'withdraw_rate',
        'status',
        'balance',
        'bank_card_number',
        'bank',
        'remark',
        'payment_at',
    ];

    static $colors = [
        0 => 'gray',
        1 => 'green',
        7 => 'green',
        8 => 'green',
        9 => 'red'
    ];

    static $status = [
        0 => '待处理',
        1 => '提现成功',
        7 => '内部转出',
        8 => '内部转入',
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

    public function getBalanceAttribute($value)
    {
        return $value / 10000;
    }

    public function setBalanceAttribute($value)
    {
        $this->attributes['balance'] = $value * 10000;
    }

    public function getHandlingFeeAttribute($value)
    {
        return $value / 10000;
    }

    public function setHandlingFeeAttribute($value)
    {
        $this->attributes['handling_fee'] = $value * 10000;
    }
}
