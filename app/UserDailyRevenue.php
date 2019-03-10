<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDailyRevenue extends Model
{
    protected $fillable = [
        'user_id',
        'total_income_amount',
        'total_charged_amount',
        'total_count',
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
