<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNetwork extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "user_networks";

    protected $fillable = [
        'user_id',
        'mem_id',
        'position',
        'team',
        'is_roi',
        'is_point',
    ];
}
