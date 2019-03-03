<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendLog extends Model
{
    protected $fillable = [
        'user_id', 'send_log'
    ];
}
