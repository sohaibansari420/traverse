<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "ranks";
    protected $fillable = [
        'id','name','points','direct', 'reward', 'value', 'image', 'club_image'
    ];
}
