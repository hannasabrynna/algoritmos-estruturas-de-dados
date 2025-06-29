<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'visitor_id',
        'attraction_id',
        'reserved_at'
    ];

    public function visitor() {
        return $this->belongsTo(Visitor::class);
    }

    public function attraction() {
        return $this->belongsTo(Attraction::class);
    }
}
