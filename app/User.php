<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pid',
        'invite',
        'username',
        'real_name',
        'password',
        'bank_card_number',
        'bank',
        'alipay',
        'amount',
        'total_income_amount',
        'one_day_max_send_count',
        'mode',
        'encryption',
        'status',
        'security_question',
        'classified_answer',
        'withdraw_password',
        'withdraw_time',
        'code',
        'baud_rate',
        'country',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'pid','id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'pid', 'id');
    }

    public function taskHistories()
    {
        return $this->hasMany(TaskHistory::class)
            ->where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::tomorrow())->select('user_id','amount');
    }

    public function getAmountAttribute($value)
    {
        return $value / 10000;
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value * 10000;
    }

    public function getTotalIncomeAmountAttribute($value)
    {
        return $value / 10000;
    }

    public function setTotalIncomeAmountAttribute($value)
    {
        $this->attributes['total_income_amount'] = $value * 10000;
    }
}
