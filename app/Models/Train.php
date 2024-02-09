<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    use HasFactory;
    protected $fillable = [
        'train_id',
        'train_name',
        'capacity',
    ];

    public function stops()
    {
        return $this->hasMany(Stop::class);
    }
}
