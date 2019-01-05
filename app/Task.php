<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'status'
    ];

    static $color = [
        'UNDONE' => 'gray',
        'COMPLETED' => 'green'
    ];

    static $status = [
        'UNDONE' => '未完成',
        'COMPLETED' => '已完成'
    ];
}
