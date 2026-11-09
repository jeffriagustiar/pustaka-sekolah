<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_type',
        'identifier',
        'nama',
        'kelas',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];
}
