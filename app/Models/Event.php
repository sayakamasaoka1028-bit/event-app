<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'event_date',
        'event_time',
        'category',
        'memo',
        'notify_before_days',
        'is_notified',
        'confirmed_by',
        'confirmed_at',
    ];
}
