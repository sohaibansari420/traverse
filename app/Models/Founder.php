<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Founder extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "founders";
    protected $fillable = [
        'username',
        'user_id',
        'email',
        'amount',
        'status',
    ];
}
