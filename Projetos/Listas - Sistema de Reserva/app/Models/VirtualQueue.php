<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualQueue extends Model
{
    protected $fillable = ['attraction_id', 'queue_data'];

    protected $casts = [
        'queue_data' => 'array'
    ];
}
