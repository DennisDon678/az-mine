<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  UserNegativeBalanceConfig extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'task_threshold', 'negative_balance_amount', 'task_start_enabled'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}