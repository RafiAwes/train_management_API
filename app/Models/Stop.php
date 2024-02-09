<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'station_id',
        'arrival_time',
        'departure_time',
        'fare',
    ];

    public function train()
    {
        return $this->belongsTo(Train::class);
    }
}
