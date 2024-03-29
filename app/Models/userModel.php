<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
}
