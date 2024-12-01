<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyDraw extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'draw_date', 'reward','claimed','type'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
