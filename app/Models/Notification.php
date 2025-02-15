<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "notifications";
    protected $fillable = [
        'id',
        'user_id',
        'type',
        'show_type',
        'title',
        'detail',
        'country',
        'till_date',
        'image',
    ];
}
