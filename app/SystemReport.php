<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemReport extends Model
{
    protected $fillable = [
        'user_total_amount',
        'card_total_deduction',
        'date',
    ];
}
