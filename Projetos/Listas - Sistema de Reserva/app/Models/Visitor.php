<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'name', 'cpf', 'birth_date',
        'email', 'ticket_type', 'credit_card'
    ];
}
