<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "promotions";
    protected $fillable = [
        'name',
        'detail',
        'user_id',
        'country',
        'start',
        'end',
        'image',
        'status',
    ];
}
