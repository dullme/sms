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

    static $remark = [
        'success' => '发送成功！',
        'unknown' => '发送成功！',   //不是系统中的卡
        'empty' => '未插入SIM卡！',  //卡槽中没有卡
        'failed' => 'SIM卡识别失败！',    //iccid和imsi其中有一个为空表示读卡失败 需要切卡后重新读取
        'wrong' => 'SIM存在问题！',  //该卡的IMSI错误与系统中的不匹配
        'seal' => '该卡已失效',  //被封卡了
        'insufficient_balance' => '该卡余额不足！',
        'too_much_money' => '余额过多！',
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
