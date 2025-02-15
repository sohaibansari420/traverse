<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExtra extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "user_extras";

    protected $fillable = [
        'user_id',
        'paid_left',
        'paid_right',
        'free_left',
        'free_right',
        'bv_left',
        'bv_right',
        'binary_active',
        'lb',
        'rb',
    ];
}
