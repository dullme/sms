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
        'failure' => '发送失败！',   //概率导致的失败
        'unknown' => 'SIM卡注册失败！',   //不是系统中的卡
        'empty' => '未插入SIM卡！',  //卡槽中没有卡
        'failed' => 'SIM卡识别失败！',    //iccid和imsi其中有一个为空表示读卡失败 需要切卡后重新读取
        'wrong' => 'SIM存在问题！',  //该卡的IMSI错误与系统中的不匹配
        'daily_send_amount' => '发送过于频繁！',  //单日单卡发送上限
        'seal' => '该卡已失效',  //被封卡了
        'insufficient_balance' => '该卡余额不足！',
        'too_much_money' => '发送失败！',
    ];

    static $status_code = [
        'success' => '发送成功！',
        'failure' => '概率导致的失败！',
        'unknown' => '不是系统中的卡！',
        'empty' => '卡槽中没有卡！',
        'failed' => 'ICCID和IMSI其中有一个为空！',
        'wrong' => '该卡的IMSI错误与系统中的不匹配！',
        'daily_send_amount' => '单日单卡发送上限！',
        'seal' => '该卡已被封！',
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
