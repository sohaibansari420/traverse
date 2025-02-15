<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankAchiever extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "rank_achievers";
    protected $fillable = [
        'id','user_id','rank_id', 'reward', 'is_sent', 'send_date', 'created_at', 'updated_at'
    ];
}
