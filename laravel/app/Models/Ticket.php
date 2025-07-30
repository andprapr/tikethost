<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_tiket',
        'hadiah',
        'is_used',
        'prize_sent'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'prize_sent' => 'boolean'
    ];
}
