<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'content',
        'amount',
        'status',
        'running',
        'count',
        'unfinished',
        'mobile',
    ];

    static $color = [
        'UNDONE' => 'gray',
        'COMPLETED' => 'green'
    ];

    static $status = [
        'UNDONE' => '未完成',
        'COMPLETED' => '已完成'
    ];

    public function getAmountAttribute($value)
    {
        return $value / 10000;
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value * 10000;
    }
}
