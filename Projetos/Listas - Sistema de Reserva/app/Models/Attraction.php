<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attraction extends Model
{
     protected $fillable = [
        'name', 'type', 'capacity_per_time_slot',
        'available_time_slots', 'minimum_age', 'has_priority_access'
    ];

    protected $casts = [
        'available_time_slots' => 'array',
        'has_priority_access' => 'boolean',
    ];
}
