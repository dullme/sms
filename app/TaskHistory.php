<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskHistory extends Model
{
    static $colors=[
        0 => 'gray',
        1 => 'green',
    ];

    static $status=[
        0 => '失败',
        1 => '成功',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
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
