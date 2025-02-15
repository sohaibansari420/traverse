<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFamily extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "user_families";

    protected $fillable = [
        'user_id',
        'mem_id',
        'level',
        'plan_id',
        'weekly_roi',
        'current_roi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
