<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Previous_order_balance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'previous_order_balance',
    ];
}
